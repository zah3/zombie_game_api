<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-16
 * Time: 16:18
 */

namespace Tests\Feature\APIEndpoints;


use App\Character;
use App\Fraction;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $response = $this->actingAs($user, 'api')
            ->json('GET', 'api/user/characters');

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

    public function testShowCorrectCharacterForUser()
    {
        // Creates user and character
        $user = factory(User::class)->create();
        $userCharacter = factory(Character::class)->create([
            'user_id' => $user->id,
        ]);
        factory(Character::class)->create();

        // Send request to endpoint
        $response = $this->actingAs($user, 'api')
            ->json('GET', 'api/user/characters/' . $userCharacter->id);
        // Check response
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'fraction',
                    'name',
                    'experience',
                    'created_at',
                    'updated_at',
                ]
            ])->assertJsonFragment([
                'id' => $userCharacter->id,
                'name' => $userCharacter->name,
                'experience' => $userCharacter->experience,
            ]);
    }

    public function testShowNotShowingOtherUserCharacter()
    {
        // Creates user and character
        $user = factory(User::class)->create();
        $userCharacter = factory(Character::class)->create([
            'user_id' => $user->id,
        ]);

        $otherUser = factory(User::class)->create();

        // Send request to endpoint
        $response = $this->actingAs($otherUser, 'api')
            ->json('GET', 'api/user/characters/' . $userCharacter->id);

        // Check response
        $response->assertStatus(404);
    }

    public function testStoreWithCorrectData()
    {
        // Creates user and character
        $user = factory(User::class)->create();
        $userCharacter = factory(Character::class)->make([
            'user_id' => $user->id,
            'fraction_id' => Fraction::ID_NORMAL,
        ]);

        // Send request to endpoint
        $response = $this->actingAs($user, 'api')
            ->json('POST', 'api/user/characters/', $userCharacter->toArray());
        // Check response
        $response->assertStatus(201);
        $this->assertDatabaseHas('characters', $userCharacter->toArray());
    }

    public function testStoreCharacterForUserOverCharacterLimit()
    {
        // Creates user and character
        $user = factory(User::class)->create();
        factory(Character::class, Character::LIMIT_PER_USER)->create([
            'user_id' => $user->id,
        ]);
        $userCharacter = factory(Character::class)->make([
            'user_id' => $user->id,
        ]);

        // Send request to endpoint
        $response = $this->actingAs($user, 'api')
            ->json('POST', 'api/user/characters', $userCharacter->toArray());

        // Check response
        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
        $this->assertDatabaseMissing('characters', $userCharacter->toArray());
    }
}