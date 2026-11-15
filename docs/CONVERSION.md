## Public partials contract

The shared site chrome is native Blade (`resources/views/partials/*.blade.php`). The legacy copies in
`resources/views/legacy/inc/*.php` stay for unconverted pages (`legacy_inc('header', get_defined_vars())` keeps working);
delete them once the last legacy page is gone. Output is identical to the legacy partials (proved by `php tools/partials_diff.php`).

**Usage in a converted page** (pick one):

```blade
{{-- A) layout: header + @yield('content') + footer --}}
@extends('layouts.site')
@section('content') ...page markup... @endsection

{{-- B) explicit includes (same thing, e.g. when the page needs content between footer and </body>) --}}
@include('partials.header')
...markup...
@include('partials.footer')
```
Page variables are plain view data (`view('about', ['page_title' => ..., 'page_css' => [...]])`, or `@php $page_title = '...'; @endphp`
at the top of the view). `@include` inherits them from the page, so there is nothing to pass by hand. All are optional:

| Variable | Meaning / default |
|---|---|
| `page_title`, `page_description`, `page_keywords` | `<title>`, meta description/keywords, og/twitter. Defaults = the XJMU site-wide strings (`App\Support\SiteChrome::DEFAULT_*`) |
| `page_url` | canonical/og:url. Default `https://xjmu.online` + current path without query and without `.php/.html`; `http://` is always rewritten to `https://` |
| `page_image` | og/twitter image. Default `https://xjmu.online/images/logo.png` |
| `page_og_type` | default `website` |
| `page_noindex` | truthy => `robots: noindex, nofollow` (and no googlebot tag) |
| `page_css` | array of CSS paths under `public/` (e.g. `['css/about.css']`); emitted after `css/global.css` with `?v=filemtime` (via `get_css_url`) |
| `body_class` | extra class on `<body>` |

Partials and their data (everything computed in `App\Support\SiteChrome`, fed by `App\Providers\PublicPartialsServiceProvider`
view composers; the Blade files hold only markup + trivial `@if/@foreach`):

