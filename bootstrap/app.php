<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Our login route is named 'auth.login', not Laravel's default 'login'
        $middleware->redirectGuestsTo(fn() => route('auth.login'));

        // Exclude SSL Commerz callback URLs from CSRF — they POST from external servers
        $middleware->validateCsrfTokens(except: [
            'payment/success',
            'payment/fail',
            'payment/cancel',
            'payment/ipn',
        ]);

        $middleware->alias([
            'manager' => \App\Http\Middleware\IsManager::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();