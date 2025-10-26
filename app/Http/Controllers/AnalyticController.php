<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Carbon;

class AnalyticController extends Controller
{
    public function downloadAnalytics($from, $to)
    {
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate   = Carbon::parse($to)->endOfDay();

        $totalRentals = Rental::whereBetween('rental_start', [$fromDate, $toDate])->count();
        $totalIncome  = Rental::whereBetween('rental_start', [$fromDate, $toDate])->sum('total');
        $rentalsPerVehicle = Rental::selectRaw('vehicle_id, COUNT(*) as rentals, SUM(total) as income')
            ->whereBetween('rental_start', [$fromDate, $toDate])
            ->groupBy('vehicle_id')
            ->with('vehicle')
            ->get();

        $incomeByDay = Rental::selectRaw('DATE(rental_start) as day, SUM(total) as income')
            ->whereBetween('rental_start', [$fromDate, $toDate])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return Pdf::view('livewire.filament.pages.pdf.analytics-summary', [
            'from' => $from,
            'to' => $to,
            'totalRentals' => $totalRentals,
            'totalIncome' => $totalIncome,
            'rentalsPerVehicle' => $rentalsPerVehicle,
            'incomeByDay' => $incomeByDay,
        ])
        ->format('A4')
        ->name("report_{$from}_to_{$to}.pdf")
        ->download();
    }

}
