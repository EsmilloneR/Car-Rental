<?php

namespace App\Jobs;

use App\Models\Rental;
use App\Models\User;
use App\Notifications\RentalCompletedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

use Filament\Notifications\Notification;

class OngoingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $deleteWhenMissingModels = true;
    public $rentalId;
    public $tries = 3;
    // public $uniqueFor = 3600;
    // public $uniqueFor = 60;


    /**
     * Create a new job instance.
     *
     * @param  int  $rentalId
     * @return void
     */


    public function uniqueId(): string
    {
        return (string) $this->rentalId;
    }

    public function __construct($rentalId)
    {
        $this->rentalId = $rentalId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        Log::info("OngoingJob started for rental {$this->rentalId}");

        $rental = Rental::find($this->rentalId);

        if (!$rental) {
            Log::warning("OngoingJob: Rental ID {$this->rentalId} not found.");
            return;
        }
        Log::info("Rental found, status={$rental->status}, end={$rental->rental_end}");


        $end = Carbon::parse($rental->rental_end);
        if ($rental->status === 'ongoing' && now()->gte($end)) {
            $rental->update(['status' => 'completed']);
            $rental->user->notify(new RentalCompletedNotification($rental));

            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new RentalCompletedNotification($rental));

                Log::info("Attempting to send Filament notification to admin...");

                Notification::make()
                    ->title('Rental Completed')
                    ->body("Rental Agreement No. {$rental->agreement_no}, belonging to {$rental->user->name}, has reached its end date and has been marked as completed automatically.")
                    ->success()
                    ->sendToDatabase($admin)
                    ->broadcast($admin);

                Log::info("Filament notification successfully triggered for admin {$admin->id}");

            }

            Log::info("Rental {$rental->id} marked as completed at {$rental->rental_end}");
        }else{
            Log::info("Rental {$rental->id} still ongoing (now=" . now() . ", end={$rental->rental_end})");
        }

    }


    public function failed(\Throwable $exception)
    {
        Log::error("OngoingJob for rental {$this->rentalId} failed: {$exception->getMessage()}");
    }
}
