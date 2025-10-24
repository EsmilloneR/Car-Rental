<?php

namespace App\Filament\Resources\Manufacturers\Pages;

use App\Filament\Resources\Manufacturers\ManufacturerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManufacturers extends ListRecords
{
    protected static string $resource = ManufacturerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New Manufacturer')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->button()
                ->modalHeading('Register Manufacturer')
                ->modalDescription('Fill out the details below to add a new manufacturer to your list.')
                ->createAnother(false),
        ];
    }



}
