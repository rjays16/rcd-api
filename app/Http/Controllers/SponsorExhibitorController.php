<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SponsorExhibitor;

use Exception;
use DB;

class SponsorExhibitorController extends Controller
{
    public function getSponsorExhibitors() {
        $sponsor_exhibitors = SponsorExhibitor::with(['user.member', 'sponsor'])->get();

        if($sponsor_exhibitors->isNotEmpty()) {
            return response()->json($sponsor_exhibitors);
        } else {
            return response()->json(['message' => 'There are no sponsor exhibitors yet.'], 404);
        }
    }

    public function getSponsorExhibitor($id) {
        $sponsor_exhibitor = SponsorExhibitor::where('id', $id)
            ->with(['user.member', 'sponsor'])
            ->first();

        if(!is_null($sponsor_exhibitor)) {
            return response()->json($sponsor_exhibitor);
        } else {
            return response()->json(['message' => 'This sponsor exhibitor account was not found.'], 404);
        }
    }
}