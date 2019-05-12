<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-05-11
 * Time: 09:03
 */

namespace Tests\Feature\APIEndpoints;


use App\Notifications\PasswordResetRequestNotification;
use App\Notifications\PasswordResetSuccessNotification;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class APIPasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreWithNotExistMailDB()
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
                $this->assertNotNull($secretCodeToResetPasswordFromMail);
                $requestData = [
                    'token' => $secretCodeToResetPasswordFromMail,
                    'email' => "notExisted@com.com",
                    'password' => 'xxxxxxxxx',
                    'confirm_password' => 'xxxxxxxxx',
                ];
                $changePasswordResponse = $this->postJson(
                    'api/password/reset',
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
                $this->assertNotNull($secretCodeToResetPasswordFromMail);
                $requestData = [
                    'token' => "some_token",
                    'email' => $user->email,
                    'password' => 'xxxxxxxxx',
                    'confirm_password' => 'xxxxxxxxx',
                ];
                $changePasswordResponse = $this->postJson(
                    'api/password/reset',
                    $requestData
                );

                $changePasswordResponse->assertStatus(422)
                    ->assertJsonValidationErrors('token');
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }

    public function testStoreWithTokenFromOtherUser()
    {
        Notification::fake();
        $user = factory(User::class)->create();

        $response = $this->postJson('api/password',
            ['email' => $user->email]
        );
        $response->assertStatus(200);

        $user2 = factory(User::class)->create();

        $response2 = $this->postJson('api/password',
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
                    'api/password/reset',
                    $requestData
                );

                $changePasswordResponse->assertStatus(422)
                    ->assertJsonFragment([
                        'message' => PasswordResetSuccessNotification::MESSAGE_ERROR_INVALID_TOKEN,
                    ]);
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
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
                $this->assertNotNull($secretCodeToResetPasswordFromMail);
                $requestData = [
                    'token' => $user->passwordReset->token,
                    'email' => $user->email,
                    'password' => 'xxxxxxxxx',
                    'confirm_password' => 'xxxxxxxxx',
                ];
                $user->refresh();
                $changePasswordResponse = $this->postJson(
                    'api/password/reset',
                    $requestData
                )->assertJsonStructure([
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
                Notification::assertSentTo($user, PasswordResetSuccessNotification::class);
                return is_null($secretCodeToResetPasswordFromMail) === false;
            }
        );
    }
}