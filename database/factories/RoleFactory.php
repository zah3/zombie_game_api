<?php

use Faker\Generator as Faker;


$factory->define(App\Entities\Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'description' => $faker->text(200),
    ];
});