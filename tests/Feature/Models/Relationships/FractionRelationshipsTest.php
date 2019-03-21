<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-21
 * Time: 18:14
 */

namespace Tests\Feature\Models\Relationships;


use App\Character;
use App\Fraction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FractionRelationshipsTest extends TestCase
{
    use DatabaseTransactions;

    public function testCharacters()
    {
        $fraction = factory(Fraction::class)->create();
        factory(Character::class)->create([
            'fraction_id' => $fraction->id,
        ]);

        $characterFraction = $fraction->characters;
        $this->assertNotEmpty($characterFraction);
    }
}