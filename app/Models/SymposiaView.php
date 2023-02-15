<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SymposiaView extends Model
{
	use SoftDeletes;

	public $table = 'symposia_member_views';

	public $fillable = [
		'user_id',
		'symposia_id'
	];

    public function symposia() {
		return $this->belongsTo(Symposia::class, 'symposia_id');
	}
}