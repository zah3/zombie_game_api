<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-30
 * Time: 07:40
 */

namespace Tests\Unit\Services;


use App\Character;
use App\Facades\ExperienceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExperienceServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testAddExperienceToCharacter()
    {
        $character = factory(Character::class)->create();
        $experiencePoints = 200;
        $actualExperiencePoints = ExperienceService::addExperienceToCharacter(
            $experiencePoints,
            $character
        );
        // Takes actual information about model from DB
        $character->refresh();

        $this->assertEquals(200, $actualExperiencePoints);
        $this->assertEquals(200, $character->experience);
    }
}