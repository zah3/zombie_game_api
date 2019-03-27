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

    public function testWithActive()
    {
        $user = factory(User::class)->create([
            'is_active' => true,
        ]);
        factory(User::class, 3)->create([
            'is_active' => false,
        ]);
        $modelFormDatabase = User::query()->withActive($user->username)->first();
        $this->assertNotNull($modelFormDatabase);
        $this->assertEquals($user->is_active, $modelFormDatabase->is_active);
    }
}