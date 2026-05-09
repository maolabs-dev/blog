<?php

namespace Tests\Feature;

use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_it_can_render_the_homepage_with_posts()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('blog.maolabs'); // Verifica o branding
    }

    public function test_it_can_render_a_single_post()
    {
        // Assume que existe o post inicial de arquitetura
        $response = $this->get('/posts/arquitetura-baixo-atrito');

        $response->assertStatus(200);
        $response->assertSee('Arquitetura de Baixo Atrito');
    }

    public function test_it_returns_404_for_non_existent_post()
    {
        $response = $this->get('/posts/post-que-nao-existe');

        $response->assertStatus(404);
    }

    public function test_it_can_render_raw_markdown()
    {
        $response = $this->get('/posts/arquitetura-baixo-atrito/raw');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
    }
}
