<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlenaryEvent extends Model
{
	use SoftDeletes;

	public $table = "plenary_events";

	public $fillable = [
		'date',
		'title',
		'speaker_description',
		'starts_at',
		'ends_at',
		'header_color'
	];
}