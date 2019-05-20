<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->string('token');
            $table->timestamps();
        });

        $sqlIndexOnUserId = "CREATE UNIQUE INDEX password_resets_user_id_uindex ON password_resets (user_id);";
        $sqlIndexOnToken = "CREATE UNIQUE INDEX password_resets_token_uindex ON password_resets (token);";
        DB::unprepared($sqlIndexOnToken);
        DB::unprepared($sqlIndexOnUserId);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
