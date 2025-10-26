<?php

namespace App\Observers;

use App\Jobs\OngoingJob;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RentalObserver
{
    /**
     * Handle the Rental "created" event.
     */
    public function created(Rental $rental): void
    {
        //
    }

    /**
     * Handle the Rental "updated" event.
     */
    // public function updated(Rental $rental): void
    // {
    //     if ($rental->wasChanged('status') && $rental->status === 'ongoing') {
    //         $endTime = Carbon::parse($rental->rental_end);

    //         // if ($endTime->isPast()) {
    //         //     $endTime = now()->addMinute();
    //         // }

    //         $delaySeconds = max(now()->diffInSeconds($endTime, false), 10);

    //         OngoingJob::dispatch($rental->id)->delay(now()->addSeconds($delaySeconds));
    //         Log::info("OngoingJob dispatched for Rental ID {$rental->id}, runs in {$delaySeconds} seconds");
    //     }
    // }



     public function updated(Rental $rental): void
    {
        if ($rental->wasChanged('status') && $rental->status === 'ongoing') {
            $endTime = Carbon::parse($rental->rental_end);
            $delaySeconds = max(now()->diffInSeconds($endTime, false), 10);

            OngoingJob::dispatch($rental->id)->delay(now()->addSeconds($delaySeconds));

            Log::info("OngoingJob dispatched for Rental ID {$rental->id}, runs in {$delaySeconds} seconds");
        }
    }

    /**
     * Handle the Rental "deleted" event.
     */
    public function deleted(Rental $rental): void
    {
        //
    }

    /**
     * Handle the Rental "restored" event.
     */
    public function restored(Rental $rental): void
    {
        //
    }

    /**
     * Handle the Rental "force deleted" event.
     */
    public function forceDeleted(Rental $rental): void
    {
        //
    }
}
