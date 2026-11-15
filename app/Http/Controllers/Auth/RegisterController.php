<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SiteMailer;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(private SiteMailer $mailer)
    {
    }

    public function show()
    {
        return response()->view('auth.register');
    }

    public function register(Request $request)
    {
        $full_name = trim((string) $request->input('full_name', ''));
        $email = strtolower(trim((string) $request->input('email', '')));
        $raw_password = (string) $request->input('password', '');

        $password_error = $this->validatePassword($raw_password);
        if ($password_error) {
            return $this->script('Registration failed: ' . $password_error, 'register');
        }

        $password = password_hash($raw_password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));

        $existing = User::query()->where('email', $email)->first(['id', 'full_name', 'is_verified']);
        if ($existing) {
            if ((int) $existing->is_verified === 1) {
                return $this->script('This email is already registered. Please login.', 'login');
            }

            // Not verified yet: regenerate token and resend the verification email.
            $newToken = bin2hex(random_bytes(32));
            User::query()->where('id', $existing->id)->update(['verification_token' => $newToken]);

            $targetName = ! empty($existing->full_name) ? $existing->full_name : $full_name;
            if ($this->mailer->sendVerificationEmail($email, $targetName, $newToken)) {
                return $this->script('Your account exists but is not verified. We sent a new verification email.', 'login');
            }
            error_log('Resend-on-register failed for ' . $email . '. SMTP error: ' . ($this->mailer->getLastError() ?: 'No SMTP details available'));
            return $this->script('Unable to send verification email right now. Please try again shortly or use Resend Verification.', 'register');
        }

        try {
            User::query()->insert([
                'full_name' => $full_name,
                'email' => $email,
                'password' => $password,
                'verification_token' => $token,
                'is_superadmin' => 0,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            error_log('Registration insert failed: ' . $e->getMessage());
            return response('Registration failed.');
        }

        if ($this->mailer->sendVerificationEmail($email, $full_name, $token)) {
            return $this->script('Registration successful. Please check your email to verify.', 'login');
        }
        // Account is kept as unverified when delivery fails; the user can request the email again.
        error_log('Verification email failed for user: ' . $email . ' - account kept as unverified. SMTP error: ' . ($this->mailer->getLastError() ?: 'No SMTP details available'));
        return $this->script("Your account was created, but we could not send the verification email right now. Please use 'Resend Verification' shortly.", 'login');
    }

    public function verify(Request $request)
    {
        $token = trim((string) $request->query('token', ''));

        if ($token === '' || ! preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return $this->script('❌ Invalid or missing verification token.', 'register');
        }

        $user = User::query()->where('verification_token', $token)->first(['id', 'email', 'full_name']);
        if ($user) {
            User::query()->where('id', $user->id)->update(['is_verified' => 1, 'verification_token' => null]);
            return $this->script('✅ Email verified successfully! You can now log in.', 'login');
        }

        return $this->script('❌ Invalid or expired verification token.', 'register');
    }

    public function resend(Request $request)
    {
        if (! $request->isMethod('POST')) {
            return redirect(get_absolute_url('register'));
        }

        $email = trim((string) $request->input('email', ''));
        if (empty($email)) {
            return response("<script>alert('Please enter an email address.'); window.history.back();</script>");
        }

        // Same generic message for every outcome (no account enumeration).
        $user = User::query()->where('email', $email)->first(['id', 'full_name', 'is_verified']);
        if ($user && (int) $user->is_verified !== 1) {
            $newToken = bin2hex(random_bytes(32));
            if (User::query()->where('email', $email)->update(['verification_token' => $newToken]) !== false) {
                $this->mailer->sendVerificationEmail($email, (string) $user->full_name, $newToken);
            }
        }

        return $this->script('If an unverified account exists for that email, a new verification link has been sent.', 'login');
    }

    private function validatePassword(string $password): ?string
    {
        if (strlen($password) < 8) {
            return 'Password must be at least 8 characters long';
        }
        if (! preg_match('/[a-zA-Z]/', $password)) {
            return 'Password must contain at least one letter';
        }
        if (! preg_match('/[0-9]/', $password)) {
            return 'Password must contain at least one number';
        }
        if (! preg_match('/[!@#$%^&*()\[\]{}\-_=+|;:\'",.<?\/\\\\`~]/', $password)) {
            return 'Password must contain at least one special character';
        }
        return null;
    }

    /** Emits the same alert()+redirect script the original echoed (message is JS-escaped here). */
    private function script(string $message, string $to)
    {
        return response("<script>\n                alert('" . addslashes($message) . "');\n                window.location.href = '" . $to . "';\n              </script>");
    }
}
