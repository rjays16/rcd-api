<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sponsor;
use App\Models\SponsorStamp;
use App\Models\SponsorConsent;
use App\Models\User;

use App\Http\Requests\Sponsor\CreateStamp;

use App\Exports\SponsorStamp\Export;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

use Exception;
use DB;

class SponsorStampController extends Controller
{
    public function getSponsorStamps() {
        $stamps = SponsorStamp::all();

        if($stamps->isNotEmpty()) {
            return response()->json($stamps);
        } else {
            return response()->json(['message' => 'Sponsors stamps not found.'], 404);
        }
    }

    public function getStampsOfSponsor($id) {
        $sponsor = Sponsor::where('id', $id)->first();

        if(!is_null($sponsor)) {
            $stamps = SponsorStamp::where('sponsor_id', $id)->with('user')->get();

            if($stamps->isNotEmpty()) {
                return response()->json($stamps);
            } else {
                return response()->json(['message' => 'There are no stamps for this sponsor yet.'], 404);
            }
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

    public function getUserStamps(Request $request) {
        $user = auth()->user();
        $stamp_query = SponsorStamp::where('user_id', $user->id)->with('sponsor');

        $num_stampable_sponsors = Sponsor::notIndustryLectureOnly()->count();
        $num_member_stamps = SponsorStamp::where('user_id', $user->id)->with('sponsor')->count();

        $current_member_stamp_round_number = $user->member->current_stamp_round_number;
        $total_stamps_for_current_round = SponsorStamp::where('user_id', $user->id)->where('round_number', $current_member_stamp_round_number)->count();
        
        return response()->json([
            'stamps' => $stamp_query->get(),
            'total_stamps_for_current_round' => $total_stamps_for_current_round, // Only get the whole number,
            'stampable_sponsors' => Sponsor::notIndustryLectureOnly()->count(),
            'num_raffle_tickets' => $user->member->num_raffle_tickets,
            'current_member_stamp_round_number' => $current_member_stamp_round_number,
        ]);
    }

    public function create(CreateStamp $request) {
        $validated = $request->validated();

        $user = User::where('id', $validated["user_id"])->first();
        $member = $user->member;
        $sponsor = Sponsor::where('id', $validated["sponsor_id"])->first();

        if(!is_null($sponsor)) {
            if(!is_null($user)) {
                if(!is_null($user->sponsor_exhibitor)) {
                    return response()->json(['message' => 'You are not allowed to stamp.'], 404);
                }

                if(is_null($member)) {
                    return response()->json(['message' => 'Member account was not found.'], 404);
                }

                $consent = SponsorConsent::where('user_id', $user->id)
                    ->where('sponsor_id', $sponsor->id)
                    ->first();

                try {
                    if(is_null($consent)) {
                        $consent = new SponsorConsent();
                        $consent->user_id = $user->id;
                        $consent->sponsor_id = $sponsor->id;
                        $consent->save();

                        $user->is_anon_for_chat = false;
                        $user->save();
                    }
                    
                    $current_member_stamp_round_number = $member->current_stamp_round_number;

                    $current_round_stamp = SponsorStamp::where('user_id', $user->id)
                        ->where('round_number', $current_member_stamp_round_number)
                        ->where('sponsor_id', $sponsor->id)
                        ->first();

                    if(!is_null($current_round_stamp)) {
                        return response()->json([
                            'message' => 'There is already a booth stamp for the current round.'
                        ], 404);
                    }

                    $stamp = new SponsorStamp();
                    $stamp->user_id = $user->id;
                    $stamp->sponsor_id = $sponsor->id;
                    $stamp->date = Carbon::today()->toDateString();
                    $stamp->round_number = $current_member_stamp_round_number;
                    $stamp->save();

                    $num_stampable_sponsors = Sponsor::notIndustryLectureOnly()->count();

                    $total_stamps_for_current_round = SponsorStamp::where('user_id', $user->id)->where('round_number', $current_member_stamp_round_number)->count();

                    // if($stamp) {
                    //     $total_stamps_for_current_round = $total_stamps_for_current_round + 1;
                    // }

                    // Check if the member's current number of stamps is divisible by the total number of stampable sponsors
                    /*
                        For example, if 34 is the current number of stamps and the number of stampable sponsors is 34,
                        - then increase the member's number of raffle tickets
                        - and then mark the member as eligible for the next stamp round
                    */

                    if($total_stamps_for_current_round > 0 && $total_stamps_for_current_round % $num_stampable_sponsors == 0) {
                        $member->is_eligible_for_next_stamp_round = true;
                        $member->current_stamp_round_number = $current_member_stamp_round_number + 1; // and then increment the member's current stamp round number  
                        
                        $member->num_raffle_tickets = $member->current_stamp_round_number;                   
                    } else {
                        // Else, tag the member as ineligible 
                        $member->is_eligible_for_next_stamp_round = false;
                        
                        // Also record the stamp's appropriate round number based on the member's current stamp round number
                        $stamp->round_number = $member->current_stamp_round_number;
                        $stamp->save();
                    }

                    $member->save();
                    DB::commit();
                    
                    $message = 'Successfully stamped for this booth.';

                    $is_eligible_for_next_stamp_round = $member->is_eligible_for_next_stamp_round;
                    if($is_eligible_for_next_stamp_round) {
                        $message = "You have gained a new raffle ticket and are also qualified for the next stamp round.";
                    }

                    return response()->json([
                        'message' => $message,
                        'total_stamps_for_current_round' => $total_stamps_for_current_round,
                        'num_stampable_sponsors' => $num_stampable_sponsors,
                        'current_member_stamp_round_number' => $member->current_stamp_round_number,
                        'is_eligible_for_next_stamp_round' => $member->is_eligible_for_next_stamp_round
                    ]);
                } catch(Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                return response()->json(['message' => 'User not found.'], 404);
            }
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

    public function export($id) {
        return Excel::download(new Export($id), 'stamps.xlsx');
    }
}