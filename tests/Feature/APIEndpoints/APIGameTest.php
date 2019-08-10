<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-08-04
 * Time: 21:41
 */

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
                    "experience",
                    "agility",
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
                "experience" => $character->experience,
                "agility" => $character->agility,
                "strength" => $character->strength,
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

        $response->assertNotFound();
    }

    public function testUpdate()
    {
        $character = factory(Character::class)->create();

        $dataToUpdate = [
            'fraction_id' => 2,
            'experience' => 2,
            'agility' => 10,
            'strength' => 10,
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
                    "agility",
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
                "experience" => $character->experience,
                "agility" => $character->agility,
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
            'agility' => 103,
            'strength' => 120,
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
        $response->assertNotFound();
    }
}