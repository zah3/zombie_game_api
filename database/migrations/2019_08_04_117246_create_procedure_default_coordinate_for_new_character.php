<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureDefaultCoordinateForNewCharacter extends Migration
{
    private $dropProcedure = "
        DROP PROCEDURE IF EXISTS create_default_coordinate_for_new_character;
    ";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared($this->dropProcedure);
        $createProcedure = "
            CREATE PROCEDURE create_default_coordinate_for_new_character(IN character_id INT)
                BEGIN
                    INSERT INTO coordinates(character_id, x, y, created_at, updated_at) 
                        VALUES (character_id, 0, 0, now(), NULL);
                END;
        ";
        DB::unprepared($createProcedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared($this->dropProcedure);
    }
}
