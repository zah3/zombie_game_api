<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
