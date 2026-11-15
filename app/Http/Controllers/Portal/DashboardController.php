<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

/** Applicant portal pages: dashboard (front), profile and the multi-step application form (edit-details). */
class DashboardController extends Controller
{
    public function front(Request $request)
    {
        return response()->view('portal.front', [
            'full_name' => $request->session()->get('full_name', ''),
        ]);
    }

    public function editDetails(Request $request)
    {
        $session = $request->session();
        $userId = $session->get('user_id');

        // One-shot upload messages (set by save_documents), shown once.
        $uploadSuccess = $session->pull('upload_success');
        $uploadErrors = $session->pull('upload_errors');

        $degree = '';
        $major = '';
        try {
            $row = Application::query()->where('user_id', $userId)->first(['degree_applying_for', 'major_applying_for']);
            if ($row) {
                $degree = $row->degree_applying_for ?? '';
                $major = $row->major_applying_for ?? '';
            }
        } catch (\Exception $e) {
            // Columns may not exist yet; ignore (as the original did)
        }

        return response()->view('portal.edit-details', [
            'full_name' => $session->get('full_name', 'User'),
            'uploadSuccess' => $uploadSuccess,
            'uploadErrors' => $uploadErrors,
            'degree_applying_for' => $degree,
            'major_applying_for' => $major,
        ]);
    }

    public function profile(Request $request)
    {
        return $this->renderProfile($request, '');
    }

    public function updateProfile(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $feedback = '';
        // The original read the user row once, before applying updates, so the form re-renders with the previous values.
        $user = User::query()->where('id', $userId)->first(['full_name', 'email']);

        if ($request->has('update_profile')) {
            $name = trim((string) $request->input('full_name', ''));
            $email = trim((string) $request->input('email', ''));

            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $feedback = '❌ Invalid email format.';
            } elseif (User::query()->where('email', $email)->where('id', '!=', $userId)->exists()) {
                $feedback = '❌ Email already in use by another account.';
            } else {
                User::query()->where('id', $userId)->update(['full_name' => $name, 'email' => $email]);
                $request->session()->put('full_name', $name);
                $feedback = '✅ Profile updated successfully.';
            }
        }

        if ($request->has('change_password')) {
            $current = (string) $request->input('current_password', '');
            $new = (string) $request->input('new_password', '');
            $confirm = (string) $request->input('confirm_password', '');

            $hash = (string) User::query()->where('id', $userId)->value('password');

            if (! password_verify($current, $hash)) {
                $feedback = '❌ Current password is incorrect.';
            } elseif ($new !== $confirm) {
                $feedback = '❌ New passwords do not match.';
            } elseif ($error = $this->passwordError($new)) {
                $feedback = '❌ ' . $error;
            } else {
                User::query()->where('id', $userId)->update(['password' => password_hash($new, PASSWORD_DEFAULT)]);
                $feedback = '✅ Password changed successfully.';
            }
        }

        return response()->view('portal.profile', ['user' => $user, 'feedback' => $feedback]);
    }

    private function renderProfile(Request $request, string $feedback)
    {
        $user = User::query()->where('id', $request->session()->get('user_id'))->first(['full_name', 'email']);

        return response()->view('portal.profile', ['user' => $user, 'feedback' => $feedback]);
    }

    private function passwordError(string $password): ?string
    {
        if (strlen($password) < 8) {
            return 'Password must be at least 8 characters long';
        }
        if (! preg_match('/[A-Z]/', $password)) {
            return 'Password must contain at least one uppercase letter';
        }
        if (! preg_match('/[a-z]/', $password)) {
            return 'Password must contain at least one lowercase letter';
        }
        if (! preg_match('/[0-9]/', $password)) {
            return 'Password must contain at least one number';
        }
        return null;
    }
}
