<?php

namespace App\Filament\Resources\Vehicles\Pages;

use App\Filament\Resources\Vehicles\VehicleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;


    public function getHeading(): string
    {
        return 'Vehicles';
    }

    public function getSubheading(): ?string
    {
        return 'Manage all registered vehicles, their details, and availability status.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New Vehicle')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->button()
                ->modalHeading('Register Vehicle')
                ->modalDescription('Fill out the details below to add a new vehicle to your list.')
                ->createAnother(false),
        ];
    }
}
