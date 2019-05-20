<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-05-11
 * Time: 02:23
 */

namespace App\Entities\Constants\Helpers;


abstract class ExceptionMessage
{
    public const VERIFICATION_USER_IS_ALREADY_VERIFIED = "User is already verified'";
    public const PASSWORD_RESET_TOKEN_CREATED_MORE_THEN_LIMIT = "Token is not more valid. Over 60 minutes.";

}