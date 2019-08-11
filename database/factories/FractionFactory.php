<?php

use Faker\Generator as Faker;


$factory->define(App\Entities\Fraction::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});