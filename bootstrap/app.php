<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Create temp directories for views and cache
$tempDir = PHP_OS_FAMILY === 'Windows'
    ? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'laravel'
    : '/tmp/laravel';

if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

$dirs = [
    $tempDir . DIRECTORY_SEPARATOR . 'views',
    $tempDir . DIRECTORY_SEPARATOR . 'cache',
    dirname(__DIR__) . '/storage/logs',
    dirname(__DIR__) . '/storage/framework/cache',
    dirname(__DIR__) . '/storage/framework/sessions',
    dirname(__DIR__) . '/storage/framework/views',
    dirname(__DIR__) . '/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

putenv('TMPDIR=' . $tempDir);
ini_set('upload_tmp_dir', $tempDir);
ini_set('sys_temp_dir', $tempDir);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/login',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();