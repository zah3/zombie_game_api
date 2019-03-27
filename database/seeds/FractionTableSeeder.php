<?php

use Illuminate\Database\Seeder;
use App\Fraction;

class FractionTableSeeder extends Seeder
{
    private $i = 2;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 0;
        do {
            $faker = Faker\Factory::create();
            $fraction = new Fraction();
            $fraction->name = ($i == 0) ? Fraction::NAME_NORMAL : $faker->colorName;
            $fraction->save();
            $i++;
        } while ($i <= $this->i);
    }
}
