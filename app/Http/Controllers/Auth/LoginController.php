<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Security\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function show()
    {
        return response()->view('auth.login');
    }

    public function login(Request $request)
    {
        $emailIn = $request->input('email');
        $passIn = $request->input('password');

        if (empty($emailIn) || empty($passIn) || ! is_string($emailIn) || ! is_string($passIn)) {
            return $this->alertRedirect('Email and password are required');
        }

        $email = trim($emailIn);

        // Brute-force guard: per email+IP, and per IP alone (one address trying many accounts).
        $pairKey = 'login:' . sha1(strtolower($email) . '|' . $request->ip());
        $ipKey = 'login-ip:' . $request->ip();
        $maxPair = (int) config('security.login_max_attempts', 5);
        $maxIp = (int) config('security.login_ip_max_attempts', 30);
        if (RateLimiter::tooManyAttempts($pairKey, $maxPair) || RateLimiter::tooManyAttempts($ipKey, $maxIp)) {
            $wait = max(RateLimiter::availableIn($pairKey), RateLimiter::availableIn($ipKey));
            Audit::record('login.locked', ['email' => mb_substr($email, 0, 120)]);
            return $this->alertRedirect('Too many sign-in attempts. Please try again in ' . max(1, (int) ceil($wait / 60)) . ' minute(s).');
        }
        $lock = (int) config('security.login_lock_minutes', 15) * 60;

        try {
            $user = User::query()->where('email', $email)->first(['id', 'full_name', 'email', 'password', 'role', 'is_verified']);
        } catch (\Throwable $e) {
            error_log('Login query error: ' . $e->getMessage());
            return $this->alertRedirect('An error occurred. Please try again later.');
        }

        // Legacy hashes come from password_hash(); verify with password_verify and never rehash.
        if (! $user || ! password_verify($passIn, (string) $user->password)) {
            RateLimiter::hit($pairKey, $lock);
            RateLimiter::hit($ipKey, $lock);
            Audit::record('login.failed', ['email' => mb_substr($email, 0, 120)]);
            return $this->alertRedirect('Incorrect email or password');
        }
        if ($user->is_verified !== null && ! $user->is_verified) {
            return $this->alertRedirect('Email not verified. Please check your inbox.');
        }

        RateLimiter::clear($pairKey);

        // Two-step sign-in for admins (config/security.php): the password alone does not open the admin panel.
        if ($user->role === 'admin' && config('security.admin_two_step')) {
            $request->session()->regenerate();
            $request->session()->forget(['user_id', 'role', 'full_name']);
            if (! $this->sendAdminCode($request, $user)) {
                Audit::record('login.code_not_sent', ['email' => mb_substr($email, 0, 120)]);
                return $this->alertRedirect('We could not email your sign-in code. Please try again in a minute.');
            }
            return $this->redirectPage(get_absolute_url('admin-verify'), 'Redirecting…');
        }

        $request->session()->regenerate();
        $request->session()->put([
            'admin_last_activity' => time(),
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'role' => $user->role,
        ]);
        Audit::record($user->role === 'admin' ? 'login.admin' : 'login.ok');

        $target = $user->role === 'admin' ? get_absolute_url('admin/dashboard') : get_absolute_url('front');
        return $this->redirectPage($target, 'Redirecting…');
    }

    /** GET|POST /admin-verify: second step of an admin sign-in. */
    public function verifyCode(Request $request)
    {
        $pending = $request->session()->get('admin_pending');
        if (! is_array($pending) || empty($pending['user_id']) || ($pending['expires'] ?? 0) < time()) {
            $request->session()->forget('admin_pending');
            return $request->isMethod('post') || is_array($pending)
                ? $this->alertRedirect('Your sign-in code has expired. Please sign in again.')
                : redirect(get_absolute_url('login'));
        }
        $view = fn (string $error = '', string $notice = '') => response()->view('auth.verify-code', [
            'maskedEmail' => $pending['masked'], 'error' => $error, 'notice' => $notice,
        ])->header('Cache-Control', 'no-store, private');

        if (! $request->isMethod('post')) {
            return $view();
        }

        if ($request->input('resend')) {
            if (time() - (int) ($pending['sent_at'] ?? 0) < 60) {
                return $view('', 'A code was sent less than a minute ago. Please check your inbox and spam folder.');
            }
            $user = User::query()->where('id', $pending['user_id'])->where('role', 'admin')->first(['id', 'full_name', 'email', 'role']);
            if (! $user || ! $this->sendAdminCode($request, $user)) {
                return $view('We could not email a new code. Please try again in a minute.');
            }
            return $view('', 'A new code has been sent.');
        }

        $code = preg_replace('/\D/', '', (string) $request->input('code'));
        if (! hash_equals((string) $pending['hash'], hash('sha256', $code . '|' . $pending['user_id']))) {
            $pending['tries'] = (int) ($pending['tries'] ?? 0) + 1;
            Audit::record('login.code_wrong', ['user' => $pending['user_id']]);
            if ($pending['tries'] >= (int) config('security.admin_code_max_tries', 5)) {
                $request->session()->forget('admin_pending');
                return $this->alertRedirect('Too many wrong codes. Please sign in again.');
            }
            $request->session()->put('admin_pending', $pending);
            return $view('That code is not correct. Please check the email and try again.');
        }

        // The code is right: re-check the account, then open the session exactly as a normal sign-in does.
        $user = User::query()->where('id', $pending['user_id'])->where('role', 'admin')->first(['id', 'full_name', 'role']);
        $request->session()->forget('admin_pending');
        if (! $user) {
            return $this->alertRedirect('Incorrect email or password');
        }
        $request->session()->regenerate();
        $request->session()->put([
            'admin_last_activity' => time(),
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'role' => $user->role,
        ]);
        Audit::record('login.admin', ['two_step' => true]);

        return $this->redirectPage(get_absolute_url('admin/dashboard'), 'Redirecting…');
    }

    /** Email a fresh 6-digit code and remember only its hash in the session. */
    private function sendAdminCode(Request $request, $user): bool
    {
        $code = (string) random_int(100000, 999999);
        $minutes = (int) config('security.admin_code_minutes', 10);
        $name = (string) $user->full_name;
        $html = '<div style="font-family: Arial, sans-serif; max-width: 520px; margin: 0 auto; color: #16202e; line-height: 1.6;">'
            . '<h2 style="color: #0b2a4a;">Your admin sign-in code</h2>'
            . '<p>Dear ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>Use this code to finish signing in to the XJMU admin panel:</p>'
            . '<p style="font-size: 28px; font-weight: bold; letter-spacing: 6px; background: #f5f8fc; padding: 12px 18px; display: inline-block; border-radius: 8px;">' . $code . '</p>'
            . '<p>The code works for ' . $minutes . ' minutes. If you did not try to sign in, change your password now: someone else knows it.</p>'
            . '</div>';
        $text = "Your XJMU admin sign-in code is {$code}. It works for {$minutes} minutes. If you did not try to sign in, change your password now.";
        $sent = app(\App\Services\SiteMailer::class)->sendViaSmtp((string) $user->email, $name, 'Your admin sign-in code - Xinjiang Medical University', $html, $text, 'Admin sign-in code');
        if (! $sent) {
            return false;
        }
        [$local, $domain] = array_pad(explode('@', (string) $user->email, 2), 2, '');
        $request->session()->put('admin_pending', [
            'user_id' => $user->id,
            'hash' => hash('sha256', $code . '|' . $user->id),
            'expires' => time() + $minutes * 60,
            'sent_at' => time(),
            'tries' => 0,
            'masked' => mb_substr($local, 0, 2) . str_repeat('*', max(1, mb_strlen($local) - 2)) . '@' . $domain,
        ]);

        return true;
    }

    public function logout(Request $request)
    {
        Audit::record('logout');
        $request->session()->flush();
        $request->session()->regenerate();

        return $this->redirectPage(get_absolute_url('login'), 'Logging out…');
    }

    private function redirectPage(string $url, string $title)
    {
        $safe = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        return response('<!DOCTYPE html><html><head><meta charset="utf-8">'
            . '<meta http-equiv="refresh" content="0;url=' . $safe . '">'
            . '<title>' . $title . '</title></head><body>'
            . '<script>window.location.replace("' . $safe . '");</script>'
            . '</body></html>');
    }

    private function alertRedirect(string $message)
    {
        $loginUrl = htmlspecialchars(get_absolute_url('login'), ENT_QUOTES, 'UTF-8');
        return response('<script>alert("' . addslashes($message) . '");window.location.href="' . $loginUrl . '";</script>');
    }
}
