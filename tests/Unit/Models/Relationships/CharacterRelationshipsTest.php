<?php

namespace Tests\Unit\Models\Relationships;

use App\Entities\Character;
use App\Entities\Fraction;
use App\Entities\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CharacterRelationshipsTest extends TestCase
{
    use DatabaseTransactions;

    public function testUser()
    {
        $user = factory(User::class)->create();
        $character = factory(Character::class)->create([
            'user_id' => $user->id,
        ]);

        $userCharacter = $character->user;
        $this->assertNotNull($userCharacter);
    }

    public function testFraction()
    {
        $fraction = factory(Fraction::class)->create();
        $character = factory(Character::class)->create([
            'fraction_id' => $fraction->id,
        ]);

        $characterFraction =$character->fraction;
        $this->assertNotNull($characterFraction);
    }

    public function testCoordinate()
    {
        $character = factory(Character::class)->create();
        // It's also checks mysql create_default_coordinate_for_new_character trigger
        $this->assertNotNull($character->coordinate);
        $this->assertEquals($character->id,$character->coordinate->character_id);
    }
    
    public function testAbilities()
    {
        $character = factory(Character::class)->create();
        // It's also checks mysql after_character_insert trigger
        $this->assertNotEmpty($character->abilities->toArray());
    }
}