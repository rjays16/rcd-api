<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopSchedule extends Model
{
    use SoftDeletes;

    public $table = "workshop_schedules";

    public $fillable = [
        'workshop_name',
        'workshop_sdate',
        'workshop_edate',
        'is_active',
    ];
}
