<?php

namespace Tests\Unit\Models\Scopes;

use App\Entities\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RoleScopeTest extends TestCase
{
    use DatabaseTransactions;

    public function testWithName()
    {
        $role = factory(Role::class)->create();
        $foundRole = Role::query()->withName($role->name)->first();
        $this->assertNotNull($foundRole);
        $this->assertEquals($role->name, $foundRole->name);
    }

}