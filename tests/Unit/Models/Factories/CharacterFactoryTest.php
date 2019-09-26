<?php

namespace Tests\Unit\Models\Factories;

use App\Entities\Character;
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