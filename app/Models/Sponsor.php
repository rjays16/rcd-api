<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

use App\Models\SponsorStamp;

use App\Enum\SponsorTypeEnum;

class Sponsor extends Model
{
	use SoftDeletes;

	public $table = 'sponsors';

	public $fillable = [
		'user_id',
		'sponsor_type_id',
        'logo',
		'name',
		'rep_name',
		'rep_phone',
		'rep_landline',
		'website',
		'description',
		'phone',
        'company_email',
        'interactive_profile',
		'kuula_iframe',
		'num_360_views',
		'num_company_profile_views',
		'booth_design_id',
		'announcement',
		'address',
		'has_industry_lecture',
		'is_lecture_only',
		'lecture',
		'lecture_background',
		'slug'
	];

	protected $casts = [
        'has_industry_lecture' => 'boolean',
        'is_lecture_only' => 'boolean'
    ];

	protected $appends = [
        'user_is_eligible_to_stamp',
		'user_has_stamped_for_current_round'
    ];

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function type() {
		return $this->belongsTo(SponsorType::class, 'sponsor_type_id');
	}

	public function booth_design() {
		return $this->belongsTo(BoothDesign::class, 'booth_design_id');
	}

	public function assets() {
		return $this->hasMany(SponsorAsset::class, 'sponsor_id');
	}

	public function exhibitors() {
		return $this->hasMany(SponsorExhibitor::class, 'sponsor_id');
	}

	public function visit_logs() {
		return $this->hasMany(SponsorVisitLog::class, 'sponsor_id');
	}

	public function stamps() {
		return $this->hasMany(SponsorStamp::class, 'sponsor_id');
	}

	public function scopeHasIndustryLecture($query) {
		return $query->where('has_industry_lecture', true);
	}

	public function scopeIsIndustryLecture($query) {
		return $query->where('is_lecture_only', true)->hasIndustryLecture();
	}

	public function scopeNotIndustryLectureOnly($query) {
		return $query->where('is_lecture_only', false);
	}

	public function scopePlatinum($query) {
		return $query->where('sponsor_type_id', SponsorTypeEnum::PLATINUM);
	}

	public function scopeGold($query) {
		return $query->where('sponsor_type_id', SponsorTypeEnum::GOLD);
	}

	public function scopeSilver($query) {
		return $query->where('sponsor_type_id', SponsorTypeEnum::SILVER);
	}

	public function scopeBronze($query) {
		return $query->where('sponsor_type_id', SponsorTypeEnum::BRONZE);
	}

	public function getUserHasStampedForCurrentRoundAttribute() {
		$user_has_stamped_for_current_round = false;

		if(!Auth::check()) {
			return false;
		}

		$user = Auth::user();
		$member = $user->member;

		if(is_null($member)) {
			return false;
		}
		
		$stamp = SponsorStamp::where('user_id', $user->id)
			->where('sponsor_id', $this->id)
			->first();

		$current_member_stamp_round_number = $member->current_stamp_round_number;
		$current_round_stamp = SponsorStamp::where('user_id', $user->id)
			->where('round_number', $current_member_stamp_round_number)
			->where('sponsor_id', $this->id)
			->first();

		if(!is_null($current_round_stamp)) {
			$user_has_stamped_for_current_round = true;
		}

		return $user_has_stamped_for_current_round;
	}

	public function getUserIsEligibleToStampAttribute() {
		$user_is_eligible_to_stamp = false;

		if(!Auth::check()) {
			return false;
		}

		$user = Auth::user();
		$member = $user->member;

		if(is_null($member)) {
			return false;
		}
		
		// The member is considered as stamped for the current round if the member is marked as eligible
		// And the current number of sponsor stamps has not yet reached the current number of stampable sponsors

		// First, get the current number of stamps in the member's current round
		$current_member_stamp_round_number = $member->current_stamp_round_number;
		$current_round_stamps = SponsorStamp::where('user_id', $user->id)
			->where('round_number', $current_member_stamp_round_number)
			->where('sponsor_id', $this->id)
			->count();

		// Second, check if the member has already stamped for the booth sponsor during the current round
		$stamp = SponsorStamp::where('user_id', $user->id)
			->where('round_number', $current_member_stamp_round_number)
			->where('sponsor_id', $this->id)
			->first();

		$num_stampable_sponsors = self::notIndustryLectureOnly()->count();

		if(is_null($stamp) && $current_round_stamps < $num_stampable_sponsors) {
			$user_is_eligible_to_stamp = true;
		}

		return $user_is_eligible_to_stamp;
	}
}