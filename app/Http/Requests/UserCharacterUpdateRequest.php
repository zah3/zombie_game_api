<?php

namespace App\Http\Requests;


use App\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCharacterUpdateRequest extends FormRequest
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
            'inputs.name' => [
                'string',
                Rule::unique('characters','name')->ignore($this->route('character')),
                'max:255',
                'alpha_dash',
                'min:4',
            ],
        ];
    }
}
