<?php

namespace App\Exceptions;

use App\Traits\Api\ApiResponder;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponder;

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
    public function render($request, Throwable $exception)
    {
        switch (class_basename($exception)) {
            // case 'ValidationException':
            //     if ($request->expectsJson()){
            //         Log::debug($request->all());
            //         Log::info("API Error: ". $exception);
            //         return $this->convertValidationExceptionToResponse($exception, $request);
            //     }
            // break;

            case 'ModelNotFoundException':
                if ($request->expectsJson()) {
                    $modelName = strtolower(class_basename($exception->getModel()));
                    Log::info('API Error: '.$modelName);

                    return $this->errorResponse("{$modelName} does not exist with any specified identifiers", 404);
                }
                break;

            case 'ModelNotFoundException':
                if ($request->expectsJson()) {
                    $modelName = strtolower(class_basename($exception->getModel()));
                    Log::info('API Error: '.$modelName);

                    return $this->errorResponse("{$modelName} does not exist with any specified identifiers", 404);
                }
                break;

            case 'AuthenticatedException':
                if ($request->expectsJson()) {
                    Log::debug($request->all());
                    Log::info('API Error: '.$exception);

                    return $this->unauthenticated($request, $exception);
                }
                break;

            case 'AuthorizationException':
                if ($request->expectsJson()) {
                    Log::info('API Error: '.$exception->getMessage());

                    return $this->errorResponse($exception->getMessage(), 403);
                }
                break;

            case 'MethodNotAllowedHttpException':
                if ($request->expectsJson()) {
                    Log::info('API Error: '.$exception->getMessage());

                    return $this->errorResponse('The specified method for the request is invalid', 405);
                }
                break;

            case 'NotFoundHttpException':
                if ($request->expectsJson()) {
                    Log::info('API Error: '.$exception->getMessage());

                    return $this->errorResponse('The specified URL cannot be found', 404);
                }
                break;

            case 'HttpException':
                if ($request->expectsJson()) {
                    Log::info('API Error: '.$exception->getMessage());

                    return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
                }
                break;

            case 'QueryException':
                if ($request->expectsJson()) {
                    if ($exception->errorInfo[0] == '42S02') {
                        Log::info('API Error: '.$exception);

                        return $this->errorResponse($exception->errorInfo[2], 409);
                    }

                    $errorCode = $exception->errorInfo[1];

                    if ($errorCode == 1451) {
                        Log::info('API Error: '.$exception);

                        return $this->errorResponse('Cannot remove the resource permanently. it is related with other resources', 409);
                    }

                    return $this->errorResponse($exception->errorInfo[2], 409);
                }
                break;

            case 'AuthorizationException':
                if ($request->expectsJson()) {
                    Log::debug($request->all());
                    Log::info('API Error: '.$exception);

                    return $this->errorResponse($exception->getMessage(), 403);
                }
                break;

            case 'TokenExpiredException':
                if ($request->expectsJson()) {
                    Log::debug($request->all());
                    Log::info('API Error: '.$exception);

                    return $this->errorResponse($exception->getMessage(), 403);
                }
                break;

            case 'TokenInvalidException':
                if ($request->expectsJson()) {
                    Log::debug($request->all());
                    Log::info('API Error: '.$exception);

                    return $this->errorResponse($exception->getMessage(), 403);
                }
                break;

            case 'JWTException':
                if ($request->expectsJson()) {
                    Log::debug($request->all());
                    Log::info('API Error: '.$exception);

                    return $this->errorResponse($exception->getMessage(), 403);
                }
                break;

            case 'TypeError':
                if ($request->expectsJson()) {
                    Log::debug($request->all());
                    Log::info('API Error: '.$exception);

                    return $this->errorResponse($exception->getMessage(), 403);
                }
                break;

            case 'ErrorException':
                if ($request->expectsJson()) {
                    Log::debug($request->all());
                    Log::info('API Error: '.$exception);

                    return $this->errorResponse($exception->getMessage(), 409);
                }
                break;

            case 'Error':
                if ($request->expectsJson()) {
                    Log::debug($request->all());
                    Log::info('API Error: '.$exception);

                    return $this->errorResponse($exception->getMessage(), 403);
                }
                break;

            case 'TokenMismatchException':
                return redirect()->back()->withInput($request->input());
                break;

                if (config('app.debug')) {
                    return parent::render($request, $exception);
                }

                return parent::render($request, $exception);
        }

        return parent::render($request, $exception);
    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {

        });
    }
}
