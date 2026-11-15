<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Curriculum;
use App\Models\Event;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomeAccreditation;
use App\Models\HomeFact;
use App\Models\HomeProgram;
use App\Models\HomeStep;
use App\Models\HomeWhy;
use App\Models\PageSection;
use App\Models\Program;
use App\Models\Scholarship;
use App\Models\Testimonial;
use App\Support\Settings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Read-side queries behind the public pages: admin-managed content (published rows in admin order),
 * latest news/events and settings maps with fallbacks. Replaces the old get_*() helpers inside the templates.
 */
class PublicContent
{
    /** Published rows in the order the admin set (sort_order, id). */
    private function published(string $model): Builder
    {
        return $model::query()->where('is_published', 1)->orderBy('sort_order')->orderBy('id');
    }

    public function programs(): Collection { return $this->published(Program::class)->get(); }

    public function scholarships(): Collection { return $this->published(Scholarship::class)->get(); }

    public function faqs(): Collection { return $this->published(Faq::class)->get(); }

    public function gallery(): Collection { return $this->published(GalleryItem::class)->get(); }

    public function curriculum(): Collection { return $this->published(Curriculum::class)->get(); }

    public function homePrograms(): Collection { return $this->published(HomeProgram::class)->get(); }

    public function homeWhy(): Collection { return $this->published(HomeWhy::class)->get(); }

    public function homeSteps(): Collection { return $this->published(HomeStep::class)->get(); }

    public function homeAccreditations(): Collection { return $this->published(HomeAccreditation::class)->get(); }

    public function homeFacts(): Collection { return $this->published(HomeFact::class)->get(); }

    /** Free-form admin blocks of a page ("about", "mbbs", "fees", "admissions"). */
    public function pageSections(string $pageKey): Collection
    {
        return $this->published(PageSection::class)->where('page_key', $pageKey)->get();
    }

    public function testimonials(?string $category = null): Collection
    {
        $q = $this->published(Testimonial::class);
        if ($category !== null && $category !== '') {
            $q->where('category', $category);
        }

        return $q->get();
    }

    public function latestBlogs(int $n = 3): Collection
    {
        return Blog::query()
            ->select('id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'created_at')
            ->where('status', 'published')
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->limit(max(1, $n))
            ->get();
    }

    public function latestEvents(int $n = 3): Collection
    {
        return Event::query()
            ->select('id', 'title', 'slug', 'excerpt', 'event_date', 'event_time_start', 'venue', 'featured_image')
            ->where('status', 'published')
            ->orderByDesc('event_date')->orderByDesc('id')
            ->limit(max(1, $n))
            ->get();
    }

    /**
     * Settings with fallbacks: [key => value, ...] for a [key => default, ...] map (empty value -> default).
     *
     * @param  array<string, string>  $defaults
     * @return array<string, string>
     */
    public function settings(array $defaults): array
    {
        $out = [];
        foreach ($defaults as $key => $default) {
            $out[$key] = (string) Settings::get($key, $default);
        }

        return $out;
    }

    /**
     * A multi-line setting as rows of "|"-separated cells (blank lines skipped). Every row has at least two cells
     * ([label, value]) so templates never need a null check.
     *
     * @return array<int, array<int, string>>
     */
    public function settingRows(string $key): array
    {
        $rows = [];
        foreach (preg_split('/\r\n|\r|\n/', (string) Settings::get($key, '')) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $cells = array_map('trim', explode('|', $line));
            $cells[1] ??= '';
            $rows[] = $cells;
        }

        return $rows;
    }
}
