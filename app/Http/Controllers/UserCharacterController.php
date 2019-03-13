<?php

namespace App\Http\Controllers;

use App\Character;
use App\Http\Requests\UserCharacterRequest;
use App\Http\Resources\CharacterResource;
use Illuminate\Support\Facades\Auth;

class UserCharacterController extends Controller
{

    /**
     * GET user/characters
     * Returns all characters of user
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = Auth::user();
        $userCharacters = Character::query()
            ->with(['fraction'])
            ->withUser($user)
            ->get();

        return CharacterResource::collection($userCharacters);
    }

    /**
     * Create new character for user
     *
     * @param UserCharacterRequest $userCharacterRequest
     */
    public function store(UserCharacterRequest $userCharacterRequest)
    {

    }
}
