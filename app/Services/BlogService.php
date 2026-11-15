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
        return [
            'page_title' => htmlspecialchars((string) $blog->title) . ' | XJMU Blog',
            'page_description' => ! empty($blog->meta_description)
                ? htmlspecialchars($blog->meta_description)
                : htmlspecialchars(substr(strip_tags((string) $excerptOrContent), 0, 155)) . '...',
            'page_keywords' => ! empty($blog->meta_keywords) ? htmlspecialchars($blog->meta_keywords) : 'XJMU blog, Xinjiang Medical University',
            'page_url' => get_absolute_url('blog-post?slug=' . urlencode($blog->slug)),
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
}
