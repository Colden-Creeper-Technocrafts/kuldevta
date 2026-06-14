<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureIsAdmin::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('sangh:update-status')->dailyAt('06:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
