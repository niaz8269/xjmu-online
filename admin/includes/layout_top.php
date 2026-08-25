<?php
/**
 * Admin layout — top half (AdminLTE 4 / Bootstrap 5).
 * Set before including:
 *   $admin_page_title  (string)  e.g. 'Dashboard'
 *   $admin_active      (string)  menu key to highlight, e.g. 'dashboard'
 *   $admin_subtitle    (string, optional) small text under the page title
 * Requires auth.php to have run (provides $admin_name, $is_superadmin, e()).
 */
$admin_page_title = $admin_page_title ?? 'Admin';
$admin_active     = $admin_active ?? '';
$ALTE = 'https://cdn.jsdelivr.net/npm/admin-lte@4.0.2';

// Sidebar menu definition: key => [label, icon, url, (optional) children]
$adminMenu = [
    'dashboard'   => ['Dashboard', 'bi-speedometer2', 'dashboard'],
    '_apps'       => ['Applications', 'bi-file-earmark-text', null, [
        'applications'        => ['All Submissions', 'applications'],
        'manage-applications' => ['Status & Remarks', 'manage-applications'],
    ]],
    'users'       => ['Users', 'bi-people', 'users'],
    '_content'    => ['Content', 'bi-collection', null, [
        'manage-blogs'        => ['Blogs', 'manage-blogs'],
        'manage-events'       => ['Events', 'manage-events'],
        'manage-programs'     => ['Programs', 'manage-programs'],
        'manage-scholarships' => ['Scholarships', 'manage-scholarships'],
        'manage-downloads'    => ['Downloads', 'manage-downloads'],
        'manage-gallery'      => ['Gallery', 'manage-gallery'],
        'manage-consultants'  => ['Consultants', 'manage-consultants'],
        'manage-testimonials' => ['Testimonials', 'manage-testimonials'],
        'manage-faqs'         => ['FAQs', 'manage-faqs'],
        'manage-pages'        => ['Page Content', 'manage-pages'],
    ]],
    '_homepage'   => ['Homepage Cards', 'bi-grid-1x2', null, [
        'manage-home-programs'       => ['Program Cards', 'manage-home-programs'],
        'manage-home-why'            => ['Why-Us Cards', 'manage-home-why'],
        'manage-home-steps'          => ['Apply Steps', 'manage-home-steps'],
        'manage-home-accreditations' => ['Accreditations', 'manage-home-accreditations'],
        'manage-home-facts'          => ['MBBS Facts', 'manage-home-facts'],
        'manage-mbbs-curriculum'     => ['MBBS Curriculum', 'manage-mbbs-curriculum'],
    ]],
    'settings'    => ['Site Settings', 'bi-gear', 'settings'],
    'test_email'  => ['Email Test', 'bi-envelope', 'test_email'],
];

// Which menu keys actually exist yet (avoid linking to not-yet-built pages).
$builtPages = array_map(function ($f) {
    return basename($f, '.php');
}, glob(__DIR__ . '/../*.php'));

function admin_menu_url($slug) { return $slug; }
function admin_is_active($key, $active) { return $key === $active ? ' active' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($admin_page_title) ?> · XJMU Admin</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $ALTE ?>/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css?v=1">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    <!-- Top navbar -->
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="../index" class="nav-link" target="_blank" rel="noopener">View Site <i class="bi bi-box-arrow-up-right small"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                        <i class="bi bi-person-circle me-1"></i><?= e($admin_name) ?>
                        <?php if ($is_superadmin): ?><span class="badge text-bg-warning ms-1">Super</span><?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="../profile" class="dropdown-item"><i class="bi bi-gear me-2"></i>My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a href="../logout" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="dashboard" class="brand-link">
                <span class="brand-text fw-light"><strong>XJMU</strong> Admin</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <?php foreach ($adminMenu as $key => $item): ?>
                        <?php list($label, $icon, $url, $children) = array_pad($item, 4, null); ?>
                        <?php if ($children): ?>
                            <?php $openParent = array_key_exists($admin_active, $children); ?>
                            <li class="nav-item<?= $openParent ? ' menu-open' : '' ?>">
                                <a href="#" class="nav-link<?= $openParent ? ' active' : '' ?>">
                                    <i class="nav-icon bi <?= $icon ?>"></i>
                                    <p><?= e($label) ?><i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php foreach ($children as $ckey => $citem): ?>
                                        <?php $exists = in_array($ckey, $builtPages, true); ?>
                                        <li class="nav-item">
                                            <a href="<?= $exists ? e($citem[1]) : '#' ?>" class="nav-link<?= admin_is_active($ckey, $admin_active) ?><?= $exists ? '' : ' disabled text-muted' ?>">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p><?= e($citem[0]) ?><?= $exists ? '' : ' <span class="badge text-bg-secondary ms-1">soon</span>' ?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <?php $exists = in_array($key, $builtPages, true); ?>
                            <li class="nav-item">
                                <a href="<?= $exists ? e($url) : '#' ?>" class="nav-link<?= admin_is_active($key, $admin_active) ?><?= $exists ? '' : ' disabled text-muted' ?>">
                                    <i class="nav-icon bi <?= $icon ?>"></i>
                                    <p><?= e($label) ?><?= $exists ? '' : ' <span class="badge text-bg-secondary ms-1">soon</span>' ?></p>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Main content -->
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <h1 class="mb-0 h3"><?= e($admin_page_title) ?></h1>
                        <?php if (!empty($admin_subtitle)): ?>
                            <small class="text-muted"><?= e($admin_subtitle) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
