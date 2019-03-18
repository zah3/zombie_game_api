<?php

namespace App\Http\Controllers\API;

use App\Character;
use App\Http\Requests\UserCharacterStoreRequest;
use App\Http\Requests\UserCharacterUpdateRequest;
use App\Http\Resources\CharacterResource;
use App\Repositories\CharacterRepository;
use App\Rules\CharacterLimit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
     * @param UserCharacterStoreRequest $userCharacterRequest
     *
     * @return CharacterResource
     */
    public function store(UserCharacterStoreRequest $userCharacterRequest) : CharacterResource
    {
        $character = CharacterRepository::create(
            $userCharacterRequest->user(),
            null,
            $userCharacterRequest->input(['name']),
            null
        );

        return CharacterResource::make($character);
    }

    /**
     * GET user/character/{character.id}
     * Displays provided character for current logged user
     *
     * @param $request
     * @param int $characterId
     *
     * @return $this | JsonResponse
     */
    public function show(Request $request, int $characterId)
    {
        $user = $request->user();
        $character = Character::query()
            ->with(['fraction'])
            ->withUser($user)
            ->findOrFail($characterId);

        return CharacterResource::make($character)->response()->setStatusCode(200);
    }

    /**
     * PUT /user/characters/{character.id}
     *
     * @param UserCharacterUpdateRequest $userCharacterUpdateRequest
     * @param int $characterId
     *
     * @return $this | JsonResponse
     */
    public function update(
        UserCharacterUpdateRequest $userCharacterUpdateRequest,
        int $characterId
    )
    {
        $user = $userCharacterUpdateRequest->user();
        $character = Character::query()->withUser($user)->findOrFail($characterId);
        $characterUpdated = CharacterRepository::update(
            $character,
            $userCharacterUpdateRequest->all()
        );
        return CharacterResource::make($characterUpdated)->response()->setStatusCode(200);
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
        $character = Character::query()->withUser($user)->findOrFail($characterId);
        $character->delete();

        return response(null, 204);
    }
}
