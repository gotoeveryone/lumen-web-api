<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Models\Token;
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
        if (!$user || !app('hash')->check($password, $user->password)) {
            return response([
                'code' => 403,
                'message' => 'Invalid user.',
            ], 403);
        }

        // データのログイン処理
        $user->logged();

        // トークンの生成
        $token = Token::createToken($user);

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
        Token::deleteToken($request->get('access_token'));
        return response('', 204);
    }
}
