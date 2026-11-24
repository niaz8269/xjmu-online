<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

/** Dynamic sitemap.xml (same URLs, priorities and Content-Type as the original sitemap.php). */
class SitemapController extends Controller
{
    private const STATIC = [
        ['/',               'daily',   '1.0'],
        ['/mbbs',           'weekly',  '1.0'],
        ['/programs',       'weekly',  '0.9'],
        ['/admissions',     'weekly',  '0.9'],
        ['/fees',           'monthly', '0.9'],
        ['/adm-HtApply',    'monthly', '0.8'],
        ['/about',          'monthly', '0.8'],
        ['/contact',        'monthly', '0.8'],
        ['/scholarships',   'monthly', '0.8'],
        ['/blogs',          'daily',   '0.8'],
        ['/events',         'weekly',  '0.8'],
        ['/gallery',        'monthly', '0.7'],
        ['/downloads',      'monthly', '0.7'],
        ['/support',        'monthly', '0.7'],
        ['/be-our-partner', 'monthly', '0.6'],
        ['/privacy',        'yearly',  '0.4'],
        ['/terms',          'yearly',  '0.4'],
        ['/site-map',       'monthly', '0.5'],
    ];

    public function __invoke()
    {
        $base = 'https://xjmu.online';

        $blogs = [];
        try {
            $blogs = DB::table('blogs')->where('status', 'published')->orderByDesc('published_at')->get(['slug', 'published_at', 'updated_at'])->all();
        } catch (\Throwable $e) {
        }
        $events = [];
        try {
            $events = DB::table('events')->where('status', 'published')->orderByDesc('event_date')->get(['slug', 'event_date'])->all();
        } catch (\Throwable $e) {
        }

        // lastmod is the real date the page last changed; with no known date the tag is left out (never "today").
        $url = fn (string $loc, ?string $lastmod, string $freq, string $prio) => "  <url>\n    <loc>{$loc}</loc>\n" . ($lastmod ? "    <lastmod>{$lastmod}</lastmod>\n" : '') . "    <changefreq>{$freq}</changefreq>\n    <priority>{$prio}</priority>\n  </url>\n";
        $newestPost = 0;
        foreach ($blogs as $b) {
            $newestPost = max($newestPost, strtotime((string) $b->published_at) ?: 0, strtotime((string) ($b->updated_at ?? '')) ?: 0);
        }
        $newestEvent = 0;
        foreach ($events as $e) {
            $newestEvent = max($newestEvent, strtotime((string) $e->event_date) ?: 0);
        }
        $dates = config('sitemap_dates', []);
        $dates['/blogs'] = $newestPost ? date('Y-m-d', $newestPost) : null;
        $dates['/events'] = $newestEvent ? date('Y-m-d', $newestEvent) : null;

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n"
            . "        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n"
            . "        xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n"
            . "        https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n\n";

        foreach (self::STATIC as [$loc, $freq, $prio]) {
            $xml .= $url($base . $loc, $dates[$loc] ?? null, $freq, $prio);
        }
        foreach (config('seo_guides', []) as $slug => $guide) {
            $xml .= $url($base . '/' . $slug, $guide['updated'], 'weekly', '0.9');
        }
        $xml .= "\n";
        foreach ($blogs as $b) {
            $t = max(strtotime((string) $b->published_at) ?: 0, strtotime((string) ($b->updated_at ?? '')) ?: 0);
            $xml .= $url($base . '/blog/' . rawurlencode($b->slug), $t ? date('Y-m-d', $t) : null, 'monthly', '0.7');
        }
        $xml .= "\n";
        foreach ($events as $e) {
            $xml .= $url($base . '/event?slug=' . urlencode($e->slug), ! empty($e->event_date) ? date('Y-m-d', strtotime($e->event_date)) : null, 'weekly', '0.6');
        }
        $xml .= "\n</urlset>\n";

        return response($xml)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
