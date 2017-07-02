<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Models\Token;

/**
 * 認証情報のチェックを行います。
 * 
 * @author Kazuki_Kamizuru
*/
class Authenticate
{
    /**
     * ハンドラ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // 認証情報が取得できない
        if (!($data = Token::getData($request))) {
            abort(Response::HTTP_UNAUTHORIZED, 'Access token is invalid');
        }

        // 取得したデータをリクエストに付与
        $request->merge($data);
        return $next($request);
    }
}
