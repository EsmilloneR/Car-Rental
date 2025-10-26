<?php

namespace App\Filament\Resources\Rentals\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class RentalChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Revenue';

    protected static ?int $sort = 2;
    protected ?string $pollingInterval = '10s';
    protected bool $isCollapsible = true;
    protected int | string | array $columnSpan = '2';
    protected function getData(): array
    {
    // $data = Payment::where('status', 'completed')
    //         ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
    //         ->groupBy('month')
    //         ->pluck('total', 'month');

    $data = Payment::where('status', 'completed')
        ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->values(),
                    'barPercentage' => 0.5,
                    'categoryPercentage' => 0.6,
                ],
            ],
            'labels' => $data->keys()->map(fn ($m) => date('F', mktime(0, 0, 0, $m, 1))),
        ];
    }



    protected function getType(): string
    {
        return 'bar';
    }
}
