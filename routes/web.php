<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show')->where('slug', '[a-z0-9\-]+');
Route::get('/posts/{slug}/raw', [PostController::class, 'raw'])->name('posts.raw')->where('slug', '[a-z0-9\-]+');

Route::get('/sitemap.xml', function () {
    $posts = Post::all();
    return response()->view('sitemap', compact('posts'))->header('Content-Type', 'text/xml');
});
