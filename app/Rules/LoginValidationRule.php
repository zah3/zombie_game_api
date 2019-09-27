<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * If someone use this validation then, have to remember about user will be.
 *
 * Class LoginValidation
 * @package App\Rules
 */
class LoginValidationRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Auth::attempt(['username' => request('username'), 'password' => request('password')])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Password or username are incorrect.';
    }
}
