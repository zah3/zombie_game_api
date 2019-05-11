<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-05-11
 * Time: 09:03
 */

namespace Tests\Feature\APIEndpoints;


use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class APIPasswordTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreWithoutEmailField()
    {
        $response = $this->postJson('api/password');
        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function testStoreWithWrongEmail()
    {
        $response = $this->postJson('api/password',
            ['email' => 'eweq@o2.pl']
        );
        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function testStoreExistedMail()
    {
        $user = factory(User::class)->create();
        $response = $this->postJson('api/password',
            ['email' => 'eweq@o2.pl']
        );
        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }
}