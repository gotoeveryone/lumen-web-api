<?php

namespace App\Models;

use Carbon\Carbon;

class Token extends BaseModel
{
    /**
     * 当モデルでは登録・更新日付を利用しない
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * ユーザを取得します。
     *
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'expire',
    ];

    /**
     * トークンを生成して返却します。
     *
     * @param App\Model\User
     * @return string 生成されたトークン
     */
    public static function createToken(User $user)
    {
        // トークンを取得
        $token = str_random(50);

        // Redisへ保存
        if (self::useRedis()) {
            $redis = app('redis');
            $redis->set($token, json_encode([
                'user_id' => $user->id,
                'environment' => env('APP_ENV'),
            ]));
            $redis->expire($token, 600);
            return $token;
        }

        $model = new self;
        $model->token = $token;
        $model->user_id = $user->id;
        $model->expire = 600;
        $model->environment = env('APP_ENV');
        $model->created = Carbon::now();
        $model->save();
        return $token;
    }

    /**
     * トークンから保存している情報を取得する。
     *
     * @param string $token
     * @return array|false データがあればその情報、無ければfalse
     */
    public static function getData(string $token)
    {
        if (self::useRedis()) {
            $redis = app('redis');
            // データが取得できない
            // 有効期限切れの場合も同様
            if (!($body = $redis->get($token)) || !($res = json_decode($body))) {
                return false;
            }
        } else {
            // データが取得できない
            if (!($res = self::where('token', $token)->first())) {
                return false;
            }
            // 有効期限切れ
            $seconds = $res->created->addSeconds($res->expire);
            if ($res->expire && Carbon::now()->diffInMinutes($seconds, false) < 0) {
                return false;
            }
        }

        // 取得したデータと実行環境が不一致
        if ($res->environment !== env('APP_ENV')) {
            return false;
        }

        return $res;
    }

    /**
     * トークンを削除します。
     *
     * @param string $token
     * @return void
     */
    public static function deleteToken(string $token)
    {
        // Redisから削除
        if (self::useRedis()) {
            $redis = app('redis');
            if ($token && $redis->get($token)) {
                $redis->del($token);
            }
            return;
        }
        self::where('token', $token)->delete();
    }

    /**
     * Redisを利用するかどうかを判定します。
     *
     * @return boolean Redisを利用する場合はtrue
     */
    private static function useRedis()
    {
        return (bool) env('REDIS_HOST');
    }
}
