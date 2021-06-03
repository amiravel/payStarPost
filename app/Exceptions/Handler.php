<?php

namespace App\Exceptions;

use App\Response\Response;
use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use ReflectionClass;
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
        $exceptionClass = (new ReflectionClass($e))->getShortName();
//        dd($e->getCode(), $e->getMessage(), $e->getLine(), $e->getFile());
        if ($exceptionClass == 'ValidationException'){
            return Response::json($exceptionClass, $e->errors());
        }

        return Response::json($exceptionClass);
    }
}
