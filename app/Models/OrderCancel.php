<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancel extends Model
{
	public $table = 'orders_cancel';

	public $fillable = [
        'user_id',
        'fee_id'
	];

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

    public function fee() {
		return $this->hasMany(Fee::class, 'fee_id');
	}
}