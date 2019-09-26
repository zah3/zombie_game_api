<?php

namespace Tests\Feature\APIEndpoints;

use App\Entities\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class API_User_Test extends TestCase
{
    use DatabaseTransactions;

    public function testLoginCheckCaseSensitiveInFields()
    {
        $this->withoutMiddleware();

        $user = factory(User::class)->make([
            'username' => 'usernamE',
        ]);
        $goodDataWithCamelCase = [
            'email' => $user->email,
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
        $response->assertStatus(201);
        $this->assertDatabaseHas(
            'users',
            [
                'username' => $user->username,
                'email_verified_at' => null,
            ]
        );

        // Send incorrect data for log in
        // Password is in lowercase
        $response2 = $this->json(
            'POST',
            'api/login',
            [
                'password' => strtolower($goodDataWithCamelCase['password']),
                'username' => $user->username,
            ]
        );
        $response2->assertStatus(422)
            ->assertJsonValidationErrors('username');

        // Send incorrect data for login
        // Username is in lowercase
        $response3 = $this->json(
            'POST',
            'api/login',
            [
                'password' => $goodDataWithCamelCase['password'],
                'username' => strtolower($user->username),
            ]
        );
        $response3->assertStatus(422)
            ->assertJsonValidationErrors('username');

        // Not Verified mail
        $response4 = $this->json(
            'POST',
            'api/login',
            [
                'password' => $goodDataWithCamelCase['password'],
                'username' => $user->username,
            ]
        );
        $response4->assertStatus(422);

        $userFromDB = User::whereUsername($user->username)->firstOrFail();
        $userFromDB->email_verified_at = now();
        $userFromDB->save();
        // Send correct Response
        $response5 = $this->json(
            'POST',
            'api/login',
            [
                'password' => $goodDataWithCamelCase['password'],
                'username' => $user->username,
            ]
        );
        $response5->assertStatus(200);
    }

    public function testRegisterWithIncorrectPasswordData()
    {
        Notification::fake();
        $this->withoutMiddleware();

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
        Notification::assertNothingSent();

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
        Notification::assertNothingSent();

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
        Notification::assertNothingSent();
    }

    public function testRegisterWithIncorrectUsernameData()
    {
        Notification::fake();
        $this->withoutMiddleware();

        // Try to create user with already existed username
        $user = factory(User::class)->create([
            'username' => 'existed',
        ]);

        $incorrectData2 = [
            'email' => $user->email,
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

        Notification::assertNothingSent();
        // Send correct data
        $goodData = [
            'email' => 'email@o2.pl',
            'username' => 'username',
            'password' => 'password',
            'confirm_password' => 'password',
        ];

        $response3 = $this->json(
            'POST',
            'api/register',
            $goodData
        );
        $response3->assertStatus(201)
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
    public function testRegisterWithIncorrectTooLongUsernameData()
    {
        Notification::fake();
        $this->withoutMiddleware();

        $user = factory(User::class)->make([
            'username' => 'LoremIpsumLoremIpsumLoremIpsumLoremIpsumLoremIpsum' .
                'LoremIpsumLoremIpsumLoremIpsumLoremIpsumLoremIpsum' .
                'LoremIpsumLoremIpsumLoremIpsumLoremIpsumLoremIpsum' .
                'LoremIpsumLoremIpsumLoremIpsumLoremIpsumLoremIpsum' .
                'LoremIpsumLoremIpsumLoremIpsumLoremIpsumLoremIpsum' .
                'LoremIpsumLoremIpsumLoremIpsumLoremIpsumLoremIpsum' .
                'LoremIpsumLoremIpsumLoremIpsumLoremIpsumLoremIpsum',
        ]);

        $incorrectData = [
            'email' => 'email@o2.pl',
            'username' => $user->username,
            'password' => 'password',
            'confirm_password' => 'password',
        ];

        $response = $this->json(
            'POST',
            'api/register',
            $incorrectData
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('username');
        Notification::assertNothingSent();
    }
    public function testRegisterWithNonAlphaDashUsernameData()
    {
        $this->withoutMiddleware();

        Notification::fake();

        $user = factory(User::class)->make([
            'username' => 'Lorem Ipsum Lorem',
        ]);

        $incorrectData = [
            'email' => 'email@o2.pl',
            'username' => $user->username,
            'password' => 'password',
            'confirm_password' => 'password',
        ];

        $response = $this->json(
            'POST',
            'api/register',
            $incorrectData
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('username');
        Notification::assertNothingSent();
    }
}
