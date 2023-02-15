<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Fee;
use App\Models\Order;
use App\Models\OrderCancel;
use App\Models\OrderItem;
use App\Models\ConventionMember;
use App\Models\ForExRate;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Ideapay;

use App\Enum\OrderStatusEnum;
use App\Enum\IdeapayStatusEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\WorkshopEnum;
use App\Enum\RegistrationTypeEnum;
use App\Enum\UserStatusEnum;

use App\Http\Requests\Order\Cancel;
use App\Http\Requests\Order\UndoCancellation;
use App\Http\Requests\Order\Create;
use App\Http\Requests\Order\Update;
use App\Http\Requests\Order\UpdateStatus;
use App\Http\Requests\Registration\CalculateRates;
use App\Http\Requests\Rate\Convert;

use App\Services\OrderService;
use App\Services\FeeService;
use App\Services\IdeapayService;

use Exception;
use DB;

use Carbon\Carbon;

class OrderController extends Controller
{
    public function convertAmount(Convert $request) {
        $validated = $request->validated();

        return response()->json([
            'amount' => $validated["amount"],
            'conversion_rate' => ForExRate::getActivePHPRate(),
            'converted_amount' => ForExRate::convertAmountToPHP(floatval($validated["amount"]))
        ]);
    }

    public function getOrders() {
        $orders = Order::with(['member.user', 'order_items', 'transaction', 'transactions', 'first_payment'])->get();

        if($orders->isNotEmpty()) {
            return response()->json($orders);
        } else {
            return response()->json(['message' => 'There were no orders found'], 404);
        }
    }

