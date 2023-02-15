<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Chat;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\ChatList;
use App\Models\SponsorVisitLog;

use App\Events\Chat\Send;

use App\Enum\UserStatusEnum;
use App\Enum\SponsorTypeEnum;
use App\Enum\RoleEnum;

use App\Http\Requests\Chat\Create;
use App\Http\Requests\Chat\Retrieve;

use App\Exports\SponsorChat\Export;
use App\Exports\SponsorChat\ExportVisitorsOnly;
use Maatwebsite\Excel\Facades\Excel;

use Exception;
use DB;

use Carbon\Carbon;

class SponsorChatController extends Controller
{
    public function getAttendees(Request $request) {
        $sponsor = Sponsor::with('type')->where('user_id', Auth::user()->id)->first();

        if(!is_null($sponsor)) {
            $attendees = null;
            $attendee_ids = SponsorVisitLog::where('sponsor_id', $sponsor->id)->pluck('user_id')->toArray();

            if(!empty($attendee_ids)) {
                $attendees = User::leftJoin('chat_lists', 'users.id', '=', 'chat_lists.attendee_user_id')
                    ->select('chat_lists.viewed_sponsor', 'chat_lists.updated_date', 'users.id', 'users.active_token',
                        'users.first_name', 'users.middle_name', 'users.last_name', 'users.is_anon_for_chat')
                    ->whereIn('users.id', $attendee_ids)
                    ->where('chat_lists.sponsor_user_id', Auth::user()->id);
                
                if($request->is_search == 'true' && $request->searchTerm) {
                    $attendees = $attendees->where(function($query) use ($request) {
                        $query = $query->where('first_name','LIKE', "%$request->searchTerm%")
                            ->orWhere('last_name','LIKE', "%$request->searchTerm%")
                            ->orWhere('email','LIKE', "%$request->searchTerm%");
                    });
                }

                $attendees = $attendees->distinct('users.id')
                    ->groupBy('chat_lists.updated_date', 'chat_lists.viewed_sponsor',
                        'users.first_name', 'users.middle_name', 'users.last_name',
                        'users.active_token', 'users.id', 'users.is_anon_for_chat'
                    )
                    ->orderByRaw("chat_lists.updated_date DESC")
                    ->get();

                if($attendees->isNotEmpty()) {
                    return response()->json($attendees);
                } else {
                    return response()->json(['message' => 'No attendees found.'], 404);
                }
            } else {
                return response()->json(['message' => 'No attendees found.'], 404);
            }
        } else {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }
    }

    public function getAttendeeWithSponsorMessages(Retrieve $request) {
        $validated = $request->validated();
        $validated['sponsor_id'] = Auth::user()->id;

        $attendee_id = $validated['user_id'];
        $sponsor_id = $validated['sponsor_id'];

        $chatList = ChatList::where('sponsor_user_id', $validated['sponsor_id'])
            ->where('attendee_user_id', $attendee_id)->first();

        $chats = Chat::where([['sender_id', $sponsor_id], ['receiver_id', $attendee_id]])->get();
        if(!empty($chats)){
            foreach($chats as $chat) {
                $chat->viewed = true;
                $chat->save();
            }
        }
       
        if(!empty($chatList)){
            $chatList->viewed_sponsor = true;
            $chatList->save();
        }

        $chats = Chat::with('sender', 'receiver')
            ->where(function ($query) use ($sponsor_id, $attendee_id) {
                $query->where('sender_id', $sponsor_id)
                    ->where('receiver_id', $attendee_id);
            })
            ->orWhere(function ($query) use ($sponsor_id, $attendee_id) {
                $query->where('sender_id', $attendee_id)
                    ->where('receiver_id', $sponsor_id);
            })
            ->orderBy('id')
            ->get();
        
        if(!is_null($chats)) {
            return response()->json($chats);
        }else{
            return response()->json(['message' => 'No conversation yet'], 404);
        }
    }

    public function sendMessage(Create $request) {
        $validated = $request->validated();
        $validated['sender_id'] = Auth::user()->id;

        DB::beginTransaction();
        try {
            $chat = Chat::create($validated);
            $chatListExist = ChatList::where('sponsor_user_id', $validated['sender_id'])
                ->where('attendee_user_id', $validated['receiver_id'])
                ->exists();

            if($chatListExist) {
                $chatList = ChatList::where('sponsor_user_id', $validated['sender_id'])
                    ->where('attendee_user_id', $validated['receiver_id'])
                    ->first();
                $chatList->updated_by = $validated['sender_id'];
                $chatList->last_chat_id = $chat->id;
                $chatList->last_message = $validated['message'];
                $chatList->updated_date = Carbon::now();
                $chatList->viewed_sponsor = true;
                $chatList->viewed_attendee = false;
                $chatList->save();
            } else {
                $chatList = ChatList::create([
                    'sponsor_user_id' =>  $validated['sender_id'],
                    'attendee_user_id' =>  $validated['receiver_id'],
                    'updated_by' => $validated['sender_id'],
                    'last_chat_id' => $chat->id,
                    'last_message' => $validated['message'],
                    'updated_date' => Carbon::now(),
                    'viewed_sponsor' => true,
                    'viewed_attendee' => false,
                ]);
            }

            event(new Send($chat->load('sender')));
            DB::commit();
            return response()->json([
                'message' => 'Message sent',
                'chat' => $chat
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function export($id) {
        return Excel::download(new Export($id), 'chat_report.xlsx');
    }

    public function exportVisitorsOnly($id) {
        return Excel::download(new ExportVisitorsOnly($id), 'visitors_only_report.xlsx');
    }
}