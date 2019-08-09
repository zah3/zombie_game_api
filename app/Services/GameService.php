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
use App\Repositories\CoordinateRepository;
use Illuminate\Support\Facades\DB;

class GameService
{
    /**
     * Saves game status
     *
     * @param Character $character
     * @param int $fractionId
     * @param int $experience
     * @param int $agility
     * @param int $strength
     * @param array $coordinate
     * @param array $abilities
     *
     * @return bool
     */
    public function save(
        Character $character,
        int $fractionId,
        int $experience,
        int $agility,
        int $strength,
        array $coordinate,
        array $abilities
    ) : bool
    {
        DB::beginTransaction();
        try {
        $newFields = [
            'fraction_id' => $fractionId,
            'experience' => $experience,
            'agility' => $agility,
            'strength' => $strength,
        ];
        CharacterRepository::update($character, $newFields);

        CoordinateRepository::update($character->coordinate, $coordinate);

        $existsAbilities = $character->abilities;
        foreach ($abilities as $key => $ability) {
            $existingAbility = $existsAbilities->where('id', '=', $ability['id'])->first();
            if ($existingAbility === null) {
                continue;
            }
            if ($ability['is_active'] !== $existingAbility) {
                $existingAbility = $character->abilities()
                    ->findOrFail($existingAbility->id);

                $existingAbility->pivot->is_active = $ability['is_active'];
                $existingAbility->pivot->save();
            }
        }
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(
                $exception->getCode(),
                $exception->getMessage()
            );
            return false;
        }

        DB::commit();
        return true;
    }


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