<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminBlogsEventsTest extends TestCase
{
    use RefreshDatabase;

    private array $cleanup = [];

    protected function tearDown(): void
    {
        foreach ($this->cleanup as $f) {
            @unlink(public_path($f));
        }
        parent::tearDown();
    }

    private function adminSession(string $role = 'admin'): array
    {
        $id = DB::table('users')->insertGetId([
            'full_name' => 'Boss', 'email' => 'b' . uniqid() . '@example.com', 'password' => 'x', 'role' => $role, 'is_verified' => 1,
        ]);
        return ['user_id' => $id, 'role' => 'admin', 'full_name' => 'Boss'];
    }

    private function blog(array $o = []): int
    {
        return DB::table('blogs')->insertGetId($o + [
            'title' => 'T', 'slug' => 's' . uniqid(), 'excerpt' => 'e', 'content' => 'c', 'status' => 'draft', 'is_featured' => 0,
            'views' => 0, 'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function test_pages_require_admin_and_create_blog_redirects_to_edit_blog(): void
    {
        foreach (['manage-blogs', 'edit-blog', 'delete-blog', 'manage-events', 'edit-event', 'delete-event', 'settings', 'test_email'] as $p) {
            $this->get('/admin/' . $p)->assertRedirect('/login');
        }
        $this->withSession($this->adminSession())->get('/admin/create-blog')->assertRedirect('/admin/edit-blog');
        $this->withSession($this->adminSession())->get('/admin/create-blog.php')->assertRedirect('/admin/edit-blog');
    }

    public function test_manage_blogs_lists_stats_and_flash(): void
    {
        $this->blog(['title' => 'Alpha', 'status' => 'published', 'published_at' => '2026-01-30 10:00:00', 'views' => 1534]);
        $this->blog(['title' => 'Beta']);
        $r = $this->withSession($this->adminSession() + ['delete_success' => 'Blog post deleted successfully!'])->get('/admin/manage-blogs');
        $r->assertOk()->assertSee('Alpha')->assertSee('Jan 30, 2026')->assertSee('1,534')->assertSee('Not published')
            ->assertSee('Blog post deleted successfully!')->assertSee('action="delete-blog"', false)->assertSee('name="_token"', false);
    }

    public function test_create_blog_with_upload_slug_whitelist_and_redirect(): void
    {
        $s = $this->adminSession();
        $r = $this->withSession($s)->post('/admin/edit-blog', [
            'title' => ' Hello World! ', 'slug' => '', 'excerpt' => ' short ', 'content' => "  <p>raw</p>\n", 'status' => 'bogus',
            'meta_description' => 'md', 'meta_keywords' => 'k', 'is_featured' => '1',
            'featured_image' => UploadedFile::fake()->createWithContent('cover.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==')),
        ]);
        $row = DB::table('blogs')->first();
        $this->assertNotNull($row);
        $r->assertRedirect("/admin/edit-blog?id={$row->id}&success=1");
        $this->assertSame('Hello World!', $row->title);
        $this->assertSame('hello-world-', $row->slug);
        $this->assertSame('short', $row->excerpt);
        $this->assertSame("  <p>raw</p>\n", $row->content, 'content is stored untouched');
        $this->assertSame('draft', $row->status, 'unknown status is forced to draft');
        $this->assertNull($row->published_at);
        $this->assertSame(1, (int) $row->is_featured);
        $this->assertMatchesRegularExpression('#^uploads/blogs/blogs_[0-9a-f]{12}\.(webp|png)$#', $row->featured_image);
        $this->assertFileExists(public_path($row->featured_image));
        $this->cleanup[] = $row->featured_image;

        $this->withSession($s)->get("/admin/edit-blog?id={$row->id}&success=1")->assertOk()
            ->assertSee('Blog post saved successfully!')->assertSee('Edit Blog Post')->assertSee('value="Hello World!"', false);
    }

    public function test_edit_blog_keeps_published_at_and_rejects_bad_uploads_without_losing_the_save(): void
    {
        $id = $this->blog(['status' => 'published', 'published_at' => '2025-05-05 05:05:05', 'featured_image' => 'uploads/blogs/keep.webp']);
        $s = $this->adminSession();
        $r = $this->withSession($s)->post("/admin/edit-blog?id={$id}", [
            'title' => 'New', 'slug' => 'new-slug', 'excerpt' => 'x', 'content' => 'y', 'status' => 'published',
            'featured_image' => UploadedFile::fake()->create('evil.html', 3, 'text/html'),
        ]);
        $r->assertRedirect("/admin/edit-blog?id={$id}&success=1");
        $row = DB::table('blogs')->find($id);
        $this->assertSame('New', $row->title);
        $this->assertSame('2025-05-05 05:05:05', $row->published_at);
        $this->assertSame('uploads/blogs/keep.webp', $row->featured_image, 'rejected upload keeps the old image');
        $this->assertNotNull($row->updated_at);
        $this->assertSame(1, DB::table('blogs')->count());

        $this->withSession($s)->get('/admin/edit-blog?id=987654')->assertOk()->assertSee('Blog post not found');
    }

    public function test_delete_blog_is_post_only_and_removes_the_image(): void
    {
        @mkdir(public_path('uploads/blogs'), 0777, true);
        file_put_contents(public_path('uploads/blogs/zz_test_del.txt'), 'x');
        $id = $this->blog(['featured_image' => 'uploads/blogs/zz_test_del.txt']);
        $s = $this->adminSession();

        $r = $this->withSession($s)->get('/admin/delete-blog?id=' . $id);
        $r->assertStatus(403)->assertSessionHas('delete_error', 'Invalid or expired request. Please use the Delete button.');
        $this->assertStringEndsWith('/admin/manage-blogs', $r->headers->get('Location'));
        $this->assertSame(1, DB::table('blogs')->count());

        $this->withSession($s)->post('/admin/delete-blog', ['id' => $id])
            ->assertRedirect('/admin/manage-blogs')->assertSessionHas('delete_success', 'Blog post deleted successfully!');
        $this->assertSame(0, DB::table('blogs')->count());
        $this->assertFileDoesNotExist(public_path('uploads/blogs/zz_test_del.txt'));

        $this->withSession($s)->post('/admin/delete-blog', ['id' => 0])->assertRedirect('/admin/manage-blogs');
    }

    public function test_events_create_edit_list_and_delete(): void
    {
        $s = $this->adminSession();
        $this->withSession($s)->get('/admin/edit-event')->assertOk()->assertSee('Add Event')->assertSee('name="_token"', false);

        $this->withSession($s)->post('/admin/edit-event', [
            'title' => 'Open Day', 'slug' => '', 'event_date' => '2026-03-01', 'event_time_start' => '14:00', 'event_time_end' => '',
            'venue' => 'Hall', 'status' => 'published',
        ])->assertRedirect('/admin/manage-events')->assertSessionHas('event_message', 'Event created.');
        $ev = DB::table('events')->first();
        $this->assertSame('open-day', $ev->slug);
        $this->assertNull($ev->event_time_end);
        $this->assertSame('published', $ev->status);

        $this->withSession($s)->post('/admin/edit-event?id=' . $ev->id, ['title' => 'Renamed', 'slug' => 'renamed', 'event_date' => '', 'status' => 'nope'])
            ->assertRedirect('/admin/manage-events')->assertSessionHas('event_message', 'Event updated.');
        $ev = DB::table('events')->find($ev->id);
        $this->assertSame(['Renamed', 'draft', date('Y-m-d')], [$ev->title, $ev->status, $ev->event_date]);

        $this->withSession($s + ['event_message' => 'Event updated.'])->get('/admin/manage-events')->assertOk()
            ->assertSee('Renamed')->assertSee('Event updated.')->assertSee('action="delete-event"', false);
        $this->withSession($s)->get('/admin/edit-event?id=999')->assertSee('Event not found.');

        $r = $this->withSession($s)->get('/admin/delete-event?id=' . $ev->id);
        $r->assertStatus(403)->assertSessionHas('event_message', 'Invalid or expired request. Please use the Delete button.');
        $this->assertSame(1, DB::table('events')->count());
        $this->withSession($s)->post('/admin/delete-event', ['id' => $ev->id])
            ->assertRedirect('/admin/manage-events')->assertSessionHas('event_message', 'Event deleted.');
        $this->assertSame(0, DB::table('events')->count());
    }

    public function test_delete_endpoints_check_the_database_role(): void
    {
        $id = $this->blog();
        // session says admin but the users row is a plain user => 403 and nothing deleted
        $s = $this->adminSession('user');
        $this->withSession($s)->post('/admin/delete-blog', ['id' => $id])->assertStatus(403)->assertSee('Access denied. Admin privileges required.');
        $this->withSession($s)->post('/admin/delete-event', ['id' => 1])->assertStatus(403)->assertSee('Access denied.');
        $this->assertSame(1, DB::table('blogs')->count());
    }
}
