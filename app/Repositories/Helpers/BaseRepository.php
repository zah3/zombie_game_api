<?php

namespace App\Repositories\Helpers;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
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
            array_key_exists($fieldName, $newFields) &&
            $model->$fieldName !== $newFields[$fieldName]
        ) {
            $model->$fieldName = $newFields[$fieldName];
        }
        return $model;
    }
}