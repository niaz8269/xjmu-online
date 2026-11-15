@extends('layouts.site')
@section('content')

<div class="blog-post-page">

    <!-- Hero -->
    <section class="bp-hero">
        <div class="container">
            @if ($blog->is_featured)
                <span class="ds-eyebrow" style="color:#ffd0d2">Featured</span>
            @else
                <span class="ds-eyebrow" style="color:#ffd0d2">Blog</span>
            @endif
            <h1>{{ $blog->title }}</h1>
            @if ($published_date)
                <p class="bp-meta"><i class="fas fa-calendar-alt"></i> Published {{ $published_date }}</p>
            @endif
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="container">
        <nav class="bp-crumb" aria-label="breadcrumb">
            <a href="{{ get_absolute_url('index') }}">Home</a>
            <span aria-hidden="true">&rsaquo;</span>
            <a href="{{ get_absolute_url('blogs') }}">Blogs</a>
            <span aria-hidden="true">&rsaquo;</span>
            <span>{{ $blog->title }}</span>
        </nav>
    </div>

    <div class="bp-wrap">
        <div class="container">
            <article class="bp-article">

                <!-- Featured Image -->
                @if ($featured_image)
                <div class="bp-featured">
                    <img src="{{ $featured_image }}"
                         alt="{{ $blog->title }}" loading="lazy">
                </div>
                @endif

                @if (!empty($blog->excerpt))
                    <p class="bp-excerpt">{{ $blog->excerpt }}</p>
                @endif

                <hr class="bp-divider">

                <!-- Post Content (admin-authored HTML) -->
                <div class="post-body blog-content">
                    {!! $blog->content !!}
                </div>

                <!-- Back link -->
                <div class="bp-back">
                    <a href="{{ get_absolute_url('blogs') }}" class="ds-btn ds-btn--outline">
                        <i class="fas fa-arrow-left me-2"></i>Back to All Blogs
                    </a>
                </div>

            </article>
        </div>
    </div>

</div>

@endsection
