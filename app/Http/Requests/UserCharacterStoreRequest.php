<?php

namespace App\Http\Requests;

use App\Role;
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
        //TODO check it
//        $user = $this->user();
//        dd($this);
//        if ($user->hasRole(Role::USER)) {
//            return $user->id ===
//        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'string',
                'unique:characters',
                'name',
                'max:255',
                new CharacterLimit(Auth::user())
            ],
        ];
    }
}
