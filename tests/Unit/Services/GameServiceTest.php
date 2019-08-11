<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-30
 * Time: 07:40
 */

namespace Tests\Unit\Services;


use App\Entities\Character;
use App\Entities\Fraction;
use App\Facades\GameService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GameServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testSaveSaveGame()
    {
        $character = factory(Character::class)->create();
        $fraction = factory(Fraction::class)->create();
        $experience = 10;
        $agility = 20;
        $strength = 30;
        $coordinate = [
            'x' => 12,
            'y' => 3,
        ];
        $abilities =  [
                [
                    'id' => 1,
                    'is_active' => 1,
                ],
                [
                    'id' => 2,
                    'is_active' => 1,
                ],
                [
                    'id' => 3,
                    'is_active' => 1,
                ],
                [
                    'id' => 4,
                    'is_active' => 1,
                ],
        ];

        GameService::save(
            $character,
            $fraction->id,
            $experience,
            $agility,
            $strength,
            $coordinate,
            $abilities
        );
        $character->refresh();

        $this->assertDatabaseHas(
            'characters',
            [
                'id' => $character->id,
                'user_id' => $character->user_id,
                'fraction_id' => $fraction->id,
                "name"=> $character->name,
                'experience' => $experience,
                'strength' => $strength,
                'agility' => $agility,
                "created_at"=> $character->created_at->format('Y-m-d H:i:s'),
                "updated_at"=> $character->updated_at->format('Y-m-d H:i:s'),
                "deleted_at"=> null
            ]
        );

        $this->assertDatabaseHas(
            'coordinates',
            [
                'character_id' => $character->id,
                'x' => $coordinate['x'],
                "y"=> $coordinate['y'],
            ]
        );

        $this->assertDatabaseHas(
            'character_abilities',
            [
                'character_id' => $character->id,
                'ability_id' => $abilities[0]['id'],
                "is_active" => $abilities[0]['is_active'],
            ]
        );
        $this->assertDatabaseHas(
            'character_abilities',
            [
                'character_id' => $character->id,
                'ability_id' => $abilities[1]['id'],
                "is_active" => $abilities[1]['is_active'],
            ]
        );
        $this->assertDatabaseHas(
            'character_abilities',
            [
                'character_id' => $character->id,
                'ability_id' => $abilities[2]['id'],
                "is_active" => $abilities[2]['is_active'],
            ]
        );
        $this->assertDatabaseHas(
            'character_abilities',
            [
                'character_id' => $character->id,
                'ability_id' => $abilities[3]['id'],
                "is_active" => $abilities[3]['is_active'],
            ]
        );
    }
}