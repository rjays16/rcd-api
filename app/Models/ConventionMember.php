<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Order;

use App\Enum\OrderStatusEnum;
use App\Enum\UserStatusEnum;
use App\Enum\RegistrationTypeEnum;
use App\Enum\WorkshopEnum;

class ConventionMember extends Model
{
	use SoftDeletes;

	public $table = 'convention_members';

	public $fillable = [
		'user_id',
        'pma_number',
        'prc_license_number',
        'prc_expiration_date',
        'pds_number',
        'type',
        'is_interested_for_ws',
        'ws_to_attend',
        'training_institution',
        'applicant_institution',
        'resident_certificate',
        'is_good_standing',
        'sub_type',
        'is_sponsor_exhibitor',
        'num_raffle_tickets',
        'is_eligible_for_next_stamp_round',
        'current_stamp_round_number',
        'can_generate_certificate',
	];

	protected $casts = [
        'is_interested_for_ws' => 'boolean',
        'is_good_standing' => 'boolean',
        'is_sponsor_exhibitor' => 'boolean',
        'is_eligible_for_next_stamp_round' => 'boolean',
        'can_generate_certificate' => 'boolean'
    ];

	protected $appends = [
        'limit_convention_access',
        // 'has_pending_order',
        // 'has_pending_payment',
		// 'pending_order_payment_method',
        // 'can_generate_certificate',
        // 'is_late_registrant',
        'can_submit_abstract',
        'has_paid_registration_fee',
        'paid_fees'
    ];

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function order() {
        return $this->hasOne(Order::class, 'convention_member_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'convention_member_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'convention_member_id');
    }

    public function payment() {
        return $this->hasOne(Payment::class, 'convention_member_id');
    }

    public function registration_type() {
        return $this->belongsTo(RegistrationType::class, 'type');
    }

    public function registration_sub_type() {
		return $this->belongsTo(RegistrationSubType::class, 'sub_type');
	}

    public function workshop() {
        return $this->belongsTo(Workshop::class, 'ws_to_attend');
    }

    public function getLimitConventionAccessAttribute() {
        return $this->is_sponsor_exhibitor ? false : !$this->has_paid_registration_fee;
    }

    // public function getHasPendingOrderAttribute() {
    //     $order = $this->order;
    //     $has_pending_order = false;
    //     if(!empty($order)) {
    //         $pending_order_ids = $order->where('status', OrderStatusEnum::PENDING)
	// 			->pluck('id');

	// 		$pending_order = Order::whereIn('id', $pending_order_ids)->doesntHave('payment')->get();

    //         if($pending_order->isNotEmpty()) {
    //             $has_pending_order = true;
    //         }
    //     }

    //     return $has_pending_order;
    // }

	// public function getHasPendingPaymentAttribute() {
    //     $order = $this->order;
    //     $has_pending_payment = "No";
    //     if(!empty($order)) {
    //         $pending_order_ids = $order->where('status', [OrderStatusEnum::PENDING])
	// 			->pluck('id');

	// 		$pending_order = Order::whereIn('id', $pending_order_ids)->whereHas('payment')->get();

    //         if($pending_order->isNotEmpty()) {
    //             $has_pending_payment = "Yes";
    //         }
    //     }

    //     return $has_pending_payment;
    // }

	// public function getPendingOrderPaymentMethodAttribute() {
	// 	$payment_method = "None";
	// 	$order_payment = null;

    //     $order = $this->order;
    //     if(!empty($order)) {
    //         $pending_order = $order->where('status', [OrderStatusEnum::PENDING])->first();

    //         if(!is_null($pending_order)) {
    //             if(!is_null($pending_order->payment)) {
    //                 $order_payment = $pending_order->payment->first();
    //                 if(!is_null($order_payment)) {
    //                     $payment_method = $order_payment->method->name;
    //                 }
    //             }
    //         }
    //     }

    //     return $payment_method;
    // }

    // public function getCanGenerateCertificateAttribute() {
    //     $can_generate_certificate = false;
    //     $order = $this->order;
    //     $payments = $this->payments;

    //     if(!empty($order) && !empty($payments)) {
    //         $order_id = $order->id;

	// 		$pending_order = Order::where('id', $order_id)
    //         ->whereHas('payment', function ($query) { 
    //             $query->where('status', [OrderStatusEnum::PENDING, OrderStatusEnum::FAILED]);
    //         })->get();

