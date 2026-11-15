<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;

/** Testimonials (approve/hide/reorder/filter/photo) and page sections (Quill HTML, page_key redirects). */
class AdminContentTestimonialsPagesTest extends AdminContentCase
{
    private const URL = '/admin/manage-testimonials';

    public function test_testimonial_create_update_delete(): void
    {
        $this->admin()->post(self::URL, ['id' => 0, 'name' => 'A', 'quote' => ''])->assertRedirect(self::URL);
        $this->assertSame(0, DB::table('testimonials')->count());
        $this->admin()->get(self::URL)->assertSee('Name and quote are required.', false);

        $this->admin()->post(self::URL, ['id' => 0, 'name' => 'Amy', 'quote' => 'Great', 'category' => 'alumni', 'role' => 'MBBS', 'location' => 'Beijing',
            'is_published' => 'on', 'photo' => $this->png()])->assertRedirect(self::URL);
        $row = (array) DB::table('testimonials')->first();
        $this->track($row['photo']);
        $this->assertSame('alumni', $row['category']);
        $this->admin()->get(self::URL)->assertSee('Testimonial added.', false)->assertSee('MBBS · Beijing', false)->assertSee('Alumni', false);

        $this->admin()->post(self::URL, ['id' => $row['id'], 'name' => 'Amy', 'quote' => 'Better', 'category' => 'nonsense'])->assertRedirect(self::URL);
        $upd = $this->row('testimonials', $row['id']);
        $this->assertSame('student', $upd['category']);
        $this->assertSame('Better', $upd['quote']);
        $this->assertSame(0, (int) $upd['is_published']);
        $this->assertSame($row['photo'], $upd['photo']);

        $this->admin()->post(self::URL, ['_do' => 'delete', 'id' => $row['id']])->assertRedirect(self::URL);
        $this->assertSame(0, DB::table('testimonials')->count());
        $this->assertFileDoesNotExist(public_path($row['photo']));
        $this->admin()->get(self::URL)->assertSee('Testimonial deleted.', false);
    }

    public function test_default_avatar_is_never_unlinked(): void
    {
        $id = DB::table('testimonials')->insertGetId(['name' => 'N', 'quote' => 'Q', 'photo' => 'images/avatar.svg']);
        $this->admin()->post(self::URL, ['_do' => 'delete', 'id' => $id]);
        $this->assertSame(0, DB::table('testimonials')->count());
        $this->assertFileExists(public_path('images/avatar.svg')); // shared default avatar survives
    }

    public function test_approve_hide_filter_and_counts(): void
    {
        $a = DB::table('testimonials')->insertGetId(['name' => 'Pending One', 'quote' => 'q', 'is_published' => 0, 'sort_order' => 1]);
        $b = DB::table('testimonials')->insertGetId(['name' => 'Live One', 'quote' => 'q', 'is_published' => 1, 'sort_order' => 2]);

        $this->admin()->get(self::URL . '?filter=pending')->assertSee('Pending One')->assertDontSee('Live One');
        $this->admin()->get(self::URL . '?filter=published')->assertSee('Live One')->assertDontSee('Pending One');
        $this->admin()->get(self::URL . '?filter=bogus')->assertSee('Live One')->assertSee('Pending One');
        $this->admin()->get(self::URL)->assertSee('/submit-testimonial', false)->assertSee('Total submissions');

        $this->admin()->post(self::URL, ['_do' => 'approve', 'id' => $a])->assertRedirect(self::URL);
        $this->assertSame(1, (int) $this->row('testimonials', $a)['is_published']);
        $this->admin()->get(self::URL)->assertSee('Testimonial approved — it is now live on the website.', false);

        $this->admin()->post(self::URL, ['_do' => 'hide', 'id' => $b])->assertRedirect(self::URL);
        $this->assertSame(0, (int) $this->row('testimonials', $b)['is_published']);
        $this->admin()->get(self::URL)->assertSee('Testimonial hidden — no longer shown on the website.', false);
    }

