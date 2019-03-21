<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-19
 * Time: 08:10
 */

namespace Tests\Feature\Models\Relationships;


use App\Character;
use App\Role;
use App\RoleUser;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRelationshipsTest extends TestCase
{
    use DatabaseTransactions;

    public function testRoles()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        factory(RoleUser::class)->create([
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
        $rolesForUser = $user->roles;
        $this->assertNotNull($rolesForUser);
    }

    public function testCharacters()
    {
        $user = factory(User::class)->create();
        factory(Character::class)->create([
            'user_id' => $user->id,
        ]);
        $userCharacters = $user->characters;
        $this->assertNotEmpty($userCharacters);
    }
}