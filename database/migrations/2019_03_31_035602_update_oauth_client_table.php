<?php

use Illuminate\Database\Migrations\Migration;

class UpdateOauthClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::unprepared(
            "INSERT INTO oauth_personal_access_clients (client_id, created_at, updated_at) VALUES
                  (1,NOW(),NOW()) "
        );
        $client = \Laravel\Passport\Passport::client()->forceFill([
            'user_id' => null,
            'name' => \App\User::GAME_TOKEN,
            'secret' => str_random(40),
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => false,
        ]);

        $client->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
