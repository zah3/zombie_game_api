<?php

namespace Tests\Unit\Models\Relationships;

use App\Entities\{
    Character, Coordinate, PasswordReset, Role, RoleUser, User
};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRelationshipsTest extends TestCase
{
    use DatabaseTransactions;

    public function testRoles()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $userRole = factory(RoleUser::class)->create([
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
        $rolesForUser = $user->roles;
        $this->assertNotNull($rolesForUser);
        $this->assertEquals($user->id, $userRole->user_id);
        $this->assertEquals($role->id, $userRole->role_id);
    }

    public function testCharacters()
    {
        $user = factory(User::class)->create();
        factory(Character::class)->create([
            'user_id' => $user->id,
        ]);
        $userCharacters = $user->characters;
        $this->assertNotEmpty($userCharacters);
        $this->assertEquals($user->id, $userCharacters[0]->user_id);
    }

    public function testResetPassword()
    {
        $user = factory(User::class)->create();
        factory(PasswordReset::class)->create([
            'user_id' => $user->id,
        ]);
        $userPasswordReset = $user->passwordReset;
        $this->assertNotNull($userPasswordReset);
        $this->assertEquals($user->id, $userPasswordReset->user_id);
    }
}