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
            "VALUES ('number 1'),('number_2'),('number_3'),('number_4')"
        );
    }
}
