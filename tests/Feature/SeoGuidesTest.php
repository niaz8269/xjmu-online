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

    public function test_blogs_page_lists_every_guide_and_guides_are_blog_posts(): void
    {
        $html = $this->get('/blogs')->assertOk()->getContent();
        foreach (array_keys(config('seo_guides')) as $slug) {
            $this->assertStringContainsString('/' . $slug . '"', $html, $slug);
            $g = $this->get('/' . $slug)->assertOk()->getContent();
            $this->assertStringContainsString('BlogPosting', $g, $slug);
            $this->assertStringContainsString('/blogs', $g, $slug);
        }
    }

    public function test_each_guide_opens_with_a_quick_answer_of_40_to_70_words(): void
    {
        foreach (config('seo_guides') as $slug => $guide) {
            $this->assertNotEmpty($guide['answer'] ?? null, $slug);
            $words = str_word_count($guide['answer']);
            $this->assertTrue($words >= 40 && $words <= 70, "$slug answer has $words words");
            $this->get('/' . $slug)->assertOk()->assertSee('Quick answer')->assertSee('SpeakableSpecification', false);
        }
    }

    public function test_guides_show_no_reviewer_byline_unless_one_is_configured(): void
    {
        // The owner asked for the byline to be removed (22 Nov 2026): off by default.
        $html = $this->get('/mbbs-in-china-2027')->assertOk()->getContent();
        $this->assertStringNotContainsString('Reviewed by', $html);
        $this->assertStringNotContainsString('guide-byline', $html);
        $this->assertStringNotContainsString('"@type":"Person"', $html);
        $this->assertStringContainsString('"@type":"BlogPosting"', $html);

        config(['guide_reviewer.name' => 'Dr. Ayaz']);
        $this->assertStringContainsString('Reviewed by <strong>Dr. Ayaz</strong>', $this->get('/mbbs-in-china-2027')->getContent());
    }

    public function test_rupee_fee_table_is_on_three_guides_with_the_rate_and_date(): void
    {
        $s = require resource_path('guides/_shared.php');
        $this->assertSame(['pkr' => 41.4, 'inr' => 13.9], ['pkr' => $s['fx']['pkr'], 'inr' => $s['fx']['inr']]);
        // 49,950 x 41.4 = 2,067,930 and 49,950 x 13.9 = 694,305, shown rounded to the nearest thousand
        $this->assertSame(['First-year package', '¥49,950', 'USD 7,000', 'PKR 2,068,000', 'INR 694,000'], $s['feeLocal']['rows'][0]);
        $this->assertSame('PKR 9,510,000', $s['feeLocal']['rows'][2][3]);

        foreach (['mbbs-in-china-fees-2027', 'mbbs-in-china-for-pakistani-students', 'mbbs-in-china-for-indian-students'] as $slug) {
            $this->assertSame('fees-in-rupees', config('seo_guides.' . $slug . '.sections.1.id'), $slug);
            $this->get('/' . $slug)->assertOk()->assertSee('PKR 2,068,000')->assertSee('INR 694,000')->assertSee($s['fx']['date']);
        }
        $this->get('/mbbs-in-china-2027')->assertOk()->assertDontSee('PKR 2,068,000');
    }

    public function test_every_page_footer_links_to_each_guide(): void
    {
        $html = $this->get('/about')->assertOk()->getContent();
        foreach (array_keys(config('seo_guides')) as $slug) {
            $this->assertStringContainsString('<a href="/' . $slug . '">', $html, $slug);
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

    public function test_flagged_blog_post_gets_shorter_title_description_and_guide_links_without_touching_the_row(): void
    {
        $slug = 'advantages-of-studying-mbbs-in-china-for-international-students-complete-2026-guide';
        $title = 'Advantages of Studying MBBS in China for International Students — Complete 2026 Guide';
        Blog::query()->create(['title' => $title, 'slug' => $slug, 'excerpt' => 'x', 'content' => '<p>Body</p>',
            'status' => 'published', 'published_at' => '2026-01-01 00:00:00', 'created_at' => '2026-01-01 00:00:00']);

        $html = $this->get('/blog/' . $slug)->assertOk()
            ->assertSee('<title>Advantages of MBBS in China for International Students 2026–27 | XJMU Blog</title>', false)
            ->assertSee('Planning for 2027?', false)
            ->getContent();
        $this->assertStringContainsString('<h1>' . $title . '</h1>', $html); // visible heading unchanged
        foreach (array_keys(config('seo_guides')) as $guideSlug) {
            $this->assertStringContainsString('/' . $guideSlug . '"', $html);
        }
        $this->assertSame($title, Blog::query()->where('slug', $slug)->value('title')); // database row unchanged

        // Other posts are not touched.
        Blog::query()->create(['title' => 'Other', 'slug' => 'other-post', 'excerpt' => 'x', 'content' => '<p>Body</p>',
            'status' => 'published', 'published_at' => '2026-01-01 00:00:00', 'created_at' => '2026-01-01 00:00:00']);
        $this->get('/blog/other-post')->assertOk()->assertDontSee('Planning for 2027?', false)->assertSee('<title>Other | XJMU Blog</title>', false);
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
