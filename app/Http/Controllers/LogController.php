<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\PlenaryDay;
use App\Http\Requests\Log\Create;

use App\Events\Log\MemberLog;

use Carbon\Carbon;

use Exception;
use DB;

class LogController extends Controller
{
    public function getLogs() {
        $logs = Log::with('member')->get();

        if($logs->isNotEmpty()) {
            return response()->json($fees);
        } else {
            return response()->json(['message' => 'No log were found'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();
        $validated["date_time"] = Carbon::now()->timezone('Asia/Manila')->toDateTimeString();

        //check if last log was IN
        $last_log = Log::where('convention_member_id', $validated["convention_member_id"])->orderBy('id', 'DESC')->first();

        DB::beginTransaction();
        try {
            if(!is_null($last_log)){
                if(((bool)$last_log->is_login) == true && $validated['is_login'] == true){
                    $logout = new Log();
                    $logout->convention_member_id = $last_log->convention_member_id;
                    $logout->date_time = $last_log->date_time;
                    $logout->url = $last_log->url;
                    $logout->is_login = false;
                    $logout->is_logout = true;
                    $logout->save();

                    // event(new MemberLog($logout));
                    $last_log = Log::where('convention_member_id', $validated["convention_member_id"])->orderBy('id', 'DESC')->first();
                }
                else if((((bool)$last_log->is_logout) == true && $validated['is_login'] == true) || (((bool)$last_log->is_login) == true && $validated['is_logout'] == true)){
                    $log = Log::create($validated);
                    // event(new MemberLog($log));
                }
            }else{
                $log = Log::create($validated);
                // event(new MemberLog($log));
            }
           
            DB::commit();
            return response()->json([
                'message' => 'Successfully created log.'
            ]);
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $log = Log::where('id', $id)->first();

        if(!is_null($log)) {
            $log->delete();
            return response()->json(['message' => 'Log deleted']);
        } else {
            return response()->json(['message' => 'Log not found'], 404);
        }
    }
}