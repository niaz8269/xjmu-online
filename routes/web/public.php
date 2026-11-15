<?php

use Illuminate\Support\Facades\Route;

// public routes (converted from legacy PHP). Owned by the public conversion workstream.

// ---- batch A: home, about, mbbs, programs, admissions, fees, scholarships, support, privacy, terms, be-our-partner --
// Clean URL and legacy `.php` suffix both work (same as the legacy catch-all).
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
// the home page's registration pop-up posts back to `/` without a CSRF token, as the original page did
Route::post('/', [\App\Http\Controllers\HomeController::class, 'register'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class, \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
foreach ([
    'about' => [\App\Http\Controllers\PageController::class, 'about'],
    'admissions' => [\App\Http\Controllers\PageController::class, 'admissions'],
    'fees' => [\App\Http\Controllers\PageController::class, 'fees'],
    'support' => [\App\Http\Controllers\PageController::class, 'support'],
    'privacy' => [\App\Http\Controllers\PageController::class, 'privacy'],
    'terms' => [\App\Http\Controllers\PageController::class, 'terms'],
    'be-our-partner' => [\App\Http\Controllers\PageController::class, 'partner'],
    'mbbs' => [\App\Http\Controllers\MbbsController::class, 'index'],
    'programs' => [\App\Http\Controllers\ProgramController::class, 'index'],
    'scholarships' => [\App\Http\Controllers\ScholarshipController::class, 'index'],
] as $uri => $action) {
    Route::get('/' . $uri, $action);
    Route::get('/' . $uri . '.php', $action);
}
// .htaccess aliases that served another page's content
Route::get('/programs.html', [\App\Http\Controllers\ProgramController::class, 'index']);
Route::get('/mbbs.html', [\App\Http\Controllers\MbbsController::class, 'index']);
// ---- end batch A ---------------------------------------------------------------------------------------------------

// ---- batch B: blogs, blog-post, events, event, gallery, downloads, contact, stubs --------------------------------
// Clean URL and legacy `.php` suffix both work (same as the legacy catch-all).
foreach ([
    'blogs' => [\App\Http\Controllers\BlogController::class, 'index'],
    'blog-post' => [\App\Http\Controllers\BlogController::class, 'show'],
    'events' => [\App\Http\Controllers\EventController::class, 'index'],
    'event' => [\App\Http\Controllers\EventController::class, 'show'],
    'gallery' => [\App\Http\Controllers\GalleryController::class, 'index'],
    'downloads' => [\App\Http\Controllers\DownloadController::class, 'index'],
    'contact' => [\App\Http\Controllers\ContactController::class, 'index'],
] as $uri => $action) {
    Route::get('/' . $uri, $action);
    Route::get('/' . $uri . '.php', $action);
}
// .htaccess aliases that served another page's content
Route::get('/research.html', [\App\Http\Controllers\BlogController::class, 'index']);
Route::get('/campus-life.html', [\App\Http\Controllers\GalleryController::class, 'index']);

// stub pages: permanent redirects, as the original scripts did
foreach (['news' => '/events', 'careers' => '/be-our-partner', 'research' => '/blogs', 'mylogo' => '/'] as $uri => $target) {
    Route::permanentRedirect('/' . $uri, $target);
    Route::permanentRedirect('/' . $uri . '.php', $target);
}
// ---- end batch B ---------------------------------------------------------------------------------------------------
