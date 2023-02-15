<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Ideapay;
use App\Models\Config;

use App\Enum\OrderStatusEnum;
use App\Enum\IdeapayStatusEnum;

use App\Services\IdeapayService;
use App\Services\PaymentService;

use Illuminate\Support\Facades\Mail;
use App\Mail\Cancelled_Failed;

use Carbon\Carbon;

use Exception;
use DB;

class IdeapayController extends Controller
{
    public function success() {
        return response('successful payment');
    }

    public function verifyOrderStatus(Request $request){
        $data = [
            'response_code' => $request->response_code,
            'response_message' => $request->response_message,
            'payment_id' => $request->payment_id,
    		'signature' => hash('sha512', config('ideapay.client_secret'))
        ];

        // $channel = Log::build([
        //     'driver' => 'single',
        //     'path' => storage_path('logs/ideapay/controller.log'),
        // ]);

        $ideapay_payment = Ideapay::where('payment_ref', $data['payment_id'])->first();

        if(is_null($ideapay_payment)) {
            return response('Payment not found');
        } else {
            $transaction = Transaction::where('ideapay_id', $ideapay_payment->id)->with('order', 'order.member', 'order.member.user')->first();
            if($ideapay_payment->status == IdeapayStatusEnum::PENDING) {
                try {
                    $status = (new IdeapayService())->getStatus($data);
                    $transaction = $ideapay_payment->transaction;

                    if($transaction) {
                        $order = $transaction->order;
                        $user = $order->member->user;
                        if($status == IdeapayStatusEnum::SUCCESS) {
                            $ideapay_payment->status = $status;
                            $ideapay_payment->save();

                            $order->status = $ideapay_payment->status;
                            $order->save();

                            // Record payment on success
                            new PaymentService($ideapay_payment);
                            // return response('Payment verified');
                            return view('ideapay.success');
                        } else {
                            // Log::critical(['slack', $channel])->info("The transaction exists, but Ideapay returned an unsucessful status. Ideapay ID: $ideapay_payment->id. Ideapay Status: $status \n");
                            Mail::to($transaction->order->member->user->email)->send(new Cancelled_Failed($transaction->order->member->user));
                            return view('ideapay.error');
                        }
                    } else {
                        // Log::critical(['slack', $channel])->info("The transaction was not found. Ideapay ID: $ideapay_payment->id \n");
                        Mail::to($transaction->order->member->user->email)->send(new Cancelled_Failed($transaction->order->member->user));
                        return view('ideapay.error');
                    }
                } catch(Exception $e) {
                    throw $e;
                }
            } elseif($ideapay_payment->status == IdeapayStatusEnum::SUCCESS) {
                // return response('Payment was successful');
                // Log::stack(['slack', $channel])->info("This is an existing successful Ideapay record. Ideapay ID: $ideapay_payment->id \n");
                return view('ideapay.success');
            } elseif($ideapay_payment->status == IdeapayStatusEnum::FAILED) {
                // return response('Payment was unsuccessful');
                // Log::stack(['slack', $channel])->info("This is an existing failed Ideapay record. Ideapay ID: $ideapay_payment->id \n");
                Mail::to($transaction->order->member->user->email)->send(new Cancelled_Failed($transaction->order->member->user));
                return view('ideapay.error');
            } else {
                // Log::stack(['slack', $channel])->info("Payment record could not be processed. Ideapay ID: $ideapay_payment->id \n");
                Mail::to($transaction->order->member->user->email)->send(new Cancelled_Failed($transaction->order->member->user));
                // return response('Payment record could not be processed');
                return view('ideapay.error');
            }
        }
    }

    public function create(Request $request) {
        $order_id = $request->order_id;
        $ideapay_fee = Config::getIdeapayFee();
        $order = Order::with('transaction')->where('id', $order_id)->first();

        if(!is_null($order)) {
            if($order->status == OrderStatusEnum::COMPLETED) {
                return response()->json([
                    'message' => 'This order has already been completed'
                ], 400);
            } else {
                DB::beginTransaction();
                try {
                    $payment = IdeapayService::create($order);

                    $ideapay = new Ideapay();
                    $ideapay->status = IdeapayStatusEnum::PENDING;
                    $ideapay->payment_ref = $payment['payment_ref'];
                    $ideapay->payment_url = $payment['url'];
                    $ideapay->save();

                    $transaction = Transaction::where('order_id', $order_id)->first();
                    if(is_null($transaction)) {
                        $transaction = new Transaction();
                        $transaction->order_id = $order->id;
                        $transaction->amount = $order->amount;
                        $transaction->ideapay_fee = $order->convenience_fee;
                        $transaction->save();
                    }
                    $transaction->ideapay_id = $ideapay->id;
                    $transaction->save();
                    DB::commit();

                    return response()->json([
                        'payment_url' => $ideapay->payment_url,
                        'order_id' => $transaction->order_id
                    ]);
                } catch(Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
        } else {
            return response()->json([
                'message' => 'This order does not exist'
            ], 404);
        }
    }
}