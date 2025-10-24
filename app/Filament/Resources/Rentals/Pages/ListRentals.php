<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Filament\Resources\Rentals\RentalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRentals extends ListRecords
{
    protected static string $resource = RentalResource::class;

    public function getHeading(): string
    {
        return 'Rental Transactions';
    }

    public function getSubheading(): ?string
    {
        return 'View, manage, and track all rental bookings and their current status.';
    }
}
