<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Config;

use App\Enum\OrderStatusEnum;

use App\Enum\PaymentMethodEnum;

use App\Mail\Invoice;

use Exception;
use DB;

use Carbon\Carbon;

class VIPOrderService {
    private $member;
    private $registration_fee;
    private $workshop_fee;

    public function __construct($member, $registration_fee, $workshop_fee) {
        $this->member = $member;
        $this->registration_fee = $registration_fee;
        $this->workshop_fee = $workshop_fee;
    }

    public function addToMember() {
        DB::beginTransaction();
        try {
            $member_registration_fee = null;
            $member_workshop_fee = null;
            $order = null;
            $ideapay_fee = 0;

            $member_order_query = Order::where('convention_member_id', $this->member->id);
            // $member_orders = $member_order_query->get();
            $member_orders = $member_order_query->first();
            
            // if($member_orders->isNotEmpty()) {
            if(!is_null($member_orders)) {
                $order = $member_orders;

                $member_registration_fee = $member_order_query
                    ->whereHas('order_items', function ($query) { 
                        $query->where('fee_id', $this->registration_fee->id);
                    })
                    ->first();

                if(!is_null($this->workshop_fee)){
                    $member_workshop_fee = $member_order_query
                    ->whereHas('order_items', function ($query) { 
                        $query->where('fee_id', $this->workshop_fee->id);
                    })
                    ->first();
                }  
            }

            $current_date = Carbon::now()->format('Y-m-d');

            if(is_null($member_registration_fee) && is_null($member_workshop_fee )) {
                $registration_fee = $this->registration_fee->amount;
                $workshop_fee = 0;
                $amount = $registration_fee + $ideapay_fee;

                if($this->registration_fee->uses_late_amount && $current_date >= $this->registration_fee->late_amount_starts_on) {
                    $registration_fee = $this->registration_fee->late_amount;
                }

                $order = new Order();
                $order->convention_member_id = $this->member->id;
                $order->amount = $amount;
                $order->status = OrderStatusEnum::PENDING;
                $order->save();

                // Create the registration Fee Order Item
                $order_item = new OrderItem();
                $order_item->order_id = $order->id;
                $order_item->fee_id = $this->registration_fee->id;
                $order_item->save();

                // Create the workshop Fee Order Item
                if(!is_null($this->workshop_fee)) {
                    $order_item = new OrderItem();
                    $order_item->order_id = $order->id;
                    $order_item->fee_id = $this->workshop_fee->id;
                    $order_item->save();

                    $workshop_fee = $this->workshop_fee->amount;
                }

                $order->is_free = $order->amount == number_format(0, 2);
                $order->save();
                DB::commit();
                
                return array(
                    'message' => 'Successfully added order',
                    'order_id' => $order->id,
                    'order' => $order,
                    'total_amount' => number_format($order->amount,2),
                    'ideapay_fee' => number_format($ideapay_fee,2),
                    'registration_fee' => number_format($registration_fee,2),
                    'workshop_fee' => number_format($workshop_fee,2),
                    'reg_and_ws_fee' => number_format($workshop_fee + $registration_fee,2),
                    'code' => 200,
                );
            } else {
                return array(
                    'message' => 'Member already has this order fee',
                    'order_id' => $order->id,
                    'order' => $order,
                    'code' => 400,
                );
            }
        } catch(Exception $e){
            DB::rollBack();
            return array(
                'message' => 'Unable to add order',
                'code' => 400,
            );
        }
    }
}