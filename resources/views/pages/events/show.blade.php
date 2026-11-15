@extends('layouts.site')
@section('content')

<div class="event-post-page">

    <!-- Hero -->
    <section class="ev-hero">
        <div class="container">
            <span class="ds-eyebrow" style="color:#ffd0d2">Event</span>
            <h1>{{ $event->title }}</h1>
            @if (!empty($event->event_date))
            <p class="ev-meta">
                <i class="fas fa-calendar-alt"></i>
                {{ $detail['date'] }}
                @if ($detail['time'])
                    &nbsp;&middot;&nbsp;<i class="fas fa-clock"></i> {{ $detail['time'] }}
                @endif
            </p>
            @endif
            @if (!empty($event->venue))
            <p class="ev-meta"><i class="fas fa-map-marker-alt"></i> {{ $event->venue }}</p>
            @endif
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="container">
        <nav class="ev-crumb" aria-label="breadcrumb">
            <a href="{{ get_absolute_url('index') }}">Home</a>
            <span aria-hidden="true">&rsaquo;</span>
            <a href="{{ get_absolute_url('events') }}">Events</a>
            <span aria-hidden="true">&rsaquo;</span>
            <span>{{ $event->title }}</span>
        </nav>
    </div>

    <div class="ev-wrap">
        <div class="container">
            <article class="ev-article">

                <!-- Featured Image -->
                @if ($detail['image'])
                <div class="ev-featured">
                    <img src="{{ $detail['image'] }}"
                         alt="{{ $event->title }}" loading="lazy">
                </div>
                @endif

                @if (!empty($event->excerpt))
                    <p class="ev-excerpt">{{ $event->excerpt }}</p>
                @endif

                <hr class="ev-divider">

                <!-- Event Content (admin-authored HTML) -->
                @if (!empty($event->content))
                <div class="event-body event-content">
                    {!! $event->content !!}
                </div>
                @endif

                <!-- Back link -->
                <div class="ev-back">
                    <a href="{{ get_absolute_url('events') }}" class="ds-btn ds-btn--outline">
                        <i class="fas fa-arrow-left me-2"></i>Back to All Events
                    </a>
                </div>

            </article>
        </div>
    </div>

</div>

@endsection