    public function getOrder($id) {
        $order = Order::where('id', $id)
            ->with(['member.user', 'order_items', 'transaction', 'transactions', 'first_payment'])
            ->first();

        if(!is_null($order)) {
            return response()->json($order);
        } else {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }

    public function getUserOrders(Request $request) {
        $orders = Order::where('convention_member_id', $request->member_id)
            ->with(['transaction', 'order_items.fee', 'payment', 'first_payment', 'member.user'])
            ->get();
        
        if($orders->isNotEmpty()) {
            return response()->json($orders);
        } else {
            return response()->json(['message' => 'This member has no order fees'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();
        $user = User::where('id', $validated['user_id'])
            ->with('member.orders')
            ->first();
        
        DB::beginTransaction();
        try{
            $fee_service = new FeeService($user->member->type, $validated["is_interested_for_ws"], $validated["ws_to_attend"]);
            $registration_fee_config = $validated["ws_to_attend"] == null ? $fee_service->getRegistrationFee() : null ;
            $workshop_fee_config = $validated["ws_to_attend"] == null ? null : $fee_service->getWorkshopFee();
            $registration_fee = $registration_fee_config != null? $registration_fee_config["fee"] : null;
            $workshop_fee =  $validated["ws_to_attend"] == null ? null : $workshop_fee_config["fee"];

            $order_service = new OrderService($user->member, $registration_fee, $workshop_fee);
            $member_order = $order_service->addToMember();

            if($member_order["code"] == 200) {
                $validated["status"] = UserStatusEnum::REGISTERED;
                $validated["ws_to_attend"] = $validated["ws_to_attend"];
                $user->update($validated);
                $user->member->update($validated);

                DB::commit();
                return response()->json([
                    'message' => 'Successfully added orders.',
                    'user_id' =>  $user->id,
                    'order_id' =>  $member_order["order_id"],
                    'payment_url' => $member_order["payment_url"],
                    'is_free' => $member_order["is_free"]
                ]);
            } else {
                return response()->json([
                    'message' => $member_order["message"],
                    'error' => $member_order["error"]
                ], 400);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancel(Cancel $request) {
        $validated = $request->validated();
        $user = User::where('id', $validated['user_id'])
            ->with('member')
            ->first();
        
        $fee = Fee::where([['registration_type', $user->member->type],['workshop_type', $validated['workshop_type']]])->first();
        
        DB::beginTransaction();
        try {
            $validated['fee_id'] = $fee->id;
            $cancel_order = new OrderCancel();
            $cancel_order->create($validated);

            DB::commit();
            return response()->json([
                'message' => 'Order cancelled',
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function undoCancellation(UndoCancellation $request) {
        $validated = $request->validated();

        $user = User::where('id', $validated['user_id'])->with('member')->first();            
        if(is_null($user)) {
            return response()->json([
                'message' => 'User was not found.',
            ], 404);
        }

        $cancelled_order = OrderCancel::where('user_id', $user->id)->where('fee_id', $validated['fee_id']);
        if(is_null($cancelled_order)) {
            return response()->json([
                'message' => 'The cancelled order was not found.',
            ], 404);
        }
        
        DB::beginTransaction();
        try {
            $cancelled_order->delete($validated);
            DB::commit();
            return response()->json([
                'message' => 'Cancellation was successfully undone.',
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Update $request) {
        $validated = $request->validated();
        $order = Order::where('id', $validated['order_id'])->first();
        $member = ConventionMember::where('id', $validated['convention_member_id'])->first();

        DB::beginTransaction();
        try {
            if(!is_null($member)) {
                if(!is_null($order)) {
                    if($order->RawOrderPaymentsValue >= ($order->amount - $order->transaction->ideapay_fee)) {
                        $order->status = OrderStatusEnum::COMPLETED;
                    }
                    
                    $order->save();

                    DB::commit();
                    return response()->json([
                        'message' => 'Order updated',
                        'order_status' => $order->status
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Order not found'
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Member not found'
                ], 404);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateStatusAndPayment(UpdateStatus $request) {
        $validated = $request->validated();
        $order = Order::with('first_payment')->where('id', $validated['order_id'])->first();
        $member = ConventionMember::where('id', $validated['convention_member_id'])->first();

        DB::beginTransaction();
        try {
            if(!is_null($member)) {
                if(!is_null($order)) {
                    // Check if the current order status is not completed AND the request data is attempting to complete it
                    if($order->status != OrderStatusEnum::COMPLETED && $validated['status'] == OrderStatusEnum::COMPLETED) {
                        if(is_null($order->first_payment)) {
                            $transaction = $order->transaction;
                            if(is_null($transaction)) {
                                $transaction = new Transaction();
                                $transaction->order_id = $order->id;
                                $transaction->amount = $order->amount;
                                $transaction->save();
                            }

                            $payment = new Payment(); 
                            $payment->convention_member_id = $member->id;
                            $payment->payment_method = PaymentMethodEnum::IDEAPAY;
                            $payment->order_id = $transaction->order->id;
                            $payment->amount = $transaction->order->amount;
                            $payment->date_paid = Carbon::now();
                            $payment->save();

                            $ideapay_payment = null;
                            if(array_key_exists('payment_ref', $validated) && !is_null($validated['payment_ref'])) {
                                $ideapay_payment = Ideapay::where('payment_ref', $validated['payment_ref'])->first();
                            }

                            if(is_null($ideapay_payment)) {
                                $ideapay_payment = new Ideapay();
                                $ideapay_payment->payment_ref = $validated['payment_ref'];
                                $ideapay_payment->payment_url = $validated['payment_url'];
                                $ideapay_payment->status = IdeapayStatusEnum::SUCCESS;
                                $ideapay_payment->save();
                            }

                            $transaction->ideapay_id = $ideapay_payment->id;
                            $transaction->ideapay_fee = $order->convenience_fee;
                            $transaction->save();
                        }
                    }

                    $order->status = $validated['status'];
                    $order->save();

                    DB::commit();
                    return response()->json([
                        'message' => 'Order updated',
                        'order_status' => $order->status
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Order not found'
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Member not found'
                ], 404);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}