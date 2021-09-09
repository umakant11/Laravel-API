<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Debug\Exception\ErrorException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        \Symfony\Component\Routing\Exception\RouteNotFoundException::class,
        \Symfony\Component\Debug\Exception\ErrorException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Exception $e, $request) {
            return $this->handleException($request, $e);
        });
    }

    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            $error = 'Method not allowed';
            return response()->json(['success' => false, 'message' => $error], 405);
        }

        if ($exception instanceof AuthorizationException) {
            $error = 'Unauthorised request';
            return response()->json(['success' => false, 'message' => $error], 401);
        }

        if ($exception instanceof AuthenticationException) {
            $error = $exception->getMessage();
            return response()->json(['success' => false, 'message' => $error], 401);
        }

        if ($exception instanceof NotFoundHttpException) {
            $exceptionMessage = $exception->getMessage();
            if ($exceptionMessage == "") {
                $exceptionMessage = "Route not found.";
            }
            return response()->json(['success' => false, 'message' => $exceptionMessage], 404);
        }

        if ($exception instanceof RouteNotFoundException) {
            $error = $exception->getMessage();
            return response()->json(['success' => false, 'message' => $error], 404);
        }
    }
}
