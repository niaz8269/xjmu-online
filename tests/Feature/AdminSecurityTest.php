<?php

namespace Tests\Feature;

use App\Support\Security\Audit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    private array $files = [];

    protected function tearDown(): void
    {
        foreach ($this->files as $f) {
            @unlink($f);
        }
        @unlink(Audit::path());
        parent::tearDown();
    }

    private function adminSession(string $role = 'admin'): array
    {
        $id = DB::table('users')->insertGetId([
            'full_name' => 'Boss', 'email' => 'b' . uniqid() . '@example.com', 'password' => password_hash('Correct-Horse-1', PASSWORD_DEFAULT),
            'role' => $role, 'is_verified' => 1,
        ]);
        return ['user_id' => $id, 'role' => 'admin', 'full_name' => 'Boss', 'admin_last_activity' => time()];
    }

    private function privateFile(): string
    {
        $dir = public_path('uploads/admissions');
        @mkdir($dir, 0755, true);
        $name = 'test_' . uniqid() . '.pdf';
        file_put_contents($dir . '/' . $name, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF\n");
        $this->files[] = $dir . '/' . $name;
        return 'uploads/admissions/' . $name;
    }

    public function test_documents_open_only_for_a_signed_in_admin(): void
    {
        $rel = $this->privateFile();
        $this->get('/admin/document?p=' . urlencode($rel))->assertRedirect('/login');
        $this->withSession(['user_id' => 5, 'role' => 'user'])->get('/admin/document?p=' . urlencode($rel))->assertRedirect('/login');
        $this->withSession($this->adminSession())->get('/admin/document?p=' . urlencode($rel))
            ->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_document_viewer_refuses_paths_outside_the_private_folders(): void
    {
        $s = $this->adminSession();
        foreach (['../.env', 'uploads/admissions/../../.env', '/etc/passwd', 'uploads/blogs/x.jpg', 'composer.json', '', "uploads/admissions/a\0.pdf"] as $bad) {
            $this->withSession($s)->get('/admin/document?p=' . urlencode($bad))->assertNotFound();
        }
    }

    public function test_demoted_admin_loses_access_on_the_next_request(): void
    {
        $s = $this->adminSession('user'); // session still says admin, database says user
        $this->withSession($s)->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_idle_admin_session_expires(): void
    {
        $s = $this->adminSession();
        $s['admin_last_activity'] = time() - 3600;
        $this->withSession($s)->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_admin_pages_are_never_cached(): void
    {
        $r = $this->withSession($this->adminSession())->get('/admin/dashboard');
        $r->assertOk();
        $this->assertStringContainsString('no-store', (string) $r->headers->get('Cache-Control'));
    }

    public function test_login_locks_after_repeated_failures(): void
    {
        RateLimiter::clear('login:' . sha1('victim@example.com|127.0.0.1'));
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'victim@example.com', 'password' => 'wrong' . $i])->assertSee('Incorrect email or password', false);
        }
        $this->post('/login', ['email' => 'victim@example.com', 'password' => 'wrong-again'])->assertSee('Too many sign-in attempts', false);
        $events = array_column(Audit::recent(), 'event');
        $this->assertContains('login.failed', $events);
        $this->assertContains('login.locked', $events);
    }

    public function test_admin_changes_and_activity_page_are_recorded(): void
    {
        $s = $this->adminSession();
        $this->withSession($s)->post('/admin/manage-faqs', ['_do' => 'delete', 'id' => 99]);
        $this->assertContains('admin.POST', array_column(Audit::recent(), 'event'));
        $this->withSession($s)->get('/admin/activity')->assertOk()->assertSee('admin.POST');
    }

    public function test_applications_list_filters_by_status_and_exports_csv_for_admins_only(): void
    {
        $a = DB::table('users')->insertGetId(['full_name' => '=cmd Ann', 'email' => 'ann@example.test', 'password' => 'x', 'role' => 'user', 'created_at' => now()]);
        $b = DB::table('users')->insertGetId(['full_name' => 'Bob', 'email' => 'bob@example.test', 'password' => 'x', 'role' => 'user', 'created_at' => now()]);
        DB::table('applications')->insert(['user_id' => $a, 'degree_applying_for' => "Bachelor's Degree", 'major_applying_for' => 'MBBS', 'is_submitted' => 1, 'status' => 'Approved']);
        DB::table('applications')->insert(['user_id' => $b, 'degree_applying_for' => "Master's Degree", 'major_applying_for' => 'Nursing', 'is_submitted' => 1, 'status' => 'Pending']);

        $this->get('/admin/applications?export=csv')->assertRedirect();   // signed out

        $html = $this->withSession($this->adminSession())->get('/admin/applications?status=Approved')->assertOk()->getContent();
        $this->assertStringContainsString('ann@example.test', $html);
        $this->assertStringNotContainsString('bob@example.test', $html);

        $csv = $this->withSession($this->adminSession())->get('/admin/applications?export=csv&status=Pending')->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')->getContent();
        $this->assertStringContainsString('"Bob","bob@example.test"', $csv);
        $this->assertStringNotContainsString('ann@example.test', $csv);

        // A name starting with "=" must not become an Excel formula.
        $all = $this->withSession($this->adminSession())->get('/admin/applications?export=csv')->getContent();
        $this->assertStringContainsString('"\'=cmd Ann"', $all);

        $users = $this->withSession($this->adminSession())->get('/admin/users?export=csv')->assertOk()->getContent();
        $this->assertStringContainsString('bob@example.test', $users);
    }

    public function test_manage_page_sets_status_only_from_the_fixed_list(): void
    {
        $u = DB::table('users')->insertGetId(['full_name' => 'Cy', 'email' => 'cy@example.test', 'password' => 'x', 'role' => 'user', 'created_at' => now()]);
        DB::table('applications')->insert(['user_id' => $u, 'is_submitted' => 1, 'status' => 'Pending']);
        $post = fn (array $d) => $this->withSession($this->adminSession() + ['_token' => 'tok'])->post('/admin/manage-applications', ['_token' => 'tok', 'user_id' => $u] + $d);

        $post(['status' => 'Please upload your passport', 'app_status' => 'Approved'])->assertOk();
        $this->assertSame('Approved', DB::table('applications')->where('user_id', $u)->value('status'));
        $this->assertSame('Please upload your passport', DB::table('applications')->where('user_id', $u)->value('admin_remark'));

        $post(['status' => 'x', 'app_status' => 'Hacked'])->assertOk();
        $this->assertSame('Approved', DB::table('applications')->where('user_id', $u)->value('status'));   // unchanged
    }
}
