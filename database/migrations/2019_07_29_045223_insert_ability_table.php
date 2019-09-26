<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertAbilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "INSERT into abilities (name)" .
            "VALUES ('Combo'),('Kick'),('Power up'),('Defense')," .
            "('Heal'),('Health'),('Pilum'),('Strength'),('Speed up')"
        );
    }
}
