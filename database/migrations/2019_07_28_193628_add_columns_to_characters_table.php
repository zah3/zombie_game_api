<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Character;

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
            $table->unsignedInteger('agility')->after('experience')->default(Character::DEFAULT_AGILITY);
            $table->unsignedInteger('strength')->after('agility')->default(Character::DEFAULT_STRENGTH);
        });
    }
}
