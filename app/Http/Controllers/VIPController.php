<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ConventionMember;

use App\Enum\RegistrationTypeEnum;
use App\Enum\RoleEnum;

use App\Http\Requests\VIP\Create;

use App\Imports\VIP\Import;
use App\Exports\VIP\Template;

use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

use Exception;
use DB;

class VIPController extends Controller
{
    public function getVIPs(Request $request) {
        $vip_types = [RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR, RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC];

        $vips = ConventionMember::whereIn('type', $vip_types)
            ->whereHas('user')
            ->join('users', 'users.id', '=', 'convention_members.user_id')
            ->select('convention_members.id', 'convention_members.user_id', 'convention_members.type', 'convention_members.sub_type',
                'users.id', 'users.email', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.status');

        if($request->exists('is_search') && $request->is_search) {
            $vips = $vips->whereHas('user', function ($query) use ($request) { 
                $query->where('first_name', 'like', "%$request->keyword%")
                    ->orWhere('middle_name', 'like', "%$request->keyword%")
                    ->orWhere('last_name', 'like', "%$request->keyword%")
                    ->orWhere('email', 'like', "%$request->keyword%");
            });
        } else if(!$request->show_all) {
            $vips = $vips->limit(20);
        }

        $vips = $vips->with(['user.member', 'registration_type', 'registration_sub_type'])
            ->orderBy('users.last_name', 'asc')
            ->get()
            ->makeHidden(['can_generate_certificate', 'can_submit_abstract', 'has_paid_registration_fee', 'has_pending_order', 'has_pending_payment',
                'limit_convention_access', 'order', 'paid_fees', 'payments', 'pending_order_payment_method']);

        if($vips->isNotEmpty()) {
            return response()->json($vips);
        } else {
            return response()->json(['message' => 'No vips were found'], 404);
        }
    }

    public function getVIP($id) {
        $vip_types = [RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR, RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC];

        $vip = ConventionMember::whereIn('type', $vip_types)
            ->whereHas('user', function ($query) use ($id) { 
                $query->where('id', $id);
            })
            ->with(['user', 'registration_type', 'registration_sub_type'])
            ->first();

        if(!is_null($vip)) {
            return response()->json($vip);
        } else {
            return response()->json(['message' => 'VIP not found'], 404);
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();
        $vip_types = [RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR,RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC];

        $vip = ConventionMember::where('id', $id)
            ->whereIn('type', [RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR,RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC])
            ->with(['user', 'registration_type', 'registration_sub_type'])
            ->first();

        if(is_null($vip)) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        DB::beginTransaction();
        try {
            if(Auth::user()->role == RoleEnum::ADMIN) {
                if($request->exists("is_good_standing")) {
                    $validated["is_good_standing"] = $request["is_good_standing"];
                }

                if($request->exists("status")) {
                    $validated["status"] = $request["status"];
                }
            }

            $vip->fill($validated);
            $vip->save();

            $vip->user->fill($validated);
            $vip->user->save();

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated convention member'
            ]);
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteMember($id) {
        $vip_types = [RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR,RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC];

        $vip = ConventionMember::whereIn('type', $vip_types)
            ->whereHas('user', function ($query) use ($id) { 
                $query->where('id', $id);
            })
            ->with(['user'])
            ->first();

        if(!is_null($vip)) {
            // if(!empty($vip->orders)) {
            //     $orders = $vip->orders;
            //     foreach($orders as $order) {
            //         $order->transaction->delete();
            //         $order->transaction->ideapay->delete();

            //         if(!is_null($order->payment)) {
            //             $payment->delete();
            //         }
                    
            //         $order->delete();
            //     }
            // }

            $vip->user->email = $vip->user->email.'_deleted_'.time();
            $vip->user->save();

            $vip->delete();
            $vip->user->delete();
            return response()->json(['message' => 'Successfully deleted vip']);
        } else {
            return response()->json(['message' => 'VIP account was not found'], 404);
        }
    }

    public function delete($id) {
        $vip_types = [RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR,RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC];

        $vip = ConventionMember::whereIn('type', $vip_types)
            ->whereHas('user', function ($query) use ($id) { 
                $query->where('id', $id);
            })
            ->with(['user', 'orders', 'orders.transaction'])
            ->first();

        if(!is_null($vip)) {
            DB::beginTransaction();
            try {
                if(!empty($vip->orders)) {
                    $orders = $vip->orders;
                    foreach($orders as $order) {
                        $order->transaction->delete();
                        $order->transaction->ideapay->delete();

                        if(!empty($order->payment)) {
                            $payments = $order->payment;
                            foreach($payments as $payment) {
                                $payment->delete();
                            }
                        }
                        
                        $order->delete();
                    }
                }

                $vip->user->email = $vip->user->email.'_deleted_'.time();
                $vip->user->save();

                $vip->delete();
                $vip->user->delete();
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Successfully deleted account.']);
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } else {
            return response()->json(['status' => 'fail', 'message' => 'Account not found.'], 404);
        }
    }

    public function exportTemplate() {
		return (new Template())->download('vip_template.xlsx');
    }

    public function import(Request $request) {
        if($request->hasFile('file')) {
            try {
                $import = new Import($request->vip_type);
                Excel::import($import, $request->file('file'));

                $num_imported = $import->getNumImported();

                $message = "No new VIPs were created";
                if($num_imported > 0) {
                    $message = "Successfully imported $num_imported vip/s";
                }

                return response()->json([
                    'message' => $message
                ]);
            } catch(Exception $e) {
                throw $e;
            }
        }

        return response()->json([
            'message' => 'No file selected'
        ], 400);
    }
}
