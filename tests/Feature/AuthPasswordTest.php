<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SiteMailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthPasswordTest extends TestCase
{
    use RefreshDatabase;

    private SiteMailer $mailer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailer = new SiteMailer();
        $this->app->instance(SiteMailer::class, $this->mailer);
    }

    public function test_forgot_password_json(): void
    {
        $this->get('/forgot_password')->assertExactJson(['success' => false, 'message' => 'Invalid request method.']);
        $this->post('/forgot_password', ['email' => ''])->assertExactJson(['success' => false, 'message' => '❌ Please enter an email address.']);

        $this->post('/forgot_password', ['email' => 'ghost@example.com'])
            ->assertExactJson(['success' => true, 'message' => '✅ If the email exists, a reset link has been sent.']);
        $this->assertCount(0, $this->mailer->outbox);

        User::create(['full_name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'is_verified' => 1]);
        $this->post('/forgot_password', ['email' => 'a@example.com'])
            ->assertExactJson(['success' => true, 'message' => '✅ Password reset link has been sent to your email. Please check your inbox.']);
        $this->assertCount(1, $this->mailer->outbox);
        preg_match('/token=([a-f0-9]{64})/', $this->mailer->outbox[0]['html'], $m);
        $this->assertSame(hash('sha256', $m[1]), DB::table('password_resets')->where('email', 'a@example.com')->value('token'));

        // asking again replaces the previous token
        $this->post('/forgot_password', ['email' => 'a@example.com']);
        $this->assertSame(1, DB::table('password_resets')->count());
    }

    public function test_forgot_password_mail_failure_message(): void
    {
        User::create(['full_name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'is_verified' => 1]);
        $this->app->instance(SiteMailer::class, new class extends SiteMailer {
            public function sendPasswordResetEmail(string $email, string $name, string $token): bool { return false; }
        });
        $this->post('/forgot_password', ['email' => 'a@example.com'])
            ->assertExactJson(['success' => false, 'message' => '❌ Failed to send reset email. Please try again later or contact support.']);
    }

    private function seedReset(?string $created = null): string
    {
        $token = bin2hex(random_bytes(32));
        User::create(['full_name' => 'A', 'email' => 'a@example.com', 'password' => password_hash('Oldpass#1', PASSWORD_DEFAULT), 'is_verified' => 1]);
        DB::table('password_resets')->insert(['email' => 'a@example.com', 'token' => hash('sha256', $token), 'created_at' => $created ?? now()->format('Y-m-d H:i:s')]);
        return $token;
    }

    public function test_reset_password_invalid_tokens(): void
    {
        $this->get('/reset_password')->assertSee('Invalid or expired token.', false);
        $this->get('/reset_password?token=nothex')->assertSee('Invalid or expired token.', false);
        $this->get('/reset_password?token=' . str_repeat('a', 64))->assertSee('Invalid or expired token.', false);
    }

    public function test_reset_password_expired_token_is_deleted(): void
    {
        $t = $this->seedReset(now()->subHours(2)->format('Y-m-d H:i:s'));
        $this->get('/reset_password?token=' . $t)->assertSee('Invalid or expired token.', false);
        $this->assertSame(0, DB::table('password_resets')->count());
    }

    public function test_reset_password_flow(): void
    {
        $t = $this->seedReset();
        $this->get('/reset_password?token=' . $t)->assertOk()->assertSee('Reset Your Password')->assertSee('name="_token"', false);

        $this->post('/reset_password?token=' . $t, ['password' => 'abcdefgh', 'confirm_password' => 'different'])
            ->assertSee('❌ Passwords do not match.');
        $this->post('/reset_password?token=' . $t, ['password' => 'short', 'confirm_password' => 'short'])
            ->assertSee('❌ Password must be at least 8 characters.');

        $this->post('/reset_password?token=' . $t, ['password' => 'Newpass#99', 'confirm_password' => 'Newpass#99'])
            ->assertSee('Password reset successfully!')->assertSee("<a href='login'>Login Now</a>", false);
        $this->assertTrue(password_verify('Newpass#99', User::first()->password));
        $this->assertSame(0, DB::table('password_resets')->count());
        $this->get('/reset_password?token=' . $t)->assertSee('Invalid or expired token.', false);
    }
}
