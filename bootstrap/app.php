<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\TrimStrings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->use([
            TrimStrings::class,
        ]);
        
        $middleware->api(\App\Http\Middleware\ForceJsonResponse::class);
        $middleware->api(\App\Http\Middleware\GlobalApiKey::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
