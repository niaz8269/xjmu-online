<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| xjmu.online routes
|--------------------------------------------------------------------------
| Public pages, auth, applicant portal and admin live in routes/web/*.php (same URLs as the original site;
| the old `.php` forms keep working). Only the old permanent redirects are kept here.
*/
// Pages that are the same for every visitor: no session cookie, cacheable for a day (as on the original site).
// Do NOT use for pages that render a CSRF token (events modal, login, register, testimonial form).
$cacheable = fn (\Illuminate\Routing\Route $route) => $route
    ->withoutMiddleware([
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ])
    ->middleware('public.cache');

require __DIR__ . '/web/public.php';
require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/portal.php';
require __DIR__ . '/web/admin.php';

// Old / mistyped addresses (see config/legacy_redirects.php): 301 to the closest real page.
foreach (config('legacy_redirects.paths', []) as $from => $to) {
    Route::permanentRedirect($from, $to);
}
Route::get('/downloads/{path}', fn () => redirect(config('legacy_redirects.downloads_fallback', '/downloads'), 301))->where('path', '.+');

Route::redirect('/index.php', '/', 301);
Route::redirect('/login.html', '/login', 301);
Route::redirect('/register.html', '/register', 301);

// Old root-level admin URLs (the original site 301-redirected them into /admin).
foreach (['create-blog', 'delete-blog', 'delete-event', 'edit-blog', 'edit-event', 'manage-blogs', 'manage-events'] as $page) {
    Route::any('/' . $page, fn () => redirect('/admin/' . $page, 301));
    Route::any('/' . $page . '.php', fn () => redirect('/admin/' . $page, 301));
}
