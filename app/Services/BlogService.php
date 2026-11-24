<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** Queries and presentation helpers for the public blog pages (blogs, blog-post). */
class BlogService
{
    public const PER_PAGE = 9;

    private const PLACEHOLDER_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300"><defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:rgb(102,126,234);stop-opacity:1" /><stop offset="100%" style="stop-color:rgb(118,75,162);stop-opacity:1" /></linearGradient></defs><rect width="400" height="300" fill="url(#grad)"/></svg>';

    /** One page of published posts matching the (optional) search, plus featured posts and pagination links. */
    public function listing(string $search, int $currentPage): array
    {
        $hasSearch = ! empty($search);
        $query = Blog::query()->where('status', 'published');
        if ($hasSearch) {
            $like = "%{$search}%";
            $query->where(fn ($q) => $q->where('title', 'like', $like)->orWhere('excerpt', 'like', $like)->orWhere('content', 'like', $like));
        }

        $totalPosts = (clone $query)->count();
        $totalPages = (int) ceil($totalPosts / self::PER_PAGE);

        $showFeatured = $currentPage === 1 && ! $hasSearch;
        $featured = $showFeatured
            ? Blog::query()->where('status', 'published')->where('is_featured', 1)->orderByDesc('published_at')->limit(3)->get()
            : collect();

        $blogs = $query->orderByDesc('published_at')->limit(self::PER_PAGE)->offset(($currentPage - 1) * self::PER_PAGE)->get();

        return [
            'search' => $search,
            'hasSearch' => $hasSearch,
            'featured' => $featured->map(fn (Blog $b) => $this->card($b))->all(),
            'blogs' => $blogs->map(fn (Blog $b) => $this->card($b))->all(),
            'guides' => ($currentPage === 1 && ! $hasSearch) ? $this->guideCards() : [],
            'pagination' => $totalPages > 1 ? $this->pagination($currentPage, $totalPages, $search) : null,
        ];
    }

    /** Published post by slug, or null. */
    public function findPublished(string $slug): ?Blog
    {
        if ($slug === '') {
            return null;
        }
        try {
            return Blog::query()->where('slug', $slug)->where('status', 'published')->first();
        } catch (\Throwable $e) {
            Log::error('blog-post query error: ' . $e->getMessage());
            return null;
        }
    }

    /** Count a view; failures are ignored. */
    public function incrementViews(Blog $blog): void
    {
        try {
            Blog::query()->whereKey($blog->id)->update(['views' => DB::raw('views + 1')]);
        } catch (\Throwable $e) {
        }
    }

    /** Page variables for a post page. Strings are pre-escaped because the layout escapes them again (as before). */
    public function postMeta(Blog $blog): array
    {
        $excerptOrContent = $blog->excerpt ?? $blog->content;
        $seo = config('blog_seo.posts.' . $blog->slug, []); // code-side SEO overrides (config/blog_seo.php)
        return [
            // raw: the layout escapes the title once (pre-escaping here printed "&amp;" in the browser tab)
            'page_title' => self::headTitle(isset($seo['title']) ? (string) $seo['title'] : (string) $blog->title),
            'page_description' => isset($seo['description'])
                ? self::esc($seo['description'])
                : (! empty($blog->meta_description)
                    ? self::esc($blog->meta_description)
                    : self::esc(substr(strip_tags((string) $excerptOrContent), 0, 155)) . '...'),
            'page_keywords' => ! empty($blog->meta_keywords) ? self::esc($blog->meta_keywords) : 'XJMU blog, Xinjiang Medical University',
            'page_url' => get_absolute_url(ltrim($this->path($blog->slug), '/')),
            'page_image' => $this->hasImage($blog->featured_image) ? get_absolute_url($blog->featured_image) : 'https://xjmu.online/images/logo.png',
            'body_class' => 'ds-page',
            'page_css' => ['css/design-system.css', 'css/blog-post.css'],
        ];
    }

    public function publishedDate(Blog $blog): string
    {
        return ! empty($blog->published_at) ? date('F j, Y', strtotime($blog->published_at)) : '';
    }

    public function hasImage(?string $path): bool
    {
        return ! empty($path) && file_exists(public_path($path));
    }

    public function featuredImageUrl(Blog $blog): ?string
    {
        return $this->hasImage($blog->featured_image) ? get_absolute_url($blog->featured_image) : null;
    }

