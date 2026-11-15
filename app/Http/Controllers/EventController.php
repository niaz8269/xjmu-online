<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(private EventService $events)
    {
    }

    /** /events: all published events, newest first. */
    public function index()
    {
        $tableExists = $this->events->tableExists();

        return view('pages.events.index', [
            'table_exists' => $tableExists,
            'events' => $tableExists ? $this->events->cards() : [],
            'page_title' => 'News & Events | Xinjiang Medical University',
            'page_description' => 'Stay updated with the latest campus events, webinars, open days, and news at Xinjiang Medical University. Browse upcoming activities for 2026 students.',
            'page_keywords' => 'XJMU events, campus events, medical university events, Xinjiang Medical University news, webinars',
            'page_url' => 'https://xjmu.online/events',
            'page_image' => 'https://xjmu.online/images/logo.png',
            'body_class' => 'ds-page',
            'page_css' => ['css/design-system.css', 'css/events.css'],
        ]);
    }

    /** /event?slug=...: one published event; 404 page when missing. */
    public function show(Request $request)
    {
        $slug = $request->query('slug');
        $slug = is_string($slug) ? trim($slug) : '';
        $event = $this->events->findPublished($slug);

        if (! $event) {
            return response()->view('pages.events.not-found', [
                'page_title' => 'Event Not Found | XJMU',
                'page_description' => 'The event you requested could not be found.',
                'page_keywords' => 'XJMU events',
                'page_url' => 'https://xjmu.online/events',
                'page_image' => 'https://xjmu.online/images/logo.png',
                'body_class' => 'ds-page',
                'page_css' => ['css/design-system.css', 'css/event.css'],
            ], 404);
        }

        return view('pages.events.show', $this->events->postMeta($event) + [
            'event' => $event,
            'detail' => $this->events->detail($event),
        ]);
    }
}
