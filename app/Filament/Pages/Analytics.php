<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Rentals\Widgets\RentalChart;
use App\Models\Rental;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Spatie\LaravelPdf\Facades\Pdf;
use BackedEnum;
use UnitEnum;
class Analytics extends Page
{
    protected string $view = 'volt-livewire::filament.pages.analytics';
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static BackedEnum|string|null $activeNavigationIcon = Heroicon::ChartBar;
    protected static ?string $navigationLabel = 'Reports';
    protected static string | UnitEnum | null $navigationGroup = 'Analytics';
    public ?string $from = null;
    public ?string $to = null;

    public $totalRentals = 0;
    public $totalIncome = 0;
    public $rentalsPerVehicle = [];
    public $incomeByDay = [];


    public function mount()
    {
        $this->to = now()->toDateString();
        $this->from = now()->subDays(29)->toDateString();
        $this->refreshStats();
    }


    public function refreshStats()
    {
        $from = Carbon::parse($this->from)->startOfDay();
        $to   = Carbon::parse($this->to)->endOfDay();

        $this->totalRentals = Rental::whereBetween('rental_start', [$from, $to])->count();
        $this->totalIncome  = Rental::whereBetween('rental_start', [$from, $to])->sum('total');

        $this->rentalsPerVehicle = Rental::selectRaw('vehicle_id, COUNT(*) as rentals, SUM(total) as income')
            ->whereBetween('rental_start', [$from, $to])
            ->groupBy('vehicle_id')
            ->with('vehicle')
            ->get();

        $this->incomeByDay = Rental::selectRaw('DATE(rental_start) as day, SUM(total) as income')
            ->whereBetween('rental_start', [$from, $to])
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    public function exportPdf()
    {
        return redirect()->route('analytics.download', [
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }


    public function getHeading(): string
    {
        return 'Rental Analytics';
    }

    public function getSubheading(): ?string
    {
        return 'View statistics, performance, and income summaries';
    }

    protected function getFooterWidgets(): array
    {
        return [
            RentalChart::class,
        ];
    }
}
