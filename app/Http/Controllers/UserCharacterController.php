<?php

namespace App\Http\Controllers;

use App\Character;
use App\Http\Requests\UserCharacterRequest;
use App\Http\Resources\CharacterResource;
use App\Repositories\CharacterRepository;
use App\Rules\CharacterLimit;
use App\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserCharacterController extends Controller
{

    /**
     * GET user/characters
     * Returns all characters of user
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index() : AnonymousResourceCollection
    {
        $user = Auth::user();
        $userCharacters = Character::query()
            ->with(['fraction'])
            ->withUser($user)
            ->get();

        return CharacterResource::collection($userCharacters);
    }

    /**
     * POST user/characters
     * Creates new character for user
     *
     * @param UserCharacterRequest $userCharacterRequest
     *
     * @return CharacterResource
     */
    public function store(UserCharacterRequest $userCharacterRequest) : CharacterResource
    {
        $character = CharacterRepository::create(
            $userCharacterRequest->user(),
            null,
            $userCharacterRequest->input('name'),
            null
        );

        return CharacterResource::make($character);
    }

    /**
     * DELETE user/characters/{character.id}
     * Deletes specified character
     *
     * @param Request $request
     * @param int $characterId
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request, int $characterId)
    {
        $user = $request->user();
        $character = Character::query()->withUser($user)->withId($characterId)->firstOrFail();
        $character->delete();

        return response(null, 204);
    }
}
