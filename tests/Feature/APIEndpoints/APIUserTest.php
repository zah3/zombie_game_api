<?php

namespace Tests\Feature\APIEndpoints;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class API_User_Test extends TestCase
{
    use DatabaseTransactions;

    public function testLoginCheckCaseSensitiveInFields()
    {
        $user = factory(User::class)->make([
            'password' => 'PassWord',
            'username' => 'username',
        ]);
        $goodDataWithCamelCase = [
            'username' => $user->username,
            'password' => 'PassWord',
            'confirm_password' => 'PassWord',
        ];

        $response = $this->json(
            'POST',
            'api/register',
            $goodDataWithCamelCase
        );
        // Create user in database from endpoint
        $response->assertStatus(200);
        $this->assertDatabaseHas(
            'users',
            [
                'username' => $user->username,
                'is_active' => 0,
            ]
        );

        // Send incorrect data for login
        $response = $this->json(
            'POST',
            'api/login',
            [
                'password' => strtolower($user->password),
                'username' => $user->username,
            ]
        );
        $response->assertStatus(422)
            ->assertJsonValidationErrors('username');

        // Send correct data for login
        $response = $this->json(
            'POST',
            'api/login',
            [
                'password' => $user->password,
                'username' => $user->username,
            ]
        );
        $response->assertStatus(200);
    }

    public function register_test()
    {
        $goodData = [
            'username' => 'username',
            'password' => 'password',
            'confirm_password' => 'password',
        ];
        $response = $this->json(
            'POST',
            'api/register',
            $goodData
        );
        $response->assertStatus(200);
    }
}
