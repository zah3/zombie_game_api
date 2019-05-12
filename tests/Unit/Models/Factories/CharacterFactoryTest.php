<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-19
 * Time: 08:06
 */

namespace Tests\Unit\Models\Factories;


use App\Character;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CharacterFactoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $character = factory(Character::class)->create();
        $this->assertDatabaseHas('characters', $character->toArray());
    }
}