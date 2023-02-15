<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

use App\Models\Payment;
use App\Models\Transaction;

use App\Enum\PaymentMethodEnum;

use App\Mail\Invoice;
use App\Events\Payment\Redirect;

use Carbon\Carbon;

use Exception;
use DB;

class PaymentService {
    private $ideapay_payment;

    public function __construct($ideapay_payment) {
        $this->ideapay_payment = $ideapay_payment;
        $this->create();
    }

    public function create() {
        DB::beginTransaction();
        try {
            $is_free = false;
            $ideapay_payment = $this->ideapay_payment;

            $transaction = Transaction::with('order')->where('ideapay_id', $ideapay_payment->id)->first();

            if(is_null($transaction)) {
                throw new Exception("Unable to process payment, please report to the site admin. Ideapay: $ideapay_payment->id");
            }

            $order = $transaction->order;
            $convention_member = $order->member;

            $is_earlybird = true;
            $current_date = Carbon::now()->format('Y-m-d');
            // if($transaction->$order->order_items->$fee->uses_late_amount && $current_date >= $transaction->$order->order_items->$fee->uses_late_amount) {
            //     $is_earlybird = false;
            // }

            $payment = new Payment(); 
            $payment->convention_member_id = $convention_member->id;
            $payment->payment_method = PaymentMethodEnum::IDEAPAY;
            $payment->order_id = $transaction->order->id;
            $payment->amount = $transaction->order->amount;
            $payment->intl_amount = $transaction->order->intl_amount;
            $payment->date_paid = Carbon::now();
            $payment->is_earlybird = $is_earlybird;
            $payment->save();
            DB::commit();
            
            // event(new Redirect($order));
            Mail::to($convention_member->user->email)->send(new Invoice($convention_member->user, $payment)); 
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}