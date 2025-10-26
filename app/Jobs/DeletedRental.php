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


    public function handle(){
        $rental = Rental::with(['inspections', 'payments'])->find($this->rentalId);

        if ($rental && $rental->status === 'cancelled') {
            $rental->inspections()->delete();
            $rental->payments()->delete();

            $rental->delete();
        }
    }
}

