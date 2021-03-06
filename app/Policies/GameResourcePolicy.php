<?php

namespace App\Policies;

use App\Entities\Character;
use App\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameResourcePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given post can be seen by the user.
     *
     * @param User $user
     * @param Character $character
     *
     * @return bool
     */
    public function view(User $user, Character $character)
    {
        return $user->id === $character->user_id;
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param User $user
     * @param Character $character
     *
     * @return bool
     */
    public function update(User $user, Character $character)
    {
        return $user->id === $character->user_id;
    }
}
