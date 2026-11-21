{{-- Portal top bar. $active: dashboard | application | profile --}}
  <header class="p-bar">
    <div class="p-bar__in">
      <a class="p-brand" href="index" target="_blank" rel="noopener noreferrer">
        <img src="assets/images/logo.webp" alt="Xinjiang Medical University">
        <span class="p-brand__t">International Students <small>Centralized Admissions System (CAS)</small></span>
      </a>
      <nav class="p-nav" aria-label="Portal">
        <a href="front" class="{{ ($active ?? '') === 'dashboard' ? 'is-active' : '' }}">Dashboard</a>
        <a href="edit-details" class="{{ ($active ?? '') === 'application' ? 'is-active' : '' }}">My application</a>
        <a href="profile" class="{{ ($active ?? '') === 'profile' ? 'is-active' : '' }}" data-i18n="portal.profile_btn">My Profile</a>
      </nav>
      <div class="p-user">
        <div class="dropdown">
          <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-label="Language">
            <i class="bi bi-globe2 me-1"></i><span data-lang-toggle>English</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item active" href="#" data-lang-item="en">English</a></li>
            <li><a class="dropdown-item" href="#" data-lang-item="zh">中文 (Chinese)</a></li>
            <li><a class="dropdown-item" href="#" data-lang-item="fr">Français</a></li>
          </ul>
        </div>
        <span class="d-none d-md-inline">Welcome, <strong>{{ $full_name ?? session('full_name', '') }}</strong>!</span>
        <a href="logout" class="btn btn-sm btn-outline-secondary" data-i18n="portal.logout_btn">Logout</a>
      </div>
    </div>
  </header>
