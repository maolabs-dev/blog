<?php

namespace App\Http\Controllers;



use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::all();

        if ($request->has('search')) {
            $search = strtolower($request->get('search'));
            $posts = $posts->filter(function ($post) use ($search) {
                return str_contains(strtolower($post->title), $search) ||
                       str_contains(strtolower($post->body), $search) ||
                       collect($post->tags)->contains(fn($tag) => str_contains(strtolower($tag), $search));
            });
        }

        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        return view('posts.show', [
            'post' => Post::find($slug)
        ]);
    }

    public function raw($slug)
    {
        $post = Post::find($slug);
        
        return response($post->rawBody)
            ->header('Content-Type', 'text/markdown; charset=UTF-8');
    }
}
