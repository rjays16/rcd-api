<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Config;
use App\Models\User;
use App\Models\Role;
use App\Models\Order;

use App\Enum\RegistrationTypeEnum;
use App\Enum\FeeEnum;

use Exception;

class RateService {
	private $order_id;

    public function __construct($data) {
        $this->order_id = $data["order_id"];
        $this->transaction_id = $data["order_id"];
    }

    public function checkRoles($user) {
        if(!is_null($user)){
            $role = Role::where('id', $user->role)->first();
            if(is_null($role)) {
                return response()->json([
                    'message' => 'Your role is invalid. Please contact the site admin.'
                ], 404);
            } else {
                return response()->json([
                    'message' => 'Role is valid.'
                ]);
            }
        }else{
            return response()->json([
                'message' => 'No user found.'
            ], 404);
        }
    } 

	public function calculate() {
        $total_amount = 0;
        $is_free = false;

        $order = Order::with('order_items', 'order_items.fee:id,name,year,amount,intl_amount', 'convention_member_id','convention_member_id.user:id,role')
                ->whereHas('transaction', function ($query) use ($order) { 
                    $query->where('order_id', $order->id)->first();
                })
                ->where('id', $this->order_id)
                ->first();
                $this->checkRoles($user);

        if(is_null($order)) {
            return response()->json([
                'message' => 'This order does not have an order yet.'
            ], 404);
        }
        
        $ideapay_fee = Config::getIdeapayFee();

        if($order->amount == 0.0 && is_null($user->member->ws_to_attend)){
            $ideapay_fee = number_format(0,2);
            $is_free = true;
        }

        foreach($order->order_items as $item ){
            $total_amount += $item->fee->amount;

            if($item->fee->uses_late_amount){
                $total_amount += $item->fee->late_amount;
            }
        }

        if(!is_null($user)){
            return response()->json([
                'amount' => number_format($total_amount,2),
                'ideapay_fee' => $ideapay_fee,
                'total_amount' => $order->amount,
                'is_free' => $is_free
            ]);
        }else{
            return response()->json([
                'message' => "No fee was found for given registration type."
            ],400);
        }
	}
}