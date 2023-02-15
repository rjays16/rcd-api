<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
	use SoftDeletes;

	public $table = 'fees';

	public $fillable = [
		'name',
        'description',
        'year',
        'type',
        'scope', # if true, it is global (USD). If false, it is local (PHP)
        'amount', # From April 18, to July 30
        'intl_amount',
        'status',
        'uses_late_amount',
        'late_amount', # From July 1, to October 18
        'late_amount_starts_on',
        'registration_type',
        'workshop_type'
	];

    protected $casts = [
        'scope' => 'boolean',
        'status' => 'boolean',
        'uses_late_amount' => 'boolean',
        'year' => 'string'
    ];

    public function fee_type() {
        return $this->belongsTo(FeeType::class, 'type');
    }

    public function registration() {
        return $this->belongsTo(RegistrationType::class, 'registration_type');
    }

    public function workshop() {
        return $this->belongsTo(Workshop::class, 'workshop_type');
    }
}