@extends('layouts.app')

@section('title', 'Início — Maolabs Blog')

@section('content')
    <div class="search" role="search">
        <form action="{{ route('posts.index') }}" method="GET" class="search-form" aria-label="buscar publicações">
            <label for="search-input" class="sr-only">buscar publicações</label>
            <div class="search-input-wrapper">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="search" id="search-input" name="search" value="{{ request('search') }}" placeholder="buscar publicações..." autocomplete="off">
                @if(request('search'))
                    <a href="{{ route('posts.index') }}" class="search-clear" aria-label="Limpar busca">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if ($posts->isEmpty())
        <div class="empty-state" role="status">
            <p>Nenhum post encontrado para sua busca.</p>
            <a href="{{ route('posts.index') }}">Ver todos os posts</a>
        </div>
    @endif

    <div class="posts">
        @foreach ($posts as $post)
            <article class="post-card">
                <div class="meta">
                    <time datetime="{{ $post->date }}">{{ \Carbon\Carbon::parse($post->date)->format('d/m/Y') }}</time>
                    <span aria-hidden="true">&bull;</span>
                    <div class="tags" aria-label="Tags">
                        @foreach ($post->tags as $tag)
                            <span class="tag">#{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>

                <h2><a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a></h2>

                <p class="excerpt">{{ $post->excerpt }}</p>

                <a href="{{ route('posts.show', $post->slug) }}" class="read-more" aria-label="Ler mais sobre {{ $post->title }}">
                    Ler mais
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </article>
        @endforeach
    </div>
@endsection
