<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $table = 'order_items';

	public $fillable = [
        'order_id',
        'fee_id'
	];

	public function order() {
		return $this->belongsTo(Order::class, 'order_id');
	}

    public function fee() {
		return $this->belongsTo(Fee::class, 'fee_id');
	}
}
