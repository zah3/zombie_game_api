<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-31
 * Time: 19:58
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;
use App\User;

/**
 * Class UserServiceFacade
 * @package App\Facades
 * @method static hasUserVerifiedEmail(User $user) : bool
 * @method static setEmailAsVerified(User $user) : bool
 * @method static sendEmailVerificationNotification(User $user) : bool
 */
class UserService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'UserService';
    }
}