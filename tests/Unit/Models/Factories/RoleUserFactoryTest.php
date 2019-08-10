<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-19
 * Time: 08:42
 */

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