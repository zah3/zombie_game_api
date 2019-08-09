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
use App\Repositories\Helpers\BaseRepository;
use App\User;

class CharacterRepository extends BaseRepository
{
    /**
     * Creates new character record in database
     *
     * @param User $user
     * @param Fraction|null $fraction
     * @param string $name
     * @param int|null $experience
     * @param int|null $agility
     * @param int|null $strength
     *
     * @return Character
     */
    public static function create(
        User $user,
        ?Fraction $fraction,
        string $name,
        ?int $experience = null,
        ?int $agility = null,
        ?int $strength = null
    ) : Character
    {
        $character = new Character();
        $character->user_id = $user->id;
        $character->fraction_id = $fraction !== null ?
            $fraction->id :
            Fraction::ID_NORMAL;
        $character->name = $name;
        $character->experience = $experience !== null ? $experience : 0;
        $character->agility = $agility !== null ? $agility : Character::DEFAULT_AGILITY;
        $character->strength = $strength !== null ? $strength : Character::DEFAULT_STRENGTH;
        $character->save();

        return $character;
    }

    /**
     * Updates existed character in database
     *
     * @param Character $character
     * @param array $newFields
     *
     * @return Character
     */
    public static function update(
        Character $character,
        Array $newFields
    ) : Character
    {
        $character = self::updateField($character, $newFields, 'user_id');
        $character = self::updateField($character, $newFields, 'fraction_id');
        $character = self::updateField($character, $newFields, 'name');
        $character = self::updateField($character, $newFields, 'agility');
        $character = self::updateField($character, $newFields, 'strength');
        $character = self::updateField($character, $newFields, 'experience');

        $character->save();
        return $character;
    }
}