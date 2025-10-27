<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class PaymentCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;
    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


    public function toDatabase($notifiable)
    {
        return [
            'message' => "Payment #{$this->payment->id} has been marked as completed.",
            'agreement_no' => $this->payment->agreement_no ?? 'Unknown Agreement No.',
            'user' => $this->payment->rentals->user->name ?? 'Unknown User',
        ];
    }


    public function toFilament($notification)
    {
        return FilamentNotification::make()
            ->title('Rental Completed')
            ->body("Rental #{$notification->data['message']}")
            ->success()
            ->icon('heroicon-o-check-circle')
            ->sendToDatabase(auth()->user());
    }
}
