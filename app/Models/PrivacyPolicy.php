<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivacyPolicy extends Model
{
	public $table = 'privacy_policy';

	public $fillable = [
        'banner',
        'content',
	];
}