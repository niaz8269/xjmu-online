<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
        try {
            $user = User::query()->where('email', $email)->first(['id', 'full_name', 'email', 'password', 'role', 'is_verified']);
        } catch (\Throwable $e) {
            error_log('Login query error: ' . $e->getMessage());
            return $this->alertRedirect('An error occurred. Please try again later.');
        }

        // Legacy hashes come from password_hash(); verify with password_verify and never rehash.
        if (! $user || ! password_verify($passIn, (string) $user->password)) {
            return $this->alertRedirect('Incorrect email or password');
        }
        if ($user->is_verified !== null && ! $user->is_verified) {
            return $this->alertRedirect('Email not verified. Please check your inbox.');
        }

        $request->session()->put([
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'role' => $user->role,
        ]);
        $request->session()->regenerate();

        $target = $user->role === 'admin' ? get_absolute_url('admin/dashboard') : get_absolute_url('front');
        return $this->redirectPage($target, 'Redirecting…');
    }

    public function logout(Request $request)
    {
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
