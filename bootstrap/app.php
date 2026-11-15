<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // NOTE: alias() REPLACES the whole map, so all aliases must live in this single call.
        $middleware->alias([
            'loggedin' => \App\Http\Middleware\EnsureLoggedIn::class,
            'admin'    => \App\Http\Middleware\EnsureAdmin::class,
        ]);

        // The original admin pages read raw $_POST (no trimming, '' stays ''); keep that for /admin/* forms
        // (the pages themselves trim() exactly where the originals did).
        $middleware->trimStrings(except: [fn (Request $request) => $request->is('admin/*')]);
        $middleware->convertEmptyStringsToNull(except: [fn (Request $request) => $request->is('admin/*')]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
