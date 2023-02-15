<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingInstitution;

class TrainingInstitutionController extends Controller
{
    public function getTrainingInstitutions(Request $request) {
        $training_instructions = TrainingInstitution::all();

        if($training_instructions->isNotEmpty()) {
            return response()->json($training_instructions);
        } else {
            return response()->json(['message' => 'No trainings were found'], 404);
        }
    }
}