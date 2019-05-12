<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-05-11
 * Time: 09:03
 */

namespace Tests\Feature\APIEndpoints;


use App\Notifications\PasswordResetRequestNotification;
use App\PasswordReset;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
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

    public function testStoreWithSuccess()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $response = $this->postJson('api/password',
            ['email' => $user->email]
        );
        $response->assertStatus(200);

        Notification::assertSentTo(
            $user,
            PasswordResetRequestNotification::class,
            function ($notification, $channels) use ($user) {
                $mailData = $notification->toMail($user)->toArray();
                $secretCodeToResetPasswordFromMail = $mailData['introLines'][3];
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }
    public function testStoreWithTwoAttempts()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $response = $this->postJson('api/password',
            ['email' => $user->email]
        );
        $response->assertStatus(200);
        $response2 = $this->postJson('api/password',
            ['email' => $user->email]
        );
        $response2->assertStatus(200);
        // Check if is not put same email in password_records table
        $passwordResets = PasswordReset::whereUserId($user->id)->get();
        $this->assertEquals(1,count($passwordResets));
        Notification::assertSentTo(
            $user,
            PasswordResetRequestNotification::class,
            function ($notification, $channels) use ($user) {
                $mailData = $notification->toMail($user)->toArray();
                $secretCodeToResetPasswordFromMail = $mailData['introLines'][3];
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }

    public function testCreateCheckThrottle()
    {
        $user = factory(User::class)->create();
        $i = 0;
        do {
            $response = $this->postJson('api/password',
                ['email' => $user->email]
            );
            $i++;
        } while ($i < 5);
        $this->assertEquals("Too Many Attempts.",$response->json()['message']);
        $response->assertStatus(429);
    }
}