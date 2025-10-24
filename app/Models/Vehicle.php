<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'manufacturer_id',
        'model',
        'year',
        'plate_number',
        'color',
        'transmission',
        'seats',
        'avatar',
        'photos',
        'rate_hour',
        'rate_day',
        'rate_week',
        'active',
        'description'
    ];

    public function getCarAttribute(){
        return "{$this->make} {$this->model}";
    }

    public function rentals(){
        return $this->hasMany(Rental::class);
    }
    public function location()
    {
        return $this->hasMany(Location::class);
    }

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class);
    }

    public function scopeAvailable($query, $start, $end)
    {
        return $query->whereDoesntHave('rentals', function($qq) use($start, $end) {
            $qq->whereIn('status', ['reserved', 'ongoing'])
            ->where(function($w) use($start, $end) {
                // Vehicles reserved or ongoing within the start and end range
                $w->whereBetween('rental_start', [$start, $end])
                    ->orWhereBetween('rental_end', [$start, $end])
                    ->orWhere(function($z) use($start, $end) {
                        // Vehicles that overlap the requested range
                        $z->where('rental_start', '<=', $start)
                        ->where('rental_end', '>=', $end);
                    });
            });
        });
    }

    protected $attributes = [
        'description' => '',
    ];

    protected $casts = [
        'photos' => 'array'
    ];

    public function currentRental()
    {
        return $this->hasOne(Rental::class)
            ->where('rental_start', '<=', now())
            ->where('rental_end', '>=', now())
            ->whereIn('status', ['reserved', 'ongoing'])  // if you want to filter by status
            ->latest('rental_start');
    }
}
