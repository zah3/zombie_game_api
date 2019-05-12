<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-31
 * Time: 21:24
 */

namespace Tests\Feature\Services;


use App\Facades\UserService;
use App\Notifications\VerifyEmail;
use App\RoleUser;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserServiceTest extends TestCase
{

    use DatabaseTransactions;

    public function testHasUserVerifiedEmailReturnTrue()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => now(),
        ]);
        $user->refresh();
        $this->assertDatabaseHas('users', $user->toArray());

        $returnFromMethod = UserService::hasUserVerifiedEmail($user);
        $this->assertTrue($returnFromMethod);
    }

    public function testHasUserVerifiedEmailReturnFalse()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);
        $user->refresh();
        $this->assertDatabaseHas('users', $user->toArray());

        $returnFromMethod = UserService::hasUserVerifiedEmail($user);
        $this->assertFalse($returnFromMethod);
    }

    public function testSetEmailAsVerified()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);
        $this->assertDatabaseHas('users', $user->toArray());

        UserService::setEmailAsVerified($user);
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function testSendEmailVerificationNotification()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);
        Notification::fake();

        Notification::assertNothingSent();

        UserService::sendEmailVerificationNotification($user);

        // Assert a notification was sent to the given users...
        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );
    }

    public function testAuthorizeRolesReturnTrue()
    {
        $user = factory(User::class)->create();
        factory(RoleUser::class)->create([
            'user_id' => $user->id,
        ]);

        $returnFromMethod = UserService::authorizeRoles($user, $user->roles()->first()->name);
        $this->assertNotNull($returnFromMethod);
        $this->assertTrue($returnFromMethod);
    }

    public function testAuthorizeRolesReturnException()
    {
        $user = factory(User::class)->create();
        factory(RoleUser::class)->create([
            'user_id' => $user->id,
        ]);

        $this->expectExceptionMessage('This action is unauthorized.');
        $this->expectException(\Exception::class);
        UserService::authorizeRoles($user, 'test');
    }

    public function testHasAnyRoleReturnTrue()
    {
        $user = factory(User::class)->create();
        factory(RoleUser::class)->create([
            'user_id' => $user->id,
        ]);

        $returnFromMethod = UserService::hasAnyRole(
            $user,
            [
                $user->roles()->first()->name,
                'test',
            ]
        );
        $this->assertNotNull($returnFromMethod);
        $this->assertTrue($returnFromMethod);
    }

    public function testHasAnyRoleReturnFalse()
    {
        $user = factory(User::class)->create();
        factory(RoleUser::class)->create([
            'user_id' => $user->id,
        ]);

        $returnFromMethod = UserService::hasAnyRole(
            $user,
            [
                'test_2',
                'test',
            ]
        );
        $this->assertFalse($returnFromMethod);
    }

    public function testHasRoleReturnTrue()
    {
        $user = factory(User::class)->create();
        factory(RoleUser::class)->create([
            'user_id' => $user->id,
        ]);

        $returnFromMethod = UserService::hasRole(
            $user,
            $user->roles()->first()->name
        );
        $this->assertTrue($returnFromMethod);
    }

    public function testHasRoleReturnFalse()
    {
        $user = factory(User::class)->create();
        factory(RoleUser::class)->create([
            'user_id' => $user->id,
        ]);

        $returnFromMethod = UserService::hasRole(
            $user,
            'notExistedRole'
        );
        $this->assertFalse($returnFromMethod);
    }
}
