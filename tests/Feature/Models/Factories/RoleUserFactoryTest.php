<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-19
 * Time: 08:42
 */

use Tests\TestCase;

class RoleUserFactoryTest extends TestCase
{
    public function testCreate()
    {
        $roleUser = factory(\App\RoleUser::class)->create();
        $this->assertDatabaseHas('role_user', $roleUser->toArray());
    }
}