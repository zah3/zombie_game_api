<?php

use Faker\Generator as Faker;


$factory->define(App\PasswordReset::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class)->create()->id,
        'token' => $faker->uuid,
    ];
});