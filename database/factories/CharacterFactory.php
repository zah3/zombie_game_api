<?php
use Faker\Generator as Faker;


$factory->define(App\Character::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
        'fraction_id' => function () {
            return factory(\App\Fraction::class)->create()->id;
        },
        'name' => $faker->unique()->firstName .'-'. $faker->unique()->lastName,
        'experience' => 0,
    ];
});
