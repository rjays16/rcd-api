<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Sponsor;

use App\Http\Requests\Sponsor\IndustryLecture\Update;

use Exception;
use DB;

class SponsorIndustryLectureController extends Controller
{
    public function getSponsorIndustryLectures() {
        $sponsors = Sponsor::hasIndustryLecture()->with(['user', 'type'])->get();

        if($sponsors->isNotEmpty()) {
            return response()->json($sponsors);
        } else {
            return response()->json([
                'message' => 'There were no industry-sponsored lectures found.'
            ], 404);
        }
    }

    public function getSponsorIndustryLectureByID($id) {
        $sponsor = Sponsor::hasIndustryLecture()->with(['user', 'type'])->where('id', $id)->first();

        if(!is_null($sponsor)) {
            return response()->json($sponsor);
        } else {
            return response()->json([
                'message' => 'This industry-sponsored lecture was not found.'
            ], 404);
        }
    }

    public function getSponsorIndustryLectureBySlug($slug) {
        $sponsor = Sponsor::hasIndustryLecture()->with(['user', 'type'])->where('slug', $slug)->first();

        if(!is_null($sponsor)) {
            return response()->json($sponsor);
        } else {
            return response()->json([
                'message' => 'This industry-sponsored lecture was not found.'
            ], 404);
        }
    }

    public function update(Update $request, $id) {
        $validated = $request->validated();

        $sponsor = Sponsor::where('id', $id)->first();
        if(is_null($sponsor)) {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $sponsor->fill($validated);
            $sponsor->save();
            
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated sponsor.'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}