<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\OnDemandLogs\ExportPlenary;
use App\Exports\OnDemandLogs\ExportWorkshop;
use App\Exports\OnDemandLogs\ExportSymposium;
use App\Exports\OnDemandLogs\ExportIndustry;

use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

use Exception;
use DB;

class OnDemandLogsController extends Controller
{
    public function getPlenaryLogs() {
        $plenary_logs = collect(DB::select("CALL RCDsp_OnDemandPlenaryLogs()"));

        if(!is_null($plenary_logs)) {
            return response()->json($plenary_logs);
        } else {
            return response()->json(['message' => 'There are no logs for the on demand plenary yet.'], 404);
        }
    }

    public function getWorkshopLogs() {
        $workshop_logs = collect(DB::select("CALL RCDsp_OnDemandWorkshopLogs()"));

        if(!is_null($workshop_logs)) {
            return response()->json($workshop_logs);
        } else {
            return response()->json(['message' => 'There are no logs for the on demand workshops yet.'], 404);
        }
    }

    public function getSymposiumLogs() {
        $symposium_logs = collect(DB::select("CALL RCDsp_OnDemandSymposiumLogs()"));

        if(!is_null($symposium_logs)) {
            return response()->json($symposium_logs);
        } else {
            return response()->json(['message' => 'There are no logs for the on demand symposium yet.'], 404);
        }
    }

    public function getIndustryLogs() {
        $industry_logs = collect(DB::select("CALL RCDsp_OnDemandIndustryLogs()"));

        if(!is_null($industry_logs)) {
            return response()->json($industry_logs);
        } else {
            return response()->json(['message' => 'There are no logs for the industry lectures yet.'], 404);
        }
    }

    public function exportPlenary() {
        return Excel::download(new ExportPlenary(), 'ondemand_plenary_logs.xlsx');
    }

    public function exportWorkshop() {
        return Excel::download(new ExportWorkshop(), 'ondemand_workshop_logs.xlsx');
    }

    public function exportSymposium() {
        return Excel::download(new ExportSymposium(), 'ondemand_symposium_logs.xlsx');
    }

    public function exportIndustry() {
        return Excel::download(new ExportIndustry(), 'industry_lecture_logs.xlsx');
    }
}