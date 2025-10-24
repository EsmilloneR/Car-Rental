<?php

namespace App\Filament\Pages;

use App\Models\Location;
use App\Models\Rental;
use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;

class CarTracking extends Page
{
    protected string $view = 'volt-livewire::filament.pages.car-tracking';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-map-pin';
    protected static BackedEnum|string|null $activeNavigationIcon = 'heroicon-s-map-pin';
    protected ?string $heading = 'GPS Vehicle Tracking';
    protected ?string $subheading = 'Real-time visualization of vehicle positions powered by GPS data. Keep your fleet under control at all times.';
    protected static ?string $navigationLabel = "Vehicle Locations";
    protected static string | UnitEnum | null $navigationGroup = 'Fleet Management';



}
