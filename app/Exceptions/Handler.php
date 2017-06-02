<?php

namespace App\Exceptions;

use Exception;
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
        $code = 500;
        $message = $e->getMessage();
        $default = 'Unknown Error';
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            $code = 404;
            $default = 'Not Found';
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $code = 405;
            $default = 'Method Not Allowed';
        } elseif ($e instanceof ValidationException) {
            $code = 400;
            $default = 'Bad Request';
        }
        return response([
            'code' => $code,
            'message' => $message ? $message : $default,
        ], $code);
    }
}
