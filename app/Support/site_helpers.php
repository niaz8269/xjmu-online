<?php
/**
 * Site helpers used by the Blade views (URL building, settings, published-content getters). Same names as the original site's includes/*.php.
 */

use Illuminate\Support\Facades\DB;

if (! function_exists('get_absolute_path')) {
    function get_absolute_path($p) { return ltrim((string) $p, '/'); }
}
if (! function_exists('get_absolute_url')) {
    function get_absolute_url($p = '') {
        $p = ltrim((string) $p, '/');
        $p = preg_replace('/\.php(\?.*)?$/', '$1', $p);
        $p = preg_replace('/\.html(\?.*)?$/', '$1', $p);
        if ($p === 'index' || $p === '') { return rtrim(url('/'), '/') . '/'; }
        return rtrim(url('/'), '/') . '/' . $p;
    }
}
if (! function_exists('get_css_url')) { function get_css_url($f) { return get_absolute_url($f) . '?v=' . @filemtime(public_path($f)); } }
if (! function_exists('get_js_url'))  { function get_js_url($f)  { return get_absolute_url($f) . '?v=' . @filemtime(public_path($f)); } }

if (! function_exists('settings_all')) {
    function settings_all() { return \App\Support\Settings::all(); }
}
if (! function_exists('get_setting')) {
    function get_setting($k, $d = '') { return \App\Support\Settings::get($k, $d); }
}
if (! function_exists('setting_e')) {
    function setting_e($k, $d = '') { return htmlspecialchars((string) get_setting($k, $d), ENT_QUOTES, 'UTF-8'); }
}
if (! function_exists('yt_embed')) {
    function yt_embed($v) {
        $v = trim((string) $v);
        if ($v === '') return '';
        if (preg_match('~^[A-Za-z0-9_-]{11}$~', $v)) return 'https://www.youtube.com/embed/' . $v;
        if (preg_match('~(?:youtu\.be/|[?&]v=|/embed/|/shorts/|/v/)([A-Za-z0-9_-]{11})~', $v, $m)) return 'https://www.youtube.com/embed/' . $m[1];
        return '';
    }
    function video_embed($key) { return yt_embed(get_setting($key, '')); }
}

if (! function_exists('content_rows')) {
    function content_rows($sql, $params = []) {
        try { return array_map(fn ($r) => (array) $r, DB::select($sql, $params)); }
        catch (\Throwable $e) { logger()->error('content_rows: ' . $e->getMessage()); return []; }
    }
    function get_programs()     { return content_rows("SELECT * FROM programs WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_scholarships() { return content_rows("SELECT * FROM scholarships WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_downloads()    { return content_rows("SELECT * FROM downloads WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_gallery()      { return content_rows("SELECT * FROM gallery WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_faqs()         { return content_rows("SELECT * FROM faqs WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_consultants()  { return content_rows("SELECT * FROM consultants WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_testimonials($c = null) {
        return ($c !== null && $c !== '')
            ? content_rows("SELECT * FROM testimonials WHERE is_published=1 AND category=? ORDER BY sort_order, id", [$c])
            : content_rows("SELECT * FROM testimonials WHERE is_published=1 ORDER BY sort_order, id");
    }
    function get_page_sections($k)      { return content_rows("SELECT * FROM page_sections WHERE page_key=? AND is_published=1 ORDER BY sort_order, id", [$k]); }
    function get_home_programs()        { return content_rows("SELECT * FROM home_programs WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_home_why()             { return content_rows("SELECT * FROM home_why WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_home_steps()           { return content_rows("SELECT * FROM home_steps WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_home_accreditations()  { return content_rows("SELECT * FROM home_accreditations WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_home_facts()           { return content_rows("SELECT * FROM home_facts WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_mbbs_curriculum()      { return content_rows("SELECT * FROM mbbs_curriculum WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_latest_events($n = 3)  { $n = max(1, (int) $n); return content_rows("SELECT id, title, slug, excerpt, event_date, event_time_start, venue, featured_image FROM events WHERE status = 'published' ORDER BY event_date DESC, id DESC LIMIT $n"); }
    function get_latest_blogs($n = 3)   { $n = max(1, (int) $n); return content_rows("SELECT id, title, slug, excerpt, featured_image, published_at, created_at FROM blogs WHERE status = 'published' ORDER BY COALESCE(published_at, created_at) DESC LIMIT $n"); }
}

if (! function_exists('mail_safe'))  { function mail_safe($v) { return trim(preg_replace('/[\r\n]+/', ' ', (string) $v)); } }
if (! function_exists('valid_email')) { function valid_email($e) { $e = trim((string) $e); return filter_var($e, FILTER_VALIDATE_EMAIL) ? $e : ''; } }
