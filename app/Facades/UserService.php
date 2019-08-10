<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-31
 * Time: 19:58
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;
use App\Entities\User;

/**
 * Class UserServiceFacade
 * @package App\Facades
 * @method static hasUserVerifiedEmail(User $user) : bool
 * @method static setEmailAsVerified(User $user) : void
 * @method static sendEmailVerificationNotification(User $user) : bool
 * @method static authorizeRoles(User $user, $roles)
 * @method static hasAnyRole(User $user, array $roles) : bool
 * @method static  hasRole(User $user, string $role) : bool
 * @method static  generateResetPasswordToken() : string
 */
class UserService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'UserService';
    }
}