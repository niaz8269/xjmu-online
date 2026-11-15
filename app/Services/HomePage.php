<?php

namespace App\Services;

use App\Support\FaqSchema;
use App\Support\Settings;

/** Builds the view data of the home page (`pages.index`): settings text, news, events, gallery, testimonials, FAQs. */
class HomePage
{
    public function __construct(private PublicContent $content)
    {
    }

    /** Resolve an image path from the DB (absolute URL, root-relative path or stored relative path) to a usable URL. */
    public function imageUrl(?string $path, string $fallback = 'images/campus2.webp'): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return get_absolute_url($fallback);
        }
        if (preg_match('#^https?://#i', $path) || $path[0] === '/') {
            return $path;
        }

        return get_absolute_url($path);
    }

    /** @return array<string, mixed> */
    public function data(): array
    {
        $blogs = $this->content->latestBlogs(4);
        $events = $this->content->latestEvents(3);
        $gallery = $this->content->gallery();

        // Dynamic FAQs (admin-managed); fall back to the core MBBS FAQs when none are set.
        $faqs = $this->content->faqs()
            ->map(fn ($f) => ['question' => (string) $f->question, 'answer' => (string) $f->answer])->all();
        if (! $faqs) {
            $faqs = config('public_pages.home_faqs_fallback');
        }

        $news = $blogs->map(fn ($b) => [
            'href' => get_absolute_url('blog/' . rawurlencode($b->slug)),
            'img' => $this->imageUrl($b->featured_image),
            'date' => $this->formatDate($b->published_at ?: $b->created_at, 'F j, Y'),
            'title' => $b->title,
            'excerpt' => mb_strimwidth((string) $b->excerpt, 0, 170, '…'),
        ])->all();

        $firstEvent = $events->first();

        $bannerLink = (string) Settings::get('home_about_banner_link', '#mbbs-xjmu');
        $bannerHref = (str_starts_with($bannerLink, '#') || preg_match('#^https?://#', $bannerLink)) ? $bannerLink : get_absolute_url($bannerLink);

        $stat = fn (int $i, string $default) => (int) Settings::get("home_stat{$i}_num", $default);

        return [
            't' => $this->content->settings(config('public_pages.home_settings')),
            'faqSchema' => FaqSchema::items(array_map(fn ($f) => [$f['question'], $f['answer']], $faqs)),
            'homeFaqs' => $faqs,
            'newsFeat' => $news[0] ?? null,
            'newsRest' => array_slice($news, 1, 3),
            'homeAccred' => $this->content->homeAccreditations(),
            'latestEvents' => $events->map(fn ($e) => [
                'href' => get_absolute_url('event?slug=' . urlencode($e->slug)),
                'date' => $this->formatDate($e->event_date, 'M j, Y'),
                'title' => $e->title,
                'venue' => $e->venue,
            ])->all(),
            'evImg' => $this->imageUrl(($firstEvent?->featured_image ?? '') ?: 'images/campus9.webp', 'images/campus9.webp'),
            'media' => $this->galleryTiles($gallery->take(3)),
            'campus' => $this->galleryTiles($gallery->take(8)),
            'stats' => [1 => $stat(1, '65'), 2 => $stat(2, '2000'), 3 => $stat(3, '45'), 4 => $stat(4, '500')],
            'banner' => [
                'href' => $bannerHref,
                'img' => $this->imageUrl(Settings::get('home_about_banner_image', 'images/about.jpeg'), 'images/about.jpeg'),
            ],
            'homePrograms' => $this->content->homePrograms()->map(fn ($p) => [
                'url' => ! empty($p->url) ? $p->url : get_absolute_url('programs'),
                'title' => $p->title,
                'description' => $p->description,
            ])->all(),
            'homeFacts' => $this->content->homeFacts(),
            'homeWhy' => $this->content->homeWhy(),
            'homeSteps' => $this->content->homeSteps(),
            'students' => $this->testimonialCards('student'),
            'alumni' => $this->testimonialCards('alumni'),
        ];
    }

    private function formatDate(?string $value, string $format): string
    {
        $ts = strtotime((string) $value);

        return $ts ? date($format, $ts) : '';
    }

    /** @return array<int, array{img: string, alt: string, title: ?string}> */
    private function galleryTiles($items): array
    {
        return $items->map(fn ($g) => [
            'img' => $this->imageUrl($g->image_path),
            'alt' => $g->title ?: 'Campus',
            'title' => $g->title,
        ])->values()->all();
    }

    /** @return array<int, array{quote: ?string, img: string, name: ?string, meta: string}> */
    private function testimonialCards(string $category): array
    {
        return $this->content->testimonials($category)->map(fn ($t) => [
            'quote' => $t->quote,
            'img' => $this->imageUrl($t->photo, 'images/avatar.svg'),
            'name' => $t->name,
            'meta' => trim(($t->role ?? '') . (! empty($t->location) ? ' · ' . $t->location : ''), ' ·'),
        ])->all();
    }
}
