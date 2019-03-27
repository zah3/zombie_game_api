<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-27
 * Time: 08:22
 */

namespace Tests\Feature\Models\Scopes;


use App\Role;
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