<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_it_renders_a_valid_sitemap_with_every_post()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'O sitemap não é um XML válido');

        // Uma <url> para a home e uma para cada post
        $this->assertCount(Post::all()->count() + 1, $xml->url);
    }

    public function test_compiled_view_is_valid_php_with_short_open_tag_enabled()
    {
        // Em produção não há php.ini e short_open_tag fica ligado; um "<?xml"
        // literal na view vira código PHP e quebra a página com ParseError.
        $compiled = tempnam(sys_get_temp_dir(), 'sitemap');
        File::put($compiled, Blade::compileString(File::get(resource_path('views/sitemap.blade.php'))));

        exec(escapeshellarg(PHP_BINARY).' -d short_open_tag=1 -l '.escapeshellarg($compiled).' 2>&1', $output, $exitCode);
        File::delete($compiled);

        $this->assertSame(0, $exitCode, implode("\n", $output));
    }
}
