<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class IsUserWithUsernameVerified implements Rule
{


    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        $user = User::withUsername($value)->firstOrFail();
        return $user->email_verified_at !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        return 'Email is not verified.';
    }
}