<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateProcedureDefaultAbilitiesForNewCharacter extends Migration
{
    private $dropProcedure = "
            DROP PROCEDURE IF EXISTS create_default_abilities_for_new_character;
        ";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createProcedure = "
            CREATE PROCEDURE create_default_abilities_for_new_character(IN characterId INT)
                BEGIN
                    INSERT INTO
                        character_abilities(character_id, ability_id, is_active, created_at, updated_at)
                    SELECT
                        characterId,
                        abilities.id as ability_id,
                        0,
                        now(),
                        NULL
                    FROM
                        abilities;
                END;
        ";
        DB::unprepared($this->dropProcedure);
        DB::unprepared($createProcedure);
    }

    public function down()
    {
        DB::unprepared($this->dropProcedure);
    }
}
