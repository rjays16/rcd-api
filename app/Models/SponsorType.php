<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorType extends Model
{
    public $table = 'sponsor_types';

    protected $casts = [
        'has_360_view' => 'boolean',
        'has_ticker_text' => 'boolean'
    ];
}