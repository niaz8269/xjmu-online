<?php

namespace Tests\Feature;

use App\Services\SiteMailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminSettingsMailTest extends TestCase
{
    use RefreshDatabase;

    private function adminSession(): array
    {
        $id = DB::table('users')->insertGetId([
            'full_name' => 'Boss', 'email' => 'b' . uniqid() . '@example.com', 'password' => 'x', 'role' => 'admin', 'is_verified' => 1,
        ]);
        return ['user_id' => $id, 'role' => 'admin', 'full_name' => 'Boss'];
    }

    private function setting(string $key, string $type, ?string $value, string $group = 'general', int $order = 0): void
    {
        DB::table('site_settings')->insert(['setting_key' => $key, 'setting_value' => $value, 'group_name' => $group,
            'label' => ucfirst($key), 'input_type' => $type, 'sort_order' => $order]);
    }

    public function test_settings_page_groups_and_saves_with_light_validation(): void
    {
        $this->setting('site_name', 'text', 'Old name', 'general', 1);
        $this->setting('contact_email', 'email', 'old@example.com', 'contact');
        $this->setting('social_facebook', 'url', '', 'social');
        $this->setting('office_address', 'textarea', 'Addr', 'contact', 2);
        $s = $this->adminSession();

        $this->withSession($s)->get('/admin/settings')->assertOk()->assertSee('General &amp; Hero', false)->assertSee('Contact Details')
            ->assertSee('Site Settings')->assertSee('Global content shown across the public website')->assertSee('name="_token"', false)
            ->assertSee('name="site_name"', false)->assertSee('<textarea class="form-control" id="f_office_address"', false);

        Cache::put('site_settings', ['site_name' => 'stale'], 300);
        $this->withSession($s)->post('/admin/settings', [
            'site_name' => '  New name ', 'contact_email' => 'not-an-email', 'social_facebook' => 'facebook.com/xjmu', 'unknown_key' => 'ignored',
        ])->assertOk()->assertSee('Settings saved.');

        $v = DB::table('site_settings')->pluck('setting_value', 'setting_key');
        $this->assertSame('New name', $v['site_name']);
        $this->assertSame('old@example.com', $v['contact_email'], 'invalid e-mail is skipped');
        $this->assertSame('https://facebook.com/xjmu', $v['social_facebook'], 'url gets a scheme');
        $this->assertSame('Addr', $v['office_address'], 'keys that were not posted stay untouched');
        $this->assertNull(Cache::get('site_settings'), 'the public Settings cache is dropped after saving');
    }

    public function test_test_email_page_diagnostics_and_validation(): void
    {
        $s = $this->adminSession();
        $this->withSession($s)->get('/admin/test_email')->assertOk()->assertSee('Environment')->assertSee('PHPMailer loaded')
            ->assertSee('name="_token"', false)->assertSee('Send &amp; show SMTP log', false);
        $this->withSession($s)->post('/admin/test_email', ['to' => 'nope', 'type' => 'reset'])->assertSee('Invalid email address.');
    }

    public function test_test_email_never_sends_with_the_log_driver(): void
    {
        $this->assertSame('', (string) config('smtp.host'), 'tests run with the fake/log driver');
        $mailer = $this->app->make(SiteMailer::class);
        $this->app->instance(SiteMailer::class, $mailer);

        $this->withSession($this->adminSession())->post('/admin/test_email', ['to' => 'someone@example.com', 'type' => 'reset'])
            ->assertOk()->assertSee('Log driver');
        $this->assertCount(1, $mailer->outbox);
        $this->assertSame('someone@example.com', $mailer->outbox[0]['to']);
        $this->assertSame('Reset Your Password - XJMU (TEST)', $mailer->outbox[0]['subject']);

        $this->withSession($this->adminSession())->post('/admin/test_email', ['to' => 'someone@example.com', 'type' => 'verification']);
        $this->assertSame('Verify Your Email - XJMU (TEST)', $mailer->outbox[1]['subject']);
    }
}
