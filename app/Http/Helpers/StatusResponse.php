<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2018-11-11
 * Time: 11:21
 */

namespace App\Http\Helpers;


class StatusResponse
{
    /**
     * The standard success code and default option.
     * @var int
     */
    public const STATUS_OK = 200;
    /**
     * Stored model in DB.
     * @var int
     */
    public const STATUS_OBJECT_CREATED = 201;
    /**
     *  Action was executed successfully, but there is no content to return.
     * @var int
     */
    public const STATUS_NO_CONTENT = 204;
    /**
     * Return paginated list of resources.
     * @var int
     */
    public const STATUS_PARTIAL_CONTENT = 206;


    /**
     * Failed validation.
     * @var int
     */
    public const STATUS_BAD_REQUEST = 400;
    /**
     * User needs to be authorized.
     * @var int
     */
    public const STATUS_UNAUTHORIZED = 401;
    /**
     * Authenticated user, but does not have the permissions to perform an action.
     * @var int
     */
    public const STATUS_FORBIDDEN = 403;
    /**
     * Resources is not found.
     * @var int
     */
    public const STATUS_NOT_FOUND = 404;

}