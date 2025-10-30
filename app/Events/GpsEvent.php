<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GpsEvent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $location;

    public function __construct($location)
    {
        $this->location = $location;
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
