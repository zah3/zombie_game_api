<?php

namespace Tests\Unit\Models\Scopes;

use App\Entities\Character;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CharacterScopeTest extends TestCase
{

    use DatabaseTransactions;

    public function testWithUser()
    {
        $character = factory(Character::class)->create();
        $foundCharacter = Character::query()->withUser($character->user)->first();
        $this->assertNotNull($foundCharacter);
        $this->assertEquals($character->id, $foundCharacter->id);
    }
    public function testWithDeleted()
    {
        $character = factory(Character::class)->create();
        $character->deleted_at = now();
        $character->save();
        $character->refresh();
        $foundCharacter = Character::query()->withDeleted()->first();
        $this->assertNotNull($foundCharacter);
        $this->assertEquals($character->id, $foundCharacter->id);
    }
}