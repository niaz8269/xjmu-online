<?php

namespace App\Http\Controllers;

use App\Services\PublicContent;
use App\Support\FaqSchema;

/**
 * Content pages: about, admissions, MBBS fees, student support and the static legal / partner pages.
 * The <head> data of each page lives in config/public_pages.php.
 */
class PageController extends Controller
{
    public function __construct(private PublicContent $content)
    {
    }

    private function page(string $key, string $view, array $data = [])
    {
        return view('pages.' . $view, config('public_pages.' . $key) + $data);
    }

    /** @return array<string, string> embed URLs of the page's admin-set videos, keyed by section */
    private function videos(string $prefix, array $sections): array
    {
        $out = [];
        foreach ($sections as $section) {
            $out[$section] = video_embed("video_{$prefix}_{$section}");
        }

        return $out;
    }

    public function about()
    {
        return $this->page('about', 'about', [
            'aboutSections' => $this->content->pageSections('about'),
            'videos' => $this->videos('about', ['history', 'leadership', 'vision', 'campus', 'international']),
        ]);
    }

    public function admissions()
    {
        return $this->page('admissions', 'admissions', [
            'admissionsSections' => $this->content->pageSections('admissions'),
            'videos' => $this->videos('admissions', ['overview', 'requirements', 'process', 'dates']),
        ]);
    }

    public function fees()
    {
        // The whole list is in config/seo_faqs.php ('fees') and editable in the admin panel (SEO & Guides > Page FAQs).
        $faqs = array_map(fn ($r) => ['q' => $r[0], 'a' => $r[1]], config('seo_faqs.fees', []));

        return $this->page('fees', 'fees', [
            'feeSections' => $this->content->pageSections('fees'),
            'feeFaqs' => $faqs,
            'faqSchema' => FaqSchema::items(array_map(fn ($f) => [$f['q'], $f['a']], $faqs)),
        ]);
    }

    public function support()
    {
        return $this->page('support', 'support', ['faqs' => $this->content->faqs()]);
    }

    public function privacy()
    {
        return $this->page('privacy', 'privacy');
    }

    public function terms()
    {
        return $this->page('terms', 'terms');
    }

    /** HTML site map: every public page, guide and article, so each is two clicks from the home page. */
    public function siteMap()
    {
        $pages = [
            ['/', 'Home'], ['/mbbs', 'MBBS in China at XJMU'], ['/programs', 'Programs'], ['/admissions', 'Admissions'],
            ['/fees', 'Fees'], ['/scholarships', 'Scholarships'], ['/adm-HtApply', 'How to apply'], ['/about', 'About the university'],
            ['/contact', 'Contact'], ['/support', 'Student support'], ['/events', 'Events'], ['/gallery', 'Gallery'],
            ['/downloads', 'Downloads'], ['/be-our-partner', 'Become a partner'], ['/blogs', 'All news and articles'],
        ];
        $guides = [];
        foreach (config('seo_guides', []) as $slug => $g) {
            $guides[] = ['/' . $slug, $g['h1']];
        }
        $posts = [];
        try {
            $posts = \Illuminate\Support\Facades\DB::table('blogs')->where('status', 'published')->orderByDesc('published_at')->get(['slug', 'title'])
                ->map(fn ($b) => ['/blog/' . rawurlencode($b->slug), $b->title])->all();
        } catch (\Throwable $e) {
        }

        return $this->page('site-map', 'site-map', ['mapPages' => $pages, 'mapGuides' => $guides, 'mapPosts' => $posts]);
    }

    public function partner()
    {
        return $this->page('be-our-partner', 'be-our-partner');
    }
}
