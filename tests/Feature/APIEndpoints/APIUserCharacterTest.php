<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-16
 * Time: 16:18
 */

namespace Tests\Feature\APIEndpoints;


use App\Character;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class API_User_Character_Test extends TestCase
{
    use DatabaseTransactions;

    public function testIndexShowCorrectCharactersForUser()
    {
        // Creates user and character
        $user = factory(User::class)->create();
        $userCharacters = factory(Character::class, 2)->create([
            'user_id' => $user->id,
        ]);
        $charactersNotBelongToUser = factory(Character::class, 10)->create();

        // Send request to endpoint
        $response = $this->actingAs($user)
            ->get('api/user/characters');

        // Check response
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'fraction',
                        'name',
                        'experience',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        $userCharacters->each(function (Character $character) use ($response) {
            $response->assertJsonFragment([
                'id' => $character->id,
                'name' => $character->name,
                'experience' => $character->experience,
            ]);
        });

        $charactersNotBelongToUser->each(function (Character $character) use ($response) {
            $response->assertJsonMissingExact([
                'id' => $character->id,
                'name' => $character->name,
                'experience' => $character->experience,
            ]);
        });
    }
}