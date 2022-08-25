<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $e)
    {
//        if ($e instanceof BusinessException) {
//            return response()->json([
//                'errno' => $e->getCode(),
//                'errmsg' => $e->getMessage()
//            ]);
//        }
        $status = 200;
        if ($e instanceof AuthFailedException) {
            $status = 403;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        } elseif ($e instanceof ForbiddenException) {
            $status = 401;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        } elseif ($e instanceof NotFoundException) {
            $status = 404;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        } elseif ($e instanceof OperationException) {
            $status = 400;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        } elseif ($e instanceof RepeatException) {
            $status = 400;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        } elseif ($e instanceof ParameterException) {
            $status = 400;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        } elseif ($e instanceof UserException) {
            $status = 401;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        } elseif ($e instanceof ValidateException) {
            $status = 404;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        } else {
            $status = 404;
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }
        return response()->json($response, $status);
    }
}
