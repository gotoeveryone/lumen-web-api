<?php

namespace App\Exceptions;

use Exception;
use Log;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = $e->getMessage();
        if ($e instanceof ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
        } elseif ($e instanceof ValidationException) {
            $code = Response::HTTP_BAD_REQUEST;
        } elseif ($e instanceof HttpException) {
            $code = $e->getStatusCode();
        }

        $showMessage = $message ? $message : $this->getDefault($code);
        Log::error("${code}: ${showMessage}");
        Log::error(var_export($request->all(), true));

        return response([
            'code' => $code,
            'message' => $showMessage,
        ], $code);
    }

    /**
     * ステータスコードから、デフォルトのメッセージを取得します。
     *
     * @param int $code
     * @return string メッセージ
     */
    private function getDefault(int $code)
    {
        return Response::$statusTexts[$code] ?? 'Unknown Error';
    }
}
