@extends('layouts.site')
@section('content')

<!-- Hero Section -->
<section class="downloads-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1><i class="fas fa-download me-3"></i><span data-i18n="downloads.hero_title">Downloads</span></h1>
                <p data-i18n="downloads.hero_desc">Access official documents, forms, brochures, and resources from Xinjiang Medical University</p>
            </div>
        </div>
    </div>
</section>

<main class="download-section">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Important Notice -->
                <div class="important-notice">
                    <h5><i class="fas fa-exclamation-circle me-2"></i> <span data-i18n="downloads.notice_title">Important Notice</span></h5>
                    <p data-i18n="downloads.notice_desc">All documents are for official use only. Please verify document versions before use. For the most updated forms, contact the admissions office at studyatxjmu@gmail.com</p>
                </div>

                @unless ($has_items)
                    <p class="text-muted">No documents available at the moment.</p>
                @endunless
                @foreach ($groups as $group)
                    <h3 class="mb-4" id="{{ $group['id'] }}">{{ $group['name'] }}</h3>
                    <div class="row g-4 mb-5">
                        @foreach ($group['items'] as $d)
                            <div class="col-md-4">
                                <div class="download-card">
                                    <div class="download-icon {{ $d['icon_class'] }}"><i class="fas {{ $d['fa_icon'] }}"></i></div>
                                    <h4>{{ $d['title'] }}</h4>
                                    <a href="{{ $d['file_path'] }}" class="download-btn" download target="_blank" rel="noopener noreferrer">
                                        <i class="fas fa-download"></i> <span>Download</span>
                                    </a>
                                    @if (!empty($d['description']))
                                    <div class="file-preview"><i class="fas fa-info-circle"></i> {{ $d['description'] }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

@verbatim
            <!-- Quick Links Sidebar -->
            <div class="col-lg-3">
                <div class="quick-links-sidebar">
                    <h4><i class="fas fa-link me-2"></i> <span data-i18n="downloads.quick_links">Quick Links</span></h4>
                    
                    <a href="#admission" class="quick-link-item" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-graduation-cap"></i>
                        <span data-i18n="downloads.admission_forms">Admission Forms</span>
                    </a>
                    
                    <a href="#brochures" class="quick-link-item" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-book-open"></i>
                        <span data-i18n="downloads.brochures">University Brochures</span>
                    </a>
                    
                    <a href="#academic" class="quick-link-item" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-university"></i>
                        <span data-i18n="downloads.academic">Academic Documents</span>
                    </a>
                    
                    <a href="#visa" class="quick-link-item" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-passport"></i>
                        <span>Visa & Immigration</span>
                    </a>
                    
                    <a href="#research" class="quick-link-item" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-flask"></i>
                        <span data-i18n="downloads.research">Research Materials</span>
                    </a>
                    
                    <a href="#campus" class="quick-link-item" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-home"></i>
                        <span data-i18n="downloads.campus_life">Campus Life</span>
                    </a>
                    
                    <a href="#policies" class="quick-link-item" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-file-contract"></i>
                        <span data-i18n="downloads.policies">University Policies</span>
                    </a>
                    
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3"><i class="fas fa-headset me-2"></i> <span data-i18n="downloads.need_help">Need Help?</span></h6>
                        <p class="small mb-2">Contact our support team:</p>
                        <a href="mailto:studyatxjmu@gmail.com" class="btn btn-outline-primary btn-sm w-100 mb-2" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-envelope me-2"></i> <span data-i18n="downloads.email_support">Email Support</span>
                        </a>
                        <a href="contact" class="btn btn-outline-success btn-sm w-100" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-phone me-2"></i> <span data-i18n="downloads.contact_page">Contact Page</span>
                        </a>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3"><i class="fas fa-clock me-2"></i> Recent Updates</h6>
                        <p class="small mb-1"><i class="fas fa-file-pdf text-danger"></i> MBBS Form Updated</p>
                        <p class="small text-muted">15 Jan 2025</p>
                        <p class="small mb-1"><i class="fas fa-file-word text-primary"></i> Application Form Revised</p>
                        <p class="small text-muted">10 Jan 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endverbatim
@verbatim

<!-- Page Specific JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for quick links
        document.querySelectorAll('.quick-link-item').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Category navigation active state
        document.querySelectorAll('.category-nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.category-nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Search functionality
        const searchInput = document.querySelector('.search-box input');
        const searchBtn = document.querySelector('.search-box button');
        
        if (searchInput && searchBtn) {
            searchBtn.addEventListener('click', function() {
                performSearch();
            });
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            function performSearch() {
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    alert(`Searching for: "${searchTerm}"\n\nIn a real implementation, this would filter the downloads.`);
                    searchInput.value = '';
                }
            }
        }

        // Track downloads
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const fileName = this.parentElement.querySelector('h4').textContent;
                console.log(`Download initiated: ${fileName}`);
                // Here you can add analytics tracking
            });
        });

        // Update character count for any textareas (if added later)
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                const charCount = this.value.length;
                const counter = this.nextElementSibling;
                if (counter && counter.classList.contains('character-count')) {
                    counter.querySelector('span').textContent = charCount;
                }
            });
        });
    });
</script>
@endverbatim

@endsection
