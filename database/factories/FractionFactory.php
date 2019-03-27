<?php

use Faker\Generator as Faker;


$factory->define(App\Fraction::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});