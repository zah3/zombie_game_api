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
            'fraction_id' => 'required|exists:fractions,id',
            'experience' => 'int|required|between:' . $character->experience . ',4294967295',
            'strength' => 'int|required|between:' . $character->strength . ',4294967295',
            'stamina' => 'int|required|between:' . $character->stamina . ',4294967295',
            'speed' => 'int|required|between:' . $character->speed . ',4294967295',
            'ability_points' => 'int|required|between:0,4294967295',
            'coordinates.x' => 'numeric|required',
            'coordinates.y' => 'numeric|required',
            'abilities.*.id' => 'int|required|exists:abilities,id',
            'abilities.*.is_active' => 'required|in:0,1',
        ]);
        GameService::save(
            $character,
            $request->input('fraction_id'),
            $request->input('experience'),
            $request->input('strength'),
            $request->input('speed'),
            $request->input('stamina'),
            $request->input('ability_points'),
            $request->input('coordinates'),
            $request->input('abilities')
        );

        $character->load(['fraction', 'coordinate', 'abilities']);
        return GameResource::make($character);
    }
}
