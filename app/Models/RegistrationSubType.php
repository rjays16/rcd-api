<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationSubType extends Model
{
    public $table = 'registration_sub_types';

	public $fillable = [
		'name',
	];
}
