<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** SEO: old .php URLs 301 to the clean URL (like the original .htaccess); processing endpoints are left alone. */
class CanonicalUrlsTest extends TestCase
{
    use RefreshDatabase;

    public function test_php_urls_redirect_permanently_to_clean_urls_keeping_the_query(): void
    {
        $this->get('/about.php')->assertStatus(301)->assertRedirect(url('/about'));
        $this->get('/blog-post.php?slug=abc')->assertStatus(301)->assertRedirect(url('/blog-post?slug=abc'));
        $this->get('/blogs.php?search=mbbs&page=2')->assertStatus(301)->assertRedirect(url('/blogs?search=mbbs&page=2'));
    }

    public function test_processing_endpoints_are_not_redirected(): void
    {
        foreach (['/get_personal.php', '/save_data.php', '/send_contact_email.php', '/download_application.php'] as $uri) {
            $this->assertNotSame(301, $this->get($uri)->getStatusCode(), $uri);
        }
    }

    public function test_international_aliases_go_to_admissions(): void
    {
        foreach (['/international', '/international.php', '/international.html'] as $uri) {
            $this->get($uri)->assertStatus(301)->assertRedirect(url('/admissions'));
        }
    }

    public function test_post_requests_are_not_redirected(): void
    {
        $this->assertNotSame(301, $this->post('/register-event.php', [])->getStatusCode());
        $this->assertNotSame(301, $this->post('/login.php', [])->getStatusCode());
    }
}
