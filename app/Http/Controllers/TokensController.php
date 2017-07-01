<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Token;

/**
 * トークンコントローラ
 * 
 * @author Kazuki_Kamizuru
 */
class TokensController extends Controller
{
    /**
     * ログイン
     *
     * @param Request $request
     * @return Response
     */
    public function deleteExpired(Request $request)
    {
        // 許可IP以外はエラー
        if (env('ALLOW_IP') !== $request->getClientIp()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        // Redis利用時は処理しない
        if (Token::useRedis()) {
            abort(Response::HTTP_BAD_REQUEST, 'Failed. Because using Redis in token saving storage.');
        }

        // 期限切れトークンを削除
        return response([
            'count' => Token::deleteExpired(),
        ], Response::HTTP_OK);
    }
}
