<?php

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class CharacterRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CharacterRepository';
    }
}