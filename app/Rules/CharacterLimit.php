<?php

namespace App\Rules;

use App\Character;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class CharacterLimit implements Rule
{

    private $user;

    /**
     * Create a new rule instance.
     *
     * @param User $user
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

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
        $userCharacters = Character::query()
            ->withUser($this->user)
            ->count();
        if ($userCharacters >= Character::LIMIT_PER_USER){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "User {$this->user->username} has archived limit of characters.";
    }
}
