<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsorStamp extends Model
{
	use SoftDeletes;

	public $table = 'sponsor_stamps';

	public $fillable = [
		'user_id',
		'sponsor_id',
        'date',
		'round_number'
	];

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function sponsor() {
		return $this->belongsTo(Sponsor::class, 'sponsor_id');
	}
}