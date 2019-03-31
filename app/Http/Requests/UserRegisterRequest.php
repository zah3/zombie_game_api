<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'username' => 'required|unique:users|max:255|min:3|alpha_dash',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password'
        ];
    }
}
