<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Support\CasLinks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** First mention per page of other universities, "Study in China" and "MBBS in China" links to the CAS site. */
class CasLinksTest extends TestCase
{
    use RefreshDatabase;

    private const LINK = '<a href="https://cas.moe/" target="_blank" rel="noopener">';

    public function test_each_term_is_linked_once_and_xjmu_headings_and_existing_links_are_left_alone(): void
    {
        CasLinks::reset();
        $out = CasLinks::html('<h2>MBBS in China</h2><p>MBBS in China at Zhejiang University, not <a href="/x">Jilin University</a>. '
            . 'Zhejiang University again. Xinjiang Medical University. Jilin University here.</p>');
        $this->assertStringContainsString('<h2>MBBS in China</h2>', $out);
        $this->assertStringContainsString('<p>MBBS in China at ' . self::LINK . 'Zhejiang University</a>', $out);
        $this->assertStringContainsString('<a href="/x">Jilin University</a>', $out);
        $this->assertStringContainsString('Zhejiang University again. Xinjiang Medical University. ' . self::LINK . 'Jilin University</a> here', $out);
        $this->assertSame(2, substr_count($out, self::LINK));
        $this->assertSame('', CasLinks::text(null));
        $this->assertSame('&lt;b&gt; x', CasLinks::text('<b> x'));
    }

    public function test_moe_guide_links_every_other_university_once(): void
    {
        $html = $this->get('/moe-listed-mbbs-universities-in-china')->assertOk()->getContent();
        foreach (config('cas_links.universities') as $name) {
            $this->assertSame(1, substr_count($html, self::LINK . e($name) . '</a>'), $name);
        }
        $this->assertCount(42, config('cas_links.universities'));
        $this->assertStringNotContainsString(self::LINK . 'Xinjiang Medical University', $html);
        $this->assertSame(1, substr_count($html, '<h1'));
    }

    public function test_blog_post_content_is_linked_without_changing_the_database_row(): void
    {
        $content = '<p>Study in China with us. Study in China is popular.</p>';
        Blog::query()->create(['title' => 'T', 'slug' => 'cas-post', 'excerpt' => 'x', 'content' => $content,
            'status' => 'published', 'published_at' => '2026-01-01 00:00:00', 'created_at' => '2026-01-01 00:00:00']);
        $html = $this->get('/blog/cas-post')->assertOk()->getContent();
        $this->assertStringContainsString('<p>' . self::LINK . 'Study in China</a> with us. Study in China is popular.</p>', $html);
        $this->assertSame($content, Blog::query()->where('slug', 'cas-post')->value('content'));
    }
}