| Partial | Composer variable | Source |
|---|---|---|
| `partials.header` (doctype, `<head>`, topbar, nav, mobile menu, `<body>` opening) | `$siteHead`, `$siteTop` | page vars above; `Settings` keys `contact_phone2`, `contact_phone_pk`, `contact_email`, `social_youtube`, `social_facebook` |
| `partials.footer` (footer, JSON-LD, includes consultants, bootstrap/global JS, `</body></html>`) | `$siteFoot` | `Settings` `office_address`, `contact_phone(2)`, `contact_email`, `social_*`; year |
| `partials.consultants` (included by the footer, don't include it yourself) | `$siteConsultants` | `Consultant` where `is_published=1` order `sort_order,id`; `Settings` `site_name` |
| `partials.event-register-modal` (events pages include it explicitly) | `$siteModal` | `lg_csrf_field()` + URL of `register-event` |
| `partials.chatbot`, `partials.socials` | none | empty placeholders, as in the original |

Notes: URLs go through the existing `get_absolute_url()/get_css_url()/get_js_url()` helpers so they are unchanged.
The names `siteHead/siteTop/siteFoot/siteConsultants/siteModal` are reserved by the composers; don't use them for page data.
`resources/views/errors/{404,500}.blade.php` replace Laravel's default error pages with the original `404.html`/`500.html`
(the original site used `ErrorDocument 404/500`), so `/nonexistent-page` now differs from `storage/snap_baseline`, which had captured Laravel's default 404.

---

# Converting xjmu.online from plain PHP to native Laravel

**Rule #1 — nothing visible changes.** Same logo, markup, CSS, JS, URLs, wording and behaviour as the original
site. This is a stack change only. Do not restyle, rename classes, reword copy or "improve" layouts.

**Rule #2 — the live database is never touched.** Work only against the local SQLite copy
(`database/database.sqlite`). Never run `migrate`/`migrate:fresh`/`xjmu:import` against anything else,
never connect to the live MySQL, never edit `.env` DB settings, never add destructive migrations.

## Where things are

| Thing | Location |
|---|---|
| Original (plain PHP) pages as they run today | `resources/views/legacy/**` (generated from `E:/Work/XJMU`; **source of truth for behaviour**) |
| Original untouched source | `E:/Work/XJMU` (read-only reference) |
| Legacy helper shims (`get_setting`, `get_programs`, …) | `app/Support/legacy.php`, `app/Support/Settings.php` |
| Models for every table (columns = original names, `$timestamps = false`) | `app/Models/*` (already created; extend, don't recreate) |
| Native routes (matched **before** the legacy catch-all) | `routes/web/{public,auth,portal,admin}.php` — each workstream edits only its own file |
| Pixel baseline of public pages | `tools/snapshot.sh <outDir>` then `cmp`/`diff` against `storage/snap_baseline` |

## How a page is converted

1. Read the legacy view (and the original in `E:/Work/XJMU`) fully.
2. Move logic (queries, validation, mail, file IO, redirects) into a **controller** (`app/Http/Controllers/...`),
   **form request** or **service** (`app/Services/...`). Use Eloquent models / query builder, never `$pdo`.
3. Move markup into a **Blade view** (`resources/views/...`) producing **byte-identical HTML** apart from
   whitespace and CSRF token values. Escape with `{{ }}` where the original used `htmlspecialchars`, `{!! !!}` where
   it echoed raw HTML.
4. Register the route in your `routes/web/<group>.php` with the same URL(s) as the old page (`/about`, and
   also accept the old `.php` suffix with `->where` or a redirect only if the old URL was linked anywhere).
5. Verify: render the page via the snapshot tool and diff against the baseline; for POST/JSON endpoints, exercise
   them (feature tests in `tests/Feature/...` or `php artisan tinker`) and compare responses with the legacy
   route (legacy view still reachable by temporarily calling it, or by reading the code).
6. Only when verified, delete the corresponding `resources/views/legacy/...` file.

## Conventions

- Sessions: use Laravel's `session()` / `$request->session()`. Keys stay `user_id`, `full_name`, `role` (the
  `legacy` middleware mirrors them to `$_SESSION` for unconverted pages). Put converted routes in the
  `web` group; add `->middleware('legacy')` **only** while a not-yet-converted page needs `$_SESSION`.
- CSRF: converted POST routes use Laravel CSRF (`@csrf`, `csrf_token()`; the field is `_token`). Update the
  page's own JS (fetch/XHR bodies, `<meta name="csrf-token">`) accordingly. Legacy catch-all routes stay exempt.
- Auth guards: write route middleware (`auth.legacy`, `admin`) instead of copy-pasting login checks. Names:
  `App\Http\Middleware\EnsureLoggedIn` (alias `loggedin`) and `EnsureAdmin` (alias `admin`) — the
  Auth workstream owns them; Admin workstream owns `EnsureAdmin`.
- No `$pdo`, no `require`/`include`, no `header()`/`exit`, no `echo` of HTML in controllers, no inline `$_GET/$_POST`
  (use `Request`). Redirects with `redirect()`; JSON with `response()->json()` keeping the original shape.
- Uploaded files keep the same paths under `public/uploads/**` and the same filename rules.
- Mail: `App\Services\SiteMailer` (Auth workstream) wraps PHPMailer/`mail()` exactly as the originals did, reading
  SMTP from `.env` (`SMTP_*`). Do not send real mail in tests; fake it.
- Config values previously read from `.env`/constants go through `config()`; site settings through
  `App\Support\Settings` (table `site_settings`).
- One class per file, PSR-4, PHP 8.3, no new composer packages without a reason noted in `docs/DECISIONS.md`
  (append one dated line per decision).
- Don't commit. Don't touch files owned by another workstream (routes files, partials, layouts) — if you need
  something from them, use it through its documented name or ask in your final report.

## Definition of done for each page

- Route is native Laravel, legacy view deleted, no `legacy_inc`/`get_defined_vars` left in it.
- Rendered HTML identical to baseline (public pages) / identical behaviour (forms, JSON, admin actions).
- No PHP warnings/notices in `storage/logs/laravel.log` for the page.

## Admin layout contract

Built by the Admin foundation workstream. Replaces `legacy/admin/includes/{auth,helpers,layout_top,layout_bottom}.php`
(those legacy files stay until every admin page is converted). Verified: `/admin` renders byte-for-byte the same as the
legacy dashboard apart from whitespace; guests and non-admins get `302 -> /login` exactly as before.

**Routes** — in `routes/web/admin.php`, wrap in `Route::middleware('admin')->group(...)`. Same URLs as the old pages
(`/admin/manage-blogs`, and also the `.php` form if the old URL was linked). IMPORTANT: Laravel keys routes by method+URI, so a
route registered later with the same URI/method *replaces* an earlier one. The legacy catch-alls in `routes/web.php` are
registered after, so a converted page whose URI equals a legacy line (only `/admin` was such a case, now removed) would be
overwritten; the generic `/admin/{script}` legacy route does not clash with distinct URIs.
Also: `$middleware->alias([...])` in `bootstrap/app.php` REPLACES the map — keep all aliases in the one call there.

**Middleware `admin`** (`App\Http\Middleware\EnsureAdmin`): not logged in (`user_id` unset) or `role !== 'admin'` -> logs
"Unauthorized admin access attempt - user_id: X" and `redirect('/login')`. Otherwise looks up `users.is_superadmin` and shares
`$admin_name` (session `full_name`, default 'Admin') and `$is_superadmin` with all views (also `$request->attributes`).
No superadmin-only gating exists in the originals; check `$request->attributes->get('is_superadmin')` where a page needs it.

**Controller** — extend `App\Http\Controllers\Admin\AdminController`. Return
`$this->page('admin.<view>', $title, $activeMenuKey, $subtitleOrNull, $data)`; it passes `admin_page_title`, `admin_active`,
`admin_subtitle` (same names as the old `$admin_*` variables) plus your data array. Dynamic titles ("Edit Blog Post") are
computed in the controller. Use `DB`/Eloquent; never `$pdo`.

**View** — `resources/views/admin/<name>.blade.php`:
```blade
@extends('admin.layout')
@section('content')
   ...exact markup that sat between layout_top and layout_bottom...
@endsection
@push('head')    {{-- optional: extra <link>/<style> inside <head> --}} @endpush
@push('scripts') {{-- optional: scripts emitted after the layout's bootstrap/adminlte scripts, before </body> --}} @endpush
```
Inline `<script>` blocks that the original put inside the page body (before layout_bottom's bundle scripts) should stay in
`@section('content')` to keep HTML identical. `$admin_active` = the menu key (`dashboard`, `applications`, `manage-blogs`, `users`,
`settings`, `test_email`, ...; keys in `App\Support\Admin\Menu::items()`). Sidebar links stay relative (`dashboard`, `../login`) like the original.
Sidebar no longer renders the "soon" badge (every listed page existed in the original, so it never showed).
Escaping: `{{ }}` where the original used `e()`, `{!! !!}` for raw HTML. CSRF: use `@csrf` (field `_token`) in converted forms.

**Helpers** (`app/Support/Admin/`):
- `Uploads::save($request, 'image', 'gallery', Uploads::imageExt(), Uploads::imageMime(), 6*1024*1024)` = old `save_upload()`
  (returns `'uploads/gallery/x.webp'` or null if no file; throws `RuntimeException` with the original messages; WebP optimisation kept).
  Also `docExt()`, `docMime()`, `optimizeImage()`. Replaces `IMG_EXT()/IMG_MIME()/DOC_EXT()/DOC_MIME()`.
- `Html::statusBadge($status)` = old `status_badge()` (print with `{!! !!}`).
- `Menu::items()` / `Menu::activeClass()` = sidebar definition.
- Original helpers had no flash/slugify/pagination functions (those, if a page needs them, live in the page itself).
Flash messages: use `session()->flash(...)` + redirect; keep the original wording.

**Converted so far:** `/admin`, `/admin/dashboard`, `/admin/dashboard.php` -> `Admin\DashboardController` + `admin/dashboard.blade.php`
(legacy `dashboard.php` deleted). Test: `tests/Feature/AdminGateTest.php`.
