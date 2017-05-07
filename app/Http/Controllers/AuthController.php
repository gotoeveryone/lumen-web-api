<?php

namespace App\Http\Controllers;

use Hash;
use Redis;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Models\User;

/**
 * 認証コントローラ
 * 
 * @author Kazuki_Kamizuru
 */
class AuthController extends Controller
{
    /**
     * ログイン
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $account = $request->get('account');
        $password = $request->get('password');

        // ユーザが取得できない、またはパスワードが異なる場合は403
        $user = User::where('account', $account)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response([
                'code' => 403,
                'message' => 'Invalid user.',
            ], 403);
        }

        $user->last_logged = Carbon::now();
        $user->save();

        // トークンを取得
        $token = str_random(50);

        // Redisへ保存
        if (env('REDIS_HOST')) {
            Redis::set($token, json_encode([
                'id' => $user->id,
                'account' => $account,
                'environment' => env('APP_ENV'),
            ]));
            Redis::expire($token, 600);
        }

        // トークンを返す
        return response([
            'access_token' => $token,
        ], 200);
    }

    /**
     * ログアウト
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $token = $request->get('access_token');
        if ($token && Redis::get($token)) {
            Redis::del($token);
        }

        return response('', 204);
    }
}
