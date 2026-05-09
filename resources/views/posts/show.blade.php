@extends('layouts.app')

@section('title', $post->title . ' — Maolabs Blog')
@section('meta_description', $post->excerpt)

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "description": "{{ $post->excerpt }}",
  "author": {
    "@@type": "Person",
    "name": "Maolabs"
  },
  "datePublished": "{{ $post->date }}",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ url()->current() }}"
  }
}
</script>
@endsection

@section('content')
    <article>
        <header class="post-header">
            <div class="meta">
                <time datetime="{{ $post->date }}">{{ \Carbon\Carbon::parse($post->date)->format('d/m/Y') }}</time>
                <span aria-hidden="true">&bull;</span>
                <div class="tags" aria-label="Tags">
                    @foreach ($post->tags as $tag)
                        <span class="tag">#{{ $tag }}</span>
                    @endforeach
                </div>
            </div>

            <h1>{{ $post->title }}</h1>
        </header>

        <div class="prose">
            {!! $post->body !!}
        </div>

        <nav class="back-link" aria-label="Navegação do post">
            <a href="{{ route('posts.index') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para o início
            </a>
            <span aria-hidden="true" style="margin: 0 1rem; color: var(--text-faint)">&bull;</span>
            <a href="{{ route('posts.raw', $post->slug) }}" target="_blank" style="color: var(--text-faint); font-size: 0.875rem;">
                Ver Markdown (.md)
            </a>
        </nav>
    </article>
@endsection
