<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SymposiaCategory;

use App\Http\Requests\Symposia\Category\Create;

use Exception;
use DB;

class SymposiaCategoryController extends Controller
{
    public function getCategories() {
        $symposia_categories = SymposiaCategory::all();

        if($symposia_categories->isNotEmpty()) {
            return response()->json($symposia_categories);
        } else {
            return response()->json(['message' => 'No symposia categories were found.'], 404);
        }
    }

    public function getCategory($id) {
        $symposia_category = SymposiaCategory::where('id', $id)->first();

        if(!is_null($symposia_category)) {
            return response()->json($symposia_category);
        } else {
            return response()->json(['message' => 'This symposia category was not found.'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            SymposiaCategory::create($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully created the symposia category.',
            ]);
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $symposia_category = SymposiaCategory::where('id', $id)->first();

        if(is_null($symposia_category)) {
            return response()->json(['message' => 'Symposia category was not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $symposia_category->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated the symposia category.',
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $symposia_category = SymposiaCategory::where('id', $id)->first();

        if(!is_null($symposia_category)) {
            if($symposia_category->symposia->isNotEmpty()) {
                return response()->json(['message' => 'Unable to delete. This category has events.'], 400);
            } else {
                $symposia_category->delete();
                return response()->json(['message' => 'Successfully deleted symposia category.']);
            }
        } else {
            return response()->json(['message' => 'The symposia category was not found.'], 404);
        }
    }
}