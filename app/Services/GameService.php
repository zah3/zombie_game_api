<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-29
 * Time: 19:06
 */

namespace App\Services;


use App\Character;
use App\Repositories\CharacterRepository;

class GameService
{

    /**
     * Adds experience to character
     *
     * @param int $experiencePoints
     * @param Character $character
     *
     * @return int Character.experience
     */
    public function addExperienceToCharacter(int $experiencePoints, Character $character) : int
    {
        $character = CharacterRepository::addExperience($experiencePoints, $character);
        return $character->experience;
    }
}