<?php

namespace App\Http\Requests;

use App\Rules\CharacterLimit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserCharacterStoreRequest extends FormRequest
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
        //dd($this->user());
        return [
            'name' => [
                'required',
                'string',
                'unique:characters',
                'max:255',
                new CharacterLimit($this->user())
            ]
        ];
    }
}
