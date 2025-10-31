<?php

namespace App\Filament\Pages;

use App\Models\Rental;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class RentalCalendar extends Page
{
    protected string $view = 'volt-livewire::filament.pages.rental-calendar';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDateRange;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::CalendarDateRange ;
    protected static ?string $modelLabel = 'Rental Calendar';
    protected static ?int $navigationSort = 2;

    protected static string | UnitEnum | null $navigationGroup = 'Analytics';

    protected ?string $heading = 'Rental Schedule Overview';
    protected ?string $subheading = 'View and track all vehicle rental periods, including pending, ongoing, completed, and cancelled bookings — all in one calendar.';
    public $rentals;

    public function mount()
    {
        $this->rentals = Rental::with('vehicle.manufacturer')
        ->where('status', '!=', 'completed')
        ->get()
        ->map(function ($rental) {
            return [
                'title' => ' ('. $rental->user->name.') ' . $rental->vehicle->manufacturer->brand. ' '.$rental->vehicle->model.'('.ucfirst($rental->status).')',
                'start' => $rental->rental_start,
                'end' => $rental->rental_end,
                'color' => match($rental->status){
                    'pending' => '#facc15',
                    'ongoing' => '#3b82f6',
                    'cancelled' => '#ef4444',
                    default => '#6b7280'
                },
                'url' => route('filament.admin.resources.rentals.edit', $rental->id),
            ];
        });
    }

    public static function getScripts(): array
    {
        return [
            'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'
        ];
    }
}
