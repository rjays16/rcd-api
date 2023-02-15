<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SymposiaCategory extends Model
{
	use SoftDeletes;

	public $table = 'symposia_categories';

	public $fillable = [
		'title',
		'chair',
        'subtitle',
		'card_header_color'
	];

	public function symposia() {
		return $this->hasMany(Symposia::class, 'category_id');
	}
}