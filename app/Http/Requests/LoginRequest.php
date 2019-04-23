<?php

namespace App\Http\Requests;

use App\Facades\UserService;
use App\Rules\LoginValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => [
                'required',
                'max:255',
                new LoginValidation(),
                function($attribute, $value, $fail) {
                    if(UserService::hasUserVerifiedEmail($this->user()) === false) {
                        // It's necessary to logout user, even if not has a token
                        // just for safety
                        Auth::logout();
                        $fail('You have to verified Your email.');
                    }
                }
            ],
            'password' => 'required',
        ];
    }
}
