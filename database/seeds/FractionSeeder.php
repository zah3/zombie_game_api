<?php

use Illuminate\Database\Seeder;
use App\Fraction;

class FractionSeeder extends Seeder
{

    private $i = 2;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 2; $i++) {
            $faker = Faker\Factory::create();
            $fraction = new Fraction();
            $fraction->name = ($i == 0) ? Fraction::FRACTION_NAME_NORMAL : $faker->colorName;
            $fraction->save();
        }
    }
}
