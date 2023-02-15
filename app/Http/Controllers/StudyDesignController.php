<?php

namespace App\Http\Controllers;

use App\Models\StudyDesign;
use Illuminate\Http\Request;

class StudyDesignController extends Controller
{
    public function Study(){
        $study = StudyDesign::all();

        if(!is_null($study)) {
            return response()->json($study);
        } else {
            return response()->json(['message' => 'The data for the countries have not been set yet'], 404);
        }
    }
}
