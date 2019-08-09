<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-08-06
 * Time: 18:38
 */

namespace App\Repositories;


use App\Coordinate;
use App\Repositories\Helpers\BaseRepository;

class CoordinateRepository extends BaseRepository
{
    /**
     * Updates existing coordinate model
     *
     * @param Coordinate $coordinate
     * @param array $newFields
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public static function update(Coordinate $coordinate, array $newFields)
    {
        $coordinate = self::updateField($coordinate, $newFields, 'x');
        $coordinate = self::updateField($coordinate, $newFields, 'y');

        $coordinate->save();
        return $coordinate;
    }
}