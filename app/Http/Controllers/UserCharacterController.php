<?php

namespace App\Http\Controllers;

use App\Character;
use App\Http\Requests\UserCharacterRequest;
use App\Http\Resources\CharacterResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserCharacterController extends Controller
{

    /**
     * GET user/characters
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

    public function store(UserCharacterRequest $userCharacterRequest)
    {

    }
}
