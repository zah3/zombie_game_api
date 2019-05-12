<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-19
 * Time: 08:37
 */

namespace Tests\Unit\Models\Factories;


use App\Role;
use Tests\TestCase;

class RoleFactoryTest extends TestCase
{
    public function testCreate()
    {
        $role = factory(Role::class)->create();
        $this->assertDatabaseHas('roles', $role->toArray());
    }
}