<?php

use App\Fraction;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class SeedFractionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "INSERT INTO fractions (name,created_at) VALUES 
                  ('" . Fraction::NAME_NORMAL . "',NOW()),
                  ('" . Fraction::NAME_ZOMBIE_KILLER . "',NOW()),
                  ('" . Fraction::NAME_KNIGHT . "',NOW())"
        );
    }
}
