<?php
/**
 * Site settings accessor. Requires a $pdo (from db.php) to be available, or it
 * will include db.php itself. Values are cached per-request.
 *
 *   get_setting('contact_phone', '+86 ...')   // read with fallback
 *   setting_url('social_facebook')            // read, return '' if empty
 */

if (!function_exists('settings_all')) {
    function settings_all() {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $cache = [];
        try {
            $pdo = $GLOBALS['pdo'] ?? null;
            if (!$pdo) {
                // db.php assigns a local $pdo when included here; promote it to
                // $GLOBALS so it works even when no page-level DB bootstrap ran.
                require_once __DIR__ . '/../db.php';
                if (!$pdo && isset($GLOBALS['pdo'])) { $pdo = $GLOBALS['pdo']; }
                if ($pdo) { $GLOBALS['pdo'] = $pdo; }
            }
            if (!$pdo) { return $cache; }
            $rows = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
            $cache = $rows ?: [];
        } catch (\Throwable $e) {
            error_log('settings_all: ' . $e->getMessage());
            $cache = [];
        }
        return $cache;
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = '') {
        $all = settings_all();
        $v = $all[$key] ?? null;
        return ($v === null || $v === '') ? $default : $v;
    }
}

if (!function_exists('setting_e')) {
    // HTML-escaped convenience for templates.
    function setting_e($key, $default = '') {
        return htmlspecialchars((string) get_setting($key, $default), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('yt_embed')) {
    /**
     * Turn a YouTube link (or bare 11-char video ID) into an embeddable URL.
     * Accepts watch?v=, youtu.be/, /embed/, /shorts/ or a plain ID.
     * Returns '' when empty/unrecognised so callers can hide the player.
     */
    function yt_embed($urlOrId) {
        $v = trim((string) $urlOrId);
        if ($v === '') return '';
        if (preg_match('~^[A-Za-z0-9_-]{11}$~', $v)) {
            return 'https://www.youtube.com/embed/' . $v;
        }
        if (preg_match('~(?:youtu\.be/|[?&]v=|/embed/|/shorts/|/v/)([A-Za-z0-9_-]{11})~', $v, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        return '';
    }

    /** Convenience: read a video setting and return its embed URL (or ''). */
    function video_embed($key) { return yt_embed(get_setting($key, '')); }
}
