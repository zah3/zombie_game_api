<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsCamelCaseSensitive()
    {
        $this->call('POST','api/login',[
            'password' => 'Zachariasz',
            'username' => 'Zachariasz'
        ]);
        //$this->assertArray(true);
    }
}
