<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationType extends Model
{
    public $table = 'registration_types';

	public $fillable = [
		'name',
        'scope',
	];

	protected $hidden = [
        'created_at',
        'updated_at'
    ];
}