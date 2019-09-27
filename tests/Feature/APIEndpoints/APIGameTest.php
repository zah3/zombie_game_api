<?php

namespace Tests\Feature\APIEndpoints;

use App\Entities\Character;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class APIGameTest extends TestCase
{
    use DatabaseTransactions;

    public function testShowStructure()
    {
        $character = factory(Character::class)->create();

        $response = $this->actingAs($character->user, 'api')
            ->json(
                'GET',
                'api/game/' . $character->id
            );
        $character->refresh();
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    "character_id",
                    "name",
                    "username",
                    "experience",
                    "stamina",
                    "speed",
                    "ability_points",
                    "strength",
                    "fraction" => [
                        "id",
                        "name",
                        "created_at",
                        "updated_at",
                    ],
                    "coordinates" => [
                        "x",
                        "y",
                    ],
                    "abilities",
                ]
            ])->assertJsonFragment([
                "character_id" => $character->id,
                "username" => $character->user->username,
                "name" => $character->name,
                "experience" => $character->experience,
                "strength" => $character->strength,
                "stamina" => $character->stamina,
                "speed" => $character->speed,
                "ability_points" => $character->ability_points,
                "fraction" => [
                    "id" => $character->fraction_id,
                    "name" => $character->fraction->name,
                    "created_at" => $character->fraction->created_at->toIso8601String(),
                    "updated_at" => $character->fraction->updated_at->toIso8601String()
                ],
                "coordinates" => [
                    "x" => $character->coordinate->x,
                    "y" => $character->coordinate->y,
                ],
            ]);
    }

    public function testShowChecksPolicy()
    {
        $character1 = factory(Character::class)->create();
        $character2 = factory(Character::class)->create();

        $response = $this->actingAs($character1->user, 'api')
            ->json(
                'GET',
                'api/game/' . $character2->id
            );

        $response->assertForbidden();
    }

    public function testUpdate()
    {
        $character = factory(Character::class)->create();

        $dataToUpdate = [
            'fraction_id' => 2,
            'experience' => 2,
            'strength' => 10,
            'stamina' => 10,
            'speed' => 10,
            'ability_points' => 21,
            'coordinates' => [
                'x' => 2,
                'y' => 2,
            ],
            'abilities' => [
                [
                    'id' => 1,
                    'is_active' => 1
                ]
            ],
        ];

        $response = $this->actingAs($character->user, 'api')
            ->json(
                'PUT',
                'api/game/' . $character->id,
                $dataToUpdate

            );
        $character->refresh();

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    "character_id",
                    "experience",
                    "strength",
                    'strength',
                    'stamina',
                    'speed',
                    'ability_points',
                    "fraction" => [
                        "id",
                        "name",
                        "created_at",
                        "updated_at",
                    ],
                    "coordinates" => [
                        "x",
                        "y",
                    ],
                    "abilities",
                ]
            ])->assertJsonFragment([
                "character_id" => $character->id,
                "experience" => $character->experience,
                "strength" => $character->strength,
                "fraction" => [
                    "id" => $character->fraction_id,
                    "name" => $character->fraction->name,
                    "created_at" => optional($character->fraction->created_at)->toIso8601String(),
                    "updated_at" => optional($character->fraction->updated_at)->toIso8601String()
                ],
                "coordinates" => [
                    "x" => $character->coordinate->x,
                    "y" => $character->coordinate->y,
                ],
            ]);
    }

    public function testUpdatesChecksPolicy()
    {
        $character1 = factory(Character::class)->create();
        $character2 = factory(Character::class)->create();

        $dataToUpdate = [
            'fraction_id' => 3,
            'experience' => 100,
            'strength' => 120,
            'stamina' => 1230,
            'speed' => 12034,
            'ability_points' => 12023,
            'coordinates' => [
                'x' => 22,
                'y' => 32,
            ],
            'abilities' => [
                [
                    'id' => 1,
                    'is_active' => 0
                ]
            ],
        ];

        $response = $this->actingAs($character1->user, 'api')
            ->json(
                'PUT',
                'api/game/' . $character2->id,
                $dataToUpdate

            );
        $response->assertForbidden();
    }

    public function testUpdateForErrors()
    {
        $character = factory(Character::class)->create([
            'fraction_id' => 3,
            'experience' => 100,
            'strength' => 123,
            'stamina' => 142,
            'speed' => 152,
            'ability_points' => 3123,
        ]);

        // Experience, agility, strength are less than it was at before
        $dataToUpdate = [
            'fraction_id' => 2,
            'experience' => 2,
            'strength' => 10,
            'speed' => 10,
            'stamina' => 10,
            'ability_points' => 10,
            'coordinates' => [
                'x' => -2.34,
                'y' => 2,
            ],
        ];

        $response = $this->actingAs($character->user, 'api')
            ->json(
                'PUT',
                'api/game/' . $character->id,
                $dataToUpdate

            );
        $character->refresh();
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['experience', 'experience', 'strength', 'speed', 'stamina']);

        // Coordinates should be float
        $dataToUpdate2 = [
            'coordinates' => [
                'x' => 'weweqe',
                'y' => 'wqq',
            ],
        ];
        $response2 = $this->actingAs($character->user, 'api')
            ->json(
                'PUT',
                'api/game/' . $character->id,
                $dataToUpdate2

            );
        $character->refresh();
        $response2->assertStatus(422)
            ->assertJsonValidationErrors(['coordinates.x', 'coordinates.y']);

        $dataToUpdate3 = [
            'abilities' => [
                [
                    'id' => 1,
                    'is_active' => 1,
                ],
                [
                    'id' => 2,
                    'is_active' => 3,// is_active has wrong value
                ],
                [
                    'id' => 3,
                    'is_active' => 1,
                ],
                [
                    'id' => 4,
                    'is_active' => 1,
                ],
                [
                    'id' => 12,// id has wrong value
                    'is_active' => 1,
                ],
            ],
        ];
        $response3 = $this->actingAs($character->user, 'api')
            ->json(
                'PUT',
                'api/game/' . $character->id,
                $dataToUpdate3

            );
        $character->refresh();
        $response3->assertStatus(422)
            ->assertJsonValidationErrors(['abilities.4.id', 'abilities.1.is_active']);

        $dataToUpdate4 = [
            'fraction_id' => 5,//invalid fraction_id
        ];
        $response4 = $this->actingAs($character->user, 'api')
            ->json(
                'PUT',
                'api/game/' . $character->id,
                $dataToUpdate4

            );
        $character->refresh();
        $response4->assertStatus(422)
            ->assertJsonValidationErrors(['fraction_id']);
    }

}