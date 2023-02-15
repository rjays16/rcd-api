<?php

namespace App\Http\Controllers;

use App\Exports\Attendance\ExportPlenary;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ConventionMember;
use App\Models\PlenaryEvent;
use App\Models\PlenaryAttendance;

use App\Enum\RoleEnum;

use App\Http\Requests\Plenary\Attendance\Create;
use App\Http\Requests\Plenary\Attendance\GetByDate;

use Carbon\Carbon;

use Exception;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class PlenaryAttendanceController extends Controller
{
    public function getPlenaryAttendance() {
        $plenary_attendance = PlenaryAttendance::with(['member.user', 'event'])->get();

        if($plenary_attendance->isNotEmpty()) {
            return response()->json($plenary_attendance);
        } else {
            return response()->json(['message' => 'There is no plenary attendance yet.'], 404);
        }
    }

    public function getMember($id){
        $convention_member = ConventionMember::with(['user'])->get();

        if($convention_member->isNotEmpty()) {
            return response()->json($convention_member);
        } else {
            return response()->json(['message' => 'Covention member not found'], 404);
        }
    }
    public function getPlenaryAttendanceByDate(GetByDate $request) {
        $validated = $request->validated();
        $plenary_attendance = PlenaryAttendance::where('date', $validated["date"])->first();

        if(!is_null($plenary_attendance)) {
            return response()->json($plenary_attendance);
        } else {
            return response()->json(['message' => 'There was no plenary attendance found for this day.'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        $user = auth()->user();
        if($user->role != RoleEnum::CONVENTION_MEMBER) {
            return response()->json(['message' => 'Invalid role.'], 400);
        }

        $date_now = Carbon::today()->toDateString();
        $plenary_attendance = PlenaryAttendance::where('date', $date_now)->where('convention_member_id', $user->member->id)->first();

        if(is_null($plenary_attendance)) {
            DB::beginTransaction();
            try {
                $validated["date"] = $date_now;
                $validated["logged_in_at"] = Carbon::now()->toTimeString();
                $validated["convention_member_id"] = $user->member->id;
                PlenaryAttendance::create($validated);

                DB::commit();
                return response()->json([
                    'message' => 'Successfully created plenary attendance'
                ]);
            } catch(Exception $e){
                DB::rollBack();
                throw $e;
            }
        } else {
            return response()->json(['message' => 'The member already has a plenary attendance for today.'], 400);
        }
    }

    public function logoutMember(Create $request) {
        $user = auth()->user();
        if($user->role != RoleEnum::CONVENTION_MEMBER) {
            return response()->json(['message' => 'Invalid role.'], 400);
        }

        $date_now = Carbon::today()->toDateString();
        $plenary_attendance = PlenaryAttendance::where('date', $date_now)->where('convention_member_id', $user->member->id)->first();

        if(!is_null($plenary_attendance)) {
            if(is_null($plenary_attendance->logged_out_at)) {
                DB::beginTransaction();
                try {
                    $validated["logged_out_at"] = Carbon::now()->toTimeString();
                    $plenary_attendance->fill($validated);
                    $plenary_attendance->save();

                    DB::commit();
                    return response()->json([
                        'message' => 'Successfully recorded the logged out time for the member.'
                    ]);
                } catch(Exception $e){
                    DB::rollBack();
                    throw $e;
                }
            } else {
                return response()->json([
                    'message' => 'The member has already logged out from the plenary on this day.',
                    'logged_out_at' => $plenary_attendance->logged_out_at
                ], 404);
            }
        } else {
            return response()->json(['message' => 'The plenary attendance for this member was not found.'], 404);
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $plenary_attendance = PlenaryAttendance::where('id', $id)->first();

        if(is_null($plenary_attendance)) {
            return response()->json(['message' => 'Plenary Attendance was not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $plenary_attendance->update($validated);

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated plenary attendance.'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $plenary_attendance = PlenaryAttendance::where('id', $id)->first();

        if(!is_null($plenary_attendance)) {
            $plenary_attendance->delete();
            return response()->json(['message' => 'Successfully deleted plenary attendance.']);
        } else {
            return response()->json(['message' => 'Plenary attendance was not found.'], 404);
        }
    }

    public function getPlenaryAttendanceByDelegate($id) {
        $date_now = Carbon::today()->toDateString();
        $plenary_attendance = collect(DB::select("CALL RCDsp_LedgerPlenaryAttendancePending('$id')"));
        $logs = collect(DB::select("CALL RCDsp_LedgerPlenaryAttendance('$id')"));

        foreach($plenary_attendance as $key=>$pa){
            $keys = array_column(json_decode($logs), 'Logged_Date');
            if($pa->Logged_Date == "2022-10-04"){
                $index = array_search($pa->Logged_Date, $keys);
                if($index !== false){
                    $plenary_attendance[$key] = $logs[$index];
                }
                if($date_now > "2022-10-04" && $pa->Login_Time == '00:00:00' && $pa->Login_Time == '00:00:00'){
                    $pa->Status = 'No Record';
                }else if($date_now < "2022-10-04" && $pa->Login_Time == '00:00:00' && $pa->Login_Time == '00:00:00'){
                    $pa->Status = 'Waiting';
                }
            }else if($pa->Logged_Date == "2022-10-05"){
                $index = array_search($pa->Logged_Date, $keys);
                if($index !== false){
                    $plenary_attendance[$key] = $logs[$index];
                }
                if($date_now > "2022-10-05" && $pa->Login_Time == '00:00:00' && $pa->Login_Time == '00:00:00'){
                    $pa->Status = 'No Record';
                }else if($date_now < "2022-10-05" && $pa->Login_Time == '00:00:00' && $pa->Login_Time == '00:00:00'){
                    $pa->Status = 'Waiting';
                }
            }else if($pa->Logged_Date == "2022-10-06"){
                $index = array_search($pa->Logged_Date, $keys);
                if($index !== false){
                    $plenary_attendance[$key] = $logs[$index];
                }
                if($date_now > "2022-10-06" && $pa->Login_Time == '00:00:00' && $pa->Login_Time == '00:00:00'){
                    $pa->Status = 'No Record';
                }else if($date_now < "2022-10-06" && $pa->Login_Time == '00:00:00' && $pa->Login_Time == '00:00:00'){
                    $pa->Status = 'Waiting';
                }
            }
        }

        if($plenary_attendance->isNotEmpty()) {
            return response()->json($plenary_attendance);
        } else {
            return response()->json(['message' => 'Member has no plenary attendance yet.'], 404);
        }
    }

    public function getPlenaryEvents(){
        $plenary_events = PlenaryEvent::orderBy('title', 'asc')->get();

        if(!is_null($plenary_events)) {
            return response()->json($plenary_events);
        } else {
            return response()->json(['message' => 'The data for the plenary events have not been set yet'], 404);
        }
    }

    public function getPlenaryAttendanceList(){
        $plenary_attendace = collect(DB::select("CALL RCDsp_LedgerListPlenaryAttendance()"));
        if(!is_null($plenary_attendace)) {
            return response()->json($plenary_attendace);
        } else {
            return response()->json(['message' => 'Member has no plenary attendance yet.'], 404);
        }
    }

    public function export() {
        return Excel::download(new ExportPlenary(), 'Plenary_attendance.xlsx');
    }
}
