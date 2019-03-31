<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class SeedUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("INSERT INTO users (username, password, email_verified_at) VALUES" .
            "('Zachariasz_admin','". Hash::make('Zachariasz') . "', now())," .
            "('Zachariasz_user','".  Hash::make('Zachariasz') . "', now())," .
            "('Karol','".  Hash::make('Karol') . "', now())," .
            "('Rafał','".  Hash::make('Rafał') . "', now())," .
            "('Maciej','".  Hash::make('Maciej') . "', now())," .
            "('Mikołaj','".  Hash::make('Mikołaj') . "', now())");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
