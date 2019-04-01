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
        DB::unprepared("INSERT INTO users (username, password, email, email_verified_at) VALUES" .
            "('Zachariasz_admin','". Hash::make('Zachariasz') . "','a@o2.pl', now())," .
            "('Zachariasz_user','".  Hash::make('Zachariasz') . "','a3@o2.pl', now())," .
            "('Karol','".  Hash::make('Karol') . "','a2@o2.pl', now())," .
            "('Rafał','".  Hash::make('Rafał') . "','a4@o2.pl', now())," .
            "('Maciej','".  Hash::make('Maciej') . "','a5@o2.pl', now())," .
            "('Mikołaj','".  Hash::make('Mikołaj') . "','a6@o2.pl', now())");
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
