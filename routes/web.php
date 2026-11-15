<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| xjmu.online routes
|--------------------------------------------------------------------------
| Public pages, auth, applicant portal and admin live in routes/web/*.php (same URLs as the original site;
| the old `.php` forms keep working). Only the old permanent redirects are kept here.
*/
require __DIR__ . '/web/public.php';
require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/portal.php';
require __DIR__ . '/web/admin.php';

Route::redirect('/index.php', '/', 301);
Route::redirect('/login.html', '/login', 301);
Route::redirect('/register.html', '/register', 301);

// Old root-level admin URLs (the original site 301-redirected them into /admin).
foreach (['create-blog', 'delete-blog', 'delete-event', 'edit-blog', 'edit-event', 'manage-blogs', 'manage-events'] as $page) {
    Route::any('/' . $page, fn () => redirect('/admin/' . $page, 301));
    Route::any('/' . $page . '.php', fn () => redirect('/admin/' . $page, 301));
}
