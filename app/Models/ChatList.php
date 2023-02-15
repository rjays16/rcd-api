<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatList extends Model
{
    use HasFactory;
    public $table = "chat_lists";

    protected $fillable = [
        'sponsor_user_id',
        'attendee_user_id',
        'updated_by',
        'last_chat_id',
        'last_message',
        'update_date',
        'viewed_sponsor',
        'viewed_attendee'
    ];

    public function sponsor() {
        return $this->belongsTo(User::class, 'sponsor_user_id');
    }

    public function attendee() {
        return $this->belongsTo(User::class, 'attendee_user_id');
    }
}