<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{
	public $table = 'terms_and_conditions';

	public $fillable = [
        'banner',
        'content',
	];
}