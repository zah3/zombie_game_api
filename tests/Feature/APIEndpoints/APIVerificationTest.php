<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-05-11
 * Time: 01:46
 */

namespace Tests\Feature\APIEndpoints;


use App\Entities\Constants\Helpers\ExceptionMessage;
use App\Notifications\VerifyEmail;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class APIVerificationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resend_validation_not_exist_email()
    {
        $this->withoutMiddleware();
        $response = $this->postJson(
            'api/verification/resend',
            ['email' => 'exapmle@com.eu']
        );
        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_resend_validation_user_have_already_verified()
    {
        $this->withoutMiddleware();

        $user = factory(User::class)->create();
        $response = $this->postJson(
            'api/verification/resend',
            ['email' => $user->email]
        );
        $response->assertStatus(422)->assertJson([
            'message' => ExceptionMessage::VERIFICATION_USER_IS_ALREADY_VERIFIED,
        ]);
    }

    public function test_resend_send_email_and_verify()
    {
        $this->withoutMiddleware();

        Notification::fake();

        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);
        $this->postJson(
            'api/verification/resend',
            ['email' => $user->email]
        );

        Notification::assertSentTo(
            $user,
            VerifyEmail::class,
            function ($notification, $channels) use ($user) {
                $mailData = $notification->toMail($user)->toArray();
                Carbon::setTestNow(now()->addMinutes(VerifyEmail::CODE_VALID_IN_MINUTES * 2));
                $verifyResponse = $this->get($mailData['actionUrl']);
                $verifyResponse->assertSuccessful();
                $verifyResponse->assertViewIs('auth.verify');
                $verifyResponse->assertViewHas('type', 'danger');
                $verifyResponse->assertViewHas('message', VerifyEmail::MESSAGE_DANGER_CODE_EXPIRED);
                $user->refresh();
                return $user->email_verified_at === null;
            }
        );

        Notification::assertSentTo(
            $user,
            VerifyEmail::class,
            function ($notification, $channels) use ($user) {
                $mailData = $notification->toMail($user)->toArray();
                $verifyResponse = $this->get($mailData['actionUrl']);
                $verifyResponse->assertSuccessful();
                $verifyResponse->assertViewIs('auth.verify');
                $verifyResponse->assertViewHas('type', 'success');
                $verifyResponse->assertViewHas('message', VerifyEmail::MESSAGE_SUCCESS);
                $user->refresh();
                // Again
                $mailData = $notification->toMail($user)->toArray();
                $verifyResponse = $this->get($mailData['actionUrl']);
                $verifyResponse->assertSuccessful();
                $verifyResponse->assertViewIs('auth.verify');
                $verifyResponse->assertViewHas('type', 'danger');
                $verifyResponse->assertViewHas('message', VerifyEmail::MESSAGE_DANGER_ALREADY_VERIFIED);
                return $user->email_verified_at !== null;
            }
        );
    }
}