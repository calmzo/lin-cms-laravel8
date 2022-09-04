<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseModel implements JWTSubject, AuthenticatableContract,
    AuthorizableContract
{
    use HasFactory, Notifiable, Authenticatable, Authorizable, BooleanSoftDeletes;

    public $fillable = [
        ''
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'issuer' => env('JWT_ISSUER'),
            'userId' => $this->getKey(),
            'permissions' => '',
            'admin' => '',
        ];    }
}
