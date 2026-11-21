<?php

use Illuminate\Support\Facades\Route;

// admin routes (converted from legacy PHP). Owned by the admin conversion workstream.

use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['admin', 'admin.audit'])->group(function () {
    Route::get('/admin/activity', [\App\Http\Controllers\Admin\SecurityController::class, 'activity']);
    Route::get('/admin/document', [\App\Http\Controllers\Admin\SecurityController::class, 'document']);
    Route::get('/admin', [DashboardController::class, 'index']);
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    Route::get('/admin/dashboard.php', [DashboardController::class, 'index']);
});

// ---- batch content: simple CRUD screens (list / ?action=new|edit&id=N via GET, save/delete/etc. via POST) ----
Route::middleware(['admin', 'admin.audit'])->group(function () {
    $content = [
        'manage-consultants'         => \App\Http\Controllers\Admin\ConsultantController::class,
        'manage-downloads'           => \App\Http\Controllers\Admin\DownloadController::class,
        'manage-faqs'                => \App\Http\Controllers\Admin\FaqController::class,
        'manage-gallery'             => \App\Http\Controllers\Admin\GalleryController::class,
        'manage-home-accreditations' => \App\Http\Controllers\Admin\HomeAccreditationController::class,
        'manage-home-facts'          => \App\Http\Controllers\Admin\HomeFactController::class,
        'manage-home-programs'       => \App\Http\Controllers\Admin\HomeProgramController::class,
        'manage-home-steps'          => \App\Http\Controllers\Admin\HomeStepController::class,
        'manage-home-why'            => \App\Http\Controllers\Admin\HomeWhyController::class,
        'manage-mbbs-curriculum'     => \App\Http\Controllers\Admin\MbbsCurriculumController::class,
        'manage-pages'               => \App\Http\Controllers\Admin\PageSectionController::class,
        'manage-programs'            => \App\Http\Controllers\Admin\ProgramController::class,
        'manage-scholarships'        => \App\Http\Controllers\Admin\ScholarshipController::class,
        'manage-testimonials'        => \App\Http\Controllers\Admin\TestimonialController::class,
    ];
    foreach ($content as $slug => $controller) {
        foreach (['/admin/' . $slug, '/admin/' . $slug . '.php'] as $uri) {
            Route::get($uri, [$controller, 'index']);
            Route::post($uri, [$controller, 'store']);
        }
    }
});
// ---- end batch content ----

// ---- batch ops: applications, users, blogs, events, settings, test_email (each URL also answers with the old `.php` suffix) ----
Route::middleware(['admin', 'admin.audit'])->group(function () {
    $ops = [
        // page slug => [methods, controller, method]
        'applications'        => [['get'], \App\Http\Controllers\Admin\ApplicationsController::class, 'index'],
        'manage-applications' => [['get', 'post'], \App\Http\Controllers\Admin\ApplicationsController::class, 'manage'],
        'review_application'  => [['get', 'post'], \App\Http\Controllers\Admin\ApplicationsController::class, 'review'],
        'delete_application'  => [['get', 'post'], \App\Http\Controllers\Admin\ApplicationsController::class, 'destroy'],
        'update_personal'     => [['get', 'post'], \App\Http\Controllers\Admin\ApplicationsController::class, 'updatePersonal'],
        'export_pdf'          => [['get'], \App\Http\Controllers\Admin\ApplicationsController::class, 'exportPdf'],
        'download_application' => [['get'], \App\Http\Controllers\Admin\ApplicationsController::class, 'download'],
        'leads'               => [['get', 'post'], \App\Http\Controllers\Admin\LeadsController::class, 'index'],
        'lead'                => [['get', 'post'], \App\Http\Controllers\Admin\LeadsController::class, 'show'],
        'lead-email'          => [['get', 'post'], \App\Http\Controllers\Admin\LeadsController::class, 'email'],
        'seo'                 => [['get', 'post'], \App\Http\Controllers\Admin\CmsController::class, 'seo'],
        'page-faqs'           => [['get', 'post'], \App\Http\Controllers\Admin\CmsController::class, 'faqs'],
        'guides'              => [['get'], \App\Http\Controllers\Admin\CmsController::class, 'guides'],
        'guide-edit'          => [['get', 'post'], \App\Http\Controllers\Admin\CmsController::class, 'guide'],
        'portal-texts'        => [['get', 'post'], \App\Http\Controllers\Admin\CmsController::class, 'portal'],
        'users'               => [['get'], \App\Http\Controllers\Admin\UsersController::class, 'index'],
        'manage_users'        => [['get'], \App\Http\Controllers\Admin\UsersController::class, 'manage'],
        'promote_user'        => [['get', 'post'], \App\Http\Controllers\Admin\UsersController::class, 'promote'],
        'demote_user'         => [['get', 'post'], \App\Http\Controllers\Admin\UsersController::class, 'demote'],
        'delete_user'         => [['get', 'post'], \App\Http\Controllers\Admin\UsersController::class, 'destroy'],
        'manage-blogs'        => [['get'], \App\Http\Controllers\Admin\BlogsController::class, 'index'],
        'create-blog'         => [['get', 'post'], \App\Http\Controllers\Admin\BlogsController::class, 'create'],
        'edit-blog'           => [['get', 'post'], \App\Http\Controllers\Admin\BlogsController::class, 'edit'],
        'delete-blog'         => [['get', 'post'], \App\Http\Controllers\Admin\BlogsController::class, 'destroy'],
        'manage-events'       => [['get'], \App\Http\Controllers\Admin\EventsController::class, 'index'],
        'edit-event'          => [['get', 'post'], \App\Http\Controllers\Admin\EventsController::class, 'edit'],
        'delete-event'        => [['get', 'post'], \App\Http\Controllers\Admin\EventsController::class, 'destroy'],
        'test_email'          => [['get', 'post'], \App\Http\Controllers\Admin\TestEmailController::class, '__invoke'],
        'settings'            => [['get', 'post'], \App\Http\Controllers\Admin\SettingsController::class, '__invoke'],
    ];
    foreach ($ops as $slug => [$methods, $controller, $action]) {
        foreach (['/admin/' . $slug, '/admin/' . $slug . '.php'] as $uri) {
            Route::match($methods, $uri, [$controller, $action]);
        }
    }
});
// ---- end batch ops ----
