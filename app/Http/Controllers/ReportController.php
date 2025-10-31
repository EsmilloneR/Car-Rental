<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class ReportController extends Controller
{
    public function downloadReport()
        {
            $topVehicles = Vehicle::withCount('rentals')
                ->orderByDesc('rentals_count')
                ->take(5)
                ->get();

            $loyalCustomers = User::withCount('rentals')
                ->orderByDesc('rentals_count')
                ->take(5)
                ->get();


            if ($topVehicles->isEmpty() || $loyalCustomers->isEmpty()) {
                return response()->json([
                    'status'  => 'error',
                    'title'   => 'No Data Available',
                    'message' => 'There are no rentals or customers to include in the report. Please try again later.',
                ], 400);
            }

            return Pdf::view('livewire.filament.pages.pdf.rental-summary', compact('topVehicles', 'loyalCustomers'))
                ->format('A4')
                ->name('rental-report.pdf')
                ->download();
        }
}
