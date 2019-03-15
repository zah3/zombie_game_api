<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-03-15
 * Time: 08:17
 */

namespace App\Repositories\Helpers;


use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * Update field for specified model
     *
     * @param Model $model
     * @param array $newFields
     * @param string $fieldName
     *
     * @return Model
     */
    public static function updateField(Model $model, array $newFields, string $fieldName)
    {
        if (
            $model->$fieldName !== $newFields[$fieldName] &&
            array_key_exists($fieldName, $newFields)
        ){
            $model->$fieldName = $newFields[$fieldName];
        }
        return $model;
    }
}