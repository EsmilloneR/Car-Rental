<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = [
        'slug',
        'brand',
        'image'
    ];

    public function manufacturer(){
        return $this->hasMany(Vehicle::class);
    }
}
