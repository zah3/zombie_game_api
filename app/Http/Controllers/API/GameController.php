<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * GET api/game/{character.id}
     * Returns game data from a system
     *
     * @param Request $request
     * @param int $characterId
     *
     * @return GameResource
     */
    public function show(Request $request, int $characterId) : GameResource
    {
        $user = $request->user();
        $character = $user->characters()->findOrFail($characterId);

        $character->load(['fraction', 'coordinate', 'abilities']);
        return GameResource::make($character);
    }

    /**
     * PUT api/game/{character.id}
     * Save game data
     *
     * @param Request $request
     * @param int $characterId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $characterId)
    {
        $user = $request->user();
        $character = $user->characters()->findOrFail($characterId);

        $request->validate([
            'fraction_id' => 'optional|exists:fractions,id',
            'experience' => 'int|optional|between:' . $character->experience . ',4294967295',
            'agility' => 'int|optional|between:' . $character->agility . ',4294967295',
            'strength' => 'int|optional|between' . $character->strength . ',4294967295',
            'coordinates.x' => 'numeric|optional',
            'coordinates.y' => 'numeric|optional',
            'abilities.id' => 'int|optional|exists:abilities,id',
            'abilities.is_active' => 'optional:in:0,1',
        ]);

        return response()->json(['message' => 'OK'], 200);
    }
}
