<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * GET api/game
     * Returns game data from a system
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $class = new \stdClass();
        return GameResource::make($class);
    }

    /**
     * POST api/game
     * Save game data
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'character' => 'required|exists,id',
            'experience' => 'int|optional',
     //       ''
        ]);

        return response()->json(['message' => 'OK'], 200);
    }
}
