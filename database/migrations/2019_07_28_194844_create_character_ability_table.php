<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacterAbilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_abilities', function (Blueprint $table) {
            $table->unsignedInteger('character_id');
            $table->unsignedInteger('ability_id');
            $table->unsignedTinyInteger('is_active')->default(0);
            $table->timestamps();

            $table->unique(['character_id', 'ability_id']);

            $table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');
            $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
        });
    }
}
