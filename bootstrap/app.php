<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson() || $request->is('ai/chat'),
        );

        $exceptions->render(function (TooManyRequestsHttpException $exception, Request $request) {
            if (! $request->is('ai/chat')) {
                return null;
            }

            $retryAfter = (int) ($exception->getHeaders()['Retry-After'] ?? 60);

            return response()->json([
                'message' => "Permintaan terlalu sering. Coba lagi dalam {$retryAfter} detik.",
                'code' => 'AI_RATE_LIMITED',
                'retry_after' => $retryAfter,
            ], Response::HTTP_TOO_MANY_REQUESTS);
        });
    })->create();
