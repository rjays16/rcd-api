<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ConventionMember;
use App\Models\RegistrationType;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\Payment;

use App\Enum\RegistrationTypeEnum;
use App\Enum\RoleEnum;
use App\Enum\ConventionMemberTypeEnum;
use App\Enum\UserStatusEnum;
use App\Enum\FeeTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\WorkshopEnum;

use App\Http\Requests\Delegate\Create;

use App\Imports\Delegate\Import;
use App\Exports\Delegate\Template;

use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

use Exception;
use DB;

class DelegateController extends Controller
{
    public function getDelegates(Request $request) {
        $delegate_types = [
            RegistrationTypeEnum::INTERNATIONAL_LADS,
            RegistrationTypeEnum::INTERNATIONAL_NON_LADS,
            RegistrationTypeEnum::INTERNATIONAL_RESIDENT,
            RegistrationTypeEnum::LOCAL_PDS_MEMBER,
            RegistrationTypeEnum::LOCAL_PDS_RESIDENT,
            RegistrationTypeEnum::LOCAL_NON_PDS_MD,
            RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS,
            RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER
        ];

        $delegates = ConventionMember::whereIn('type', $delegate_types)
            ->whereHas('user')
            ->join('users', 'users.id', '=', 'convention_members.user_id')
            ->select('convention_members.id', 'convention_members.user_id', 'convention_members.type',
                'users.id', 'users.email', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.status');

        if($request->exists('is_search') && $request->is_search) {
            $delegates = $delegates->whereHas('user', function ($query) use ($request) { 
                $query->where('first_name', 'like', "%$request->keyword%")
                    ->orWhere('middle_name', 'like', "%$request->keyword%")
                    ->orWhere('last_name', 'like', "%$request->keyword%")
                    ->orWhere('email', 'like', "%$request->keyword%");
            });
        } else if(!$request->show_all) {
            $delegates = $delegates->limit(20);
        }

        $delegates = $delegates->with(['user.member', 'registration_type', 'registration_sub_type'])
            ->orderBy('users.last_name', 'asc')
            ->get()
            ->makeHidden(['can_generate_certificate', 'can_submit_abstract', 'has_paid_registration_fee', 'has_pending_order', 'has_pending_payment',
                'limit_convention_access', 'order', 'paid_fees', 'payments', 'pending_order_payment_method']);

        if($delegates->isNotEmpty()) {
            return response()->json($delegates);
        } else {
            return response()->json(['message' => 'No members were found'], 404);
        }
    }

    public function getDelegate($id) {
        $delegate_types = [
            RegistrationTypeEnum::INTERNATIONAL_LADS,
            RegistrationTypeEnum::INTERNATIONAL_NON_LADS,
            RegistrationTypeEnum::INTERNATIONAL_RESIDENT,
            RegistrationTypeEnum::LOCAL_PDS_MEMBER,
            RegistrationTypeEnum::LOCAL_PDS_RESIDENT,
            RegistrationTypeEnum::LOCAL_NON_PDS_MD,
            RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS,
            RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER
        ];

        $delegate = ConventionMember::whereIn('type', $delegate_types)
            ->whereHas('user', function ($query) use ($id) { 
                $query->where('id', $id);
            })
            ->with(['user', 'registration_type', 'registration_sub_type', 'user.user_status'])
            ->first();

        if(!is_null($delegate)) {
            return response()->json($delegate);
        } else {
            return response()->json(['message' => 'Delegate not found'], 404);
        }
    }

    public function getDelegateTypes() {
        $types = RegistrationType::where('member_type', ConventionMemberTypeEnum::DELEGATE)->get();

        if($types->isNotEmpty()) {
            return response()->json($types);
        } else {
            return response()->json(['message' => 'The delegate types have not been set yet'], 404);
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $delegate_types = [
            RegistrationTypeEnum::INTERNATIONAL_LADS,
            RegistrationTypeEnum::INTERNATIONAL_NON_LADS,
            RegistrationTypeEnum::INTERNATIONAL_RESIDENT,
            RegistrationTypeEnum::LOCAL_PDS_MEMBER,
            RegistrationTypeEnum::LOCAL_PDS_RESIDENT,
            RegistrationTypeEnum::LOCAL_NON_PDS_MD,
            RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS,
            RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER
        ];

        $delegate = ConventionMember::where('id', $id)    
            ->whereIn('type', $delegate_types)
            ->with(['user'])
            ->first();

        if(is_null($delegate)) {
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

            $delegate->fill($validated);
            $delegate->save();

            $delegate->user->fill($validated);
            $delegate->user->save();

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
        $delegate_types = [
            RegistrationTypeEnum::INTERNATIONAL_LADS,
            RegistrationTypeEnum::INTERNATIONAL_NON_LADS,
            RegistrationTypeEnum::INTERNATIONAL_RESIDENT,
            RegistrationTypeEnum::LOCAL_PDS_MEMBER,
            RegistrationTypeEnum::LOCAL_PDS_RESIDENT,
            RegistrationTypeEnum::LOCAL_NON_PDS_MD,
            RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS,
            RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER
        ];

        $delegate = ConventionMember::whereIn('type', $delegate_types)
            ->whereHas('user', function ($query) use ($id) { 
                $query->where('id', $id);
            })
            ->with(['user'])
            ->first();

        if(!is_null($delegate)) {
            $delegate->delete();
            $delegate->user->delete();
            return response()->json(['message' => 'Successfully deleted delegate']);
        } else {
            return response()->json(['message' => 'Delegate account was not found'], 404);
        }
    }

    public function delete($id) {
        $delegate_types = [
            RegistrationTypeEnum::INTERNATIONAL_LADS,
            RegistrationTypeEnum::INTERNATIONAL_NON_LADS,
            RegistrationTypeEnum::INTERNATIONAL_RESIDENT,
            RegistrationTypeEnum::LOCAL_PDS_MEMBER,
            RegistrationTypeEnum::LOCAL_PDS_RESIDENT,
            RegistrationTypeEnum::LOCAL_NON_PDS_MD,
            RegistrationTypeEnum::LOCAL_NON_PDS_RESIDENT_OF_APPLICANTS_INSTITUTIONS
        ];

        $delegate = ConventionMember::whereIn('type', $delegate_types)
            ->whereHas('user', function ($query) use ($id) { 
                $query->where('id', $id);
            })
            ->with(['user', 'orders', 'orders.transaction'])
            ->first();

        if(!is_null($delegate)) {
            DB::beginTransaction();
            try {
                if(!empty($delegate->orders)) {
                    $orders = $delegate->orders;
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

                $delegate->user->email = $delegate->user->email.'_deleted_'.time();
                $delegate->user->save();

                $delegate->delete();
                $delegate->user->delete();
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
		return (new Template())->download('delegate_template.xlsx');
    }

    public function import(Request $request) {
        if($request->hasFile('file')) {
            try {
                $import = new Import($request->delegate_type);
                Excel::import($import, $request->file('file'));

                $num_imported = $import->getNumImported();

                $message = "No new delegates were created";
                if($num_imported > 0) {
                    $message = "Successfully imported $num_imported delegate/s";
                }

                return response()->json([
                    'message' => $message
                ]);
            } catch(Exception $e) {
                throw $e;
            }
        } else {
            return response()->json([
                'message' => 'No file selected'
            ], 400);
        }
    }
    public function getDelegateOrdersAndFees($id) {
        $id = (int)$id;
        $user = User::where('id', $id)->with('member')->first();
        $orders = collect(DB::select("CALL RCDsp_LedgerCompleted('$id')"));
        $order_fees = collect(DB::select("CALL RCDsp_LedgerPending('$id')"));

        $free_reg_only = [
            RegistrationTypeEnum::LOCAL_PDS_MEMBER, # 5
            RegistrationTypeEnum::LOCAL_PDS_RESIDENT, # 6
            RegistrationTypeEnum::INTERNATIONAL_LADS_OFFICER, # 9
        ];
        
        $free_reg = in_array($user->member->type, $free_reg_only);
        if($free_reg && count($orders)==0 ){
            //create order registration for free delegate types
            DB::beginTransaction();
            try{
                $user->member->is_interested_for_ws = false;
                $user->member->ws_to_attend = null;
                $user->member->save();

                $fee = Fee::where([['type', FeeTypeEnum::REGISTRATION],['registration_type', $user->member->type]])->first();

                $order = new Order();
                $order->convention_member_id = $user->member->id;
                $order->amount = 0;
                $order->is_free = true;
                $order->status = OrderStatusEnum::COMPLETED;
                $order->save();
    
                // Create the Order Item (Registration/Workshop)
                $order_item = new OrderItem();
                $order_item->order_id = $order->id;
                $order_item->fee_id = $fee->id;
                $order_item->save();

                $transaction = new Transaction();
                $transaction->amount = 0;
                $transaction->order_id = $order->id;
                $transaction->save();

                $is_earlybird = true;
                $current_date = Carbon::now()->format('Y-m-d');
                
                $payment = new Payment();
                $payment->convention_member_id = $order->member->id;
                $payment->order_id = $order->id;
                $payment->payment_method = PaymentMethodEnum::FREE;
                $payment->amount = $order->amount;
                $payment->date_paid = Carbon::now();
                $payment->is_earlybird = $is_earlybird;
                $payment->save();

                DB::commit();
                $orders = collect(DB::select("CALL RCDsp_LedgerCompleted('$id')")); //call list again with the updated list
                
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
        //check if empty completed orders
        if(count($orders)!=0){
            foreach($orders as $order){
                if($order->Fee_Type == "Registration"){
                    $keys = array_column(json_decode($order_fees), 'Fee_Type');
                    $index = array_search("Registration", $keys);
                    $order_fees[$index]->Order_Status = "Completed";
                    $order_fees[$index]->Is_Applicable = false;
                    $order_fees[$index]->PayButton_Status = "PAID";
                    // $order_fees[$index]->Order_Amount = $order->Order_Amount;
                    // $order_fees[$index]->Forex = $order->Forex;
                    // $order_fees[$index]['Paid_Amount']= $order->Order_Amount*$order->Forex;
                }else if($order->Fee_Type == "Workshop"){
                    $index = null;
                    $keys = array_column(json_decode($order_fees), 'Workshop_Name');
                    if($order->Attend_Workshop == "Aesthetic"){
                        $index = array_search("Aesthetic", $keys);
                    }else if($order->Attend_Workshop == "Laser"){
                        $index = array_search("Laser", $keys);
                    }else{
                        $index = array_search("Both Aesthetic and Laser", $keys);
                    }
                    $order_fees[$index]->Order_Status = "Completed";
                    $order_fees[$index]->Is_Applicable = false;
                    $order_fees[$index]->PayButton_Status = "PAID";
                    // $order_fees[$index]->Order_Amount = $order->Order_Amount;
                    // $order_fees[$index]->Forex = $order->Forex;
                    // $order_fees[$index]['Paid_Amount']= $order->Order_Amount*$order->Forex;
                }
            }
        }
        
        //update is_applicable values
        $unset_incomplete = false;        
        $list_of_fees = array();

        foreach($order_fees as $order_fee_key => $order_fee) {
            $keys = array_column(json_decode($order_fees), 'Workshop_Name');
            if(!is_null($order_fee->workshop_type)){
                $aes_index = array_search("Aesthetic", $keys);
                $laser_index = array_search("Laser", $keys);
                $index = array_search("Both Aesthetic and Laser", $keys);

                // Check if the workshop type is for BOTH aesthetic and laser and marked as completed
                if($order_fee->workshop_type == WORKSHOPENUM::BOTH_AESTHETIC_AND_LASER && $order_fee->Order_Status == "Completed") {
                    $order_fees[$aes_index]->Is_Applicable = false;
                    $order_fees[$aes_index]->Order_Status = "Completed";
                    $order_fees[$aes_index]->PayButton_Status = "PAID";
                    $order_fees[$laser_index]->Is_Applicable = false;
                    $order_fees[$laser_index]->Order_Status = "Completed";
                    $order_fees[$laser_index]->PayButton_Status = "PAID";
                }
                
                // Check if the user paid for BOTH the aesthetic and laser workshops INDIVIDUALLY
                else if($aes_index !== false && $order_fees[$aes_index]->Order_Status == "Completed" &&
                    $laser_index !== false && $order_fees[$laser_index]->Order_Status == "Completed") {
                    $order_fees[$index]->Is_Applicable = false;
                    $order_fees[$index]->Order_Status = "Completed";
                    $order_fees[$index]->PayButton_Status = "PAID";
                    $order_fees[$aes_index]->Is_Applicable = false;
                    $order_fees[$aes_index]->Order_Status = "Completed";
                    $order_fees[$aes_index]->PayButton_Status = "PAID";
                    $order_fees[$laser_index]->Is_Applicable = false;
                    $order_fees[$laser_index]->Order_Status = "Completed";
                    $order_fees[$laser_index]->PayButton_Status = "PAID";

                    $unset_incomplete = true;
                }
                
                // Check if the user paid for EITHER the aesthetic and laser workshops OR
                // if the user cancelled either the aesthetic and laser workshops
                else if($aes_index !== false && ($order_fees[$aes_index]->Order_Status == "Completed" || $order_fees[$aes_index]->PayButton_Status == "CANCELLED") ||
                    $laser_index !== false && ($order_fees[$laser_index]->Order_Status == "Completed" || $order_fees[$laser_index]->PayButton_Status == "CANCELLED")) {
                    $index = array_search("Both Aesthetic and Laser", $keys);
                    $order_fees[$index]->Is_Applicable = false;
                    $order_fees[$index]->PayButton_Status = "NOT APPLICABLE";
                }                
            }
            
            $list_of_fees[$order_fee_key] = $order_fees[$order_fee_key];
        }
        
        if($unset_incomplete) {
            foreach($list_of_fees as $key => $fee_item) {
                if($fee_item->Fee_Type == "Workshop" && $fee_item->Order_Status == "Pending" ) {
                    unset($list_of_fees[$key]);
                }
            }
            
            sort($list_of_fees);
            $order_fees = $list_of_fees;
        }

        if(count($order_fees) != 0) {
            return response()->json(
                $order_fees
            );
        } else {
            return response()->json(['message' => 'This member has no order fees set.'], 404);
        }
    }
}