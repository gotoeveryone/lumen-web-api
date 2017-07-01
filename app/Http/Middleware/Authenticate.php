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
        // トークンが取得できない
        if (!($token = $request->get('access_token')) || !($data = Token::getData($token))) {
            abort(Response::HTTP_UNAUTHORIZED, 'Access token is invalid');
        }

        // 取得したデータをリクエストに付与
        $request->merge($data);
        return $next($request);
    }
}
