<?php

namespace Tests\Feature\APIEndpoints;

use App\Entities\Constants\Helpers\ExceptionMessage;
use App\Notifications\PasswordResetRequestNotification;
use App\Notifications\PasswordChangeNotification;
use App\Entities\PasswordReset;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class APIPasswordChangeTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreWithNotExistMailDB()
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
                $this->assertNotNull($secretCodeToResetPasswordFromMail);
                $requestData = [
                    'token' => $secretCodeToResetPasswordFromMail,
                    'email' => "notExisted@com.com",
                    'password' => 'xxxxxxxxx',
                    'confirm_password' => 'xxxxxxxxx',
                ];
                $changePasswordResponse = $this->postJson(
                    'api/password/change',
                    $requestData
                );

                $changePasswordResponse->assertStatus(422)
                    ->assertJsonValidationErrors('email');
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }

    public function testStoreWithNotExistedToken()
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
                $this->assertNotNull($secretCodeToResetPasswordFromMail);
                $requestData = [
                    'token' => "some_token",
                    'email' => $user->email,
                    'password' => 'xxxxxxxxx',
                    'confirm_password' => 'xxxxxxxxx',
                ];
                $changePasswordResponse = $this->postJson(
                    'api/password/change',
                    $requestData
                );
                $changePasswordResponse->assertStatus(422);
                $this->assertEquals(
                    $changePasswordResponse->json('message'),
                    PasswordChangeNotification::MESSAGE_ERROR_INVALID_TOKEN
                );
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }

    public function testStoreWithExpiredToken()
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
                $this->assertNotNull($secretCodeToResetPasswordFromMail);

                Carbon::setTestNow(now()->addMinutes(PasswordReset::LIMIT_IN_MINUTES));
                $requestData = [
                    'token' => $secretCodeToResetPasswordFromMail,
                    'email' => $user->email,
                    'password' => 'xxxxxxxxx',
                    'confirm_password' => 'xxxxxxxxx',
                ];
                $changePasswordResponse = $this->postJson(
                    'api/password/change',
                    $requestData
                );
                $changePasswordResponse->assertStatus(422);
                $this->assertEquals(
                    ExceptionMessage::PASSWORD_RESET_TOKEN_CREATED_MORE_THEN_LIMIT,
                    $changePasswordResponse->json('message')
                );
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }

    public function testStoreWithTokenFromOtherUser()
    {
        Notification::fake();
        $this->withoutMiddleware();

        $user = factory(User::class)->create();

        $response = $this->postJson(
            'api/password/reset',
            ['email' => $user->email]
        );
        $response->assertStatus(200);

        $user2 = factory(User::class)->create();

        $response2 = $this->postJson(
            'api/password/reset',
            ['email' => $user2->email]
        );
        $response2->assertStatus(200);

        Notification::assertSentTo(
            $user,
            PasswordResetRequestNotification::class,
            function ($notification, $channels) use ($user, $user2) {
                $mailData = $notification->toMail($user)->toArray();
                $secretCodeToResetPasswordFromMail = $mailData['introLines'][3];
                $this->assertNotNull($secretCodeToResetPasswordFromMail);
                $requestData = [
                    'token' => $user2->passwordReset->token,
                    'email' => $user->email,
                    'password' => 'xxxxxxxxx',
                    'confirm_password' => 'xxxxxxxxx',
                ];
                $changePasswordResponse = $this->postJson(
                    'api/password/change',
                    $requestData
                );

                $changePasswordResponse->assertStatus(422)
                    ->assertJsonFragment([
                        'message' => PasswordChangeNotification::MESSAGE_ERROR_INVALID_TOKEN,
                    ]);
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }

    public function testStoreWithNotGeneratedToken()
    {
        Notification::fake();
        $this->withoutMiddleware();

        $user = factory(User::class)->create();

        $requestData = [
            'token' => 'fake_token',
            'email' => $user->email,
            'password' => 'xxxxxxxxx',
            'confirm_password' => 'xxxxxxxxx',
        ];
        $changePasswordResponse = $this->postJson(
            'api/password/change',
            $requestData
        );

        $changePasswordResponse->assertStatus(422)
            ->assertJsonFragment([
                'message' => PasswordChangeNotification::MESSAGE_ERROR_USER_NOT_HAVE_GENERATED_TOKEN,
            ]);
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
                $this->assertNotNull($secretCodeToResetPasswordFromMail);
                $requestData = [
                    'token' => $secretCodeToResetPasswordFromMail,
                    'email' => $user->email,
                    'password' => 'xxxxxxxxx',
                    'confirm_password' => 'xxxxxxxxx',
                ];
                $user->refresh();
                $changePasswordResponse = $this->postJson(
                    'api/password/change',
                    $requestData
                );

                $changePasswordResponse->assertJsonStructure([
                    'id',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'deleted_at',
                    'updated_at'
                ])->assertJsonFragment([
                    'id' => $user->id,
                    'email' => $user->email,
                    'email_verified_at' => optional($user->email_verified_at)->toIso8601String(),
                    'created_at' => optional($user->created_at)->toIso8601String(),
                    'deleted_at' => optional($user->deleted_at)->toIso8601String(),
                ]);
                $changePasswordResponse->assertStatus(201);
                Notification::assertSentTo($user, PasswordChangeNotification::class);
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }
}