<?php

namespace App\Http\Controllers\Admin;

use App\Support\Cms;
use Illuminate\Http\Request;

/**
 * Content that used to be editable only in code files (table cms_overrides, see App\Support\Cms):
 * page titles and SEO tags, page FAQs, the guides and the applicant-portal texts.
 */
class CmsController extends AdminController
{
    /** Pages whose title / description / share image can be set here: path => [label, public_pages key or null]. */
    private const SEO_PAGES = [
        '/' => ['Home', 'home'], '/mbbs' => ['MBBS programme', 'mbbs'], '/programs' => ['All programs', 'programs'],
        '/admissions' => ['Admissions', 'admissions'], '/fees' => ['Fees', 'fees'], '/scholarships' => ['Scholarships', 'scholarships'],
        '/about' => ['About', 'about'], '/contact' => ['Contact', null], '/blogs' => ['Blog list', null], '/events' => ['Events', null],
        '/gallery' => ['Gallery', null], '/downloads' => ['Downloads', null], '/support' => ['Student support', 'support'],
        '/be-our-partner' => ['Become our partner', 'be-our-partner'], '/adm-HtApply' => ['How to apply', null],
    ];

    private const FAQ_PAGES = ['home' => 'Home', 'mbbs' => 'MBBS programme', 'fees' => 'Fees', 'admissions' => 'Admissions', 'scholarships' => 'Scholarships'];

    // ------------------------------------------------------------------ page titles and SEO tags
    /** GET|POST /admin/seo */
    public function seo(Request $request)
    {
        if ($request->isMethod('post')) {
            foreach (array_keys(self::SEO_PAGES) as $i => $path) {
                $value = array_filter([
                    'title' => $this->line($request->input("title.$i"), 120),
                    'description' => $this->line($request->input("description.$i"), 320),
                    'image' => $this->imageUrl($request->input("image.$i")),
                ]);
                Cms::set('meta:' . $path, $value ?: null, $this->who($request));
            }
            session()->put('cms_flash', 'Page titles and descriptions saved.');

            return redirect('/admin/seo');
        }

        $pages = [];
        foreach (self::SEO_PAGES as $path => [$label, $key]) {
            $builtIn = $key ? (array) config('public_pages.' . ($key === 'home' ? 'index' : $key), config('public_pages.' . $key, [])) : [];
            $pages[] = ['path' => $path, 'label' => $label, 'value' => Cms::get('meta:' . $path, []),
                'builtTitle' => (string) ($builtIn['page_title'] ?? ''), 'builtDescription' => (string) ($builtIn['page_description'] ?? '')];
        }

        return $this->page('admin.cms.seo', 'Page titles and SEO', 'seo', 'The title and description Google shows for each page', [
            'pages' => $pages, 'flash' => session()->pull('cms_flash'),
        ]);
    }

    // ------------------------------------------------------------------ page FAQs
    /** GET|POST /admin/page-faqs?page=home */
    public function faqs(Request $request)
    {
        $page = (string) $request->query('page', 'home');
        if (! isset(self::FAQ_PAGES[$page])) {
            $page = 'home';
        }

        if ($request->isMethod('post')) {
            if ($request->input('action') === 'reset') {
                Cms::set('faqs:' . $page, null);
                session()->put('cms_flash', 'The built-in FAQs are restored for this page.');
            } else {
                $rows = $this->pairs($request->input('q'), $request->input('a'), 300, 2000);
                Cms::set('faqs:' . $page, $rows ?: null, $this->who($request));
                session()->put('cms_flash', count($rows) . ' FAQ' . (count($rows) === 1 ? '' : 's') . ' saved for this page.');
            }

            return redirect('/admin/page-faqs?page=' . $page);
        }

        return $this->page('admin.cms.faqs', 'Page FAQs', 'page-faqs', 'Questions shown on the page and marked up for Google', [
            'page' => $page, 'pages' => self::FAQ_PAGES, 'faqs' => config('seo_faqs.' . $page, []),
            'edited' => Cms::get('faqs:' . $page) !== null, 'flash' => session()->pull('cms_flash'),
        ]);
    }

