<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-31
 * Time: 19:49
 */

namespace App\Services;


use App\Http\Helpers\StatusResponse;
use App\Notifications\VerifyEmail;
use App\Entities\User;

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
     * @return void
     */
    public function setEmailAsVerified(User $user) : void
    {
        $user->email_verified_at = now();
        $user->save();
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
        $user->notify(new VerifyEmail());
    }

    /**
     * AuthorizeRoles
     *
     * @param User $user
     * @param string || array $roles
     *
     * @return bool
     */
    public function authorizeRoles(User $user, $roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($user, $roles) ||
                abort(StatusResponse::STATUS_UNAUTHORIZED, User::MESSAGE_UNAUTHORIZED);
        }
        return $this->hasRole($user, $roles) ||
            abort(StatusResponse::STATUS_UNAUTHORIZED, User::MESSAGE_UNAUTHORIZED);
    }

    /**
     * Check if user has any of roles.
     *
     * @param User $user
     * @param array $roles
     *
     * @return bool
     */
    public function hasAnyRole(User $user, array $roles) : bool
    {
        return !is_null($user->roles()->whereIn('name', $roles)->first());
    }

    /**
     * Check if user has 1 role.
     *
     * @param User $user
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(User $user, string $role) : bool
    {
        return !is_null($user->roles()->where('name', '=', $role)->first());
    }
}