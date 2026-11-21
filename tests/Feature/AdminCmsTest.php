<?php

namespace Tests\Feature;

use App\Support\Cms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** Content edited in the admin panel: page titles/SEO, page FAQs, guides and applicant-portal texts. */
class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): array
    {
        $id = DB::table('users')->insertGetId([
            'full_name' => 'Boss', 'email' => 'boss' . uniqid() . '@example.com', 'password' => 'x', 'role' => 'admin', 'is_verified' => 1,
        ]);

        return ['user_id' => $id, 'role' => 'admin', 'full_name' => 'Boss', 'admin_last_activity' => time(), '_token' => 'tok'];
    }

    /** Re-run the boot step that lays the saved edits over the built-in content (a real request does this itself). */
    private function reload(): void
    {
        Cms::flush();
        $this->app['config']->set('seo_faqs', require config_path('seo_faqs.php'));
        $this->app['config']->set('seo_guides', require config_path('seo_guides.php'));
        Cms::applyToConfig();
    }

    public function test_cms_pages_are_admin_only(): void
    {
        foreach (['seo', 'page-faqs', 'guides', 'guide-edit?slug=mbbs-in-china-2027', 'portal-texts'] as $p) {
            $this->get('/admin/' . $p)->assertRedirect();
        }
        $admin = $this->admin();
        foreach (['seo', 'page-faqs', 'guides', 'guide-edit?slug=mbbs-in-china-2027', 'portal-texts'] as $p) {
            $this->withSession($admin)->get('/admin/' . $p)->assertOk();
        }
        $this->withSession($admin)->get('/admin/guide-edit?slug=nope')->assertRedirect('/admin/guides');
    }

    public function test_page_title_and_description_can_be_set_and_cleared(): void
    {
        $admin = $this->admin();
        $before = $this->get('/fees')->assertOk()->getContent();
        $this->assertStringContainsString('<title>MBBS Fees in China 2027', $before);

        // index 4 = /fees in the list of pages
        $this->withSession($admin)->post('/admin/seo', ['_token' => 'tok', 'title' => [4 => 'Fees <b>2027</b> at XJMU'],
            'description' => [4 => 'New description'], 'image' => [4 => 'javascript:alert(1)']])->assertRedirect('/admin/seo');
        Cms::flush();
        $this->get('/fees')->assertOk()->assertSee('<title>Fees &lt;b&gt;2027&lt;/b&gt; at XJMU</title>', false)
            ->assertSee('content="New description"', false)->assertDontSee('javascript:alert', false);
        $this->assertStringContainsString('<title>MBBS in China 2027', $this->get('/')->getContent());   // other pages untouched

        $this->withSession($admin)->post('/admin/seo', ['_token' => 'tok', 'title' => [4 => ''], 'description' => [4 => '']]);
        Cms::flush();
        $this->assertStringContainsString('<title>MBBS Fees in China 2027', $this->get('/fees')->getContent());
        $this->assertSame(0, DB::table('cms_overrides')->count());
    }

    public function test_page_faqs_can_be_edited_and_restored(): void
    {
        $admin = $this->admin();
        $this->withSession($admin)->post('/admin/page-faqs?page=scholarships', ['_token' => 'tok',
            'q' => ['Is there a <i>new</i> scholarship?', '', 'Only a question'], 'a' => ['Yes, ask admissions.', 'No question', '']])
            ->assertRedirect('/admin/page-faqs?page=scholarships');
        $this->reload();

        $html = $this->get('/scholarships')->assertOk()->getContent();
        $this->assertSame(1, substr_count($html, '<details class="ds-faq">'));   // incomplete rows are dropped
        $this->assertStringContainsString('Is there a &lt;i&gt;new&lt;/i&gt; scholarship?', $html);
        $this->assertSame(1, substr_count($html, '"@type":"Question"'));

        $this->withSession($admin)->post('/admin/page-faqs?page=scholarships', ['_token' => 'tok', 'action' => 'reset']);
        $this->reload();
        $this->assertGreaterThanOrEqual(8, substr_count($this->get('/scholarships')->getContent(), '<details class="ds-faq">'));
    }

    public function test_guide_text_can_be_edited_while_images_and_tables_stay(): void
    {
        $admin = $this->admin();
        $slug = 'mbbs-in-china-fees-2027';
        $original = config('seo_guides.' . $slug);

        $this->withSession($admin)->post('/admin/guide-edit?slug=' . $slug, ['_token' => 'tok',
            'title' => 'MBBS in China Fees 2027 | Edited', 'description' => $original['description'], 'h1' => $original['h1'],
            'lead' => $original['lead'], 'answer' => 'Edited quick answer about fees.', 'updated' => '2026-12-01',
            'fact_label' => ['Duration'], 'fact_value' => ['6 years'],
            'q' => ['Edited question?'], 'a' => ['Edited answer.'],
            'sec' => [0 => ['h' => 'Edited first heading', 'p' => "First new paragraph.\n\nSecond new <b>paragraph</b>."]],
        ])->assertRedirect('/admin/guide-edit?slug=' . $slug);
        $this->reload();

        $guide = config('seo_guides.' . $slug);
        $this->assertSame('Edited first heading', $guide['sections'][0]['h']);
        $this->assertSame(['First new paragraph.', 'Second new <b>paragraph</b>.'], $guide['sections'][0]['p']);
        $this->assertSame($original['sections'][0]['img'], $guide['sections'][0]['img']);      // image kept
        $this->assertSame($original['sections'][1], $guide['sections'][1]);                    // untouched section kept
        $this->assertSame([['Duration', '6 years']], $guide['facts']);

        $html = $this->get('/' . $slug)->assertOk()->getContent();
        $this->assertStringContainsString('<title>MBBS in China Fees 2027 | Edited</title>', $html);
        $this->assertStringContainsString('Edited quick answer about fees.', $html);
        $this->assertStringContainsString('Second new &lt;b&gt;paragraph&lt;/b&gt;.', $html);
        $this->assertStringContainsString('Edited question?', $html);
        $this->assertStringContainsString('Updated December 2026', $html);

        $this->withSession($admin)->post('/admin/guide-edit?slug=' . $slug, ['_token' => 'tok', 'action' => 'reset']);
        $this->reload();
        $this->assertSame($original['title'], config('seo_guides.' . $slug . '.title'));
    }

    public function test_portal_texts_and_contact_settings_show_in_the_portal(): void
    {
        $admin = $this->admin();
        $student = ['user_id' => 77, 'full_name' => 'Ann'];
        $this->withSession($student)->get('/front')->assertOk()->assertSee('Track your progress')->assertDontSee('role="status"', false);

        $this->withSession($admin)->post('/admin/portal-texts', ['_token' => 'tok', 'notice' => 'Deadline: 15 January <script>',
            'dashboard_intro' => Cms::PORTAL_DEFAULTS['dashboard_intro'], 'copyright' => '© 2027 XJMU'])->assertRedirect('/admin/portal-texts');
        Cms::flush();
        $this->assertSame(['notice', 'copyright'], array_keys(Cms::get('portal')));   // unchanged defaults are not stored

        $this->withSession($student)->get('/front')->assertOk()
            ->assertSee('Deadline: 15 January &lt;script&gt;', false)->assertSee('© 2027 XJMU');
    }

    public function test_site_works_when_the_overrides_table_is_missing(): void
    {
        \Illuminate\Support\Facades\Schema::drop('cms_overrides');
        Cms::flush();
        $this->assertSame([], Cms::all());
        $this->get('/fees')->assertOk();
        $this->get('/mbbs-in-china-2027')->assertOk();
    }

    public function test_public_pages_never_print_raw_template_code(): void
    {
        foreach (['/', '/fees', '/admissions', '/mbbs-in-china-2027', '/blogs', '/login'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertStringNotContainsString('{{', $html, $url);
            $this->assertStringNotContainsString('App' . chr(92) . 'Support', $html, $url);
            $this->assertStringNotContainsString('@verbatim', $html, $url);
        }
        // The contact phone in the search-engine data comes from Site Settings (fallback when unset).
        $this->assertStringContainsString('"telephone": "+86-176-4041-2827"', $this->get('/')->getContent());
    }
}
