<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends VerifyEmailBase
{
    public const CODE_VALID_IN_MINUTES = 60;

    public const MESSAGE_SUCCESS = 'E-mail is now verified. You can log in to application.';
    public const MESSAGE_DANGER_ALREADY_VERIFIED = 'You have already verified Your email address.';
    public const MESSAGE_DANGER_CODE_EXPIRED = 'Your verification code has expired. Ask about it once again.';
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
     * @param mixed $notifiable
     *
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $temporarySignedURL = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(self::CODE_VALID_IN_MINUTES), ['id' => $notifiable->getKey()]
        );

        return $temporarySignedURL;
    }
}
