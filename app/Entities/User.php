<?php

namespace App\Entities;

use App\Events\UserEvent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    public const GAME_TOKEN = "GameToken";

    public const MESSAGE_UNAUTHORIZED = 'This action is unauthorized.';
    public const MESSAGE_USER_NOT_VERIFIED = 'User is not verified.';


    /**
     * @var array
     */
    public $timestamps = [
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    /**
     * softDelete attribute.
     * @var array
     */
    public $dates = ['deleted_at'];

    protected $fillable = [
        'username',
        'email',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relation to character model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function characters() : HasMany
    {
        return $this->hasMany(Character::class);
    }

    /**
     * Relation to role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function passwordReset() : HasOne
    {
        return $this->hasOne(PasswordReset::class);
    }

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::created(
            function ($user) {
                $userEvent = new UserEvent();
                event($userEvent->userCreated($user));
            }
        );
    }

    /**
     * Scope query for users with verified emails
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeWithEmailVerifiedAt(Builder $query) : Builder
    {
        return $query->where($this->table . '.email_verified_at', '!=', null);
    }
}

