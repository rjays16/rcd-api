<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sponsor;
use App\Models\SponsorConsent;
use App\Models\User;

use App\Http\Requests\Sponsor\CreateConsent;

use Exception;
use DB;

class SponsorConsentController extends Controller
{
    public function getSponsorConsents($id) {
        $sponsor = Sponsor::where('id', $id)->first();

        if(!is_null($sponsor)) {
            $consents = $sponsor->consents;

            if($consents->isNotEmpty()) {
                return response()->json($consents);
            } else {
                return response()->json(['message' => 'There are no member consents for this sponsor yet'], 404);
            }
        } else {
            return response()->json(['message' => 'Sponsor not found'], 404);
        }
    }

    public function create(CreateConsent $request) {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::where('id', $validated["user_id"])->first();
            $sponsor = Sponsor::where('id', $validated["sponsor_id"])->first();
    
            if(!is_null($sponsor)) {
                if(!is_null($user)) {
                    $consent = SponsorConsent::where('user_id', $user->id)
                        ->where('sponsor_id', $sponsor->id)
                        ->first();
                    
                    if(is_null($consent)) {
                        $consent = new SponsorConsent();
                        $consent->user_id = $user->id;
                        $consent->sponsor_id = $sponsor->id;
                        $consent->save();

                        DB::commit();
                        return response()->json(['message' => 'Successfully sent user info for this booth']);
                    } else {
                        return response()->json(['message' => 'You have already sent your user info for this booth'], 400);
                    }
                } else {
                    return response()->json(['message' => 'User not found'], 404);
                }
            } else {
                return response()->json(['message' => 'Sponsor not found'], 404);
            }
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}