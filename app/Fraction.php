<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fraction extends Model
{
    public const FRACTION_NAME_NORMAL = 'Normal';

    protected $fillable = [
        'name'
    ];

    /**
     * Relation to character model.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
