<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Symposia;
use App\Models\SymposiaView;
use App\Models\User;

use App\Http\Requests\Symposia\View\Create;

use App\Exports\SymposiaView\Export;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

use Exception;
use DB;

class SymposiaViewController extends Controller
{
    public function getSymposiaViews() {
        $symposia_views = Symposia::all();

        if($symposia_views->isNotEmpty()) {
            return response()->json($symposia_views);
        } else {
            return response()->json(['message' => 'There are no views for the symposia yet.'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();
        
        $user = User::where('id', $validated["user_id"])->first();
        $symposia = Symposia::where('id', $validated["symposia_id"])->first();
    
        if(!is_null($symposia)) {
            if(!is_null($user)) {
                DB::beginTransaction();
                try {       
                    $symposia_view = new SymposiaView();
                    $symposia_view->user_id = $user->id;
                    $symposia_view->symposia_id = $symposia->id;
                    $symposia_view->save();

                    DB::commit();
                    return response()->json(['message' => 'Successfully logged the view for this symposia event.']);
                } catch(Exception $e){
                    DB::rollBack();
                    throw $e;
                }
            } else {
                return response()->json(['message' => 'User was not found.'], 404);
            }
        } else {
            return response()->json(['message' => 'Symposia event was not found.'], 404);
        }        
    }

    public function export() {
        return Excel::download(new Export(), 'symposia_views.xlsx');
    }
}