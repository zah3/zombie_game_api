<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    protected $primaryKey = 'character_id';

    public $incrementing = false;
}
