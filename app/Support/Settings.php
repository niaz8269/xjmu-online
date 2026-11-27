<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/** Read access to the legacy `site_settings` key/value table (editable by admins). */
class Settings
{
    private static ?array $all = null;

    public static function all(): array
    {
        return self::$all ??= Cache::remember('site_settings', 300, fn () => DB::table('site_settings')->pluck('setting_value', 'setting_key')->all());
    }

    /** Forget the cached copy of the table (after an admin edit). */
    public static function flush(): void
    {
        self::$all = null;
        Cache::forget('site_settings');
    }

    public static function get(string $key, ?string $default = ''): ?string
    {
        $v = self::all()[$key] ?? null;
        return ($v === null || $v === '') ? $default : $v;
    }

    /** Split "A | B | C" per line into rows of trimmed cells. */
    public static function rows(string $key): array
    {
        $out = [];
        foreach (preg_split('/\R/', (string) self::get($key, '')) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $out[] = array_map('trim', explode('|', $line));
            }
        }
        return $out;
    }

    /** Public URL for an image path stored in the DB (images/…, uploads/…, or absolute). */
    public static function asset(?string $path): string
    {
        if (! $path) { return asset('images/campus3.webp'); }
        if (preg_match('#^(https?:)?//#', $path)) { return $path; }
        return asset(ltrim($path, '/'));
    }
}
