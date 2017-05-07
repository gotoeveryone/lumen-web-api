<?php

namespace App\Models;

use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 登録時に日時を記録するカラム
     * 
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * 登録・更新時に日時を記録するカラム
     * 
     * @var string
     */
    const UPDATED_AT = 'modified';

    /**
     * ユーザIDを取得します。
     *
     * @return string ユーザID
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
