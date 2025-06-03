<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class PlayerAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $password;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to Courton - Your Account is Ready')
            ->greeting('Hello ' . $this->user->first_name . '!')
            ->line('Your player account has been created successfully.')
            ->line('You can now log in to your account using the following credentials:')
            ->line('Email: ' . $this->user->email)
            ->line('Password: ' . $this->password)
            ->line('For security reasons, we recommend changing your password after your first login.')
            ->action('Login to Your Account', route('auth.login'))
            ->line('Thank you for choosing Courton!');
    }
}
