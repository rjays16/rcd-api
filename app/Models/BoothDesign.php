<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoothDesign extends Model
{
	use SoftDeletes;

	public $table = 'booth_designs';

	public $fillable = [
		'name',
		'photo',
	];
}