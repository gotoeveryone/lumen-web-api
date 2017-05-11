<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Token;

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
        if (!($token = $request->get('access_token')) || !($data = Token::getData($token))) {
            Log::warning('認証エラー');
            return $this->error(403, 'Access token is invalid');
        }

        $request->setJson($data);
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
