                        <article class="blog-card">
                            <div class="blog-card-image">
                                <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" loading="lazy">
                                @if (!empty($featured))
                                <span class="blog-card-badge" data-i18n="blogs.featured_badge">Featured</span>
                                @endif
                            </div>
                            <div class="blog-card-content">
                                <h3 class="blog-card-title">
                                    <a href="blog-post?slug={{ $blog['slug'] }}" target="_blank" rel="noopener noreferrer">
                                        {{ $blog['title'] }}
                                    </a>
                                </h3>
                                <p class="blog-card-excerpt">{{ $blog['excerpt'] }}</p>
                                <div class="blog-card-footer">
                                    <span class="blog-card-date">{{ $blog['date'] }}</span>
                                    <a href="blog-post?slug={{ $blog['slug'] }}" class="blog-card-link" target="_blank" rel="noopener noreferrer">Read more</a>
                                </div>
                            </div>
                        </article>
