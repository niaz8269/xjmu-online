@extends('layouts.site')
@section('content')
    <div class="events-page">
        <section class="events-hero">
            <div class="container">
                <span class="ds-eyebrow" style="color:#ffd0d2">Campus Life</span>
                <h1 data-i18n="events.hero_title">News &amp; Events</h1>
                <p data-i18n="events.hero_desc">Latest campus events, webinars, and news from Xinjiang Medical University.</p>
            </div>
        </section>

        <div class="container">
            <nav class="events-crumb" aria-label="breadcrumb">
                <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>Events</span>
            </nav>
        </div>

        <section class="events-section">
            <div class="events-container">
                @if (!$table_exists)
                    <div class="events-empty">
                        <p data-i18n="events.setup_message">Events will appear here once the database table is set up.</p>
                        <p class="text-muted small">Run <code>install_events_table.sql</code> in phpMyAdmin, then add events from the admin panel.</p>
                    </div>
                @elseif (empty($events))
                    <div class="events-empty">
                        <h3 data-i18n="events.no_events">No upcoming events</h3>
                        <p data-i18n="events.no_events_desc">Check back later for campus events, webinars, and news.</p>
                    </div>
                @else
                    <div class="events-list">
                        @foreach ($events as $event)
                            <article class="event-card">
                                <div class="event-card-image">
                                    <a href="event?slug={{ $event['slug'] }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}" loading="lazy">
                                    </a>
                                    <span class="event-card-date">{{ $event['date'] }}</span>
                                </div>
                                <div class="event-card-content">
                                    <h2 class="event-card-title">
                                        <a href="event?slug={{ $event['slug'] }}" target="_blank" rel="noopener noreferrer">{{ $event['title'] }}</a>
                                    </h2>
                                    @if ($event['time'])
                                        <p class="event-time"><i class="fas fa-clock"></i> {{ $event['time'] }}</p>
                                    @endif
                                    @if (!empty($event['venue']))
                                        <p class="event-venue"><i class="fas fa-map-marker-alt"></i> {{ $event['venue'] }}</p>
                                    @endif
                                    @if (!empty($event['excerpt']))
                                        <p class="event-excerpt">{{ $event['excerpt'] }}</p>
                                    @endif
                                    <div class="event-card-actions">
                                        <a href="event?slug={{ $event['slug'] }}" class="event-card-link" target="_blank" rel="noopener noreferrer" data-i18n="events.view_details">View details</a>
                                        <button type="button" class="btn btn-sm btn-danger event-register-btn" data-event-title="{{ $event['title'] }}" data-event-date="{{ $event['register_date'] }}" data-event-time="{{ $event['register_time'] }}" data-i18n="events.register_btn">Register</button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>
@include('partials.event-register-modal')
@endsection
