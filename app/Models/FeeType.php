<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
	public $table = 'fee_types';

	public $fillable = [
		'name'
	];
}