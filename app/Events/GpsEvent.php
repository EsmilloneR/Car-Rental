<?php

namespace App\Events;

use App\Models\Location;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GpsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $location;

    public function __construct(Location $location)
    {
        $this->location = [
            'vehicle_id' => $location->vehicle_id,
            'latitude'   => $location->latitude,
            'longitude'  => $location->longitude,
            'speed'      => $location->speed,
        ];
    }

    public function broadcastOn()
    {
        return new Channel('gps-tracker');
    }

    public function broadcastAs()
    {
        return 'gps.updated';
    }
}
