<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class CoordinateRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CoordinateRepository';
    }
}