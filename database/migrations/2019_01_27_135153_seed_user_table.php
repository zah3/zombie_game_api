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
        DB::unprepared("INSERT INTO users (username, password, is_active) VALUES" .
            "('Zachariasz_admin','". Hash::make('Zachariasz') . "', true)," .
            "('Zachariasz_user','".  Hash::make('Zachariasz') . "', true)," .
            "('Karol','".  Hash::make('Karol') . "', true)," .
            "('Rafał','".  Hash::make('Rafał') . "', true)," .
            "('Maciej','".  Hash::make('Maciej') . "', true)," .
            "('Mikołaj','".  Hash::make('Mikołaj') . "', true)");
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
