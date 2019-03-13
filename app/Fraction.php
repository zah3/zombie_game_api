<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fraction extends Model
{
    public const NAME_NORMAL = 'Normal';
    public const NAME_ZOMBIE_KILLER = 'Zombie_killer';
    public const NAME_KNIGHT = 'Knight';

    public const ID_NORMAL = 1;
    public const ID_ZOMBIE_KILLER = 2;
    public const ID_KNIGHT = 3;

    /**
     * Relation to character model.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
