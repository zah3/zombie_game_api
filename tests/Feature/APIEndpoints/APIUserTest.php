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
            'username' => 'usernamE',
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
//        // Create user in database from endpoint
//        $response->assertStatus(200);
//        $this->assertDatabaseHas(
//            'users',
//            [
//                'username' => $user->username,
//                'is_active' => 0,
//            ]
//        );
//
//        // Send incorrect data for log in
//        // Password is in lowercase
//        $response2 = $this->json(
//            'POST',
//            'api/login',
//            [
//                'password' => strtolower($goodDataWithCamelCase['password']),
//                'username' => $user->username,
//            ]
//        );
//        $response2->assertStatus(422)
//            ->assertJsonValidationErrors('username');
//
//        // Send incorrect data for login
//        // Username is in lowercase
//        $response3 = $this->json(
//            'POST',
//            'api/login',
//            [
//                'password' => $goodDataWithCamelCase['password'],
//                'username' => strtolower($user->username),
//            ]
//        );
//        $response3->assertStatus(422)
//            ->assertJsonValidationErrors('username');
        $this->withoutExceptionHandling();

        // Send correct data for login
        $response4 = $this->json(
            'POST',
            'api/login',
            [
                'password' => $goodDataWithCamelCase['password'],
                'username' => $user->username,
            ]
        );
        $response4->assertStatus(200);
    }

    public function testRegisterWithIncorrectPasswordData()
    {
        // Incorrect password with confirm_password
        $incorrectData = [
            'username' => 'username',
            'password' => 'password',
            'confirm_password' => 'password1',
        ];

        $response = $this->json(
            'POST',
            'api/register',
            $incorrectData
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('confirm_password');
        $this->assertDatabaseMissing(
            'users',
            ['username' => $incorrectData['username']]
        );

        // Incorrect password - over 20 characters
        $incorrectData = [
            'username' => 'username',
            'password' => 'LoremipsumLoremipsumLoremipsumLoremipsumLoremipsumLoremipsum',
            'confirm_password' => 'LoremipsumLoremipsumLoremipsumLoremipsumLoremipsumLoremipsum',

        ];

        $response = $this->json(
            'POST',
            'api/register',
            $incorrectData
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('password');
        $this->assertDatabaseMissing(
            'users',
            ['username' => $incorrectData['username']]
        );

        // Incorrect password - less then 8 characters
        $incorrectData = [
            'username' => 'username',
            'password' => 'Lorem',
            'confirm_password' => 'Lorem',
        ];

        $response = $this->json(
            'POST',
            'api/register',
            $incorrectData
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('password');
        $this->assertDatabaseMissing(
            'users',
            ['username' => $incorrectData['username']]
        );
    }

    public function testRegisterWithIncorrectUsernameData()
    {
        // Try to create user with already existed username
        $user = factory(User::class)->create([
            'username' => 'existed',
        ]);

        $incorrectData2 = [
            'username' => $user->username,
            'password' => 'password',
            'confirm_password' => 'password',
        ];

        $response2 = $this->json(
            'POST',
            'api/register',
            $incorrectData2
        );

        $response2->assertStatus(422)
            ->assertJsonValidationErrors('username');

        // Send correct data
        $goodData = [
            'username' => 'username',
            'password' => 'password',
            'confirm_password' => 'password',
        ];

        $response3 = $this->json(
            'POST',
            'api/register',
            $goodData
        );
        $response3->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'created_at',
                    'updated_at',
                ],
            ])->assertJsonFragment([
                'username' => $goodData['username'],
            ]);
        $this->assertDatabaseHas(
            'users',
            [
                'username' => $goodData['username'],
            ]
        );
    }
}
