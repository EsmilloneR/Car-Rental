<?php

namespace App\Models;

use App\Events\GpsEvent;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'vehicle_id',
        'latitude',
        'longitude',
        'speed',
    ];

    protected static function booted()
    {

        static::created(function ($location) {
            broadcast(new GpsEvent($location));
        });

    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
