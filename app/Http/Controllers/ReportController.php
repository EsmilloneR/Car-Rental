<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class ReportController extends Controller
{
    public function downloadReport()
        {
            // Top 5 Rented Vehicles
            $topVehicles = Vehicle::withCount('rentals')
                ->orderByDesc('rentals_count')
                ->take(5)
                ->get();

            // Top 5 Loyal Customers
            $loyalCustomers = User::withCount('rentals')
                ->orderByDesc('rentals_count')
                ->take(5)
                ->get();

            // Generate PDF
            return Pdf::view('livewire.filament.pages.pdf.rental-summary', compact('topVehicles', 'loyalCustomers'))
                ->format('A4')
                ->name('rental-report.pdf')
                ->download();
        }
}
