<?php

// Fix temp directory for Railway
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0777, true);
}
if (!is_dir('/tmp/cache')) {
    mkdir('/tmp/cache', 0777, true);
}
if (!is_dir('/tmp/sessions')) {
    mkdir('/tmp/sessions', 0777, true);
}
putenv('TMPDIR=/tmp');
ini_set('upload_tmp_dir', '/tmp');
ini_set('session.save_path', '/tmp/sessions');

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();