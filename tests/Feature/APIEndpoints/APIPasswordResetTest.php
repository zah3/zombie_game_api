<?php

namespace Tests\Feature\APIEndpoints;

use App\Notifications\PasswordResetRequestNotification;
use App\Entities\PasswordReset;
use App\Entities\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class APIPasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreWithoutEmailField()
    {
        $this->withoutMiddleware();
        $response = $this->postJson('api/password/reset');
        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function testStoreWithWrongEmail()
    {
        $this->withoutMiddleware();

        $response = $this->postJson(
            'api/password/reset',
            ['email' => 'eweq@o2.pl']
        );
        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function testStoreWithSuccess()
    {
        Notification::fake();
        $this->withoutMiddleware();

        $user = factory(User::class)->create();
        $response = $this->postJson(
            'api/password/reset',
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
        $this->withoutMiddleware();

        $user = factory(User::class)->create();
        $response = $this->postJson(
            'api/password/reset',
            ['email' => $user->email]
        );

        $response->assertStatus(200);
        $response2 = $this->postJson(
            'api/password/reset',
            ['email' => $user->email]
        );
        $response2->assertStatus(200);
        // Check if is not put same email in password_records table
        $passwordResets = PasswordReset::whereUserId($user->id)->get();
        $this->assertEquals(1, count($passwordResets));
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
}