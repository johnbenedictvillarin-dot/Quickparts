<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// FIX TEMP DIRECTORY ISSUE - Cross-platform compatible
$tempDir = PHP_OS_FAMILY === 'Windows'
    ? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'laravel'
    : '/tmp/laravel';

if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

putenv('TMPDIR=' . $tempDir);
ini_set('upload_tmp_dir', $tempDir);

// Only set session.save_path for file-based sessions
if (env('SESSION_DRIVER', 'file') === 'file') {
    $sessionPath = $tempDir . DIRECTORY_SEPARATOR . 'sessions';
    if (!is_dir($sessionPath)) {
        mkdir($sessionPath, 0777, true);
    }
    ini_set('session.save_path', $sessionPath);
}

ini_set('sys_temp_dir', $tempDir);

$basePath = dirname(__DIR__);

// Create necessary directories
$dirs = [
    $tempDir . DIRECTORY_SEPARATOR . 'views',
    $tempDir . DIRECTORY_SEPARATOR . 'cache',
    $basePath . '/storage/logs',
    $basePath . '/storage/framework/cache',
    $basePath . '/storage/framework/sessions',
    $basePath . '/storage/framework/views',
    $basePath . '/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

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
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();