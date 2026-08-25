<?php
// Load configuration
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

$page_title = "Gallery | XJMU Campus Photos & Events";
$page_description = "Explore XJMU's campus through photos and virtual tours. Discover student life, facilities, and events at Xinjiang Medical University in Urumqi, China.";
$page_keywords = "Xinjiang Medical University gallery, campus photos, university facilities, student life photos, medical university China, campus tour, virtual tour";
$page_url = "https://xjmu.online/gallery";
$page_image = "https://xjmu.online/images/logo.png";
$body_class = 'ds-page';
$page_css = ['css/design-system.css', 'css/gallery.css'];
?>
<?php 
// Use absolute path for includes
$header_path = __DIR__ . '/includes/header.php';
if (file_exists($header_path)) {
    include $header_path;
} else {
    die('Error: Header file not found. Please check file paths.');
}
?>


<!-- Gallery Hero -->
<section class="gallery-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1><i class="fas fa-images me-3"></i><span data-i18n="gallery.hero_title">University Gallery</span></h1>
                <p data-i18n="gallery.hero_desc">Explore our campus, facilities, student life, and events through photos and videos</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="gallery-stats">
                    <span class="badge bg-light text-dark p-3">
                        <i class="fas fa-camera me-2"></i>
                        <span class="h5 mb-0">85+</span> <span data-i18n="gallery.photos_label">Photos &amp; Videos</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Content -->
<main class="section-padding">
    <div class="container">
        <?php
        require_once __DIR__ . '/includes/content.php';
        $adminGallery = get_gallery();
        $galByCat = [];
        foreach ($adminGallery as $g) { $galByCat[$g['category'] ?: 'gallery'][] = $g; }
        $galTitles = ['campus' => 'Campus Photos', 'events' => 'University Events', 'student-life' => 'Student Life', 'facilities' => 'Facilities', 'virtual-tour' => 'Virtual Tour'];
        ?>
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="gallery-sidebar">
                    <h3 class="sidebar-title"><i class="fas fa-bars me-2"></i><span data-i18n="gallery.quick_links">Quick Links</span></h3>
                    <ul class="sidebar-links">
                        <li><a href="#campus" class="active" target="_blank" rel="noopener noreferrer"><i class="fas fa-university"></i> <span data-i18n="gallery.campus_link">Campus Photos</span></a></li>
                        <li><a href="#events" target="_blank" rel="noopener noreferrer"><i class="fas fa-calendar-alt"></i> <span data-i18n="gallery.events_link">University Events</span></a></li>
                        <li><a href="#student-life" target="_blank" rel="noopener noreferrer"><i class="fas fa-users"></i> <span data-i18n="gallery.student_life_link">Student Life</span></a></li>
                        <li><a href="#facilities" target="_blank" rel="noopener noreferrer"><i class="fas fa-flask"></i> <span data-i18n="gallery.facilities_link">Facilities</span></a></li>
                        <li><a href="#virtual-tour" target="_blank" rel="noopener noreferrer"><i class="fas fa-video"></i> <span data-i18n="gallery.virtual_tour_link">Virtual Tour</span></a></li>
                    </ul>
                    
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i> <span data-i18n="gallery.gallery_info">Gallery Info</span></h6>
                        <p class="small mb-2">Click on any image to view in full size. Use the sidebar links to navigate between sections.</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="gallery-content">
                    <?php if (!$adminGallery): ?>
                        <p class="text-muted">No gallery images yet.</p>
                    <?php endif; ?>
                    <?php foreach ($galByCat as $cat => $imgs): ?>
                        <section class="gallery-section" id="<?= htmlspecialchars($cat, ENT_QUOTES) ?>">
                            <div class="section-header">
                                <h2><?= htmlspecialchars($galTitles[$cat] ?? ucwords(str_replace('-', ' ', $cat))) ?></h2>
                            </div>
                            <div class="gallery-grid">
                                <?php foreach ($imgs as $g): ?>
                                    <div class="gallery-item">
                                        <img src="<?= htmlspecialchars($g['image_path'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($g['title'] ?: 'Gallery image') ?>" loading="lazy">
                                        <?php if (!empty($g['title'])): ?>
                                            <div class="gallery-item-content"><h4><?= htmlspecialchars($g['title']) ?></h4></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<!-- Gallery JS -->
