<?php

namespace Tests\Feature\APIEndpoints;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class API_User_Test extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function register()
    {
        $goodData = [
            'username' => 'username',
            'password' => 'password',
            'confirm_password' => 'password',
        ];
        $response = $this->json(
            'POST',
            'api/register',
            $goodData
        );

        $response->status(200);
    }
}
