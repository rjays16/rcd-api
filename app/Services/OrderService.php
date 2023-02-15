<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Config;
use App\Models\Ideapay;
use App\Models\ForExRate;

use App\Enum\OrderStatusEnum;
use App\Enum\PaymentMethodEnum;
use App\Enum\IdeapayStatusEnum;

use App\Mail\Invoice;

use App\Services\IdeapayService;

use Exception;
use DB;

use Carbon\Carbon;

class OrderService {
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
            $order = null;
            $ideapay_fee = 0;
            $ideapay_rate = Config::getIdeapayRate();
            $php_rate_for_usd = ForExRate::getActivePHPRate();
            $current_date = Carbon::now()->format('Y-m-d');
            $is_workshop_fee_international = null;
            $is_free = false;
        
            // Check the current date first, identify if we should use the late amount
            $workshop_fee_amount = 0;
            $registration_fee_amount = 0;
            $is_registration_fee_international = 0;

            if(!is_null($this->registration_fee)) {
                $registration_fee_amount = $this->registration_fee->amount;
                $is_registration_fee_international = $this->registration_fee->scope;
                if($this->registration_fee->uses_late_amount && $current_date >= $this->registration_fee->late_amount_starts_on) {
                    $registration_fee_amount = $this->registration_fee->late_amount;
                }

                if($is_registration_fee_international) {
                    $registration_fee_amount = $registration_fee_amount * $php_rate_for_usd;
                }
            }

            if(!is_null($this->workshop_fee)) {
                $is_workshop_fee_international = $this->workshop_fee->scope;

                $workshop_fee_amount = $this->workshop_fee->amount;
                if($this->workshop_fee->uses_late_amount && $current_date >= $this->workshop_fee->late_amount_starts_on) {
                    $workshop_fee_amount = $this->workshop_fee->late_amount;
                }

                if($is_workshop_fee_international) {
                    $workshop_fee_amount = $workshop_fee_amount * $php_rate_for_usd;
                }
            }

            //Create the order (Registration/Workshop)
            $order = new Order();
            $order->convention_member_id = $this->member->id;
            $order->amount = $this->workshop_fee != null ? $workshop_fee_amount : $registration_fee_amount;
            $order->status = OrderStatusEnum::PENDING;
            $order->save();

            // Create the Order Item (Registration/Workshop)
            $order_item = new OrderItem();
            $order_item->order_id = $order->id;
            $order_item->fee_id = $this->workshop_fee != null ? $this->workshop_fee->id : $this->registration_fee->id;
            $order_item->save();
            
            if(number_format($order->amount, 2) > number_format(0, 2)) {
                $ideapay_fee = $order->amount * $ideapay_rate;
                $order->amount += $ideapay_fee; # ONLY ADD THE IDEAPAY FEE WHEN IT'S NOT A FREE ORDER
                $order->convenience_fee = $ideapay_fee;
                $order->save();
            } else {
                $ideapay_fee = number_format(0, 2);
            }

            $order->is_free = $order->amount == number_format(0, 2);
            $order->save();

            $is_free = $order->is_free;
            $ideapay_id = null;
            $payment_url = null;
            if(!$is_free) {
                $ideapay_service = IdeapayService::create($order); # Please ensure to uncomment this before merging to staging/live branch.

                $ideapay = new Ideapay();
                $ideapay->status = IdeapayStatusEnum::PENDING;
                $ideapay->payment_ref = $ideapay_service['payment_ref']; # Please ensure to uncomment this before merging to staging/live branch.
                $ideapay->payment_url = $ideapay_service['url']; # Please ensure to uncomment this before merging to staging/live branch.
                // $ideapay->payment_ref = 'test'; # For testing on local in case of SSL error
                // $ideapay->payment_url = 'https://google.com'; # For testing on local in case of SSL error
                $ideapay->save();

                $ideapay_id = $ideapay->id;
                $payment_url = $ideapay->payment_url;
            } else {
                $is_earlybird = true;
                // if($transaction->$order->order_items[0]->$fee->uses_late_amount && $current_date >= $transaction->$order->order_items[0]->$fee->uses_late_amount) {
                //     $is_earlybird = false;
                // }

                $payment = new Payment();
                $payment->convention_member_id = Auth::user()->member->id;
                $payment->order_id = $order->id;
                $payment->payment_method = PaymentMethodEnum::FREE;
                $payment->amount = $order->amount;
                $payment->date_paid = Carbon::now();
                $payment->is_earlybird = $is_earlybird;
                $payment->save();

                $order->status = OrderStatusEnum::COMPLETED;
                $order->save();
                DB::commit();
            }

            $transaction = new Transaction();
            $transaction->order_id = $order->id;
            $transaction->amount = $order->amount;
            $transaction->ideapay_id = $ideapay_id;
            $transaction->ideapay_fee = $ideapay_fee;
            $transaction->save();

            DB::commit();

            return array(
                'message' => 'Successfully added order',
                'order_id' => $order->id,
                'payment_url' => $payment_url,
                'is_free' => $is_free,
                'code' => 200,
            );
        } catch(Exception $e) {
            DB::rollBack();
            return array(
                'message' => 'Unable to add order',
                'error' => $e,
                'code' => 400,
            );
        }
    }
}