<?php

namespace App;

use App\Events\UserEvent;
use App\Http\Helpers\StatusResponse;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens, SoftDeletes;

    public const GAME_TOKEN = "GameToken";

    public const MESSAGE_UNAUTHORIZED = 'This action is unauthorized.';


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

    /**
     * Scope query to find specified username
     *
     * @param $query
     * @param $username
     *
     * @return mixed
     */
    public function scopeWithUsername(Builder $query, string $username) : Builder
    {
        return $query->where($this->table . '.username', '=', $username);
    }

    /**
     * AuthorizeRoles
     *
     * @param string || array $roles
     *
     * @return bool
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(StatusResponse::STATUS_UNAUTHORIZED, self::MESSAGE_UNAUTHORIZED);
        }
        return $this->hasRole($roles) ||
            abort(StatusResponse::STATUS_UNAUTHORIZED, self::MESSAGE_UNAUTHORIZED);
    }

    /**
     * Check if user has any of roles.
     *
     * @param array $roles
     *
     * @return bool
     */
    public function hasAnyRole(array $roles) : bool
    {
        return !is_null($this->roles()->whereIn('name', $roles)->first());
    }

    /**
     * Check if user has 1 role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role) : bool
    {
        return !is_null($this->roles()->where('name', '=', $role)->first());
    }
}

