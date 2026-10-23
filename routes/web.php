<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show')->where('slug', '[a-z0-9\-]+');
Route::get('/posts/{slug}/raw', [PostController::class, 'raw'])->name('posts.raw')->where('slug', '[a-z0-9\-]+');

Route::get('/sitemap.xml', function () {
    $posts = Post::all();

    // A declaração XML fica fora da view: com short_open_tag ligado (padrão sem php.ini,
    // como no FrankenPHP), qualquer "<?" num template Blade é tratado como código PHP.
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".view('sitemap', compact('posts'))->render();

    return response($xml)->header('Content-Type', 'text/xml');
});
