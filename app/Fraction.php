<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fraction extends Model
{
    public const FRACTION_NAME_NORMAL = 'Normal';
    public const FRACTION_NAME_ZOMBIE_KILLER = 'Zombie_killer';
    public const FRACTION_NAME_KNIGHT = 'Knight';

    /**
     * Relation to character model.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
