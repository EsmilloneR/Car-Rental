<?php

namespace App\Jobs;

use App\Models\Rental;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;

class DeletedRental implements ShouldQueue
{
     use Queueable;

    /**
     * The rental ID.
     *
     * @var int
     */
    public $deleteWhenMissingModels = true;

    public $rentalId;

    /**
     * Create a new job instance.
     *
     * @param  int  $rentalId
     * @return void
     */
    public function __construct(int $rentalId)
    {
        $this->rentalId = $rentalId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    // public function handle()
    // {
    //     $rental = Rental::find($this->rentalId);

    //     if ($rental && $rental->status === 'cancelled') {
    //         $cancelTime = Carbon::parse($rental->updated_at);
    //         if (now()->diffInMinutes($cancelTime) >= 1) {
    //             if (Rental::find($this->rentalId)) {
    //                 $rental->delete();
    //             }
    //         }
    //     }
    // }

    public function handle(){
        $rental = Rental::with(['inspections', 'payments'])->find($this->rentalId);

        if ($rental && $rental->status === 'cancelled') {
            // Delete related data first
            $rental->inspections()->delete();
            $rental->payments()->delete();

            // Then delete the rental record itself
            $rental->delete();
        }
    }
}

