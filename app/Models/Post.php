<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post
{
    public function __construct(
        public string $title,
        public string $slug,
        public string $date,
        public string $excerpt,
        public array $tags,
        public bool $published,
        public string $body,
        public string $rawBody
    ) {}

    public static function all()
    {
        $path = base_path('content/posts');
        
        if (!File::isDirectory($path)) {
            return collect();
        }

        return collect(File::files($path))
            ->filter(fn($file) => $file->getExtension() === 'md')
            ->map(function ($file) {
                return static::parse($file);
            })
            ->filter(fn($post) => $post->published)
            ->sortByDesc('date');
    }

    public static function find(string $slug)
    {
        $post = static::all()->firstWhere('slug', $slug);

        if (! $post) {
            abort(404);
        }

        return $post;
    }

    public static function parse($file)
    {
        $object = YamlFrontMatter::parseFile($file);

        return new static(
            title: $object->title,
            slug: $object->slug,
            date: $object->date,
            excerpt: $object->excerpt,
            tags: $object->tags,
            published: $object->published ?? true,
            body: Str::markdown($object->body()),
            rawBody: $object->body()
        );
    }
}
