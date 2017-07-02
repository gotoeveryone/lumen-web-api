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
        if (!($token = $this->getToken($request)) || !($data = Token::getData($token))) {
            abort(Response::HTTP_UNAUTHORIZED, 'Access token is invalid');
        }

        // 取得したデータをリクエストに付与
        $request->merge($data);
        return $next($request);
    }

    /**
     * リクエストからアクセストークンを取得します。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function getToken($request)
    {
        $authorization = $request->headers->get('Authorization');
        if (($pos = strpos($authorization, 'Bearer ')) === false) {
            return '';
        }
        return substr($authorization, strlen('Bearer '));
    }
}
