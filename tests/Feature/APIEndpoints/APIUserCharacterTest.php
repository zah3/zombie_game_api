<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-16
 * Time: 16:18
 */

namespace Tests\Feature\APIEndpoints;


use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class API_User_Character_Test extends TestCase
{
    use DatabaseTransactions;

    public function testIndexShowCorrectCharacterForUser()
    {
        $user = factory(User::class)->create();
    }
}