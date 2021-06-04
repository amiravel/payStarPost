<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'client_id',
        'token',
        'role_id'
    ];

    protected $with = [
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @param $token
     */
    public function setTokenAttribute($token)
    {
        $this->attributes['token'] = bcrypt($token);
    }

    /**
     * @return string
     */
    public function authenticate()
    {
        $token = Str::random(50);

        $this->update([
            'client_id' => self::generateClientId(),
            'token' => $token
        ]);

        return $token;
    }

    /**
     * @return string
     */
    public static function generateClientId()
    {
        return Str::random(50) . base64_encode(Carbon::now()->timestamp);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @param $permission
     * @return mixed
     */
    public function hasPermission($permission)
    {
        return $this->role->permissions()
            ->whereTitle($permission)
            ->exists();
    }

    /**
     * @param $clientId
     * @return mixed
     */
    public static function findByClientId($clientId)
    {
        return self::query()->whereClientId($clientId)->firstOrFail();
    }
}
