<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-21
 * Time: 18:55
 */

namespace Tests\Feature\Models\Scopes;


use App\User;
use Tests\TestCase;

class UserScopeTest extends TestCase
{
    public function testWithUsername()
    {
        $user = factory(User::class)->create();
        factory(User::class, 3)->create();
        $modelFormDatabase = User::query()->withUsername($user->username)->first();
        $this->assertNotNull($modelFormDatabase);
        $this->assertEquals($user->username, $modelFormDatabase->username);
    }

    public function withEmailVerifiedAt()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);
        factory(User::class, 3)->create([
            'email_verified_at' => null,
        ]);
        $modelFormDatabase = User::query()->withEmailVerifiedAt()->first();
        $this->assertNotNull($modelFormDatabase);
        $this->assertEquals($user->is_active, $modelFormDatabase->is_active);
    }

    public function withEmail()
    {
        $user = factory(User::class)->create([
            'email' => 'example@o2.pl',
        ]);
        factory(User::class, 3)->create();
        $modelFormDatabase = User::query()->withEmail()->first();
        $this->assertNotNull($modelFormDatabase);
        $this->assertEquals($user->email, $modelFormDatabase->email);
    }
}