<?php

namespace App\Http\Middleware;

use Redis;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * トークンを保持しているかをチェックします。
 * 
 * @author Kazuki_Kamizuru
 */
class HasToken
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
        if (!($token = $request->has('access_token'))) {
            return $this->error(403, 'Access token is invalid');
        }

        // トークンからデータが取得できない
        if (!($body = Redis::get($token)) || !($json = json_decode($body, true))) {
            return $this->error(403, 'Access token is invalid');
        }

        // 取得したデータと実行環境が不一致
        if (!isset($json['environment']) || $json['environment'] !== env('APP_ENV')) {
            return $this->error(403, 'Access token is invalid');
        }

        return $next($request);
    }

    /**
     * エラー時のレスポンスを取得します。
     *
     * @param int $code
     * @param string $message
     * @return Response
     */
    private function error($code = 500, $message = 'Internal Server Error.')
    {
        return response([
            'code' => $code,
            'message' => $message,
        ], 403);
    }
}
