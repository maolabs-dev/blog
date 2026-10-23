<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_it_renders_a_valid_sitemap_with_every_post()
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $this->assertStringStartsWith('<?xml', $response->getContent());

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'O sitemap não é um XML válido');

        // Uma <url> para a home e uma para cada post
        $this->assertCount(Post::all()->count() + 1, $xml->url);
    }

    public function test_it_renders_with_short_open_tag_enabled()
    {
        // Em produção não há php.ini e short_open_tag fica ligado. Isso muda até a
        // compilação do Blade, então a requisição roda num PHP separado com a opção
        // ligada e com as views compiladas do zero numa pasta temporária.
        $dir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'sitemap-'.uniqid();
        File::ensureDirectoryExists($dir);

        $script = $dir.DIRECTORY_SEPARATOR.'request.php';
        File::put($script, <<<'PHP'
        <?php
        require $argv[1].'/vendor/autoload.php';
        $app = require $argv[1].'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $kernel->bootstrap();
        $app['config']->set('view.compiled', $argv[2]);
        echo $kernel->handle(Illuminate\Http\Request::create('/sitemap.xml'))->getStatusCode();
        PHP);

        exec(implode(' ', array_map('escapeshellarg', [PHP_BINARY, '-d', 'short_open_tag=1', $script, base_path(), $dir])).' 2>&1', $output, $exitCode);
        File::deleteDirectory($dir);

        $this->assertSame(0, $exitCode, implode("\n", $output));
        $this->assertSame('200', trim(implode('', $output)), implode("\n", $output));
    }
}
