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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->safeColorName,
        'password' => \Illuminate\Support\Facades\Hash::make(random_bytes(10)),
        'is_active' => true,
        'remember_token' => str_random(10),
    ];
});
