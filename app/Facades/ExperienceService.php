<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-30
 * Time: 07:17
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class ExperienceService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ExperienceService';
    }
}