<script>
    // Simple Gallery JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Gallery page loaded');
        
        // Initialize Lightbox
        initializeLightbox();
        
        // Initialize sidebar navigation
        initializeSidebarNav();
        
        // Initialize video click handlers
        initializeVideoHandlers();
        
        // Add scroll spy for sidebar
        initializeScrollSpy();
    });
    
    // Lightbox Configuration
    function initializeLightbox() {
        // Configure lightbox for images only
        const imageLinks = document.querySelectorAll('.gallery-item:not(.video-item) img');
        
        imageLinks.forEach(img => {
            const link = document.createElement('a');
            link.href = img.src;
            link.setAttribute('data-lightbox', 'gallery');
            link.setAttribute('data-title', img.alt);
            
            img.parentNode.insertBefore(link, img);
            link.appendChild(img);
        });
        
        // Lightbox options
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'showImageNumberLabel': true,
            'positionFromTop': 100,
            'disableScrolling': false
        });
        
        console.log('Lightbox initialized for ' + imageLinks.length + ' images');
    }
    
    // Sidebar Navigation
    function initializeSidebarNav() {
        const sidebarLinks = document.querySelectorAll('.sidebar-links a');
        
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get target section
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    // Remove active class from all links
                    sidebarLinks.forEach(l => l.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Scroll to section
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    console.log('Scrolled to section: ' + targetId);
                }
            });
        });
    }
    
    // Video Click Handlers
    function initializeVideoHandlers() {
        const videoItems = document.querySelectorAll('.video-item');
        
        videoItems.forEach(item => {
            item.addEventListener('click', function() {
                const title = this.querySelector('h4').textContent;
                
                // Create video modal
                showVideoModal(title);
                
                console.log('Video clicked: ' + title);
            });
        });
    }
    
    // Video Modal
    function showVideoModal(title) {
        // Remove existing modal
        const existingModal = document.querySelector('.video-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'video-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <button class="close-modal">&times;</button>
                <h4>${title}</h4>
                <div class="video-container">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                                title="${title}" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <p class="mt-3">This is a sample video. Replace with actual university videos.</p>
            </div>
        `;
        
        // Add styles
        const styles = document.createElement('style');
        styles.textContent = `
            .video-modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.9);
                z-index: 99999;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.3s ease;
            }
            
            .video-modal .modal-content {
                background: white;
                border-radius: 10px;
                padding: 30px;
                max-width: 800px;
                width: 90%;
                position: relative;
                animation: slideUp 0.3s ease;
            }
            
            .close-modal {
                position: absolute;
                top: 15px;
                right: 15px;
                background: var(--primary-color, #e7232b);
                color: white;
                border: none;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                font-size: 20px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }
            
            .close-modal:hover {
                background: #cc0000;
                transform: rotate(90deg);
            }
            
            .video-modal h4 {
                color: #333;
                margin-bottom: 20px;
                font-size: 1.5rem;
            }
            
            .video-modal p {
                color: #666;
                text-align: center;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        
        document.head.appendChild(styles);
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        
        // Close button
        const closeBtn = modal.querySelector('.close-modal');
        closeBtn.addEventListener('click', closeVideoModal);
        
        // Close on ESC
        document.addEventListener('keydown', function escHandler(e) {
            if (e.key === 'Escape') {
                closeVideoModal();
                document.removeEventListener('keydown', escHandler);
            }
        });
        
        // Close on overlay click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeVideoModal();
            }
        });
    }
    
    function closeVideoModal() {
        const modal = document.querySelector('.video-modal');
        if (modal) {
            modal.remove();
            document.body.style.overflow = '';
        }
    }
    
    // Scroll Spy for Sidebar
    function initializeScrollSpy() {
        const sections = document.querySelectorAll('.gallery-section');
        const sidebarLinks = document.querySelectorAll('.sidebar-links a');
        
        function updateActiveLink() {
            let currentSection = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.clientHeight;
                
                if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                    currentSection = '#' + section.id;
                }
            });
            
            sidebarLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentSection) {
                    link.classList.add('active');
                }
            });
        }
        
        window.addEventListener('scroll', updateActiveLink);
        updateActiveLink(); // Initial call
    }
    
    // Show toast notification
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'gallery-toast';
        toast.textContent = message;
        
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #333;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            z-index: 99999;
            animation: slideIn 0.3s ease;
            max-width: 300px;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    if (toast.parentNode) toast.remove();
                }, 300);
            }
        }, 3000);
    }
</script>

<?php 
// Use absolute path for includes
$footer_path = __DIR__ . '/includes/footer.php';
if (file_exists($footer_path)) {
    include $footer_path;
} else {
    die('Error: Footer file not found. Please check file paths.');
}
?>


