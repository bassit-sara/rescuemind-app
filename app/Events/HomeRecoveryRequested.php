<?php

namespace App\Events;

use App\Models\HomeRecovery;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HomeRecoveryRequested implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $recovery;

    /**
     * Create a new event instance.
     */
    public function __construct(HomeRecovery $recovery)
    {
        // We load the user relation so the broadcast has it
        $this->recovery = $recovery->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast on a public channel for all admins listening to mt3
        return [
            new Channel('mt3.home-recovery'),
        ];
    }
}
