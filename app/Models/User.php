<?php

namespace App\Models;

use Carbon\Carbon;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements
    AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * 当モデルでは登録・更新日付を利用しない
     * 
     * @var boolean
     */
    public $timestamps = false;

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
        'password',
    ];

    /**
     * ユーザIDを取得します。
     *
     * @return string ユーザID
     */
    public function getUserId()
    {
        return $this->userId || $this->account;
    }

    /**
     * ログイン処理
     *
     * @return bool
     */
    public function logged()
    {
        $logged = Carbon::now();
        $this->last_logged = $logged;
        $this->modified = $logged;
        $this->modified_by = $this->account;
        return $this->save();
    }

    /**
     * アクティブユーザを取得します。
     *
     * @param int $userId
     * @return User|null
     */
    public static function getActiveUser(int $userId)
    {
        if (!($user = self::where('is_active', true)->find($userId))) {
            abort(403, 'Invalid user.');
        }
        return $user;
    }
}
