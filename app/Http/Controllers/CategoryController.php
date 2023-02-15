<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

use App\Http\Requests\Category\Create;

use App\Enum\AbstractTypeEnum;

use Exception;
use DB;

class CategoryController extends Controller
{
    public function getCategories() {
        $categories = Category::orderBy('category_value', 'asc')->get();

        if($categories->isNotEmpty()) {
            return response()->json($categories);
        } else {
            return response()->json(['message' => 'The data for the abstract categories have not been set yet'], 404);
        }
    }

    public function getEPosterCategories() {
        $categories = Category::whereHas('abstracts', function ($query) {
                $query->where('abstract_type', AbstractTypeEnum::E_POSTER)->where('is_finalist', true);
            })
            ->orderBy('category_value', 'asc')
            ->get();

        if($categories->isNotEmpty()) {
            return response()->json($categories);
        } else {
            return response()->json(['message' => 'There were no e-posters found with finalists.'], 404);
        }
    }

    public function getFreePaperCategories() {
        $categories = Category::whereHas('abstracts', function ($query) {
                $query->where('abstract_type', AbstractTypeEnum::FREE_PAPER)->where('is_finalist', true);
            })
            ->orderBy('category_value', 'asc')
            ->get();

        if($categories->isNotEmpty()) {
            return response()->json($categories);
        } else {
            return response()->json(['message' => 'There were no free papers found with finalists.'], 404);
        }
    }

    public function getCategory($id) {
        $abstract_category = Category::where('id', $id)->first();

        if(!is_null($abstract_category)) {
            return response()->json($abstract_category);
        } else {
            return response()->json(['message' => 'This abstract category was not found.'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            Category::create($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully created the abstract category.',
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $abstract_category = Category::where('id', $id)->first();

        if(is_null($abstract_category)) {
            return response()->json(['message' => 'Abstract category was not found.'], 404);
        }

        DB::beginTransaction();
        try {
            $abstract_category->update($validated);
            DB::commit();
            return response()->json([
                'message' => 'Successfully updated the abstract category.',
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}