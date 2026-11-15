<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Manual CSRF check for the public endpoints that answer a failed token with their own message
 * (register-event JSON, submit-testimonial in-page error) instead of Laravel's 419 page.
 * Accepts Laravel's `_token` field or X-CSRF-TOKEN header.
 */
class CsrfCheck
{
    public static function passes(Request $request): bool
    {
        $session = $request->session();
        $laravel = (string) ($request->input('_token') ?: $request->header('X-CSRF-TOKEN', ''));
        return $laravel !== '' && hash_equals((string) $session->token(), $laravel);
    }
}
