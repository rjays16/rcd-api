<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\ConventionMember;
use App\Models\RegistrationType;
use App\Models\Fee;

use App\Enum\RoleEnum;
use App\Enum\UserStatusEnum;
use App\Enum\RegistrationTypeEnum;
use App\Enum\FeeEnum;
use App\Enum\WorkshopEnum;

use App\Mail\Invoice;

use App\Http\Requests\Registration\Register\VIP;

use App\Services\RegistrationConfigService;
use App\Services\VIPOrderService;
use App\Services\FeeService;

use Exception;
use DB;

class VIPRegistrationController extends Controller
{
    public function register(VIP $request) {
        $validated = $request->validated();

        $registration_config_service = new RegistrationConfigService($validated["role"], $validated["password"], $validated["confirm_password"]);
        $registration_config = $registration_config_service->checkRegisterableStatus();

        if(!$registration_config["is_registration_allowed"]) {
            return response()->json([
                'message' => $registration_config["message"],
            ], $registration_config["code"]);
        }

        $validated["password"] = Hash::make($validated["password"]);

        try {
            $user = User::where([
                ['first_name', $validated["first_name"]],
                ['last_name', $validated["last_name"]],
                ['role', RoleEnum::CONVENTION_MEMBER]
            ])
            ->whereHas('member', function ($query) { 
                $query->where('type', RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR);
            })
            ->first();

            if(!is_null($user)) {                
                if($user->status == UserStatusEnum::IMPORTED_PENDING) {
                    // For all VIPs, the ws_to_attend MUST be BOTH regardless
                    $validated["is_interested_for_ws"] = true;
                    $validated["ws_to_attend"] = WorkshopEnum::BOTH_AESTHETIC_AND_LASER;

                    $fee_service = new FeeService(RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR, $validated["is_interested_for_ws"], $validated["ws_to_attend"]);
                    $registration_fee_config = $fee_service->getRegistrationFee();
                    $workshop_fee_config = $fee_service->getWorkshopFee();

                    if(!$workshop_fee_config["is_interest_valid"]) {
                        return response()->json([
                            'message' => $workshop_fee_config["message"],
                        ], $workshop_fee_config["code"]);
                    }
                    
                    $registration_fee = $registration_fee_config["fee"];
                    $workshop_fee = $workshop_fee_config["fee"];

                    if(!is_null($registration_fee)) {
                        $order_service = new VIPOrderService($user->member, $registration_fee, $workshop_fee);
                        $member_order = $order_service->addToMember();
        
                        DB::beginTransaction();
                        if($member_order["code"] == 200) {
                            $validated["status"] = UserStatusEnum::REGISTERED;
                            $user->update($validated); # ONLY UPDATE THE VIP IF THE REGISTRATION IS SUCCESSFUL
                            $user->member->update($validated);

                            DB::commit();
                            return response()->json([
                                'message' => 'Successfully registered vip account.',
                                'order' => $member_order["order"],
                                'order_id' => $member_order["order_id"],
                                'is_free' => true,
                                'total_amount' => $member_order["total_amount"],
                                'ideapay_fee' => $member_order["ideapay_fee"],
                                'registration_fee' => $member_order["registration_fee"],
                                'workshop_fee' => $member_order["workshop_fee"],
                                'reg_and_ws_fee' => $member_order["reg_and_ws_fee"],
                            ]);
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'message' => $member_order['message']
                            ], 400);
                        }
                    }
                } else if($user->status == UserStatusEnum::REGISTERED) {
                    return response()->json([
                        'message' => 'This account has already been registered.',
                    ], 404);
                } else {
                    return response()->json([
                        'message' => 'This account is not eligible for registration.',
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Sorry you cannot proceed to registration. Please coordinate with membership committee regarding the list of members and concerns.',
                    'error' => 'VIP does not exist.'
                ], 404);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}