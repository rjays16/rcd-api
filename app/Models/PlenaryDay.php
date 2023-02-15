<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlenaryDay extends Model
{
    use SoftDeletes;

	public $table = 'plenary_days';

	public $fillable = [
        'date',
		'title',
        'starts_at',
        'ends_at'
	];
}
