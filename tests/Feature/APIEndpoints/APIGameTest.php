<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-08-04
 * Time: 21:41
 */

namespace Tests\Feature\APIEndpoints;


use App\Character;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class APIGameTest extends TestCase
{
    use DatabaseTransactions;

    public function testShow()
    {
        $character = factory(Character::class)->create();

        $response = $this->actingAs($character->user,'api')
            ->json(
                'GET',
                'api/game/' .$character->id
            );

        dd($response->json());
    }
}