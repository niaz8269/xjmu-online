@extends('layouts.site')

@section('content')

<section class="legal-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Site map</span>
        <h1>Every page on xjmu.online</h1>
        <p>Admissions pages, MBBS in China guides and news articles from Xinjiang Medical University, in one list.</p>
    </div>
</section>

<div class="container">
    <nav class="legal-crumb" aria-label="breadcrumb">
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>Site map</span>
    </nav>
</div>

<main class="ds-section" style="padding-top:2rem">
    <div class="container">
        <div class="legal-body">
            <h2>Main pages</h2>
            <ul>
                @foreach ($mapPages as [$href, $label])
                    <li><a href="{{ $href }}">{{ $label }}</a></li>
                @endforeach
            </ul>

            <h2>MBBS in China guides</h2>
            <ul>
                @foreach ($mapGuides as [$href, $label])
                    <li><a href="{{ $href }}">{{ $label }}</a></li>
                @endforeach
            </ul>

            <h2>News and articles</h2>
            <ul>
                @foreach ($mapPosts as [$href, $label])
                    <li><a href="{{ $href }}">{{ $label }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</main>

@endsection
