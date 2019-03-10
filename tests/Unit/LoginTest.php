<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsCamelCaseSensitive()
    {
        $user = factory(User::class)->create(
            [
                'password' => 'PassWord'
            ]
        );

        $response = $this->actingAs($user)->post(
            'api/login',
            [
                'password' => strtolower($user->password),
                'username' => 'Zachariasz_user'
            ]
        );
        $response->assertJsonValidationErrors(200);
    }
}
