<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Entities\User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->userName . $faker->randomNumber(2),
        'email' => $faker->unique()->email,
        'password' => \Illuminate\Support\Facades\Hash::make(random_bytes(10)),
        'email_verified_at' => now(),
    ];
});
