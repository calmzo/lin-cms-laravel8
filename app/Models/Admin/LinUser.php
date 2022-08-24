<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use App\Models\BooleanSoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use think\facade\Config;
use Tymon\JWTAuth\Contracts\JWTSubject;

class LinUser extends BaseModel implements JWTSubject, AuthenticatableContract,
    AuthorizableContract
{
    use HasFactory, Notifiable, Authenticatable, Authorizable, BooleanSoftDeletes;


//    protected $table = 'lin_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'create_time', 'update_time', 'delete_time', 'password'
    ];

    public static function getUsers(int $start, int $count, array $params = [])
    {
        $userList = self::withSearch(['group_id'], $params)
            ->where('username', '<>', 'root');
        $total = $userList->count();

        $userList = $userList
            ->limit($start, $count)
            ->with('groups')
            ->select();

        return [
            'userList' => $userList,
            'total' => $total
        ];
    }

    public function groups()
    {
        return $this->belongsToMany(LinGroup::class, 'lin_user_group', 'group_id', 'user_id');
    }

    public function identity()
    {
        return $this->hasMany('LinUserIdentity', 'user_id');
    }

    public function searchGroupIdAttr($query, $value)
    {
        if ($value) {
            $query->join('lin_group g', 'g.id=' . $value)->where('g.id', '<>', 1);
        }
    }

    public function getAvatarAttr($value)
    {
        if ($value) {
            $host = Config::get('file.host') ?? "http://127.0.0.1:5000/";
            $dir = Config::get('file.store_dir');
            return $host . $dir . '/' . $value;
        }
        return $value;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'issuer' => env('JWT_ISSUER'),
            'userId' => $this->getKey()
        ];
    }
}
