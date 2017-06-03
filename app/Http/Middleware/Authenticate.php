<?php

namespace App\Http\Middleware;

use Log;
use Closure;
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
            Log::warning('認証エラー');
            abort(403, 'Access token is invalid');
        }

        $request->setJson($data);
        return $next($request);
    }
}
