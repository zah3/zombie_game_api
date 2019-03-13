<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-13
 * Time: 07:50
 */

namespace App\Repositories;


use App\Character;
use App\Fraction;
use App\User;

class CharacterRepository
{
    /**
     * Creates new character record in database
     *
     * @param User $user
     * @param Fraction|null $fraction
     * @param string $name
     * @param int|null $experience
     *
     * @return Character
     */
    public static function create(
        User $user,
        ?Fraction $fraction,
        string $name,
        ?int $experience
    ) : Character
    {
        $character = new Character();
        $character->user_id = $user->id;
        $character->fraction_id = $fraction !== null ?
            $fraction->id :
            Fraction::NAME_NORMAL;
        $character->name = $name;
        $character->experiance = $experience !== null ? $experience : 0;
        $character->save();

        return $character;
    }
}