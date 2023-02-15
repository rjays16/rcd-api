<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table = 'categories';

    public $fillable = [
        'category_value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function abstracts() {
        return $this->hasMany(Abstracts::class, 'abstract_category', 'category_value');
    }
}
