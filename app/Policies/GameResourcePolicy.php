<?php

namespace App\Policies;

use App\Entities\Character;
use App\Entities\Role;
use App\Entities\User;
use App\Facades\UserService;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameResourcePolicy
{
    use HandlesAuthorization;

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
