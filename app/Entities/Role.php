<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Role extends Model
{
    public const ADMIN = 'administrator';
    public const USER = 'user';

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Relation to user model.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }


    /**
     * Scope return role with name.
     *
     * @param $query
     * @param string $name
     * @return Builder
     */
    public function scopeWithName($query, string $name) : Builder
    {
        return $query->where($this->table . '.name', '=', $name);
    }
}
