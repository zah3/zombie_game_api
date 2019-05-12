<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-05-12
 * Time: 12:17
 */

namespace App\Entities\Helpers;


abstract class Utilities
{
    /**
     * Creates unique random string
     *
     * @return string
     * @throws \Exception
     */
    public static function generateRandomUniqueString() : string
    {
        return md5(time() . random_bytes(10));
    }
}