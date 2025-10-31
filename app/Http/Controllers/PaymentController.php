<?php

namespace App\Http\Controllers;

use App\Events\PaymentConfirmed;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use App\Notifications\PaymentCompletedNotification;
use App\Notifications\RentalCompletedNotification;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class PaymentController extends Controller
{
    public function success(Request $request)
    {
        $rentalId = $request->query('rental_id');

        if (!$rentalId) {
            Log::error('Missing rental_id in success redirect.');
            return redirect()->route('home')->with('error', 'Payment failed: missing rental reference.');
        }

        $rental = Rental::with(['user', 'vehicle.manufacturer'])->find($rentalId);

        if (!$rental) {
            Log::error("Rental ID {$rentalId} not found after payment success.");
            return redirect()->route('home')->with('error', 'Payment failed: rental not found.');
        }

        $rental->update([
            'status' => 'reserved',
        ]);

        $payment = Payment::create([
            'rental_id' => $rental->id,
            'payment_method' => 'online_payment',
            'amount' => $rental->total,
            'transaction_reference' => 'CRTG-' . strtoupper(uniqid()),
            'status' => Payment::STATUS_COMPLETED,
        ]);

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new PaymentCompletedNotification($payment));

            Log::info("Attempting to send Filament notification to admin...");

            Notification::make()
                ->title('Payment Completed')
                ->body("The Payment for {$payment->rentals->user->name} (Agreement No. {$payment->rentals->agreement_no}) has been successfully completed.")
                ->success()
                ->sendToDatabase($admin)
                ->broadcast($admin);

            Log::info("Filament notification successfully triggered for admin {$admin->id}");

        }


        try{

        broadcast(new PaymentConfirmed($payment));
        }catch(\Throwable $e){
            Log::warning("Broadcast failed for payment {$payment->id}: " . $e->getMessage());
        }

        session()->forget(['pending_rental_id']);

        return redirect()->route('thankyou')->with('success', 'Payment successful!');
    }

}
