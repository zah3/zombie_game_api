<?php

namespace Tests\Feature\APIEndpoints;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class API_User_Test extends TestCase
{
    use DatabaseTransactions;

    /**
     * Finish it, when factories are created
     * A basic test example.
     *
     * @return void
     */
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
