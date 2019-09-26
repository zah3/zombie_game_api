<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entities\Character;

class AddColumnsToCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->unsignedInteger('strength')->after('experience')->default(Character::DEFAULT_STRENGTH);
            $table->unsignedInteger('speed')->after('strength')->default(Character::DEFAULT_SPEED);
            $table->unsignedInteger('stamina')->after('speed')->default(Character::DEFAULT_STAMINA);
            $table->unsignedInteger('ability_points')->after('stamina')->default(Character::DEFAULT_ABILITY_POINTS);
        });
    }
}
