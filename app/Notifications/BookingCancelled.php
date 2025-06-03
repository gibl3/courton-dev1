<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;
use Carbon\Carbon;

class BookingCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $reason;

    public function __construct(Booking $booking, ?string $reason = null)
    {
        $this->booking = $booking;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $court = $this->booking->court;
        $bookingDate = $this->booking->booking_date->format('F d, Y');
        $startTime = Carbon::parse($this->booking->start_time)->format('g:i A');
        $endTime = Carbon::parse($this->booking->end_time)->format('g:i A');

        $message = (new MailMessage)
            ->subject('Booking Cancellation - Courton')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Your court booking has been cancelled.')
            ->line('Here are the booking details:')
            ->line('Court: ' . $court->name . ' (' . ucfirst($court->type) . ')')
            ->line('Date: ' . $bookingDate)
            ->line('Time: ' . $startTime . ' - ' . $endTime)
            ->line('Duration: ' . $this->booking->duration . ' hour(s)')
            ->line('Total Amount: ₱' . number_format($this->booking->total_amount, 2));

        if ($this->reason) {
            $message->line('Cancellation Reason: ' . $this->reason);
        }

        $message->line('If you have any questions, please contact our support team.');

        return $message;
    }
}
