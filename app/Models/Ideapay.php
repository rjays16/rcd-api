<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ideapay extends Model
{
    use SoftDeletes;

	public $table = 'ideapay';

	public $fillable = [
        'payment_ref',
        'payment_url',
        'status',
	];

    public function transaction() {
		return $this->hasOne(Transaction::class, 'ideapay_id');
	}

    public function status() {
        return $this->belongsTo(IdeapayStatus::class, 'status');
    }

    public static function calculateIdeapayFee() {

    }
}
