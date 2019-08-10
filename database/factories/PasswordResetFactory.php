<?php

use Faker\Generator as Faker;


$factory->define(App\Entities\PasswordReset::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\Entities\User::class)->create()->id,
        'token' => $faker->uuid,
    ];
});