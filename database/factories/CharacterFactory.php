<?php

use Faker\Generator as Faker;


$factory->define(App\Entities\Character::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(\App\Entities\User::class)->create()->id;
        },
        'fraction_id' => function () {
            return factory(\App\Entities\Fraction::class)->create()->id;
        },
        'name' => $faker->unique()->firstName .'-'. $faker->unique()->lastName,
        'experience' => 0,
    ];
});
