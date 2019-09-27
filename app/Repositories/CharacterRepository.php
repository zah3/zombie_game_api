<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-13
 * Time: 07:50
 */

namespace App\Repositories;

use App\Entities\Character;
use App\Entities\Fraction;
use App\Repositories\Helpers\BaseRepository;
use App\Entities\User;

class CharacterRepository extends BaseRepository
{
    /**
     * Creates new character record in database
     *
     * @param User $user
     * @param Fraction|null $fraction
     * @param string $name
     * @param int|null $experience
     * @param int|null $strength
     * @param int|null $speed
     * @param int|null $stamina
     * @param int|null $abilityPoints
     *
     * @return Character
     */
    public static function create(
        User $user,
        ?Fraction $fraction,
        string $name,
        int $experience = null,
        int $strength = null,
        int $speed = null,
        int $stamina = null,
        int $abilityPoints = null
    ) : Character
    {
        $character = new Character();
        $character->user_id = $user->id;
        $character->fraction_id = $fraction !== null ?
            $fraction->id :
            Fraction::ID_NORMAL;
        $character->name = $name;
        $character->experience = $experience !== null ? $experience : 0;
        $character->strength = $strength !== null ? $strength : Character::DEFAULT_STRENGTH;
        $character->speed = $speed !== null ? $speed : Character::DEFAULT_SPEED;
        $character->stamina = $stamina !== null ? $stamina : Character::DEFAULT_STAMINA;
        $character->ability_points = $abilityPoints !== null ? $abilityPoints : Character::DEFAULT_ABILITY_POINTS;
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
        $character = self::updateField($character, $newFields, 'stamina');
        $character = self::updateField($character, $newFields, 'speed');
        $character = self::updateField($character, $newFields, 'ability_points');

        $character->save();
        return $character;
    }
}