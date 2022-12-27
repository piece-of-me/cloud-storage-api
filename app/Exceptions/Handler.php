<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
    }

    public function render($request, Throwable $e)
    {
        if (($request->ajax() && !$request->pjax()) || $request->wantsJson()) {
            if ($e instanceof UnauthorizedHttpException) {
                return new JsonResponse(['message' => 'Unauthorized'], 401);
            }
            if ($e instanceof ModelNotFoundException) {
                return new JsonResponse(['message' => 'Файла не существует'], 404);
            }

            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : $e->getCode();
            return new JsonResponse(['message' => $e->getMessage()], $status !== 0 ? $status : 500);
        }
        return parent::render($request, $e);
    }
}
