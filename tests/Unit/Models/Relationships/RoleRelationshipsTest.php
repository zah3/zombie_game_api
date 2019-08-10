<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-21
 * Time: 18:29
 */

namespace Tests\Unit\Models\Relationships;


use App\Entities\Role;
use App\Entities\RoleUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RoleRelationshipsTest extends TestCase
{
    use DatabaseTransactions;

    public function testUser()
    {
        $role = factory(Role::class)->create();
        factory(RoleUser::class)->create([
            'role_id' => $role->id,
        ]);

        $usersWithRole = $role->users;
        $this->assertNotEmpty($usersWithRole);
    }
}