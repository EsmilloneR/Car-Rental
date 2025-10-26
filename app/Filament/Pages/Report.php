<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Vehicle;
use Filament\Actions\Action;
use Filament\Pages\Page;
use BackedEnum;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Report extends Page
{
    protected string $view = 'volt-livewire::filament.pages.report';
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedTrophy;
    protected static BackedEnum|string|null $activeNavigationIcon = Heroicon::Trophy;
    protected static ?string $navigationLabel = 'Most Loyal & Rented';
    // protected static string | UnitEnum | null $navigationGroup  = 'Report Management';

    public $topVehicles;
    public $loyalCustomers;

    public function mount(): void
    {
        $this->topVehicles = Vehicle::with('manufacturer')
            ->withCount('rentals')
            ->orderByDesc('rentals_count')
            ->limit(5)
            ->get();


        $this->loyalCustomers = User::select('users.name', DB::raw('COUNT(rentals.id) as total_rentals'))
            ->join('rentals', 'rentals.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_rentals')
            ->limit(5)
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Download Report')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->url(route('reports.download'), shouldOpenInNewTab: false),
        ];
    }


    public function getHeading(): string
    {
        return 'Rental Performance Overview';
    }

    public function getSubheading(): ?string
    {
        return 'A summary of your most rented vehicles and loyal customers.';
    }
}
