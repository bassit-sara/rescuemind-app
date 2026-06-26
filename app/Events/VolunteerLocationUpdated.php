<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VolunteerLocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $volunteerId;
    public $sosId;
    public $latitude;
    public $longitude;

    /**
     * Create a new event instance.
     */
    public function __construct($volunteerId, $sosId, $latitude, $longitude)
    {
        $this->volunteerId = $volunteerId;
        $this->sosId = $sosId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast on a public channel specific to the SOS request
        return [
            new Channel('tracking.sos.' . $this->sosId),
            // Also broadcast on a global channel for admins to track all active volunteers
            new Channel('tracking.volunteers.all')
        ];
    }
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'VolunteerLocationUpdated';
    }
}
