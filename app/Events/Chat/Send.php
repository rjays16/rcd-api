<?php

namespace App\Events\Chat;

use App\Models\Chat;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;

use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use Illuminate\Queue\SerializesModels;

use Illuminate\Foundation\Events\Dispatchable;

class Send implements ShouldBroadcastNow
{
    use SerializesModels;

    public $chat;

    public function __construct(Chat $chat) {
        $this->chat = $chat;
    }

    public function broadcastOn() {
        return [config('pusher.channel')]; // CHANNEL
    }

    public function broadcastAs() {
        return 'chat'; // EVENT
    }
}