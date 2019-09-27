<?php

namespace App\Services;

use App\Entities\Character;
use App\Facades\CharacterRepository;
use App\Facades\CoordinateRepository;
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
     * @param int $speed
     * @param int $stamina
     * @param int $abilityPoints
     * @param array $coordinate
     * @param array $abilities
     *
     * @return void
     */
    public function save(
        Character $character,
        ?int $fractionId,
        ?int $experience,
        ?int $strength,
        ?int $speed,
        ?int $stamina,
        ?int $abilityPoints,
        array $coordinate,
        array $abilities
    ) : void
    {
        DB::beginTransaction();
        try {
            $newFields = [
                'fraction_id' => $fractionId,
                'experience' => $experience,
                'strength' => $strength,
                'speed' => $speed,
                'stamina' => $stamina,
                'ability_points' => $abilityPoints,
            ];
            CharacterRepository::update($character, $newFields);

            CoordinateRepository::update($character->coordinate, $coordinate);

            $existingAbilities = $character->abilities;
            foreach ($abilities as $key => $ability) {
                $existingAbility = $existingAbilities->where('id', '=', $ability['id'])->first();
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
        }

        DB::commit();
    }
}