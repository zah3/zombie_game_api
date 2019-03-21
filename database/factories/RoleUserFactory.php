<?php

use Faker\Generator as Faker;


$factory->define(App\RoleUser::class, function (Faker $faker) {
    return [
        'user_id' => function(){
            return factory(\App\User::class)->create()->id;
        },
        'role_id' => function(){
            return factory(\App\Role::class)->create()->id;
        },
    ];
});