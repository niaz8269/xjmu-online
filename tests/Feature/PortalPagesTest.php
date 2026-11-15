<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;

class PortalPagesTest extends PortalCase
{
    public function test_pages_redirect_to_login_without_a_session(): void
    {
        foreach (['/front', '/edit-details', '/profile', '/download_application'] as $uri) {
            $this->get($uri)->assertRedirect(get_absolute_url('login'));
        }
    }

    public function test_front_dashboard_renders_with_escaped_name_and_status_script(): void
    {
        $r = $this->login()->get('/front');
        $r->assertOk()
            ->assertSee('Welcome, <strong>Ann &lt;Lee&gt;</strong>!', false)
            ->assertSee('fetch("get_application_status.php")', false)
            ->assertSee('Application Status | Xinjiang Medical University', false)
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        $this->login()->get('/front')->assertOk();
    }

    public function test_edit_details_prefills_degree_and_major_and_carries_the_csrf_meta(): void
    {
        Application::create(['user_id' => $this->user->id, 'degree_applying_for' => "Master's Degree", 'major_applying_for' => 'Nur"sing', 'status' => 'draft']);

        $r = $this->login()->get('/edit-details');
        $r->assertOk()
            ->assertSee('<meta name="csrf-token" content="' . self::TOKEN . '">', false)
            ->assertSee('<script src="assets/javascript/csrf.js?v=1"></script>', false)
            ->assertSee('<option value="Master\'s Degree" selected>', false)
            ->assertSee('<option value="Other">', false)
            ->assertSee('value="Nur&quot;sing"', false)
            ->assertSee('Welcome, <strong>Ann &lt;Lee&gt;</strong>!', false);
    }

    public function test_edit_details_shows_upload_messages_once(): void
    {
        $this->withSession(['user_id' => $this->user->id, 'full_name' => 'Ann', 'upload_errors' => ['Document 1 exceeds 1MB limit.'], 'upload_success' => 'ok done'])
            ->get('/edit-details')
            ->assertSee('<strong>Upload Errors:</strong>', false)
            ->assertSee('<li>Document 1 exceeds 1MB limit.</li>', false)
            ->assertSee('ok done');

        $this->get('/edit-details')->assertDontSee('Upload Errors');
    }

    public function test_profile_shows_forms_with_csrf_and_current_values(): void
    {
        $this->login()->get('/profile')->assertOk()
            ->assertSee('value="Ann &lt;Lee&gt;"', false)
            ->assertSee('value="ann@example.test"', false)
            ->assertSee('name="_token"', false)
            ->assertDontSee('alert-info');
    }

    public function test_profile_update_validates_and_saves(): void
    {
        User::create(['full_name' => 'Other', 'email' => 'other@example.test', 'password' => 'x', 'role' => 'user', 'is_verified' => 1]);

        $this->login()->post('/profile', ['_token' => self::TOKEN, 'update_profile' => '1', 'full_name' => 'N', 'email' => 'bad'])
            ->assertSee('❌ Invalid email format.');
        $this->login()->post('/profile', ['_token' => self::TOKEN, 'update_profile' => '1', 'full_name' => 'N', 'email' => 'other@example.test'])
            ->assertSee('❌ Email already in use by another account.');

        $r = $this->login()->post('/profile', ['_token' => self::TOKEN, 'update_profile' => '1', 'full_name' => 'New Name', 'email' => 'new@example.test']);
        $r->assertSee('✅ Profile updated successfully.');
        $this->assertSame('New Name', $this->user->fresh()->full_name);
        $this->assertSame('new@example.test', $this->user->fresh()->email);
        $this->assertSame('New Name', session('full_name'));
        // the original re-rendered the form with the values read before the update
        $r->assertSee('value="Ann &lt;Lee&gt;"', false);
    }

    public function test_profile_password_change_rules(): void
    {
        $post = fn (array $d) => $this->login()->post('/profile', ['_token' => self::TOKEN, 'change_password' => '1'] + $d);

        $post(['current_password' => 'nope', 'new_password' => 'Abcdefg1', 'confirm_password' => 'Abcdefg1'])->assertSee('❌ Current password is incorrect.');
        $post(['current_password' => 'Passw0rd!', 'new_password' => 'Abcdefg1', 'confirm_password' => 'Abcdefg2'])->assertSee('❌ New passwords do not match.');
        $post(['current_password' => 'Passw0rd!', 'new_password' => 'Ab1', 'confirm_password' => 'Ab1'])->assertSee('❌ Password must be at least 8 characters long');
        $post(['current_password' => 'Passw0rd!', 'new_password' => 'abcdefg1', 'confirm_password' => 'abcdefg1'])->assertSee('❌ Password must contain at least one uppercase letter');
        $post(['current_password' => 'Passw0rd!', 'new_password' => 'ABCDEFG1', 'confirm_password' => 'ABCDEFG1'])->assertSee('❌ Password must contain at least one lowercase letter');
        $post(['current_password' => 'Passw0rd!', 'new_password' => 'Abcdefgh', 'confirm_password' => 'Abcdefgh'])->assertSee('❌ Password must contain at least one number');

        // spaces are kept verbatim (no TrimStrings), as in the original
        $post(['current_password' => 'Passw0rd!', 'new_password' => ' Abcdefg1 ', 'confirm_password' => ' Abcdefg1 '])->assertSee('✅ Password changed successfully.');
        $this->assertTrue(password_verify(' Abcdefg1 ', $this->user->fresh()->password));
    }

    public function test_stub_scripts_redirect_like_the_original(): void
    {
        foreach (['applications' => '/admin/applications', 'users' => '/admin/users', 'manage-applications' => '/admin/manage-applications',
            'manage_users' => '/admin/manage_users', 'review_application' => '/admin/review_application', 'export_pdf' => '/admin/export_pdf',
            'application_status' => '/', 'mylogo' => '/'] as $from => $to) {
            $this->get('/' . $from)->assertStatus(301)->assertRedirect($to);
            $this->get('/' . $from . '.php')->assertStatus(301)->assertRedirect(preg_match('/^(?:review_|export_)/', $from) ? $to : url('/' . $from)); // canonical .php -> clean first (as the original .htaccess)
        }
    }

    public function test_dashboard_stub_sends_admins_to_the_admin_dashboard_and_everyone_else_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(get_absolute_url('login'));
        $this->login()->get('/dashboard')->assertRedirect(get_absolute_url('login'));
        $this->withSession(['user_id' => 1, 'role' => 'admin'])->get('/dashboard')->assertRedirect(url('admin/dashboard'));
    }

    public function test_how_to_apply_page_renders(): void
    {
        $r = $this->get('/adm-HtApply');
        $r->assertOk()
            ->assertSee('<title>How to Apply | MBBS Admissions | XJMU</title>', false)
            ->assertSee('Xinjiang Medical University — How to Apply')
            ->assertSee('Application Deadlines');
        $this->get('/adm-HtApply.php')->assertStatus(301)->assertRedirect(url('/adm-HtApply'));
    }
}
