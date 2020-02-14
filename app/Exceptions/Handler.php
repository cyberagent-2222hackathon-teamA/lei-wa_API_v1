<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // EloquentでfindOrFailとかを使って見つからなかった時の例外
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'error' => [
                    'code'    => 'NOT_FOUND',
                    'message' => 'データが見つかりません',
                ],
            ], 404);
        }

        // 404エラー
        elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->json([
                'error' => [
                    'code'    => 'NOT_FOUND',
                    'message' => 'URLが見つかりません',
                ],
            ], 404);
        }

        // abort(403)とか、404以外のHTTP例外
        elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return response()->json([
                'error' => [
                    'code'    => 'HTTP_ERROR',
                    'message' => $exception->getMessage(),
                ],
            ], $exception->getStatusCode());
        }

        // バリデートエラー
        elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'error' => [
                    'code'    => 'VALIDATE_FAILED',
                    'message' => '入力値が不正です',
                    'detail'  => $exception->validator->errors()->toArray(),
                ],
            ], 400);
        }

        // 手動スローエラー
        elseif ($exception instanceof \App\Exceptions\ApplicationException) {
            return response()->json([
                'error' => [
                    'code'    => 'APPLICATION_ERROR',
                    'message' => $exception->getMessage(),
                ],
            ], 400);
        }

        // その他
        return response()->json([
            'error' => [
                'code'    => 'INTERNAL_SERVER_ERROR',
                'message' => 'サーバーエラー',
            ],
        ], 500);
    }
}
