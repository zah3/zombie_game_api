<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-30
 * Time: 07:41
 */

namespace Tests\Feature\Repositories;


use App\Character;
use App\User;
use App\Repositories\CharacterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CharacterRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testSave()
    {
        $user = factory(User::class)->create();
        $character = factory(Character::class)->make();
        $createdModel = CharacterRepository::create(
            $user,
            null,
            $character->name,
            null,
            null,
            null
        );
        $this->assertNotNull($createdModel);
        $this->assertEquals($character->name, $createdModel->name);
        $this->assertDatabaseHas($character->getTable(), $createdModel->toArray());
    }

    public function testUpdate()
    {
        $character = factory(Character::class)->create();
        $newFields = factory(Character::class)->make();
        $updatedModel = CharacterRepository::update($character, $newFields->toArray());
        $this->assertNotNull($updatedModel);
        $this->assertEquals($updatedModel->name, $newFields->name);
        $this->assertDatabaseHas($character->getTable(), $updatedModel->toArray());
    }

    public function testAddExperience()
    {
        $character = factory(Character::class)->create();
        $updatedModel = CharacterRepository::addExperience(100, $character);
        $character->refresh();
        $this->assertNotNull($updatedModel);
        $this->assertEquals(100, $character->experience);
    }

    public function testAddAgility()
    {
        $character = factory(Character::class)->create();
        $updatedModel = CharacterRepository::addAgility(50, $character);
        $character->refresh();
        $this->assertNotNull($updatedModel);
        $this->assertEquals(50, $character->agility);
    }

    public function testAddStrength()
    {
        $character = factory(Character::class)->create();
        $updatedModel = CharacterRepository::addStrength(30, $character);
        $character->refresh();
        $this->assertNotNull($updatedModel);
        $this->assertEquals(30, $character->strength);
    }
}