<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Enum\SponsorAssetTypeEnum;

class SponsorAsset extends Model
{
	use SoftDeletes;

	public $table = 'sponsor_assets';

	public $fillable = [
		'sponsor_id',
        'type',
        'name',
        'url',
		'position_number'
	];

	protected $appends = [
		'analytic_stats',
	];

	public function sponsor() {
		return $this->belongsTo(Sponsor::class, 'sponsor_id');
	}

	public function analytics() {
		return $this->hasMany(SponsorAnalytics::class, 'sponsor_asset_id');
	}

	public function asset_type() {
		return $this->belongsTo(SponsorAssetType::class, 'type');
	}

	public function scopeVideo($query) {
		return $query->where('type', SponsorAssetTypeEnum::VIDEO);
	}

	public function scopeBrochure($query) {
		return $query->where('type', SponsorAssetTypeEnum::BROCHURE);
	}

	public function scopeCatalogue($query) {
		return $query->where('type', SponsorAssetTypeEnum::PRODUCT_CATALOGUE);
	}

	public function getAnalyticStatsAttribute() {
		return $this->analytics()->sum('num_of_views');
	}
}