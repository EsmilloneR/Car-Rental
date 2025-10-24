<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function broadcastOn()
    {
        return new Channel('admin.notifications');
    }

    public function broadcastAs()
    {
        return 'payment.confirmed';
    }

    public function broadcastWith()
    {
        return [
            'user' => $this->payment->rentals->user->name ?? 'Unknown',
            'manufacturer' => $this->payment->rentals->vehicle->manufacturer->name ?? '',
            'vehicle' => $this->payment->rentals->vehicle->model ?? '',
            'amount' => $this->payment->amount,
            'reference' => $this->payment->transaction_reference,
        ];
    }
}
