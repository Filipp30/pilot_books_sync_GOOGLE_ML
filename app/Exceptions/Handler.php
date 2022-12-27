<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    public function render($request, Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation fails.',
                'errors' => $e->errors()
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if ($e instanceof EmailMustBeVerifiedException) {
            return response()->json([
                'message' => 'Email must be verified.'
            ], 403);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'NotFoundException (credentials are probably incorrect).'
            ], 404);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Given credentials are probably incorrect.'
            ], 404);
        }

        if ($e instanceof TenantException) {
            return response()->json([
                'message' => 'Tenant is not found or is invalid. Contact development team: filipp-tts@outlook.com.'
            ], 500);
        }

        return response()->json([
            'message' => 'Server error. Contact development team: filipp-tts@outlook.com.',
            'error' => parent::render($request, $e)
        ], 500);
    }
}
