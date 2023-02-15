<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlenaryAttendance extends Model
{
    use SoftDeletes;

	public $table = 'plenary_attendance';

	public $fillable = [
        'date',
		'convention_member_id',
		'plenary_event_id',
        'logged_in_at',
        'logged_out_at'
	];

    public function member() {
		return $this->belongsTo(ConventionMember::class, 'convention_member_id');
	}

	public function event() {
		return $this->belongsTo(PlenaryEvent::class, 'plenary_event_id');
	}

	public function plenary_day() {
		return $this->belongsTo(PlenaryDay::class, 'plenary_day_id');
	}
}