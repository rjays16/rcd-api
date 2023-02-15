<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\ConventionMember;
use App\Models\RegistrationType;
use App\Models\Fee;
use App\Models\Config;
use App\Models\Role;
use App\Models\PlenaryDay;

use App\Enum\RoleEnum;
use App\Enum\UserStatusEnum;
use App\Enum\RegistrationTypeEnum;
use App\Enum\FeeEnum;
use App\Enum\ConfigTypeEnum;
use App\Enum\WorkshopEnum;

use App\Mail\Invoice;

use App\Http\Requests\Registration\Register\Delegate;

use App\Services\DelegateOrderService;
use App\Services\RegistrationConfigService;
use App\Services\FeeService;

use Exception;
use DB;


class DelegateRegistrationController extends Controller
{
    public function registerWalkin(Delegate $request) {
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
            $delegate_types_walkin = [
                RegistrationTypeEnum::INTERNATIONAL_LADS, # 2
                RegistrationTypeEnum::INTERNATIONAL_NON_LADS, # 3
                RegistrationTypeEnum::INTERNATIONAL_RESIDENT, # 4
                RegistrationTypeEnum::LOCAL_NON_PDS_MD, # 7
                RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS # 8
            ];

            $user_with_email_query = User::where('email', $validated["email"]);
            $other_user = $user_with_email_query->first();

            if(!is_null($other_user)) {
                if($other_user->status == UserStatusEnum::REGISTERED) {
                    return response()->json([
                        'message' => 'This account has already been registered. Please try logging in to continue with the registration process.',
                    ], 404);
                } else {
                    return response()->json([
                        'message' => 'This email has already been registered.',
                    ], 404);
                }
            }

            $user = $user_with_email_query->where('role', RoleEnum::CONVENTION_MEMBER)->first();
            if(is_null($user)) {
                DB::beginTransaction();
                try {
                    $validated["status"] = UserStatusEnum::IMPORTED_PENDING;
                    $user = User::create($validated);

                    $validated["user_id"] = $user->id;
                    ConventionMember::create($validated);

                    DB::commit();
                } catch(Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            if($user->status == UserStatusEnum::IMPORTED_PENDING) {
                $fee_service = new FeeService($validated["type"], $validated["is_interested_for_ws"], $validated["ws_to_attend"]);
                $registration_fee_config = $fee_service->getRegistrationFee();
                $workshop_fee_config = $fee_service->getWorkshopFee();
                if(!$workshop_fee_config["is_interest_valid"]) {
                    return response()->json([
                        'message' => $workshop_fee_config["message"],
                        'registration_type' => $workshop_fee_config["registration_type"],
                        'workshop_type' => $workshop_fee_config["workshop_type"],
                    ], $workshop_fee_config["code"]);
                }

                $registration_fee = $registration_fee_config["fee"];
                $workshop_fee = $workshop_fee_config["fee"];

                if(!is_null($registration_fee)) {
                    $order_service = new DelegateOrderService($user->member, $registration_fee, $workshop_fee);
                    $member_order = $order_service->addToMember();

                    DB::beginTransaction();
                    if($member_order["code"] == 200) {
                        if($request->hasFile('resident_certificate') && $validated["type"] == RegistrationTypeEnum::INTERNATIONAL_RESIDENT) {
                            $fileExtension = $request->file('resident_certificate')->getClientOriginalName();
                            $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                            $extension = $request->file('resident_certificate')->getClientOriginalExtension();
                            $fileStore = $file.'_'.time().'.'.$extension;
                            $request->file('resident_certificate')->storeAs('/images/resident', $fileStore);
                            $validated["resident_certificate"] = config('settings.APP_URL')."/storage/images/resident/".$fileStore;
                        }

                        $validated["status"] = UserStatusEnum::REGISTERED;
                        $user->update($validated); # ONLY UPDATE THE WALK-IN DELEGATE IF THE REGISTRATION IS SUCCESSFUL
                        $user->member->update($validated);

                        DB::commit();
                        return response()->json([
                            'message' => 'Successfully registered delegate account.',
                            'order_id' => $member_order["order_id"],
                            'is_free' => $member_order["is_free"],
                            'total_amount' => $member_order["total_amount"],
                            'ideapay_fee' => $member_order["ideapay_fee"],
                            'registration_fee' => $member_order["registration_fee"],
                            'workshop_fee' => $member_order["workshop_fee"],
                            'reg_and_ws_fee' => $member_order["reg_and_ws_fee"],
                            'config' => [
                                'is_registration_fee_international' => $member_order["is_registration_fee_international"],
                                'is_workshop_fee_international' => $member_order["is_workshop_fee_international"],
                                'php_rate_for_usd' => $member_order["php_rate_for_usd"],
                            ]
                        ]);
                    } else {
                        $user->member->forceDelete();
                        $user->forceDelete();
                        DB::commit();
                        return response()->json([
                            'message' => $member_order["message"],
                            'error' => $member_order["error"]
                        ], 400);
                    }
                } else {
                    $user->member->forceDelete();
                    $user->forceDelete();
                    DB::commit();
                    return response()->json([
                        'message' => 'Unable to proceed to registration. Please contact the site admin.',
                        'error' => 'Registration fees for this registration type (delegate) has not been set yet.'
                    ], 404);
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
        } catch(Exception $e) {
            throw $e;
        }
    }

    public function registerExisting(Delegate $request) {
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
            $delegate_types_existing = [
                // RegistrationTypeEnum::INTERNATIONAL_RESIDENT, # 4
                RegistrationTypeEnum::LOCAL_PDS_MEMBER, # 5
                RegistrationTypeEnum::LOCAL_PDS_RESIDENT, # 6
                RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER, # 9
                RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC, # 10
            ];

            $user = User::where([
                ['first_name', $validated["first_name"]],
                ['last_name', $validated["last_name"]],
                ['role', RoleEnum::CONVENTION_MEMBER]
            ])
            ->whereHas('member', function ($query) use ($delegate_types_existing, $validated) {
                $query->whereIn('type', $delegate_types_existing)
                    ->where('type', $validated['type']);
            })
            ->first();

            if(!is_null($user)) {
                $user_with_email_query = User::where('email', $validated["email"]);
                $other_user = $user_with_email_query->first();

                if(!is_null($other_user)) {
                    if($other_user->status == UserStatusEnum::REGISTERED) {
                        return response()->json([
                            'message' => 'This account has already been registered. Please try logging in to continue with the registration process.',
                        ], 404);
                    }
                }

                if(!is_null($other_user) && $other_user->id !== $user->id) {
                    return response()->json([
                        'message' => 'This email has already been registered.',
                    ], 404);
                }

                if($user->status == UserStatusEnum::IMPORTED_PENDING) {
                    switch($validated["type"]) {
                        case RegistrationTypeEnum::LOCAL_PDS_RESIDENT:
                        case (string) RegistrationTypeEnum::LOCAL_PDS_RESIDENT:
                            if(!array_key_exists('training_institution', $validated) || is_null($validated['training_institution'])) {
                                return response()->json([
                                    'message' => 'Please state your training institution.',
                                    'registration_type' => RegistrationTypeEnum::LOCAL_PDS_RESIDENT,
                                ], 404);
                            }
                        break;

                        case RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER:
                        case (string) RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER:
                        break;

                        case RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC:
                        case (string) RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC:
                            // For all LOCAL_PDS_COA_BOD_OC, the ws_to_attend MUST be BOTH regardless
                            $validated["is_interested_for_ws"] = true;
                            $validated["ws_to_attend"] = WorkshopEnum::BOTH_AESTHETIC_AND_LASER;
                        break;

                        case RegistrationTypeEnum::LOCAL_PDS_MEMBER:
                        case (string) RegistrationTypeEnum::LOCAL_PDS_MEMBER:
                            if(!$user->member->is_good_standing) { //LOCAL_PDS_MEMBER
                                return response()->json([
                                    'message' => 'This account is not in good standing. Please coordinate with the membership committee.',
                                ], 400);
                            }
                        break;

                        default:
                            return response()->json([
                                'message' => 'Invalid registration type.',
                                'registration_type' => $validated["type"]
                            ], 400);
                    }

                    $fee_service = new FeeService($validated["type"], $validated["is_interested_for_ws"], $validated["ws_to_attend"]);
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
                        $order_service = new DelegateOrderService($user->member, $registration_fee, $workshop_fee, $validated["email"]);
                        $member_order = $order_service->addToMember();

                        DB::beginTransaction();
                        if($member_order["code"] == 200) {
                            $validated["status"] = UserStatusEnum::REGISTERED;
                            $user->update($validated); # ONLY UPDATE THE EXISTING DELEGATE IF THE REGISTRATION IS SUCCESSFUL
                            $user->member->update($validated);

                            DB::commit();
                            return response()->json([
                                'message' => 'Successfully registered delegate account.',
                                'order_id' => $member_order["order_id"],
                                'is_free' => $member_order["is_free"],
                                'total_amount' => $member_order["total_amount"],
                                'ideapay_fee' => $member_order["ideapay_fee"],
                                'registration_fee' => $member_order["registration_fee"],
                                'workshop_fee' => $member_order["workshop_fee"],
                                'reg_and_ws_fee' => $member_order["reg_and_ws_fee"],
                                'config' => [
                                    'is_registration_fee_international' => $member_order["is_registration_fee_international"],
                                    'is_workshop_fee_international' => $member_order["is_workshop_fee_international"],
                                    'php_rate_for_usd' => $member_order["php_rate_for_usd"],
                                ]
                            ]);
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'message' => $member_order["message"],
                                'error' => $member_order["error"],
                                'member' => $user->member,
                                'registration_fee' => $member_order["registration_fee"],
                                'workshop_fee' => $member_order["workshop_fee"]
                            ], 400);
                        }
                    } else {
                        return response()->json([
                            'message' => 'Unable to proceed to registration. Please contact the site admin.',
                            'error' => 'Registration fees for this registration type (delegate) has not been set yet.',
                            'registration_fee' => $member_order["registration_fee"],
                            'workshop_fee' => $member_order["workshop_fee"]
                        ], 404);
                    }
                } else if($user->status == UserStatusEnum::REGISTERED){
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
                    'message' => 'Delegate cannot proceed to registration. Please coordinate with membership committee regarding the list of members and concerns.',
                    'error' => 'Delegate does not exist.'
                ], 404);
            }
        } catch(Exception $e) {
            throw $e;
        }
    }

    
}
