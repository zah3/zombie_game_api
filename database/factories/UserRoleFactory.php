<?php

use Faker\Generator as Faker;


$factory->define(App\RoleUser::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});