<?php

namespace App\Http\Controllers\API;

use App\Facades\GameService;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;
use App\Entities\Character;

class GameController extends Controller
{
    /**
     * GET api/game/{character.id}
     * Returns game data from a system
     *
     * @param Character $character
     *
     * @return GameResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Character $character) : GameResource
    {
        $this->authorize('view', [GameResource::class, $character]);

        $character->load(['fraction', 'coordinate', 'abilities']);
        return GameResource::make($character);
    }

    /**
     * PUT api/game/{character.id}
     * Save game data
     *
     * @param Request $request
     * @param Character $character
     *
     * @return GameResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Character $character) : GameResource
    {
        $this->authorize('update', [GameResource::class, $character]);

        $request->validate([
            'fraction_id' => 'sometimes|exists:fractions,id',
            'experience' => 'int|sometimes|between:' . $character->experience . ',4294967295',
            'agility' => 'int|sometimes|between:' . $character->agility . ',4294967295',
            'strength' => 'int|sometimes|between:' . $character->strength . ',4294967295',
            'coordinates.x' => 'numeric|sometimes',
            'coordinates.y' => 'numeric|sometimes',
            'abilities.*.id' => 'int|sometimes|exists:abilities,id',
            'abilities.*.is_active' => 'sometimes|in:0,1',
        ]);

        GameService::save(
            $character,
            $request->input('fraction_id'),
            $request->input('experience'),
            $request->input('agility'),
            $request->input('strength'),
            $request->input('coordinates'),
            $request->input('abilities')
        );

        $character->load(['fraction', 'coordinate', 'abilities']);
        return GameResource::make($character);
    }
}
