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
