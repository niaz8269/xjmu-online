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

    public function partner()
    {
        return $this->page('be-our-partner', 'be-our-partner');
    }
}
