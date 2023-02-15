<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyDesign extends Model
{
    public $table = 'study_designs';
    public $fillable = [
        'study_value'
    ];
}
