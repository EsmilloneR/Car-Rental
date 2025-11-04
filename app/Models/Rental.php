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
        'reservation_fee',
        'extra_charges',
        'penalties',
        'total',
        'status',
        'agreement_no',
        'paymongo_url',
    ];

    protected static function booted()
    {
        // 🔹 Calculate total before saving
        static::saving(function ($rental) {
            $rental->total =
                ($rental->base_amount ?? 0)
                + (($rental->reservation_fee ?? 0)
                - ($rental->extra_charges ?? 0)
                - ($rental->penalties ?? 0));


        });

        // 🔹 Generate Agreement No. and Reservation Fee when creating
        static::creating(function ($rental) {
            // Agreement number auto-generation
            if (!$rental->agreement_no) {
                $date = now()->format('Ymd');
                $count = static::whereDate('created_at', now())->count() + 1;

                do {
                    $agreementNo = 'AGR-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
                    $count++;
                } while (static::where('agreement_no', $agreementNo)->exists());

                $rental->agreement_no = $agreementNo;
            }

            // Compute reservation fee automatically if not set
            if (empty($rental->reservation_fee)) {
                $rental->reservation_fee = self::calculateReservationFee(
                    $rental->base_amount,
                    $rental->trip_type,
                    $rental->getDurationInDays()
                );
            }
        });

        // 🔹 Clear PayMongo URL once finalized
        static::updating(function ($rental) {
            if (in_array($rental->status, ['reserved', 'completed'])) {
                $rental->paymongo_url = null;
            }
        });

        // 🔹 Cascade delete related inspections and payments
        static::deleting(function ($rental) {
            $rental->inspections()->delete();
            $rental->payments()->delete();
        });
    }

    protected $casts = [
        'rental_start' => 'datetime',
        'rental_end'   => 'datetime',
    ];

    // 🔹 Relationships
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

    public static function calculateReservationFee($baseAmount, $trip_type, $durationDays = 1)
    {
        if ($baseAmount <= 0) {
            return 0;
        }

        $fee = round($baseAmount * 0.20, 2);

        if ($durationDays <= 1 && $baseAmount <= 5000 && $fee < 500) {
            $fee = 500;
        }

        return $fee;
    }

    // 🔹 Get Rental Duration in Days
    public function getDurationInDays()
    {
        if (!$this->rental_start || !$this->rental_end) {
            return 1;
        }

        return $this->rental_end->diffInDays($this->rental_start) ?: 1;
    }

    // 🔹 Virtual Attribute: Remaining Balance
    public function getRemainingBalanceAttribute()
    {
        return max(0, ($this->base_amount ?? 0) - ($this->reservation_fee ?? 0));
    }

    // 🔹 Virtual Attribute: Display formatted total
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total, 2);
    }
}
