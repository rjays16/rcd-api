<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

	public $table = 'logs';

	public $fillable = [
        'convention_member_id',
        'url',
        'date_time',
        'is_login',
        'is_logout',
	];

    public function convention_member() {
		return $this->hasOne(ConventionMember::class, '');
	}
}
