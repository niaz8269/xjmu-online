<?php

use App\Http\Controllers\Portal\ApplicationDataController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DownloadController;
use App\Http\Controllers\Portal\SaveDataController;
use App\Http\Controllers\Portal\SubmissionController;
use App\Http\Middleware\PortalJsonEndpoint;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// portal routes (converted from legacy PHP). Owned by the portal conversion workstream.
// Every URL also answers to its old ".php" form: the original page JS fetches get_personal.php, save_personal.php,
// edit-details.php ... and the old links point at them.

// The original scripts read $_POST verbatim: no trimming, '' stays ''. Laravel's global TrimStrings /
// ConvertEmptyStringsToNull would store NULLs and strip spaces (passwords included), so they are skipped for these URLs.
$portalWrites = ['profile*', 'save_*', 'submit_application*'];
TrimStrings::skipWhen(fn (Request $request) => $request->is($portalWrites));
ConvertEmptyStringsToNull::skipWhen(fn (Request $request) => $request->is($portalWrites));

/** Register a route under both /name and /name.php. */
$portal = function (array $methods, string $name, $action, array $middleware = [], bool $noCsrf = false) {
    foreach ([$name, $name . '.php'] as $uri) {
        $route = Route::match($methods, '/' . $uri, $action)->middleware($middleware);
        if ($noCsrf) {
            $route->withoutMiddleware([PreventRequestForgery::class, ValidateCsrfToken::class]);
        }
    }
};

// ---- pages (unauthenticated -> redirect to /login) ----
$portal(['GET'], 'front', [DashboardController::class, 'front'], ['loggedin']);
$portal(['GET'], 'edit-details', [DashboardController::class, 'editDetails'], ['loggedin']);
$portal(['GET'], 'profile', [DashboardController::class, 'profile'], ['loggedin']);
$portal(['POST'], 'profile', [DashboardController::class, 'updateProfile'], ['loggedin']);
$portal(['GET'], 'download_application', DownloadController::class, ['loggedin']);

// ---- JSON endpoints: original guard (500 envelope) + own 403 JSON on a bad CSRF token ----
$json = PortalJsonEndpoint::class;
$any = ['GET', 'POST'];

$portal($any, 'get_personal', [ApplicationDataController::class, 'getPersonal'], [$json, 'loggedin:401'], true);
$portal($any, 'get_education', [ApplicationDataController::class, 'getEducation'], [$json, 'loggedin:401'], true);
$portal($any, 'get_documents', [ApplicationDataController::class, 'getDocuments'], [$json, 'loggedin:401'], true);
$portal($any, 'get_application_status', [SubmissionController::class, 'applicationStatus'], [$json], true);
$portal($any, 'get_submission_status', [SubmissionController::class, 'submissionStatus'], [$json], true);

$portal($any, 'save_personal', [ApplicationDataController::class, 'savePersonal'], [$json, 'loggedin:json'], true);
$portal($any, 'save_education', [ApplicationDataController::class, 'saveEducation'], [$json, 'loggedin:json'], true);
$portal($any, 'save_documents', [ApplicationDataController::class, 'saveDocuments'], [$json], true);
$portal($any, 'save_application_program', [ApplicationDataController::class, 'saveApplicationProgram'], [$json], true);
$portal($any, 'save_data', SaveDataController::class, [$json, 'loggedin:json'], true);
$portal($any, 'submit_application', [SubmissionController::class, 'submit'], [$json], true);

// ---- tiny stubs of the original site (301 redirects) ----
foreach ([
    'applications' => '/admin/applications',
    'users' => '/admin/users',
    'manage-applications' => '/admin/manage-applications',
    'manage_users' => '/admin/manage_users',
    'review_application' => '/admin/review_application',
    'export_pdf' => '/admin/export_pdf',
    'application_status' => '/',
    'mylogo' => '/',
] as $from => $to) {
    Route::redirect('/' . $from, $to, 301);
    Route::redirect('/' . $from . '.php', $to, 301);
}

// dashboard: admins go to the admin dashboard, everybody else to the login page
$dashboard = fn (Request $request) => ($request->session()->has('user_id') && $request->session()->get('role') === 'admin')
    ? redirect('admin/dashboard')
    : redirect('login');
Route::any('/dashboard', $dashboard);
Route::any('/dashboard.php', $dashboard);

// adm-HtApply: public "How to Apply" page (a page of the original site that lived next to the portal scripts)
Route::get('/adm-HtApply', \App\Http\Controllers\HowToApplyController::class);
Route::get('/adm-HtApply.php', \App\Http\Controllers\HowToApplyController::class);
