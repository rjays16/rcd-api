<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingInstitution extends Model
{
	public $table = 'training_institutions';

	public $fillable = [
		'name'
	];

	protected $hidden = [
        'created_at',
        'updated_at'
    ];
}