<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PageIframe;

use App\Http\Requests\PageIframe\Update;

use Exception;
use DB;

class PageIframeController extends Controller
{
    public function getPageIframes() {
        $iframes = PageIframe::first();

        if(!is_null($iframes)) {
            return response()->json($iframes);
        } else {
            return response()->json(['message' => 'Page iframes have not been set yet'], 404);
        }
    }

    public function getPageIframe(Request $request) {
        $name = $request->name;
        $iframe = PageIframe::first()->$name;

        if($iframe) {
            return response()->json($iframe);
        } else {
            return response()->json(['message' => 'Page iframe has not been set yet'], 404);
        }
    }

    public function update(Update $request) {
        $validated = $request->validated();

        $iframes = PageIframe::first();

        DB::beginTransaction();
        try {
            if(is_null($iframes)) {
                $iframes = new PageIframe();
            }

            $iframes->facade = $validated["facade"];
            $iframes->entrance = $validated["entrance"];
            $iframes->lobby = $validated["lobby"];
            $iframes->sponsors = $validated["sponsors"];
            $iframes->plenary = $validated["plenary"];
            $iframes->mini_sessions = $validated["mini_sessions"];
            $iframes->save();   

            DB::commit();
            return response()->json([
                'message' => 'Successfully updated page iframes'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}