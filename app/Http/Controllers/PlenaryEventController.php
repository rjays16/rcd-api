<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PlenaryDay;
use App\Models\PlenaryEvent;

use App\Http\Requests\Plenary\Create;
use App\Http\Requests\Plenary\GetByDate;

use Exception;
use DB;

class PlenaryEventController extends Controller
{
    public function getPlenaryDays() {
        $plenary_days = PlenaryDay::all();

        if($plenary_days->isNotEmpty()) {
            return response()->json($plenary_days);
        } else {
            return response()->json(['message' => 'No plenary days were found.'], 404);
        }
    }

    public function getPlenaryEvents() {
        $plenary_events = PlenaryEvent::all();

        if($plenary_events->isNotEmpty()) {
            return response()->json($plenary_events);
        } else {
            return response()->json(['message' => 'No plenary events were found.'], 404);
        }
    }

    public function getPlenaryEventsByDate(GetByDate $request) {
        $validated = $request->validated();

        $plenary_events = PlenaryEvent::where('date', $validated["date"])->get();
        if($plenary_events->isNotEmpty()) {
            return response()->json($plenary_events);
        } else {
            return response()->json(['message' => 'No plenary events on this date were found.'], 404);
        }
    }

    public function getPlenaryEvent($id) {
        $plenary_event = PlenaryEvent::where('id', $id)->first();

        if(!is_null($plenary_event)) {
            return response()->json($plenary_event);
        } else {
            return response()->json(['message' => 'This plenary event was not found.'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            PlenaryEvent::create($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully created the plenary event.',
            ]);
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $plenary_event = PlenaryEvent::where('id', $id)->first();

        if(is_null($plenary_event)) {
            return response()->json(['message' => 'Plenary event not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $plenary_event->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated the plenary event.',
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $plenary_event = PlenaryEvent::where('id', $id)->first();

        if(!is_null($plenary_event)) {
            $plenary_event->delete();
            return response()->json(['message' => 'Successfully deleted plenary event.']);
        } else {
            return response()->json(['message' => 'The plenary event was not found.'], 404);
        }
    }
}