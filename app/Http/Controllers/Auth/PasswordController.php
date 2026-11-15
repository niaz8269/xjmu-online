<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SiteMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasswordController extends Controller
{
    public function __construct(private SiteMailer $mailer)
    {
    }

    /** forgot_password: JSON in/out, same messages as the original. */
    public function forgot(Request $request)
    {
        $response = ['success' => false, 'message' => ''];

        if (! $request->isMethod('POST')) {
            $response['message'] = 'Invalid request method.';
            return response()->json($response);
        }

        $email = trim((string) $request->input('email', ''));
        if (empty($email)) {
            $response['message'] = '❌ Please enter an email address.';
            return response()->json($response);
        }

        $user = User::query()->where('email', $email)->first(['id', 'full_name']);
        if ($user) {
            // Raw token goes in the email link; only its hash is stored.
            $token = bin2hex(random_bytes(32));
            DB::table('password_resets')->where('email', $email)->delete();
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => hash('sha256', $token),
                'created_at' => now()->format('Y-m-d H:i:s'),
            ]);

            if ($this->mailer->sendPasswordResetEmail($email, (string) $user->full_name, $token)) {
                $response['success'] = true;
                $response['message'] = '✅ Password reset link has been sent to your email. Please check your inbox.';
            } else {
                $response['message'] = '❌ Failed to send reset email. Please try again later or contact support.';
            }
        } else {
            // Prevent email enumeration: always report success.
            error_log('Password reset requested for non-existent email: ' . $email);
            $response['success'] = true;
            $response['message'] = '✅ If the email exists, a reset link has been sent.';
        }

        return response()->json($response);
    }

    /** reset_password?token=… (GET shows the form, POST sets the password). */
    public function reset(Request $request)
    {
        $token = trim((string) $request->query('token', ''));
        $error = '';
        $success = '';

        if ($token === '' || ! preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return response('Invalid or expired token.');
        }

        $tokenHash = hash('sha256', $token);
        $reset = DB::table('password_resets')->where('token', $tokenHash)->first();
        if (! $reset) {
            return response('Invalid or expired token.');
        }

        // Fail closed: a missing/unparseable timestamp counts as expired.
        $createdAt = ! empty($reset->created_at) ? strtotime((string) $reset->created_at) : false;
        if ($createdAt === false || (time() - $createdAt) > 3600) {
            DB::table('password_resets')->where('token', $tokenHash)->delete();
            return response('Invalid or expired token.');
        }

        $email = $reset->email;

        if ($request->isMethod('POST')) {
            $new = (string) $request->input('password', '');
            $confirm = (string) $request->input('confirm_password', '');

            if ($new !== $confirm) {
                $error = '❌ Passwords do not match.';
            } elseif (strlen($new) < 8) {
                $error = '❌ Password must be at least 8 characters.';
            } else {
                User::query()->where('email', $email)->update(['password' => password_hash($new, PASSWORD_DEFAULT)]);
                DB::table('password_resets')->where('email', $email)->delete();
                $success = "✅ Password reset successfully! <a href='login'>Login Now</a>";
            }
        }

        return response()->view('auth.reset_password', ['error' => $error, 'success' => $success]);
    }
}
