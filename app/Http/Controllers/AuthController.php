<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $this->validate($request, [
            'account' => 'required|max:20',
            'password' => 'required|max:40',
        ]);

        $account = $request->get('account');
        $password = $request->get('password');

        // ユーザが取得できない、またはパスワードが異なる場合は403
        $user = User::where('account', $account)->where('is_active', true)->first();
        if (!$user || !app('hash')->check($password, $user->password)) {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid user.');
        }

        // ログイン処理
        $user->logged();

        // トークンの生成
        $token = Token::createToken($user);

        // トークンを返す
        return response([
            'access_token' => $token,
        ], Response::HTTP_OK);
    }

    /**
     * ログアウト
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        Token::deleteToken($request);
        return response('', Response::HTTP_NO_CONTENT);
    }
}
