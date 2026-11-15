<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Page speed parity with the original site: content pages are cookie-free and cacheable; token pages are not. */
class PublicCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_pages_are_cacheable_and_set_no_cookies(): void
    {
        foreach (['/', '/mbbs', '/about', '/fees', '/contact', '/blogs', '/gallery', '/adm-HtApply', '/mbbs-in-china-2027'] as $uri) {
            $response = $this->get($uri)->assertOk();
            $this->assertStringContainsString('max-age=86400', $response->headers->get('Cache-Control'), $uri);
            $this->assertStringContainsString('public', $response->headers->get('Cache-Control'), $uri);
            $this->assertCount(0, $response->headers->getCookies(), $uri);
            $this->assertStringNotContainsString('name="_token"', $response->getContent(), $uri);
        }
    }

    public function test_pages_with_a_csrf_token_are_never_cached(): void
    {
        foreach (['/login', '/register', '/events', '/submit-testimonial'] as $uri) {
            $response = $this->get($uri)->assertOk();
            $this->assertStringNotContainsString('max-age=86400', (string) $response->headers->get('Cache-Control'), $uri);
            $this->assertNotCount(0, $response->headers->getCookies(), $uri);
        }
    }

    public function test_not_found_and_posts_are_not_cached(): void
    {
        $this->assertStringNotContainsString('max-age', (string) $this->get('/blog/missing')->assertNotFound()->headers->get('Cache-Control'));
        $this->assertStringNotContainsString('max-age', (string) $this->post('/', [])->headers->get('Cache-Control'));
    }
}
