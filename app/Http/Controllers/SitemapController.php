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
    ];

    public function __invoke()
    {
        $base = 'https://xjmu.online';
        $today = date('Y-m-d');

        $blogs = [];
        try {
            $blogs = DB::table('blogs')->where('status', 'published')->orderByDesc('published_at')->get(['slug', 'published_at'])->all();
        } catch (\Throwable $e) {
        }
        $events = [];
        try {
            $events = DB::table('events')->where('status', 'published')->orderByDesc('event_date')->get(['slug', 'event_date'])->all();
        } catch (\Throwable $e) {
        }

        $url = fn (string $loc, string $lastmod, string $freq, string $prio) => "  <url>\n    <loc>{$loc}</loc>\n    <lastmod>{$lastmod}</lastmod>\n    <changefreq>{$freq}</changefreq>\n    <priority>{$prio}</priority>\n  </url>\n";

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n"
            . "        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n"
            . "        xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n"
            . "        https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n\n";

        foreach (self::STATIC as [$loc, $freq, $prio]) {
            $xml .= $url($base . $loc, $today, $freq, $prio);
        }
        $xml .= "\n";
        foreach ($blogs as $b) {
            $lastmod = ! empty($b->published_at) ? date('Y-m-d', strtotime($b->published_at)) : $today;
            $xml .= $url($base . '/blog-post?slug=' . urlencode($b->slug), $lastmod, 'monthly', '0.7');
        }
        $xml .= "\n";
        foreach ($events as $e) {
            $lastmod = ! empty($e->event_date) ? date('Y-m-d', strtotime($e->event_date)) : $today;
            $xml .= $url($base . '/event?slug=' . urlencode($e->slug), $lastmod, 'weekly', '0.6');
        }
        $xml .= "\n</urlset>\n";

        return response($xml)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
