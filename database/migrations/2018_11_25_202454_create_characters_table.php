<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('fraction_id')->index()->default($this->getIdNormalFraction());
            $table->string('name');
            $table->tinyInteger('level',false,true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('users')->on('id');
            $table->foreign('fraction_id')->references('fractions')->on('id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
    }

    public function getIdNormalFraction()
    {
        return \App\Fraction::query()
                            ->where('name','=',\App\Fraction::FRACTION_NAME_NORMAL)
                            ->select('id');
    }
}
