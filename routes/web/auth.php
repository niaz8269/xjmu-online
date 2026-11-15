<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FormMailController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TestimonialSubmissionController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

// auth routes (converted from legacy PHP). Owned by the auth conversion workstream.
// Each URL also answers to its old ".php" form (the original pages posted to login.php, fetched forgot_password.php, ...).

// login / logout
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::get('/login.php', [LoginController::class, 'show']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/login.php', [LoginController::class, 'login']);
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/logout.php', [LoginController::class, 'logout']);

// registration / verification
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::get('/register.php', [RegisterController::class, 'show']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/register.php', [RegisterController::class, 'register']);
Route::get('/verify_email', [RegisterController::class, 'verify']);
Route::get('/verify_email.php', [RegisterController::class, 'verify']);
Route::match(['get', 'post'], '/resend_code', [RegisterController::class, 'resend']);
Route::match(['get', 'post'], '/resend_code.php', [RegisterController::class, 'resend']);

// password reset
Route::match(['get', 'post'], '/forgot_password', [PasswordController::class, 'forgot']);
Route::match(['get', 'post'], '/forgot_password.php', [PasswordController::class, 'forgot']);
Route::match(['get', 'post'], '/reset_password', [PasswordController::class, 'reset']);
Route::match(['get', 'post'], '/reset_password.php', [PasswordController::class, 'reset']);

// public forms. The original contact / partner endpoints had no CSRF check (the public pages post without a token);
// register-event and submit-testimonial check the token themselves (App\Http\Controllers\CsrfCheck) to keep their own error output.
$noCsrf = [PreventRequestForgery::class, ValidateCsrfToken::class];
Route::any('/send_contact_email', [FormMailController::class, 'contact'])->withoutMiddleware($noCsrf);
Route::any('/send_contact_email.php', [FormMailController::class, 'contact'])->withoutMiddleware($noCsrf);
Route::any('/send_partner_registration', [FormMailController::class, 'partner'])->withoutMiddleware($noCsrf);
Route::any('/send_partner_registration.php', [FormMailController::class, 'partner'])->withoutMiddleware($noCsrf);
Route::any('/register-event', [FormMailController::class, 'event'])->withoutMiddleware($noCsrf);
Route::any('/register-event.php', [FormMailController::class, 'event'])->withoutMiddleware($noCsrf);

Route::get('/submit-testimonial', [TestimonialSubmissionController::class, 'show']);
Route::get('/submit-testimonial.php', [TestimonialSubmissionController::class, 'show']);
Route::post('/submit-testimonial', [TestimonialSubmissionController::class, 'store'])->withoutMiddleware($noCsrf);
Route::post('/submit-testimonial.php', [TestimonialSubmissionController::class, 'store'])->withoutMiddleware($noCsrf);

Route::get('/sitemap', SitemapController::class);
Route::get('/sitemap.php', SitemapController::class);
Route::get('/sitemap.xml', SitemapController::class); // original .htaccess served sitemap.php here (also kept for local runs without Apache rewrites)
