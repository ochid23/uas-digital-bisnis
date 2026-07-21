<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Mendaftarkan alias middleware admin dan organizer
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'organizer' => \App\Http\Middleware\IsOrganizer::class, // <-- Tambahan baru di sini
        ]);

        // WAJIB DITAMBAHKAN AGAR WEBHOOK TIDAK DIBLOKIR:
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback', 
        ]);
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();