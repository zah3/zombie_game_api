<?php

namespace Tests\Unit\Models\Factories;

use App\Entities\Role;
use Tests\TestCase;

class RoleFactoryTest extends TestCase
{
    public function testCreate()
    {
        $role = factory(Role::class)->create();
        $this->assertDatabaseHas('roles', $role->toArray());
    }
}