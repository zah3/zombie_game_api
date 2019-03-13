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
     * Creates a new character
     */
    public static function create(
        User $user,
        ?Fraction $fraction,
        string $name,
        ?int $experiance
    ) : Character
    {
        $character = new Character();
        $character->user_id = $user->id;
        $character->fraction_id = $fraction !== null ?
            $fraction->id :
             ;
        $character->name = $name;
        $character->experiance = $experiance !== null ? $experiance : 0;
    }
}