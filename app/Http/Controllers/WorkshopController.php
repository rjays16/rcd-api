<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\Attendance\ExportWorkshop;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class WorkshopController extends Controller
{
    public function getWorkshopAttendanceByDelegate($id) {
        $date_now = Carbon::today()->toDateString();
        $ws_attendance = collect(DB::select("CALL RCDsp_LedgerWorkshopAttendancePending('$id')"));
        $logs = collect(DB::select("CALL RCDsp_LedgerWorkshopAttendance('$id')"));

        foreach($ws_attendance as $key=>$wsa){
            $keys = array_column(json_decode($logs), 'Logged_Date');
            if($wsa->Logged_Date == "2022-10-04"){
                $index = array_search($wsa->Logged_Date, $keys);
                if($index !== false){
                    $ws_attendance[$key] = $logs[$index];
                }
                if($date_now > "2022-10-04" && $wsa->Login_Time == '00:00:00' && $wsa->Login_Time == '00:00:00'){
                    $wsa->Status = 'No Record';
                }else if($date_now < "2022-10-04" && $wsa->Login_Time == '00:00:00' && $wsa->Login_Time == '00:00:00'){
                    $wsa->Status = 'Waiting';
                }
            }else if($wsa->Logged_Date == "2022-10-05"){
                $index = array_search($wsa->Logged_Date, $keys);
                if($index !== false){
                    $ws_attendance[$key] = $logs[$index];
                }
                if($date_now > "2022-10-05" && $wsa->Login_Time == '00:00:00' && $wsa->Login_Time == '00:00:00'){
                    $wsa->Status = 'No Record';
                }else if($date_now < "2022-10-05" && $wsa->Login_Time == '00:00:00' && $wsa->Login_Time == '00:00:00'){
                    $wsa->Status = 'Waiting';
                }
            }
        }

        if($ws_attendance->isNotEmpty()) {
            return response()->json($ws_attendance);
        } else {
            return response()->json(['message' => 'Member has no plenary attendance yet.'], 404);
        }
    }

    public function getWorkshopAttendanceList(){
        $workshop_attendance = collect(DB::select("CALL RCDsp_LedgerListWorkshopAttendance()"));
        if(!is_null($workshop_attendance)) {
            return response()->json($workshop_attendance);
        } else {
            return response()->json(['message' => 'Member has no plenary attendance yet.'], 404);
        }
    }

    public function export() {
        return Excel::download(new ExportWorkshop(), 'Workshop_attendance.xlsx');
    }

}
