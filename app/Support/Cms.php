<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Admin-edited content that overrides the built-in (code) content: table cms_overrides.
 * Keys: "meta:<path>" (page title / description / image), "faqs:<page>", "guide:<slug>", "portal".
 * Reads are cached for 5 minutes and never throw: with no table or no row, the built-in content is used.
 */
class Cms
{
    private const CACHE_KEY = 'cms_overrides';

    private static ?array $all = null;

    /** Built-in applicant-portal texts (the defaults of the "portal" item). */
    public const PORTAL_DEFAULTS = [
        'notice' => '',
        'dashboard_intro' => 'Track your progress, complete your form and upload your documents here.',
        'form_intro' => 'Complete the four sections below. Save each section when you finish it; you can come back at any time before you submit.',
        'file_hint' => 'Accepted files: PDF, Word, JPG or PNG, up to 5 MB each. Video: MP4, MOV or AVI, up to 10 MB.',
        'download_note' => 'Your application is submitted. Download the form as a PDF, or the form with all your uploaded documents as one ZIP file. Keep a copy for your visa application.',
        'copyright' => '© Copyright 2027 Xinjiang Medical University. All Rights Reserved.',
    ];

    /** @return array<string, mixed> every override, decoded */
    public static function all(): array
    {
        if (self::$all !== null) {
            return self::$all;
        }
        try {
            $raw = Cache::remember(self::CACHE_KEY, 300, fn () => DB::table('cms_overrides')->pluck('value', 'item_key')->all());
        } catch (\Throwable $e) {
            $raw = [];   // table not created yet, or the database is unavailable: built-in content is used
        }
        $out = [];
        foreach ($raw as $key => $json) {
            $value = json_decode((string) $json, true);
            if ($value !== null) {
                $out[$key] = $value;
            }
        }

        return self::$all = $out;
    }

    public static function get(string $key, $default = null)
    {
        return self::all()[$key] ?? $default;
    }

    /** Save (or, with null / empty, remove) one override. */
    public static function set(string $key, $value, ?string $by = null): void
    {
        if ($value === null || $value === [] || $value === '') {
            DB::table('cms_overrides')->where('item_key', $key)->delete();
        } else {
            DB::table('cms_overrides')->updateOrInsert(['item_key' => $key], [
                'value' => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_by' => $by, 'updated_at' => now(),
            ]);
        }
        self::flush();
    }

    public static function flush(): void
    {
        self::$all = null;
        Cache::forget(self::CACHE_KEY);
    }

    /** One applicant-portal text (admin value, or the built-in default). */
    public static function portal(string $field): string
    {
        $value = self::get('portal', [])[$field] ?? '';

        return trim((string) $value) !== '' ? (string) $value : (self::PORTAL_DEFAULTS[$field] ?? '');
    }

    /** Title / description / image set in the admin panel for a path such as "/fees" ("/" for the home page). */
    public static function meta(string $path): array
    {
        $meta = self::get('meta:' . $path, []);

        return is_array($meta) ? array_filter($meta, fn ($v) => is_string($v) && trim($v) !== '') : [];
    }

    /**
     * Lay the admin edits over the built-in guides and page FAQs, so every existing reader of
     * config('seo_guides') and config('seo_faqs') sees the edited content. Called once per request at boot.
     */
    public static function applyToConfig(): void
    {
        $all = self::all();
        if (! $all) {
            return;
        }

        $faqs = config('seo_faqs', []);
        $guides = config('seo_guides', []);
        foreach ($all as $key => $value) {
            if (str_starts_with($key, 'faqs:') && is_array($value)) {
                $page = substr($key, 5);
                $rows = array_values(array_filter($value, fn ($r) => is_array($r) && trim((string) ($r[0] ?? '')) !== '' && trim((string) ($r[1] ?? '')) !== ''));
                if ($rows && array_key_exists($page, $faqs)) {
                    $faqs[$page] = $rows;
                }
            } elseif (str_starts_with($key, 'guide:') && is_array($value)) {
                $slug = substr($key, 6);
                if (isset($guides[$slug])) {
                    $guides[$slug] = self::mergeGuide($guides[$slug], $value);
                }
            }
        }
        config(['seo_faqs' => $faqs, 'seo_guides' => $guides]);
    }

    /** Text fields replace the built-in ones; sections are matched by position so images, tables and charts are kept. */
    public static function mergeGuide(array $guide, array $edit): array
    {
        foreach (['title', 'description', 'keywords', 'h1', 'lead', 'answer', 'updated', 'cta', 'crumb', 'eyebrow'] as $field) {
            if (isset($edit[$field]) && is_string($edit[$field]) && trim($edit[$field]) !== '') {
                $guide[$field] = $edit[$field];
            }
        }
        foreach (['facts', 'faqs'] as $field) {
            if (! empty($edit[$field]) && is_array($edit[$field])) {
                $guide[$field] = array_values($edit[$field]);
            }
        }
        foreach ((array) ($edit['sections'] ?? []) as $i => $sec) {
            if (! isset($guide['sections'][$i]) || ! is_array($sec)) {
                continue;
            }
            if (isset($sec['h']) && trim((string) $sec['h']) !== '') {
                $guide['sections'][$i]['h'] = (string) $sec['h'];
            }
            if (! empty($sec['p']) && is_array($sec['p'])) {
                $guide['sections'][$i]['p'] = array_values($sec['p']);
            }
            // Image: caption, description (alt text) and file; only where the built-in section already has an image.
            if (isset($guide['sections'][$i]['img']) && is_array($sec['img'] ?? null)) {
                foreach (['alt', 'caption', 'src'] as $field) {
                    if (isset($sec['img'][$field]) && trim((string) $sec['img'][$field]) !== '') {
                        $guide['sections'][$i]['img'][$field] = (string) $sec['img'][$field];
                    }
                }
            }
            // Table: caption, heading row and rows; only where the built-in section already has a table.
            if (isset($guide['sections'][$i]['table']) && is_array($sec['table'] ?? null)) {
                if (isset($sec['table']['caption']) && trim((string) $sec['table']['caption']) !== '') {
                    $guide['sections'][$i]['table']['caption'] = (string) $sec['table']['caption'];
                }
                if (! empty($sec['table']['head']) && is_array($sec['table']['head'])) {
                    $guide['sections'][$i]['table']['head'] = array_values($sec['table']['head']);
                }
                if (! empty($sec['table']['rows']) && is_array($sec['table']['rows'])) {
                    $guide['sections'][$i]['table']['rows'] = array_values($sec['table']['rows']);
                }
            }
            foreach ((array) ($sec['sub'] ?? []) as $j => $sub) {
                if (! isset($guide['sections'][$i]['sub'][$j]) || ! is_array($sub)) {
                    continue;
                }
                if (isset($sub['h']) && trim((string) $sub['h']) !== '') {
                    $guide['sections'][$i]['sub'][$j]['h'] = (string) $sub['h'];
                }
                if (! empty($sub['p']) && is_array($sub['p'])) {
                    $guide['sections'][$i]['sub'][$j]['p'] = array_values($sub['p']);
                }
            }
        }

        return $guide;
    }
}