    /** The keyword guides (config/seo_guides.php) as blog cards; they keep their own URLs so rankings are untouched. */
    private function guideCards(): array
    {
        $cards = [];
        foreach (config('seo_guides', []) as $slug => $g) {
            $img = null;
            foreach ($g['sections'] ?? [] as $sec) {
                if (! empty($sec['img']['src'])) {
                    $img = $sec['img']['src'];
                    break;
                }
            }
            $cards[] = [
                'title' => (string) $g['h1'],
                'excerpt' => mb_strimwidth((string) $g['lead'], 0, 180, '...'),
                'slug' => $slug,
                'url' => get_absolute_url($slug),
                'date' => date('F j, Y', strtotime($g['updated'])),
                'image' => $img && file_exists(public_path($img)) ? get_absolute_url($img) : 'data:image/svg+xml,' . urlencode(self::PLACEHOLDER_SVG),
                '_ts' => strtotime($g['updated']),
            ];
        }
        usort($cards, fn ($a, $b) => $b['_ts'] <=> $a['_ts']);
        return $cards;
    }

    private function card(Blog $b): array
    {
        return [
            'title' => (string) $b->title,
            'excerpt' => (string) $b->excerpt,
            'slug' => urlencode((string) $b->slug),
            'date' => date('F j, Y', strtotime((string) $b->published_at)),
            'image' => $this->hasImage($b->featured_image)
                ? get_absolute_url($b->featured_image)
                : 'data:image/svg+xml,' . urlencode(self::PLACEHOLDER_SVG),
        ];
    }

    private function pagination(int $current, int $total, string $search): array
    {
        $suffix = $search ? '&search=' . urlencode($search) : '';
        $link = fn (int $p) => '?page=' . $p . $suffix;
        $pages = [];
        for ($i = max(1, $current - 2); $i <= min($total, $current + 2); $i++) {
            $pages[] = ['n' => $i, 'url' => $link($i), 'active' => $i === $current];
        }
        return [
            'prev' => $current > 1 ? $link($current - 1) : null,
            'pages' => $pages,
            'next' => $current < $total ? $link($current + 1) : null,
        ];
    }

    /** Clean public path of a post: /blog/{slug}. */
    public function path(string $slug): string
    {
        return '/blog/' . rawurlencode($slug);
    }

    /** Longest <title> search engines are told to expect (Bing's site scan flags anything over 70 characters). */
    public const TITLE_MAX = 70;

    /**
     * The <title> of a post: "{title} | XJMU Blog" when it fits in 70 characters, the bare title when only that fits,
     * otherwise the title cut at a word boundary. The visible heading on the page is never shortened.
     */
    public static function headTitle(string $title): string
    {
        // some titles are stored with HTML entities ("PM&amp;DC"); decode first so the tab does not show "&amp;" and the length is true
        $title = trim((string) preg_replace('/\s+/u', ' ', html_entity_decode($title, ENT_QUOTES | ENT_HTML5)));
        $full = $title . ' | XJMU Blog';
        if (mb_strlen($full) <= self::TITLE_MAX) {
            return $full;
        }
        if (mb_strlen($title) <= self::TITLE_MAX) {
            return $title;
        }
        $cut = mb_substr($title, 0, self::TITLE_MAX - 1);
        $space = mb_strrpos($cut, ' ');
        if ($space !== false && $space > self::TITLE_MAX * 0.6) {
            $cut = mb_substr($cut, 0, $space);
        }

        return rtrim($cut, " ,;:-|–—") . '…';
    }

    /**
     * Post bodies written as full HTML pages often start with their own <h1>, which makes two H1s on one page
     * (the page already has the post title as its H1). Render them as <h2> and keep the old size with a CSS hook.
     */
    public static function demoteContentH1(string $html): string
    {
        $html = (string) preg_replace('~<h1(\s[^>]*)?>~i', '<h2 data-was-h1$1>', $html);

        return (string) preg_replace('~</h1\s*>~i', '</h2>', $html);
    }

    /** Matches the original site's htmlspecialchars() default of ENT_COMPAT (apostrophes untouched), so meta tags stay identical to live. */
    private static function esc(string $value): string
    {
        return htmlspecialchars($value, ENT_COMPAT);
    }
}
