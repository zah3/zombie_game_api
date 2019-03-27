<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-21
 * Time: 08:30
 */

namespace Tests\Feature\Models\Relationships;


use App\Character;
use App\Fraction;
use App\User;
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
}