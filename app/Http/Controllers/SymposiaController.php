<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SymposiaCategory;
use App\Models\Symposia;

use App\Http\Requests\Symposia\Create;

use Exception;
use DB;

class SymposiaController extends Controller
{
    public function getEvents(Request $request) {
        $symposia_events = Symposia::query();

        if($request->exists('is_search') && $request->is_search) {
            $symposia_events = $symposia_events->where('title', 'like', "%$request->keyword%")
                ->orWhere('author', 'like', "%$request->keyword%")
                ->orWhere('card_title', 'like', "%$request->keyword%");

        } else if(!$request->show_all) {
            $symposia_events = $symposia_events->limit(20);
        }

        $symposia_events = $symposia_events->with('category')
            ->orderBy('symposia.id', 'asc')
            ->get();

        if($symposia_events->isNotEmpty()) {
            return response()->json($symposia_events);
        } else {
            return response()->json(['message' => 'No symposia events were found.'], 404);
        }
    }

    public function getEvent($id) {
        $symposia_event = Symposia::where('id', $id)->first();

        if(!is_null($symposia_event)) {
            return response()->json($symposia_event);
        } else {
            return response()->json(['message' => 'This symposia event was not found.'], 404);
        }
    }

    public function getCategorizedEvents() {
        $categorized_events = SymposiaCategory::with('symposia')->get();

        if($categorized_events->isNotEmpty()) {
            return response()->json($categorized_events);
        } else {
            return response()->json(['message' => 'No categorized symposia events were found.'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            Symposia::create($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully created the symposia event.',
            ]);
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $symposia_event = Symposia::where('id', $id)->first();

        if(is_null($symposia_event)) {
            return response()->json(['message' => 'Symposia event was not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $symposia_event->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated the symposia event.',
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $symposia_event = Symposia::where('id', $id)->first();

        if(!is_null($symposia_event)) {
            $symposia_event->delete();
            return response()->json(['message' => 'Successfully deleted symposia event.']);
        } else {
            return response()->json(['message' => 'The symposia event was not found.'], 404);
        }
    }
}