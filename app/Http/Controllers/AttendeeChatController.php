<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Chat;
use App\Models\ChatList;

use App\Http\Requests\Chat\Create;
use App\Http\Requests\Chat\Retrieve;

use App\Events\Chat\Send;

use Exception;
use DB;

use Carbon\Carbon;

class AttendeeChatController extends Controller
{
    public function getMessages(Retrieve $request) {
        if(Auth::check()) {            
            $validated = $request->validated();
            $validated['attendee_id'] = auth()->user()->id;
            $sponsor_id = $validated['user_id'];
            $attendee_id = $validated['attendee_id'];

            $chat_list = ChatList::where('sponsor_user_id', $sponsor_id)->where('attendee_user_id', $attendee_id)->first();
            $chats = Chat::where([['sender_id', $sponsor_id], ['receiver_id', $sponsor_id]])->get();

            if(!empty($chats)){
                foreach($chats as $chat) {
                    $chat->viewed = true;
                    $chat->save();
                }
            }
            
            if(!empty($chat_list)){
                $chat_list->viewed_sponsor = true;
                $chat_list->save();
            }

            $chats = Chat::with('sender', 'receiver')
                ->where(function ($query) use ($sponsor_id, $attendee_id) {
                    $query->where('sender_id', $attendee_id)
                        ->where('receiver_id', $sponsor_id);
                })
                ->orWhere(function ($query) use ($sponsor_id, $attendee_id) {
                    $query->where('sender_id', $sponsor_id)
                        ->where('receiver_id', $attendee_id);
                })
                ->orderBy('id')
                ->get();

            return response()->json($chats);
        } else {
            return response()->json(['message' => 'Please try logging in again first.'], 400);
        }
    }

    public function sendMessage(Create $request) {
        $validated = $request->validated();
        $validated['sender_id'] = auth()->user()->id;

        DB::beginTransaction();
        try {
            $chat_list_query = ChatList::where('sponsor_user_id', $validated['receiver_id'])->where('attendee_user_id', $validated['sender_id']);
            $chat_list_exists = $chat_list_query->exists();
            
            $chat = Chat::create($validated);

            if($chat_list_exists) {            
                $chat_list = $chat_list_query->first();
                $chat_list->updated_by = $validated['sender_id'];
                $chat_list->last_chat_id = $chat->id;
                $chat_list->last_message = $validated['message'];
                $chat_list->updated_date = Carbon::now();
                $chat_list->viewed_sponsor = false;
                $chat_list->viewed_attendee = true;
                $chat_list->save();
            } else {
                $chat_list = ChatList::create([
                    'sponsor_user_id' =>  $validated['receiver_id'],
                    'attendee_user_id' =>  $validated['sender_id'],
                    'updated_by' => $validated['receiver_id'],
                    'last_chat_id' => $chat->id,
                    'last_message' => $validated['message'],
                    'updated_date' => Carbon::now(),
                    'viewed_sponsor' => false,
                    'viewed_attendee' => true,
                ]);
            }

            broadcast(new Send($chat->load('sender')))->toOthers();
            DB::commit();

            return response()->json(['message'=>'Message sent'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}