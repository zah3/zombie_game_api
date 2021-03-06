<?php

namespace App\Repositories;

use App\Entities\Coordinate;
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