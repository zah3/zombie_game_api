<?php

namespace Tests\Unit\Models\Scopes;

use App\Entities\User;
use Tests\TestCase;

class UserScopeTest extends TestCase
{
    public function testWithEmailVerifiedAt()
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
}