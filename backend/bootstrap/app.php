<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful; // 👈 adicionado

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // 👇 se ainda for criar o api.php, deixe essa linha aqui ou crie o arquivo manualmente depois
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 👇 adicionando o grupo "api" com o middleware do Sanctum
        $middleware->group('api', [
            EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
