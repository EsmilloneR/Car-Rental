<?php

namespace App\Notifications;

use App\Models\Rental;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RentalCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $rental;
    /**
     * Create a new notification instance.
     */
    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Rental #{$this->rental->id} has been marked as completed.",
            'vehicle' => $this->rental->vehicle->model ?? 'Unknown Vehicle',
            'user' => $this->rental->user->name ?? 'Unknown User',
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
