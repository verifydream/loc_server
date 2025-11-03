<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, $request) {
            // Handle rate limit exceeded for API requests
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many requests. Please try again later.',
                    'errors' => [],
                ], 429);
            }

            return response()->view('errors.429', [], 429);
        });

        $this->renderable(function (\Illuminate\Database\QueryException $e, $request) {
            // Log detailed error information
            \Log::error('Database error occurred', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return generic error message to user
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'A database error occurred. Please try again later.',
                    'errors' => [],
                ], 500);
            }

            return back()->with('error', 'A database error occurred. Please try again later.');
        });

        $this->renderable(function (\PDOException $e, $request) {
            // Log detailed error information
            \Log::error('Database connection error occurred', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return generic error message to user
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'A database connection error occurred. Please try again later.',
                    'errors' => [],
                ], 500);
            }

            return back()->with('error', 'A database connection error occurred. Please try again later.');
        });

        $this->renderable(function (\Throwable $e, $request) {
            // Log detailed error information for unexpected errors
            if (!$e instanceof \Illuminate\Validation\ValidationException && 
                !$e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                \Log::error('Unexpected error occurred', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });
    }
}
