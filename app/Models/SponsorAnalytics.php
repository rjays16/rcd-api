<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsorAnalytics extends Model
{
	use SoftDeletes;

	public $table = 'sponsor_analytics';

	public $fillable = [
		'sponsor_id',
        'sponsor_asset_id',
		'num_of_views',
        'date'
	];

	public function sponsor() {
		return $this->belongsTo(Sponsor::class, 'sponsor_id');
	}

    public function asset() {
		return $this->belongsTo(SponsorAsset::class, 'sponsor_asset_id');
	}
}