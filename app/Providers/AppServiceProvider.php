<?php

namespace App\Providers;

use App\Models\Rental;
use App\Observers\RentalObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Rental::observe(RentalObserver::class);
    }
}
