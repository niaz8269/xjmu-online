@extends('layouts.site')
@section('content')

    <div class="blog-page">
        <!-- Hero Section -->
        <section class="blog-hero">
            <div class="container">
                <span class="ds-eyebrow" style="color:#ffd0d2">Insights &amp; Stories</span>
                <h1 data-i18n="blogs.hero_title">XJMU Blog</h1>
                <p data-i18n="blogs.hero_desc">Welcome to Xinjiang Medical University's official blog. Stay updated with the latest news, announcements, student life, campus events, and everything you need to know about studying MBBS in China.</p>
            </div>
        </section>

        <div class="container">
            <nav class="blog-crumb" aria-label="breadcrumb">
                <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>Blogs</span>
            </nav>
        </div>

        <!-- Search Bar -->
        <section class="blog-container" style="padding-top: 30px;">
            <form method="GET" action="blogs" class="blog-filter-bar">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Search blogs..." value="{{ $search }}">
                    <button type="submit" data-i18n="blogs.search_btn">Search</button>
                </div>
            </form>
        </section>

        @if ($hasSearch)
            <div class="blog-container">
                <p class="blog-search-note">
                    Searching for: <strong>"{{ $search }}"</strong>
                    <a href="blogs" style="margin-left: 15px;" target="_blank" rel="noopener noreferrer">Clear search</a>
                </p>
            </div>
        @endif

        @if (!empty($featured))
        <!-- Featured Blogs Section -->
        <section class="blog-section featured-section">
            <div class="blog-container">
                <div class="blog-section-header">
                    <h2 data-i18n="blogs.featured_heading">Featured Blogs</h2>
                </div>
                <div class="featured-grid">
                    @foreach ($featured as $blog)
                        @include('pages.blogs._card', ['blog' => $blog, 'featured' => true])
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        @if (!empty($guides))
        <!-- MBBS in China 2027 guides (separate URLs, listed here as blogs) -->
        <section class="blog-section">
            <div class="blog-container">
                <div class="blog-section-header">
                    <h2>MBBS in China 2027 Guides</h2>
                </div>
                <div class="blog-grid">
                    @foreach ($guides as $blog)
                        @include('pages.blogs._card', ['blog' => $blog, 'featured' => false])
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Latest Blogs Section -->
        <section class="blog-section">
            <div class="blog-container">
                <div class="blog-section-header">
                    <h2>{{ $hasSearch ? 'Search Results' : 'Latest Blogs' }}</h2>
                </div>

                @if (empty($blogs))
                    <div class="no-results">
                        <h3 data-i18n="blogs.no_blogs">No blogs found</h3>
                        <p data-i18n="blogs.no_blogs_desc">Try adjusting your search or filter criteria</p>
                    </div>
                @else
                    <div class="blog-grid">
                        @foreach ($blogs as $blog)
                            @include('pages.blogs._card', ['blog' => $blog, 'featured' => false])
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($pagination)
                        <div class="blog-pagination">
                            @if ($pagination['prev'])
                                <a href="{!! $pagination['prev'] !!}" class="pagination-btn" target="_blank" rel="noopener noreferrer">← Previous</a>
                            @endif

                            @foreach ($pagination['pages'] as $p)
                                <a href="{!! $p['url'] !!}" target="_blank" rel="noopener noreferrer"
                                   class="pagination-btn {{ $p['active'] ? 'active' : '' }}">
                                    {{ $p['n'] }}
                                </a>
                            @endforeach

                            @if ($pagination['next'])
                                <a href="{!! $pagination['next'] !!}" class="pagination-btn" target="_blank" rel="noopener noreferrer">Next →</a>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </section>

    </div>

    <!-- Include footer -->

@endsection
