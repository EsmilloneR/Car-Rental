<?php

namespace App\Filament\Resources\Manufacturers\Pages;

use App\Filament\Resources\Manufacturers\ManufacturerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManufacturer extends EditRecord
{
    protected static string $resource = ManufacturerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
