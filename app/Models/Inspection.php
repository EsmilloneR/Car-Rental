<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;
    protected $fillable = [
        'rental_id',
        'type',
        'fuel_level_in',
        'fuel_level_out',
        'odometer',
        'condition_notes',
        'photos'
    ];

    protected $casts = [
        'photos'=>'array'
    ];

    public function rentals(){
        return $this->belongsTo(Rental::class);
    }
}
