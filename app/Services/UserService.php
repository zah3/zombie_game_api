<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-31
 * Time: 19:49
 */

namespace App\Services;


use App\Notifications\VerifyEmail;
use App\User;

class UserService
{

    /**
     * Determine if the user has verified their email address.
     *
     * @param User $user
     *
     * @return bool
     */
    public function hasUserVerifiedEmail(User $user) : bool
    {
        return $user->email_verified_at !== null;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @param User $user
     *
     * @return bool
     */
    public function setEmailAsVerified(User $user) : bool
    {
        $user->email_verified_at = now();
        return $user->save();
    }

    /**
     * Send the email verification notification.
     *
     * @param User $user
     *
     * @return void
     */
    public function sendEmailVerificationNotification(User $user) : void
    {
        $user->notify(new VerifyEmail()); // my notification
    }
}