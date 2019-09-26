<?php

namespace Tests\Unit\Models\Factories;

use App\Entities\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserFactoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $user = factory(User::class)->create();
        $this->assertDatabaseHas('users',$user->toArray());
    }
}