    public function test_reorder_returns_json_and_saves_positions(): void
    {
        $ids = [];
        foreach (['x', 'y', 'z'] as $i => $n) {
            $ids[] = DB::table('testimonials')->insertGetId(['name' => $n, 'quote' => 'q', 'sort_order' => $i]);
        }
        $r = $this->admin()->post(self::URL, ['_do' => 'reorder', 'ids' => [$ids[2], $ids[0], $ids[1]]]);
        $r->assertOk()->assertExactJson(['ok' => true]);
        $this->assertSame(10, (int) $this->row('testimonials', $ids[2])['sort_order']);
        $this->assertSame(20, (int) $this->row('testimonials', $ids[0])['sort_order']);
        $this->assertSame(30, (int) $this->row('testimonials', $ids[1])['sort_order']);
        // no redirect / flash for the AJAX call
        $this->admin()->post(self::URL, ['_do' => 'reorder'])->assertOk()->assertExactJson(['ok' => true]);
        $this->admin()->get(self::URL)->assertSeeInOrder(['>z<', '>x<', '>y<'], false);
    }

    public function test_testimonials_guest_redirect(): void
    {
        $this->post(self::URL, ['_do' => 'reorder', 'ids' => [1]])->assertRedirect('/login');
    }

    // ---- page sections ----

    public function test_page_sections_crud_and_redirects(): void
    {
        $url = '/admin/manage-pages';
        $this->admin()->get($url)->assertOk()->assertSee('No sections for this page yet.', false);

        $this->admin()->post($url, ['id' => 0, 'page_key' => 'fees', 'heading' => 'Fees', 'section_key' => 'intro', 'body' => '<p>Hello <b>you</b></p>', 'sort_order' => '1', 'is_published' => 'on'])
            ->assertRedirect($url . '?page=fees');
        $row = (array) DB::table('page_sections')->first();
        $this->assertSame('fees', $row['page_key']);
        $this->assertSame('<p>Hello <b>you</b></p>', $row['body']);
        $this->admin()->get($url . '?page=fees')->assertSee('Section added.', false)->assertSee('intro');
        $this->admin()->get($url . '?page=about')->assertSee('No sections for this page yet.', false);
        $this->admin()->get($url . '?page=nope')->assertSee('No sections for this page yet.', false); // falls back to About

        // edit form uses the stored page_key and raw body in the editor
        $this->admin()->get($url . '?action=edit&id=' . $row['id'])->assertSee('Edit Section — Fees', false)
            ->assertSee('<p>Hello <b>you</b></p>', false)->assertSee('quill.js', false);
        $this->admin()->get($url . '?action=new&page=mbbs')->assertSee('Add Section — MBBS Program', false)->assertSee('name="page_key" value="mbbs"', false);

        // update: page_key not changed, section saved
        $this->admin()->post($url, ['id' => $row['id'], 'page_key' => 'fees', 'heading' => 'New', 'section_key' => 'intro2', 'body' => '<p>x</p>'])
            ->assertRedirect($url . '?page=fees');
        $upd = $this->row('page_sections', $row['id']);
        $this->assertSame('intro2', $upd['section_key']);
        $this->assertSame(0, (int) $upd['is_published']);
        $this->admin()->get($url . '?page=fees')->assertSee('Section saved.', false);

        $this->admin()->post($url, ['_do' => 'delete', 'id' => $row['id'], 'page_key' => 'fees'])->assertRedirect($url . '?page=fees');
        $this->assertSame(0, DB::table('page_sections')->count());
        $this->admin()->get($url . '?page=fees')->assertSee('Section deleted.', false);
    }

    public function test_page_section_blank_key_is_generated_and_duplicate_is_reported(): void
    {
        $url = '/admin/manage-pages';
        $this->admin()->post($url, ['id' => 0, 'page_key' => 'about', 'heading' => 'H', 'body' => 'b']);
        $key = DB::table('page_sections')->value('section_key');
        $this->assertMatchesRegularExpression('/^section-[0-9a-f]{6}$/', $key);

        $this->admin()->post($url, ['id' => 0, 'page_key' => 'about', 'section_key' => $key, 'body' => 'dup'])->assertRedirect($url . '?page=about');
        $this->assertSame(1, DB::table('page_sections')->count());
        $this->admin()->get($url . '?page=about')->assertSee('Database error. Please try again.', false);
    }

    public function test_page_sections_guest_redirect(): void
    {
        $this->get('/admin/manage-pages')->assertRedirect('/login');
        $this->post('/admin/manage-pages', ['body' => 'x'])->assertRedirect('/login');
    }
}
