@php
    $admin_page_title = $admin_page_title ?? 'Admin';
    $admin_active     = $admin_active ?? '';
    $admin_subtitle   = $admin_subtitle ?? null;
    $ALTE = 'https://cdn.jsdelivr.net/npm/admin-lte@4.0.2';
    $adminMenu = \App\Support\Admin\Menu::items();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $admin_page_title }} · XJMU Admin</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ $ALTE }}/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css?v=1">
@stack('head')
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
                        <i class="bi bi-person-circle me-1"></i>{{ $admin_name }}
                        @if ($is_superadmin)<span class="badge text-bg-warning ms-1">Super</span>@endif
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
                    @foreach ($adminMenu as $key => $item)
                        @php [$label, $icon, $url, $children] = array_pad($item, 4, null); @endphp
                        @if ($children)
                            @php $openParent = array_key_exists($admin_active, $children); @endphp
                            <li class="nav-item{{ $openParent ? ' menu-open' : '' }}">
                                <a href="#" class="nav-link{{ $openParent ? ' active' : '' }}">
                                    <i class="nav-icon bi {{ $icon }}"></i>
                                    <p>{{ $label }}<i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach ($children as $ckey => $citem)
                                        <li class="nav-item">
                                            <a href="{{ $citem[1] }}" class="nav-link{{ \App\Support\Admin\Menu::activeClass($ckey, $admin_active) }}">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>{{ $citem[0] }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ $url }}" class="nav-link{{ \App\Support\Admin\Menu::activeClass($key, $admin_active) }}">
                                    <i class="nav-icon bi {{ $icon }}"></i>
                                    <p>{{ $label }}</p>
                                </a>
                            </li>
                        @endif
                    @endforeach
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
                        <h1 class="mb-0 h3">{{ $admin_page_title }}</h1>
                        @if (!empty($admin_subtitle))
                            <small class="text-muted">{{ $admin_subtitle }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
@yield('content')
            </div><!-- /.container-fluid -->
        </div><!-- /.app-content -->
    </main>

    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Xinjiang Medical University</div>
        <strong>XJMU Admin</strong> &mdash; Centralized Admissions System
    </footer>
</div><!-- /.app-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
