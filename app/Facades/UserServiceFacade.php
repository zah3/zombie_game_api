<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-31
 * Time: 19:58
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class UserServiceFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'UserService';
    }

}