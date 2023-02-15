<?php

namespace App\Exports\SponsorAnalytics;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\Sponsor;
use App\Models\User;
use App\Models\Chat;
use App\Models\SponsorAnalytics;

use App\Enum\SponsorAssetTypeEnum;

use DB;

class BoothStatisticsExport implements FromCollection, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $sponsor_id;

    public function __construct(int $sponsor_id) {
        $this->sponsor_id = $sponsor_id;
    }

    public function title(): string {
        return 'Booth Statistics';
    }

    public function collection() {
        $sponsor = Sponsor::where('id', $this->sponsor_id)
            ->whereHas('user')
            ->first();
        if(is_null($sponsor)) {
            return null;
        }

        $total_visits = $sponsor->visit_logs()->count();
        $total_stamps = $sponsor->stamps()->count();

        $chat_sender_ids = Chat::where('receiver_id', $sponsor->user->id)->pluck('sender_id');
        $total_chatters = User::whereIn('id', $chat_sender_ids)
            ->where('is_anon_for_chat', false) // only include those who are no longer anonymous
            ->count();

        $total_360_views = $sponsor->num_360_views;
        $total_company_profile_views = $sponsor->num_company_profile_views;

        $total_product_catalog_views = SponsorAnalytics::where('sponsor_id', $sponsor->id)
            ->whereHas('asset', function ($query) { 
                $query->where('type', SponsorAssetTypeEnum::PRODUCT_CATALOGUE);
        })->sum('num_of_views');

        $total_brochures_views = SponsorAnalytics::where('sponsor_id', $sponsor->id)
            ->whereHas('asset', function ($query) { 
                $query->where('type', SponsorAssetTypeEnum::BROCHURE);
        })->sum('num_of_views');

        $total_video_views = SponsorAnalytics::where('sponsor_id', $sponsor->id)
            ->whereHas('asset', function ($query) { 
                $query->where('type', SponsorAssetTypeEnum::VIDEO);
        })->sum('num_of_views');

        $booth_stats = array(
            [ 'id' => 'Total VEX Booth Visits', 'total_visits' => $total_visits ],
            [ 'id' => 'Delegates Collected Virtual Stamps', 'total_stamps' => $total_stamps ],
            [ 'id' => 'Delegate Started a Chat', 'total_chatters' => $total_chatters ],
            [ 'id' => 'Views on 360° Tour', 'total_360_views' => $total_360_views ],
            [ 'id' => 'Views on Company Profile', 'total_company_profile_views' => $total_company_profile_views ],
            [ 'id' => 'Views on Catalog', 'total_product_catalog_views' => $total_product_catalog_views ],
            [ 'id' => 'Total Clicks on Brochures', 'total_brochures_views' => $total_brochures_views],
            [ 'id' => 'Total Views on Videos', 'total_video_views' => $total_video_views ]
        );
        
        return collect($booth_stats);
    }
}