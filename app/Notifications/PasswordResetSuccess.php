<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetSuccess extends Notification
{
    use Queueable;

    public const MESSAGE_SUCCESS = 'We have e-mailed your password reset link!';

    public const MESSAGE_ERROR_INVALID_TOKEN = 'This password reset token is invalid.';
    public const MESSAGE_ERROR_CANNOT_FIND_EMAIL = "We can't find a user with that e-mail address.";

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You are changed your password succeful.')
            ->line('If you did change password, no further action is required.')
            ->line('If you did not change password, protect your account.');
    }
}
