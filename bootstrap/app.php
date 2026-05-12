<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// FIX RAILWAY TEMP DIRECTORY ISSUE - MUST COME AFTER USE STATEMENTS
$tempDir = '/tmp/laravel';
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}
putenv('TMPDIR=' . $tempDir);
ini_set('upload_tmp_dir', $tempDir);
ini_set('session.save_path', $tempDir . '/sessions');
ini_set('sys_temp_dir', $tempDir);

// Create necessary directories
$dirs = [
    $tempDir . '/views',
    $tempDir . '/sessions',
    $tempDir . '/cache',
    storage_path('logs'),
    storage_path('framework/cache'),
    storage_path('framework/sessions'),
    storage_path('framework/views'),
    base_path('bootstrap/cache'),
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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();