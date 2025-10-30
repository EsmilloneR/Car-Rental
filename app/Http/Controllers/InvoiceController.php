<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Vehicle;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoiceController extends Controller
{
    public function receipt($id)
    {
        $payment = Payment::with('rentals.user', 'rentals.vehicle.manufacturer')->findOrFail($id);
        $rental = $payment->rentals;
        $vehicle = $rental->vehicle;

        $html = view('livewire.filament.pages.pdf.receipt', compact('payment', 'rental', 'vehicle'))->render();

        return Pdf::html($html)
            ->format('A4')
            ->download('TwayneGarage_Receipt-' . $rental->agreement_no . '.pdf');
    }
}
