<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BoothDesign;

use App\Http\Requests\Sponsor\BoothDesign\Create;

use Exception;
use DB;

class BoothDesignController extends Controller
{
    public function getBoothDesigns() {
        $designs = BoothDesign::all();

        if($designs->isNotEmpty()) {
            return response()->json($designs);
        } else {
            return response()->json(['message' => 'Booth designs not found.'], 404);
        }
    }

    public function getBoothDesign($id) {
        $design = BoothDesign::where('id', $id)->first();

        if(!is_null($design)) {
            return response()->json($design);
        } else {
            return response()->json(['message' => 'Booth design not found.'], 404);
        }
    }

    public function create(Create $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            if($request->hasFile('photo')) {
                $fileExtension = $request->file('photo')->getClientOriginalName();
                $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                $extension = $request->file('photo')->getClientOriginalExtension();
                $fileStore = $file.'_'.time().'.'.$extension;
                $request->file('photo')->storeAs('/images/booth_designs', $fileStore);
                $validated["photo"] = config('settings.APP_URL')."/storage/images/booth_designs/".$fileStore;

                BoothDesign::create($validated);
                DB::commit();
                return response()->json([
                    'message' => 'Successfully created booth design.',
                    'photo' => $validated["photo"],
                ]);
            } else {
                return response()->json([
                    'message' => 'No file was uploaded for the booth design.'
                ], 404);
            }
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Create $request, $id) {
        $validated = $request->validated();

        $design = BoothDesign::where('id', $id)->first();

        if(is_null($design)) {
            return response()->json(['message' => 'Booth design not found.'], 404);
        }

        DB::beginTransaction();
        try {
            if($request->hasFile('photo')) {
                $fileExtension = $request->file('photo')->getClientOriginalName();
                $file = pathinfo($fileExtension, PATHINFO_FILENAME);
                $extension = $request->file('photo')->getClientOriginalExtension();
                $fileStore = $file.'_'.time().'.'.$extension;
                $request->file('photo')->storeAs('/images/booth_designs', $fileStore);
                $validated["photo"] = config('settings.APP_URL')."/storage/images/booth_designs/".$fileStore;

                $design->update($validated);
                DB::commit();
                return response()->json([
                    'message' => 'Successfully updated booth design.',
                    'photo' => $validated["photo"],
                    'design' => $design
                ]);
            } else {
                return response()->json([
                    'message' => 'No file was uploaded for the booth design.'
                ], 404);
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) {
        $design = BoothDesign::where('id', $id)->first();

        if(!is_null($design)) {
            $design->delete();
            return response()->json(['message' => 'Booth design deleted.']);
        } else {
            return response()->json(['message' => 'Booth design not found.'], 404);
        }
    }
}