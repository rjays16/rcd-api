<?php

namespace App\Events\Log;

use App\Models\Log;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MemberLog implements ShouldBroadcastNow
{
    use SerializesModels;

    public $log;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function broadcastOn()
    {
        return [config('pusher.channel')]; // CHANNEL
    }

    public function broadcastAs()
    {
        return 'member-log'; // EVENT
    }
}
