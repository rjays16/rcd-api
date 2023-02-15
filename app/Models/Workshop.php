<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    public $table = 'workshops';

    public $fillable = [
      'id',
      'name',
	];
}
