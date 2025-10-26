<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
   protected $fillable = [
        'user_id',
        'vehicle_id',
        'rental_start',
        'rental_end',
        'pickup_location',
        'dropOff_location',
        'trip_type',
        'base_amount',
        'deposit',
        'extra_charges',
        'penalties',
        'total',
        'status',
        'agreement_no',
        'paymongo_url'
    ];

    protected static function booted()
    {
        static::saving(function ($rental) {
            $rental->total =
                ($rental->base_amount ?? 0)
                + (($rental->deposit ?? 0)
                - ($rental->extra_charges ?? 0)
                - ($rental->penalties ?? 0));
        });

        static::creating(function ($rental) {
            if (!$rental->agreement_no) {
                $date = now()->format('Ymd');
                $count = static::whereDate('created_at', now())->count() + 1;

                do {
                    $agreementNo = 'AGR-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
                    $count++;
                } while (static::where('agreement_no', $agreementNo)->exists());

                $rental->agreement_no = $agreementNo;
            }
        });

        static::updating(function ($rental) {
            if (in_array($rental->status, ['reserved', 'completed'])) {
                $rental->paymongo_url = null;
            }
        });

         static::deleting(function ($rental) {
            $rental->inspections()->delete();
            $rental->payments()->delete();
        });
    }

    protected $casts = [
        'rental_start' => 'datetime',
        'rental_end'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
