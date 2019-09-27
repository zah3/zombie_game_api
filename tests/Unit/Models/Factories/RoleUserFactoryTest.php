<?php

namespace Tests\Unit\Models\Factories;

use Tests\TestCase;

class RoleUserFactoryTest extends TestCase
{
    public function testCreate()
    {
        $roleUser = factory(\App\Entities\RoleUser::class)->create();
        $this->assertDatabaseHas('role_user', $roleUser->toArray());
    }
}