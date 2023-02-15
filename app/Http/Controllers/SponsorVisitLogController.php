<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sponsor;
use App\Models\SponsorVisitLog;
use App\Models\User;

use App\Http\Requests\Sponsor\CreateVisitLog;

use App\Exports\SponsorVisitLog\Export;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

use Exception;
use DB;

class SponsorVisitLogController extends Controller
{
    public function getSponsorVisitLogs($id) {
        $sponsor = Sponsor::where('id', $id)->first();

        if(!is_null($sponsor)) {
            $visit_logs = SponsorVisitLog::where('sponsor_id', $id)->with('user')->get();

            if($visit_logs->isNotEmpty()) {
                return response()->json($visit_logs);
            } else {
                return response()->json(['message' => 'There are no visits for this sponsor yet'], 400);
            }
        } else {
            return response()->json(['message' => 'Sponsor not found'], 404);
        }
    }

    public function getSponsorVisitLogCount($id) {
        $sponsor = Sponsor::where('id', $id)->first();

        if(!is_null($sponsor)) {
            $log_count = SponsorVisitLog::where('sponsor_id', $id)->with('user')->count();

            if($log_count > 0) {
                return response()->json($log_count);
            } else {
                return response()->json(['message' => 'There are no visits for this sponsor yet'], 400);
            }
        } else {
            return response()->json(['message' => 'Sponsor not found'], 404);
        }
    }

    public function create(CreateVisitLog $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::where('id', $validated["user_id"])->first();
            $sponsor = Sponsor::where('id', $validated["sponsor_id"])->first();
    
            if(!is_null($sponsor)) {
                if(!is_null($user)) {
                    $visit_log = SponsorVisitLog::where('user_id', $user->id)
                        ->where('sponsor_id', $sponsor->id)
                        ->where('date', Carbon::today()->toDateString())
                        ->first();
                    
                        if(is_null($visit_log)) {
                            $visit_log = new SponsorVisitLog();
                            $visit_log->user_id = $user->id;
                            $visit_log->sponsor_id = $sponsor->id;
                            $visit_log->num_visits = 1;
                            $visit_log->last_visited = Carbon::now();
                            $visit_log->date = Carbon::today()->toDateString();
                            $visit_log->save();
                        } else {
                            $visit_log->num_visits = $visit_log->num_visits + 1;
                            $visit_log->last_visited = Carbon::now();
                            $visit_log->date = Carbon::today()->toDateString();
                            $visit_log->save();
                        }
                    DB::commit();
                    return response()->json(['message' => 'Successfully logged the visit for this sponsor']);
                } else {
                    return response()->json(['message' => 'User not found'], 404);
                }
            } else {
                return response()->json(['message' => 'Sponsor not found'], 404);
            }
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function export($id) {
        return Excel::download(new Export($id), 'visit_logs.xlsx');
    }
}