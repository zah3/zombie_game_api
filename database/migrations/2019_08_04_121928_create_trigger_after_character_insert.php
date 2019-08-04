<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerAfterCharacterInsert extends Migration
{
    private $dropTrigger = "DROP TRIGGER IF EXISTS after_characters_insert";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createTrigger = "
            CREATE TRIGGER after_characters_insert
                AFTER INSERT ON characters
                    FOR EACH ROW
                    BEGIN
                        CALL create_default_abilities_for_new_character(new.id);
                        CALL create_default_coordinate_for_new_character(new.id);
                    END;
        ";
        DB::unprepared($this->dropTrigger);
        DB::unprepared($createTrigger);
    }

    public function down()
    {
        DB::unprepared($this->dropTrigger);
    }
}
