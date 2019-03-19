<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-19
 * Time: 08:10
 */

namespace Tests\Feature\Models\Relationships;


use App\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRelationshipsTest extends TestCase
{
    use DatabaseTransactions;

    public function testRoles()
    {
        dd(Role::all()->toArray());
    }
}