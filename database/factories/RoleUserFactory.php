<?php

use Faker\Generator as Faker;


$factory->define(App\Entities\RoleUser::class, function (Faker $faker) {
    return [
        'user_id' => function(){
            return factory(\App\Entities\User::class)->create()->id;
        },
        'role_id' => function(){
            return factory(\App\Entities\Role::class)->create()->id;
        },
    ];
});