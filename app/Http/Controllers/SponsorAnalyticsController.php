<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Chat;
use App\Models\Sponsor;
use App\Models\SponsorAsset;
use App\Models\SponsorAnalytics;

use App\Enum\SponsorTypeEnum;
use App\Enum\SponsorAssetTypeEnum;

use App\Http\Requests\Sponsor\Analytics\UpdateAssetStatistic;
use App\Http\Requests\Sponsor\Asset\UpdateName;
use App\Exports\SponsorAnalytics\Export;

use Maatwebsite\Excel\Facades\Excel;

use Exception;
use DB;

use Carbon\Carbon;

class SponsorAnalyticsController extends Controller
{
    public function getAnalytics($id) {
        $sponsor = Sponsor::where('id', $id)->with(['user', 'type'])->first();

        if(!is_null($sponsor)) {
            $total_visits = $sponsor->visit_logs()->count();
            $total_stamps = $sponsor->stamps()->count();

            $chat_sender_ids = Chat::where('receiver_id', $sponsor->user->id)->pluck('sender_id');
            $total_chatters = User::whereIn('id', $chat_sender_ids)
                ->where('is_anon_for_chat', false) // only include those who are no longer anonymous
                ->count();

            $total_360_views = $sponsor->num_360_views;
            $total_company_profile_views = $sponsor->num_company_profile_views;

            $total_product_catalog_views = SponsorAnalytics::where('sponsor_id', $id)
                ->whereHas('asset', function ($query) {
                    $query->where('type', SponsorAssetTypeEnum::PRODUCT_CATALOGUE);
            })->sum('num_of_views');

            $total_brochures_views = SponsorAnalytics::where('sponsor_id', $id)
                ->whereHas('asset', function ($query) {
                    $query->where('type', SponsorAssetTypeEnum::BROCHURE);
            })->sum('num_of_views');

            $total_video_views = SponsorAnalytics::where('sponsor_id', $id)
                ->whereHas('asset', function ($query) {
                    $query->where('type', SponsorAssetTypeEnum::VIDEO);
            })->sum('num_of_views');

            $brochures = $sponsor->assets()->brochure()->get();
            $videos = $sponsor->assets()->video()->get();

            return response()->json([
                'total_visits' => $total_visits,
                'total_stamps' => $total_stamps,
                'total_chatters' => $total_chatters,
                'total_360_views' => $total_360_views,
                'total_company_profile_views' => $total_company_profile_views,
                'total_product_catalog_views' => $total_product_catalog_views,
                'total_brochures_views' => $total_brochures_views,
                'total_video_views' => $total_video_views,
                'brochures' => $brochures,
                'videos' => $videos,
            ]);
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

    public function updateAssetStatistic(UpdateAssetStatistic $request, $id) {
        $validated = $request->validated();

        $sponsor = Sponsor::where('id', $id)->first();

        if(!is_null($sponsor)) {
            $statistic = SponsorAnalytics::where([
                ['sponsor_id', $validated["sponsor_id"]],
                ['sponsor_asset_id', $validated["sponsor_asset_id"]],
                ['date', Carbon::today()->toDateString()]
            ])->first();

            DB::beginTransaction();
            try {
                if(is_null($statistic)) {
                    $validated["date"] = Carbon::today()->toDateString();
                    $validated["num_of_views"] = 1;
                    $statistic = SponsorAnalytics::create($validated);
                    $message = 'Successfully created statistic for the sponsor analytics for this asset.';
                } else {
                    $statistic->num_of_views += 1;
                    $statistic->date = Carbon::today()->toDateString();
                    $statistic->save();
                    $message = 'Successfully updated sponsor analytics for this asset.';
                }

                DB::commit();
                return response()->json([
                    'message' => $message
                ]);
            } catch(Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

    public function updateNumCompanyProfileViews($id) {
        $sponsor = Sponsor::where('id', $id)->first();

        if(!is_null($sponsor)) {
            $sponsor->num_company_profile_views += 1;
            $sponsor->save();

            DB::commit();
            return response()->json([
                'message' => "Successfully updated the number of company profile views for this sponsor."
            ]);
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

//    public function updateName(UpdateName $request, $id)
//    {
//        $validated = $request->validated();
//
//        $sponsor = SponsorAsset::where('sponsor_id', $id)->first();
//
//        if (is_null($sponsor)) {
//            return response()->json(['message' => 'Sponsor not found.'], 404);
//        }
//        DB::beginTransaction();
//        try {
//            $sponsor->update($validated);
//            DB::commit();
//
//            return response()->json([
//                'message' => 'Successfully updated name'
//            ]);
//        } catch (Exception $e) {
//            DB::rollBack();
//            throw $e;
//        }
//    }

    public function updateNum360Views($id) {
        $sponsor = Sponsor::where('id', $id)->first();

        if(!is_null($sponsor)) {
            $sponsor->num_360_views += 1;
            $sponsor->save();

            DB::commit();
            return response()->json([
                'message' => "Successfully updated the number of 360 views for this sponsor."
            ]);
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

    public function export($id) {
        return Excel::download(new Export($id), 'analytics_report.xlsx');
    }
}
