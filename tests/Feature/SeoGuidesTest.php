<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** SEO: keyword landing pages, clean blog addresses and their sitemap entries. */
class SeoGuidesTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_guide_renders_with_one_h1_canonical_and_faq_schema(): void
    {
        foreach (config('seo_guides') as $slug => $guide) {
            $html = $this->get('/' . $slug)->assertOk()
                ->assertSee('<link rel="canonical" href="https://xjmu.online/' . $slug . '"', false)
                ->assertSee('FAQPage', false)
                ->assertSee('BreadcrumbList', false)
                ->getContent();
            $this->assertSame(1, substr_count($html, '<h1'), $slug);
            $this->assertLessThanOrEqual(70, mb_strlen($guide['title']), $slug . ' title length');
            $this->assertLessThanOrEqual(170, mb_strlen($guide['description']), $slug . ' description length');
        }
    }

    public function test_each_guide_has_at_least_3000_words_and_images_with_alt_text(): void
    {
        foreach (config('seo_guides') as $slug => $guide) {
            $text = [$guide['h1'], $guide['lead']];
            array_walk_recursive($guide['sections'], function ($v, $k) use (&$text) {
                if (is_string($v) && ! in_array($k, ['src', 'id'], true)) {
                    $text[] = $v;
                }
            });
            array_walk_recursive($guide['faqs'], function ($v) use (&$text) {
                $text[] = $v;
            });
            $this->assertGreaterThanOrEqual(3000, str_word_count(implode(' ', $text)), $slug . ' word count');

            $images = array_values(array_filter(array_column($guide['sections'], 'img')));
            $this->assertGreaterThanOrEqual(4, count($images), $slug . ' image count');
            foreach ($images as $img) {
                $this->assertFileExists(public_path($img['src']), $slug);
                $this->assertNotSame('', trim($img['alt']), $slug);
            }
        }
    }

    public function test_moe_list_has_43_universities_totalling_2790_seats(): void
    {
        $rows = collect(config('seo_guides.moe-listed-mbbs-universities-in-china.sections'))
            ->firstWhere('eyebrow', 'Full list')['table']['rows'];
        $universities = array_filter($rows, fn ($r) => $r[0] !== '');
        $this->assertCount(43, $universities);
        $this->assertSame(2790, array_sum(array_map(fn ($r) => (int) $r[2], $universities)));
    }

    public function test_old_blog_address_redirects_permanently_to_the_clean_one(): void
    {
        Blog::query()->create(['title' => 'Hello', 'slug' => 'hello-World-2026', 'excerpt' => 'x', 'content' => '<p>Body</p>',
            'status' => 'published', 'published_at' => '2026-01-01 00:00:00', 'created_at' => '2026-01-01 00:00:00']);

        $this->get('/blog-post?slug=hello-World-2026')->assertStatus(301)->assertRedirect('/blog/hello-World-2026');
        $this->get('/blog/hello-World-2026')->assertOk()
            ->assertSee('/blog/hello-World-2026"', false);
        $this->get('/blog/missing-post')->assertNotFound();
        $this->get('/blog-post?slug=missing-post')->assertNotFound();
    }

    public function test_sitemap_lists_guides_and_clean_blog_addresses(): void
    {
        Blog::query()->create(['title' => 'Hello', 'slug' => 'hello', 'excerpt' => 'x', 'content' => 'y',
            'status' => 'published', 'published_at' => '2026-01-01 00:00:00', 'created_at' => '2026-01-01 00:00:00']);

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('<loc>https://xjmu.online/mbbs-in-china-2027</loc>', $xml);
        $this->assertStringContainsString('<loc>https://xjmu.online/blog/hello</loc>', $xml);
        $this->assertStringNotContainsString('blog-post?slug=', $xml);
    }

    public function test_old_addresses_from_search_console_redirect_permanently(): void
    {
        foreach (config('legacy_redirects.paths') as $from => $to) {
            $this->get($from)->assertStatus(301)->assertRedirect(url($to));
        }
        $this->get('/downloads/brochures/prospectus-2025.pdf')->assertStatus(301)->assertRedirect(url('/downloads'));
        $this->get('/blog-post?slug=complete-list-of-45-moe-listed-universities-in-china-for-mbbs-2026-2027-xjmu-online')
            ->assertStatus(301)->assertRedirect(url('/moe-listed-mbbs-universities-in-china'));
        $this->get('/blog-post?slug=expat-eyes-a-ghanaian-student-s-endearing-memories-of-xinjiang')->assertStatus(301)->assertRedirect(url('/blogs'));
        $this->get('/blog-post?slug=something-that-never-existed')->assertNotFound();
    }
}
