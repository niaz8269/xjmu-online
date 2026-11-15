@include('partials.header')

<!-- ===================== HERO ===================== -->
<section class="contact-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Testimonials</span>
        <h1>Share your XJMU experience</h1>
        <p>Are you a current student or graduate of Xinjiang Medical University? Tell us about your journey — your testimonial may be featured on our website.</p>
    </div>
</section>

<main class="contact-page-hubspot">
    <div class="container">
        <div class="form-map-container" style="grid-template-columns:1fr; max-width:720px; margin:0 auto;">
            <div class="form-column">

                @if ($submitted)
                    <div class="success-message" style="display:block;">
                        <div class="success-content">
                            <i class="fas fa-check-circle"></i>
                            <h4>Thank you!</h4>
                            <p>Your testimonial has been submitted successfully. Our team will review it, and once approved it will appear on the website.</p>
                            <a href="{{ get_absolute_url('submit-testimonial') }}" class="ds-btn ds-btn--outline">Submit another</a>
                            <a href="{{ get_absolute_url('index') }}" class="ds-btn ds-btn--navy">Back to home</a>
                        </div>
                    </div>
                @else

                    <h2 class="section-heading">Submit your testimonial</h2>
                    <p class="section-description">It only takes a minute. Fields marked <span class="required">*</span> are required.</p>

                    @if ($error)
                        <div class="alert alert-danger" role="alert" style="border:1px solid #f3c0c0;background:#fdecec;color:#b02a2a;padding:.8rem 1rem;border-radius:6px;margin-bottom:1rem;">{{ $error }}</div>
                    @endif

                    <form class="hubspot-contact-form" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <!-- Honeypot: hidden from humans, bots tend to fill it -->
                        <div style="position:absolute;left:-9999px;" aria-hidden="true">
                            <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                        </div>

                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" required maxlength="150" value="{{ $input['name'] ?? '' }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group-half">
                                <label for="category">I am a <span class="required">*</span></label>
                                <select id="category" name="category" class="form-control">
                                    <option value="student"{!! (($input['category'] ?? '') === 'student') ? ' selected' : '' !!}>Current / Recently Admitted Student</option>
                                    <option value="alumni"{!! (($input['category'] ?? '') === 'alumni') ? ' selected' : '' !!}>Graduate / Alumni</option>
                                </select>
                            </div>
                            <div class="form-group-half">
                                <label for="role">Program &amp; Batch</label>
                                <input type="text" id="role" name="role" class="form-control" placeholder="MBBS Program | Batch 2025" maxlength="200" value="{{ $input['role'] ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="location">Location / Current Position</label>
                            <input type="text" id="location" name="location" class="form-control" placeholder="e.g. Lahore, Pakistan" maxlength="200" value="{{ $input['location'] ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="quote">Your Testimonial <span class="required">*</span></label>
                            <textarea id="quote" name="quote" class="form-control" rows="5" required maxlength="1000" placeholder="Share your experience studying at XJMU...">{{ $input['quote'] ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="photo">Your Photo <span class="text-muted" style="font-weight:400">(optional, JPG/PNG/WebP, max 4 MB)</span></label>
                            <input type="file" id="photo" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                        </div>

                        <div class="form-submit-wrapper">
                            <button type="submit" class="submit-button">Submit Testimonial</button>
                        </div>
                    </form>

                @endif
            </div>
        </div>
    </div>
</main>

@include('partials.footer')
