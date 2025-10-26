<?php

namespace App\Filament\Resources\Rentals\Widgets;

use App\Models\Payment;
use App\Models\Rental;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RentalStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Rentals', Rental::count()),

            Stat::make('Total Revenue', '₱' . number_format(
                Payment::where('status', 'completed')->sum('amount'), 2
            )),

            Stat::make('Active Rentals', Rental::where('status', 'ongoing')->count()),

            Stat::make('Vehicles Available', Vehicle::doesntHave('rentals')->count()),
        ];
    }


    // protected function getStats(): array
    // {
    //     return [
    //         Stat::make('Ongoing Rentals', Rental::where('status', 'ongoing')->count()),
    //         Stat::make('Completed Rentals', Rental::where('status', 'completed')->count()),
    //         Stat::make('Queued Jobs', \DB::table('jobs')->count()),
    //     ];
    // }
}
