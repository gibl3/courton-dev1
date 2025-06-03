<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $court = $this->booking->court;
        $bookingDate = $this->booking->booking_date->format('F d, Y');
        $startTime = Carbon::parse($this->booking->start_time)->format('g:i A');
        $endTime = Carbon::parse($this->booking->end_time)->format('g:i A');

        return (new MailMessage)
            ->subject('Booking Confirmation - Courton')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Your court booking has been created successfully.')
            ->line('Here are your booking details:')
            ->line('Court: ' . $court->name . ' (' . ucfirst($court->type) . ')')
            ->line('Date: ' . $bookingDate)
            ->line('Time: ' . $startTime . ' - ' . $endTime)
            ->line('Duration: ' . $this->booking->duration . ' hour(s)')
            ->line('Total Amount: ₱' . number_format($this->booking->total_amount, 2))
            ->line('Status: ' . ucfirst($this->booking->status))
            ->action('View Booking', route('player.bookings.show', $this->booking))
            ->line('Thank you for choosing Courton!')
            ->line('If you have any questions, please contact our support team.');
    }

    /**o 
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