    //         if($pending_order->isEmpty()) {
    //             $can_generate_certificate = true;
    //         }
    //     }

    //     return $can_generate_certificate;
    // }

    // public function getIsLateRegistrantAttribute() {
    //     $is_late_registrant = false;
    //     $order = $this->order;
    //     $late_payment_date = '2022-02-26';

    //     if(!empty($order)) {
    //         $order_id = $order->id;
	// 		$late_paid_order = Order::where('id', $order_id)
    //         ->whereHas('payment', function ($query) use ($late_payment_date) { 
    //             $query->where('date_paid', '>=', $late_payment_date);
    //         })
    //         ->get();

    //         if($late_paid_order->isNotEmpty()) {
    //             $is_late_registrant = true;
    //         }
    //     }

    //     return $is_late_registrant;
    // }

    public function getCanSubmitAbstractAttribute() {
        $can_submit = false;
        $registration_type = $this->registration_type;

        $order = $this->order;
        $payments = $this->payments;

        if(!empty($order) && !empty($payments)) {
            $order_id = $order->id;

			$pending_order = Order::where('id', $order_id)
            ->whereHas('payment', function ($query) { 
                $query->whereIn('status', [OrderStatusEnum::PENDING, OrderStatusEnum::FAILED]);
            })->get();

            if($pending_order->isEmpty()) {
                $can_submit = true;
            }
        }

        if(!is_null($registration_type) && in_array($registration_type->id, [RegistrationTypeEnum::SPEAKER_SESSION_WORKSHOP_CHAIR, RegistrationTypeEnum::LOCAL_PDS_COA_BOD_OC])) {
            $can_submit = true;
        }

        return $can_submit;
    }

    public function getHasPaidRegistrationFeeAttribute() {
        $member_order_ids = Order::query()->where('convention_member_id', $this->id)->pluck('id');

        $paid_member_registration_fee = Order::whereIn('id', $member_order_ids)
            ->whereHas('order_items', function ($query) { 
                $query->whereHas('fee', function($sub_query) {
                    $sub_query->where('registration_type', $this->type);
                });
            })
            ->whereHas('payment')
            ->first();

        return !is_null($paid_member_registration_fee);
    }

    public function getPaidFeesAttribute() {
        $member_order_ids = Order::query()->where('convention_member_id', $this->id)->pluck('id');

        $paid_member_ws_fee_aesth = Order::whereIn('id', $member_order_ids)
            ->whereHas('order_items', function ($query) { 
                $query->whereHas('fee', function($sub_query) {
                    $sub_query->where('registration_type', $this->type)
                        ->where('workshop_type', WorkshopEnum::AESTHETIC);
                });
            })
            ->whereHas('payment')
            ->first();

        $paid_member_ws_fee_laser = Order::whereIn('id', $member_order_ids)
            ->whereHas('order_items', function ($query) { 
                $query->whereHas('fee', function($sub_query) {
                    $sub_query->where('registration_type', $this->type)
                        ->where('workshop_type', WorkshopEnum::LASER);
                });
            })
            ->whereHas('payment')
            ->first();

        $paid_member_ws_fee_both = Order::whereIn('id', $member_order_ids)
            ->whereHas('order_items', function ($query) { 
                $query->whereHas('fee', function($sub_query) {
					$sub_query->where('registration_type', $this->type)
                        ->where('workshop_type', WorkshopEnum::BOTH_AESTHETIC_AND_LASER);
				});
            })
            ->whereHas('payment')
            ->first();

        $has_paid_indiv_both_aesth_laser = !is_null($paid_member_ws_fee_aesth) && !is_null($paid_member_ws_fee_laser);

        return array(
            'has_paid_registration' => $this->is_sponsor_exhibitor ? true : $this->has_paid_registration_fee,
            'has_paid_ws_laser' => $this->is_sponsor_exhibitor ? true : !is_null($paid_member_ws_fee_laser),
            'has_paid_ws_aesth' => $this->is_sponsor_exhibitor ? true : !is_null($paid_member_ws_fee_aesth),
            'has_paid_ws_both' => $this->is_sponsor_exhibitor ? true : (!is_null($paid_member_ws_fee_both) || $has_paid_indiv_both_aesth_laser),
        );
    }
}