<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantInstitution extends Model
{
    public $table = 'applicant_institutions';

	public $fillable = [
		'name'
	];

	protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
