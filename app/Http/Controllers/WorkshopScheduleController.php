<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WorkshopSchedule;
use App\Http\Requests\WorkshopSchedule\Update;
use DB;
use Exception;

class WorkshopScheduleController extends Controller
{
    public function getOpeningDateWorkshop() {
        $opening_workshop_date = WorkshopSchedule::orderBy('id', 'asc')->get();

        if(!is_null($opening_workshop_date)) {
            return response()->json($opening_workshop_date);
        } else {
            return response()->json(['message' => 'The opening date for Workshop VCC has not been set yet.'], 404);
        }
    }

    public function updateOpeningDateWorkshop(Update $request, $id)
    {
        $validated = $request->validated();

        $workshop = WorkshopSchedule::where('id', $id)->first();

        if(is_null($workshop)) {
            return response()->json(['message' => 'The Workshop was not found'], 404);
        }

        DB::beginTransaction();
        try {
            $workshop->update($validated);
            DB::commit();

            return response()->json([
                'message' => 'Successfully updated workshop Laser'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getDateSchedule($id, $dates)
    {

        $delegate_can_access_workshop = collect(DB::select("CALL RCDsp_canOpenWorkshop('$id', '$dates')"));

        if(!is_null($delegate_can_access_workshop)) {
            return response()->json($delegate_can_access_workshop);
        } else {
            return response()->json(['message' => 'Error delegate cannot proceed workshop.'], 404);
        }
    }

}