    // ------------------------------------------------------------------ guides
    /** GET /admin/guides */
    public function guides()
    {
        $rows = [];
        foreach (config('seo_guides', []) as $slug => $g) {
            $rows[] = ['slug' => $slug, 'title' => $g['title'], 'h1' => $g['h1'], 'updated' => $g['updated'],
                'faqs' => count($g['faqs'] ?? []), 'edited' => Cms::get('guide:' . $slug) !== null];
        }

        return $this->page('admin.cms.guides', 'Guides', 'guides', 'The long SEO pages such as "MBBS in China 2027"', [
            'guides' => $rows, 'flash' => session()->pull('cms_flash'),
        ]);
    }

    /** GET|POST /admin/guide-edit?slug= */
    public function guide(Request $request)
    {
        $slug = (string) $request->query('slug');
        $guide = config('seo_guides.' . $slug);
        if (! $guide) {
            return redirect('/admin/guides');
        }

        if ($request->isMethod('post')) {
            if ($request->input('action') === 'reset') {
                Cms::set('guide:' . $slug, null);
                session()->put('cms_flash', 'The built-in text is restored for this guide.');

                return redirect('/admin/guide-edit?slug=' . $slug);
            }

            $edit = array_filter([
                'title' => $this->line($request->input('title'), 120),
                'description' => $this->line($request->input('description'), 320),
                'h1' => $this->line($request->input('h1'), 200),
                'lead' => $this->text($request->input('lead'), 1000),
                'answer' => $this->text($request->input('answer'), 1000),
                'updated' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $request->input('updated')) ? (string) $request->input('updated') : '',
            ]);
            $edit['facts'] = $this->pairs($request->input('fact_label'), $request->input('fact_value'), 120, 300);
            $edit['faqs'] = $this->pairs($request->input('q'), $request->input('a'), 300, 2000);

            $sections = [];
            foreach ((array) $request->input('sec', []) as $i => $sec) {
                $i = (int) $i;
                if (! isset($guide['sections'][$i])) {
                    continue;
                }
                $row = array_filter(['h' => $this->line($sec['h'] ?? '', 200), 'p' => $this->paragraphs($sec['p'] ?? '')]);
                if (isset($guide['sections'][$i]['img'])) {
                    $src = ltrim(trim((string) ($sec['img']['src'] ?? '')), '/');
                    $img = array_filter([
                        'alt' => $this->line($sec['img']['alt'] ?? '', 300),
                        'caption' => $this->line($sec['img']['caption'] ?? '', 300),
                        // Only an existing image file inside the site's own image folders.
                        'src' => preg_match('~^(images|assets/images|uploads/(blogs|gallery|events))/[A-Za-z0-9 ._()-]+\.(webp|jpe?g|png)$~i', $src)
                            && is_file(public_path($src)) ? $src : '',
                    ]);
                    if ($img) {
                        $row['img'] = $img;
                    }
                }
                if (isset($guide['sections'][$i]['table'])) {
                    $lines = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) ($sec['table']['rows'] ?? ''))), fn ($l) => $l !== ''));
                    $cells = fn (string $line) => array_map(fn ($c) => $this->line($c, 300), explode('|', $line));
                    $table = array_filter([
                        'caption' => $this->line($sec['table']['caption'] ?? '', 300),
                        'head' => $lines ? $cells(array_shift($lines)) : [],
                        'rows' => array_map($cells, $lines),
                    ]);
                    // Keep the table rectangular: every row needs as many cells as the heading row.
                    if (! empty($table['head']) && ! empty($table['rows'])
                        && count(array_filter($table['rows'], fn ($r) => count($r) !== count($table['head']))) === 0) {
                        $row['table'] = $table;
                    } elseif (! empty($table['caption'])) {
                        $row['table'] = ['caption' => $table['caption']];
                    }
                }
                foreach ((array) ($sec['sub'] ?? []) as $j => $sub) {
                    $j = (int) $j;
                    if (isset($guide['sections'][$i]['sub'][$j])) {
                        $subRow = array_filter(['h' => $this->line($sub['h'] ?? '', 200), 'p' => $this->paragraphs($sub['p'] ?? '')]);
                        if ($subRow) {
                            $row['sub'][$j] = $subRow;
                        }
                    }
                }
                if ($row) {
                    $sections[$i] = $row;
                }
            }
            $edit['sections'] = $sections;
            $edit = array_filter($edit);

            Cms::set('guide:' . $slug, $edit ?: null, $this->who($request));
            session()->put('cms_flash', 'Guide saved. The live page now shows your text.');

            return redirect('/admin/guide-edit?slug=' . $slug);
        }

        return $this->page('admin.cms.guide', 'Edit guide', 'guides', '/' . $slug, [
            'slug' => $slug, 'guide' => $guide, 'edited' => Cms::get('guide:' . $slug) !== null, 'flash' => session()->pull('cms_flash'),
        ]);
    }

    // ------------------------------------------------------------------ applicant-portal texts
    /** GET|POST /admin/portal-texts */
    public function portal(Request $request)
    {
        if ($request->isMethod('post')) {
            $value = [];
            foreach (array_keys(Cms::PORTAL_DEFAULTS) as $field) {
                $text = $this->text($request->input($field), 1000);
                if ($text !== '' && $text !== Cms::PORTAL_DEFAULTS[$field]) {
                    $value[$field] = $text;
                }
            }
            Cms::set('portal', $value ?: null, $this->who($request));
            session()->put('cms_flash', 'Portal texts saved.');

            return redirect('/admin/portal-texts');
        }

        $fields = [];
        foreach (Cms::PORTAL_DEFAULTS as $field => $default) {
            $fields[$field] = ['value' => Cms::portal($field), 'default' => $default];
        }

        return $this->page('admin.cms.portal', 'Applicant portal texts', 'portal-texts', 'Notices and help text students see after signing in', [
            'fields' => $fields, 'flash' => session()->pull('cms_flash'),
        ]);
    }

    // ------------------------------------------------------------------ helpers
    private function who(Request $request): string
    {
        return (string) ($request->session()->get('full_name') ?: 'Admin');
    }

    /** One line of plain text. */
    private function line($value, int $max): string
    {
        return mb_substr(trim(preg_replace('/\s+/', ' ', (string) $value)), 0, $max);
    }

    /** Plain text that may have line breaks. */
    private function text($value, int $max): string
    {
        return mb_substr(trim(str_replace("\r", '', (string) $value)), 0, $max);
    }

    /** Paragraphs are separated by a blank line. */
    private function paragraphs($value): array
    {
        $parts = preg_split('/\n\s*\n/', str_replace("\r", '', (string) $value)) ?: [];

        return array_values(array_filter(array_map(fn ($p) => trim(preg_replace('/\s+/', ' ', $p)), $parts), fn ($p) => $p !== ''));
    }

    /** @return array<int, array{0: string, 1: string}> rows where both cells are filled */
    private function pairs($first, $second, int $maxFirst, int $maxSecond): array
    {
        $rows = [];
        foreach ((array) $first as $i => $a) {
            $a = $this->line($a, $maxFirst);
            $b = $this->text(((array) $second)[$i] ?? '', $maxSecond);
            if ($a !== '' && $b !== '') {
                $rows[] = [$a, $b];
            }
        }

        return $rows;
    }

    /** Only an image on this site, or an https URL. */
    private function imageUrl($value): string
    {
        $value = trim((string) $value);

        return $value !== '' && preg_match('~^https://[^\s"<>]+$~i', $value) ? mb_substr($value, 0, 300) : '';
    }
}
