<?php

if (! function_exists('mb_split')) {
    function mb_split($pattern, $string, $limit = -1) {
        return preg_split('/'.str_replace('/', '\/', $pattern).'/u', $string, $limit);
    }
}

if (! function_exists('mb_regex_encoding')) {
    function mb_regex_encoding($encoding = null) {
        return true;
    }
}

if (! function_exists('mb_internal_encoding')) {
    function mb_internal_encoding($encoding = null) {
        return 'UTF-8';
    }
}

if (! function_exists('finfo_open')) {
    function finfo_open($options = null, $arg = null) { return new class {}; }
    function finfo_file($finfo, $filename) { return 'text/plain'; }
    function finfo_close($finfo) { return true; }
}

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
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
