<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SiteMailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegisterTest extends TestCase
{
    use RefreshDatabase;

    private SiteMailer $mailer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailer = new SiteMailer();
        $this->app->instance(SiteMailer::class, $this->mailer);
    }

    public function test_register_creates_unverified_user_and_sends_mail(): void
    {
        $r = $this->post('/register', ['full_name' => ' Ann ', 'email' => ' ANN@Example.com ', 'password' => 'Passw0rd!x']);
        $r->assertSee("alert('Registration successful. Please check your email to verify.');", false);
        $r->assertSee("window.location.href = 'login';", false);
        $u = User::where('email', 'ann@example.com')->first();
        $this->assertNotNull($u);
        $this->assertSame('Ann', $u->full_name);
        $this->assertTrue(password_verify('Passw0rd!x', $u->password));
        $this->assertEmpty($u->is_verified);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $u->verification_token);
        $this->assertCount(1, $this->mailer->outbox);
        $this->assertStringContainsString('/verify_email?token=' . $u->verification_token, $this->mailer->outbox[0]['html']);
    }

    public function test_password_rules_and_messages(): void
    {
        foreach ([
            ['short1!', 'Password must be at least 8 characters long'],
            ['12345678!', 'Password must contain at least one letter'],
            ['abcdefgh!', 'Password must contain at least one number'],
            ['abcdefg1', 'Password must contain at least one special character'],
        ] as [$pw, $msg]) {
            $this->post('/register', ['full_name' => 'A', 'email' => 'x@example.com', 'password' => $pw])
                ->assertSee("alert('Registration failed: $msg');", false)
                ->assertSee("window.location.href = 'register';", false);
        }
        $this->assertSame(0, User::count());
    }

    public function test_existing_verified_email_is_told_to_login(): void
    {
        User::create(['full_name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'is_verified' => 1]);
        $this->post('/register', ['full_name' => 'A', 'email' => 'a@example.com', 'password' => 'Passw0rd!x'])
            ->assertSee('This email is already registered. Please login.', false);
        $this->assertCount(0, $this->mailer->outbox);
    }

    public function test_existing_unverified_email_gets_new_token(): void
    {
        $u = User::create(['full_name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'is_verified' => 0, 'verification_token' => 'old']);
        $this->post('/register', ['full_name' => 'B', 'email' => 'a@example.com', 'password' => 'Passw0rd!x'])
            ->assertSee('Your account exists but is not verified. We sent a new verification email.', false);
        $this->assertNotSame('old', $u->fresh()->verification_token);
        $this->assertCount(1, $this->mailer->outbox);
    }

    public function test_verify_email(): void
    {
        $token = str_repeat('a1', 32);
        $u = User::create(['full_name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'is_verified' => 0, 'verification_token' => $token]);

        $this->get('/verify_email')->assertSee('Invalid or missing verification token.', false)->assertSee("'register'", false);
        $this->get('/verify_email?token=zzz')->assertSee('Invalid or missing verification token.', false);
        $this->get('/verify_email?token=' . str_repeat('b', 64))->assertSee('Invalid or expired verification token.', false);
        $this->assertEmpty($u->fresh()->is_verified);

        $this->get('/verify_email?token=' . $token)->assertSee('Email verified successfully! You can now log in.', false)->assertSee("'login'", false);
        $u->refresh();
        $this->assertSame(1, (int) $u->is_verified);
        $this->assertNull($u->verification_token);
        $this->get('/verify_email?token=' . $token)->assertSee('Invalid or expired', false); // single use
    }

    public function test_resend_code(): void
    {
        $this->get('/resend_code')->assertRedirect(get_absolute_url('register'));
        $this->post('/resend_code', ['email' => ''])->assertSee('Please enter an email address.', false);

        $u = User::create(['full_name' => 'A', 'email' => 'a@example.com', 'password' => 'x', 'is_verified' => 0, 'verification_token' => 'old']);
        $generic = 'If an unverified account exists for that email, a new verification link has been sent.';
        $this->post('/resend_code', ['email' => 'a@example.com'])->assertSee($generic, false);
        $this->assertNotSame('old', $u->fresh()->verification_token);
        $this->assertCount(1, $this->mailer->outbox);

        // unknown / verified accounts: same message, no mail
        $this->post('/resend_code', ['email' => 'nobody@example.com'])->assertSee($generic, false);
        $u->update(['is_verified' => 1]);
        $this->post('/resend_code', ['email' => 'a@example.com'])->assertSee($generic, false);
        $this->assertCount(1, $this->mailer->outbox);
    }

    public function test_failed_mail_keeps_account_and_shows_resend_hint(): void
    {
        $this->app->instance(SiteMailer::class, new class extends SiteMailer {
            public function sendVerificationEmail(string $email, string $name, string $token): bool { return false; }
        });
        $this->post('/register', ['full_name' => 'Ann', 'email' => 'ann@example.com', 'password' => 'Passw0rd!x'])
            ->assertSee("Please use \\'Resend Verification\\' shortly.", false);
        $this->assertSame(1, User::count());
    }
}
