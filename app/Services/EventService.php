<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/** Queries and presentation helpers for the public events pages (events, event). */
class EventService
{
    private const PLACEHOLDER_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="250"><defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#e72128"/><stop offset="100%" style="stop-color:#cc0000"/></linearGradient></defs><rect width="400" height="250" fill="url(#g)"/><text x="200" y="130" fill="white" text-anchor="middle" font-size="18" font-family="sans-serif">Event</text></svg>';

    public function tableExists(): bool
    {
        return Schema::hasTable('events');
    }

    /** Card data for every published event, newest first. */
    public function cards(): array
    {
        return Event::query()
            ->where('status', 'published')
            ->orderByDesc('event_date')->orderByDesc('id')
            ->get(['id', 'title', 'slug', 'excerpt', 'event_date', 'event_end_date', 'event_time_start', 'event_time_end', 'venue', 'featured_image', 'status'])
            ->map(function (Event $e) {
                $time = $this->time($e->event_time_start ?? '', $e->event_time_end);
                return [
                    'title' => (string) $e->title,
                    'slug' => urlencode((string) $e->slug),
                    'excerpt' => (string) $e->excerpt,
                    'venue' => (string) $e->venue,
                    'image' => $this->imageOrPlaceholder($e->featured_image ?? ''),
                    'date' => $this->date('M j, Y', $e->event_date, $e->event_end_date),
                    'time' => $time,
                    'register_date' => date('j F Y', strtotime((string) $e->event_date)),
                    'register_time' => $time ?: '2PM to 5PM',
                ];
            })->all();
    }

    public function findPublished(string $slug): ?Event
    {
        if ($slug === '') {
            return null;
        }
        try {
            return Event::query()->where('slug', $slug)->where('status', 'published')->first();
        } catch (\Throwable $e) {
            Log::error('event query error: ' . $e->getMessage());
            return null;
        }
    }

    /** Page variables for an event page (strings pre-escaped; the layout escapes them again, as before). */
    public function postMeta(Event $event): array
    {
        return [
            'page_title' => htmlspecialchars((string) $event->title) . ' | XJMU Events',
            'page_description' => ! empty($event->excerpt)
                ? htmlspecialchars(substr(strip_tags($event->excerpt), 0, 155))
                : 'Campus event at Xinjiang Medical University.',
            'page_keywords' => 'XJMU events, Xinjiang Medical University, campus event',
            'page_url' => get_absolute_url('event?slug=' . urlencode($event->slug)),
            'page_image' => $this->hasImage($event->featured_image) ? get_absolute_url($event->featured_image) : 'https://xjmu.online/images/logo.png',
            'body_class' => 'ds-page',
            'page_css' => ['css/design-system.css', 'css/event.css'],
        ];
    }

    /** Detail-page view data: formatted date line, time, image URL. */
    public function detail(Event $event): array
    {
        return [
            'date' => ! empty($event->event_date) ? $this->date('F j, Y', $event->event_date, $event->event_end_date) : '',
            'time' => $this->time($event->event_time_start ?? '', $event->event_time_end),
            'image' => $this->hasImage($event->featured_image) ? get_absolute_url($event->featured_image) : null,
        ];
    }

    public function hasImage(?string $path): bool
    {
        return ! empty($path) && file_exists(public_path($path));
    }

    private function imageOrPlaceholder(?string $path): string
    {
        return $this->hasImage($path)
            ? get_absolute_url($path)
            : 'data:image/svg+xml,' . urlencode(self::PLACEHOLDER_SVG);
    }

    private function date(string $format, $start, $end = null): string
    {
        $s = date($format, strtotime((string) $start));
        if ($end && $end !== $start) {
            $s .= ' – ' . date($format, strtotime((string) $end));
        }
        return $s;
    }

    private function time($start, $end = null): string
    {
        if (empty($start)) {
            return '';
        }
        $s = date('g:i A', strtotime('1970-01-01 ' . $start));
        if (! empty($end) && $end !== $start) {
            $s .= ' – ' . date('g:i A', strtotime('1970-01-01 ' . $end));
        }
        return $s;
    }
}
