<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Symposia extends Model
{
	use SoftDeletes;

	public $table = 'symposia';

	public $fillable = [
		'title',
		'author',
        'thumbnail',
        'video',
        'category_id',
		'card_title'
	];

	protected $appends = [
		'total_views',
	];

    public function category() {
		return $this->belongsTo(SymposiaCategory::class, 'category_id');
	}

	public function views() {
		return $this->hasMany(SymposiaView::class, 'symposia_id');
	}

	public function getTotalViewsAttribute() {
		return $this->views()->count();
	}
}