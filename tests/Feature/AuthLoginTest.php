<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $o = []): User
    {
        return User::create(array_merge([
            'full_name' => 'Test User', 'email' => 'a@example.com',
            'password' => password_hash('Secret#123', PASSWORD_DEFAULT),
            'role' => 'user', 'is_verified' => 1,
        ], $o));
    }

    public function test_login_page_renders_with_csrf_token_fields(): void
    {
        $r = $this->get('/login');
        $r->assertOk();
        $r->assertSee('name="_token"', false);
        $r->assertSee('Student Login', false);
        $this->get('/login.php')->assertOk();
        $this->get('/register')->assertOk()->assertSee('name="_token"', false);
    }

    public function test_successful_login_sets_session_keys_and_redirects_to_front(): void
    {
        $u = $this->makeUser();
        $r = $this->post('/login', ['email' => ' a@example.com ', 'password' => 'Secret#123']);
        $r->assertOk()->assertSee('window.location.replace("' . get_absolute_url('front') . '")', false);
        $r->assertSee('http-equiv="refresh"', false);
        $this->assertSame($u->id, session('user_id'));
        $this->assertSame('Test User', session('full_name'));
        $this->assertSame('user', session('role'));
    }

    public function test_admin_redirects_to_admin_dashboard(): void
    {
        $this->makeUser(['role' => 'admin']);
        $this->post('/login.php', ['email' => 'a@example.com', 'password' => 'Secret#123'])
            ->assertSee(get_absolute_url('admin/dashboard'), false);
    }

    public function test_session_id_is_regenerated_on_login(): void
    {
        $this->makeUser();
        $this->startSession();
        $before = session()->getId();
        $this->post('/login', ['email' => 'a@example.com', 'password' => 'Secret#123']);
        $this->assertNotSame($before, session()->getId());
    }

    public function test_failures_alert_and_redirect_to_login(): void
    {
        $this->makeUser();
        $this->post('/login', ['email' => '', 'password' => ''])
            ->assertSee('alert("Email and password are required");', false);
        $this->post('/login', ['email' => 'a@example.com', 'password' => 'wrong'])
            ->assertSee('alert("Incorrect email or password");window.location.href="' . get_absolute_url('login') . '";', false);
        $this->post('/login', ['email' => 'nobody@example.com', 'password' => 'x'])
            ->assertSee('Incorrect email or password', false);
        $this->assertNull(session('user_id'));
    }

    public function test_unverified_user_cannot_login(): void
    {
        $this->makeUser(['is_verified' => 0]);
        $this->post('/login', ['email' => 'a@example.com', 'password' => 'Secret#123'])
            ->assertSee('Email not verified. Please check your inbox.', false);
        $this->assertNull(session('user_id'));
    }

    public function test_existing_bcrypt_hash_is_accepted_and_not_rehashed(): void
    {
        $hash = '$2y$10$' . substr(crypt('Legacy#1', '$2y$10$' . str_repeat('a', 22)), 7);
        $u = $this->makeUser(['password' => $hash]);
        $this->post('/login', ['email' => 'a@example.com', 'password' => 'Legacy#1'])->assertSee('front', false);
        $this->assertSame($hash, $u->fresh()->password);
    }

    public function test_logout_clears_session_and_redirects(): void
    {
        $this->makeUser();
        $r = $this->withSession(['user_id' => 5, 'full_name' => 'X', 'role' => 'user'])->get('/logout');
        $r->assertSee('window.location.replace("' . get_absolute_url('login') . '")', false);
        $this->assertNull(session('user_id'));
    }

    public function test_logged_in_middleware_modes(): void
    {
        \Illuminate\Support\Facades\Route::middleware(['web', 'loggedin'])->get('/t/__redirect', fn () => 'ok');
        \Illuminate\Support\Facades\Route::middleware(['web', 'loggedin:401'])->get('/t/__401', fn () => 'ok');
        \Illuminate\Support\Facades\Route::middleware(['web', 'loggedin:json'])->get('/t/__json', fn () => 'ok');

        $this->get('/t/__redirect')->assertRedirect(get_absolute_url('login'));
        $this->get('/t/__401')->assertStatus(401)->assertExactJson(['status' => 'error', 'message' => 'Unauthorized']);
        $this->get('/t/__json')->assertOk()->assertExactJson(['status' => 'error', 'message' => 'User not logged in']);

        $s = ['user_id' => 1];
        $this->withSession($s)->get('/t/__redirect')->assertOk();
        $this->withSession($s)->get('/t/__401')->assertOk();
        $this->withSession($s)->get('/t/__json')->assertOk();
    }
}
