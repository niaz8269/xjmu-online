// University Detail Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initSmoothScrolling();
    initScrollAnimations();
    initGallery();
    initBackToTop();
    initWhatsAppTracking();
    initUtilityButtons();
    initScholarshipInteractions();
});

// Smooth scrolling for internal links
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Scroll animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, observerOptions);

    // Observe all elements with animation classes
    document.querySelectorAll('.fade-in-up, .slide-in-left, .slide-in-right, .scale-on-hover').forEach(element => {
        element.classList.add('reveal-on-scroll');
        observer.observe(element);
    });
}

// Gallery functionality
function initGallery() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightboxModal = document.querySelector('.lightbox-modal');
    const lightboxImg = document.querySelector('.lightbox-img');
    const lightboxTitle = document.querySelector('.lightbox-title');
    const lightboxDesc = document.querySelector('.lightbox-desc');
    const lightboxClose = document.querySelector('.lightbox-close');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    let currentIndex = 0;
    const galleryImages = Array.from(galleryItems);

    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            // Show/hide gallery items based on filter
            galleryItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Lightbox functionality
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            currentIndex = index;
            openLightbox();
        });
    });

    function openLightbox() {
        const galleryItem = galleryImages[currentIndex];
        const imgSrc = galleryItem.querySelector('.gallery-img').src;
        const title = galleryItem.querySelector('h3').textContent;
        const description = galleryItem.querySelector('p').textContent;
        
        lightboxImg.src = imgSrc;
        lightboxTitle.textContent = title;
        lightboxDesc.textContent = description;
        
        lightboxModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightboxModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function showPrevImage() {
        currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
        updateLightbox();
    }

    function showNextImage() {
        currentIndex = (currentIndex + 1) % galleryImages.length;
        updateLightbox();
    }

    function updateLightbox() {
        const galleryItem = galleryImages[currentIndex];
        const imgSrc = galleryItem.querySelector('.gallery-img').src;
        const title = galleryItem.querySelector('h3').textContent;
        const description = galleryItem.querySelector('p').textContent;
        
        lightboxImg.src = imgSrc;
        lightboxTitle.textContent = title;
        lightboxDesc.textContent = description;
        
        // Add fade effect
        lightboxImg.style.opacity = '0';
        setTimeout(() => {
            lightboxImg.style.opacity = '1';
        }, 300);
    }

    // Event listeners
    lightboxClose.addEventListener('click', closeLightbox);
    prevBtn.addEventListener('click', showPrevImage);
    nextBtn.addEventListener('click', showNextImage);

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightboxModal.classList.contains('active')) return;
        
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') showPrevImage();
        if (e.key === 'ArrowRight') showNextImage();
    });

    // Close lightbox when clicking outside the image
    lightboxModal.addEventListener('click', function(e) {
        if (e.target === lightboxModal) {
            closeLightbox();
        }
    });
}

// Back to top button
function initBackToTop() {
    const backToTopBtn = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    });
    
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// WhatsApp tracking
function initWhatsAppTracking() {
    document.querySelectorAll('.btn-whatsapp').forEach(button => {
        button.addEventListener('click', function() {
            // Track WhatsApp clicks for analytics
            const universityName = 'Xinjiang Medical University';
            console.log(`WhatsApp inquiry for: ${universityName}`);
            
            // In a real application, you would send this to Google Analytics
            // gtag('event', 'whatsapp_click', {
            //     'event_category': 'Admission Inquiry',
            //     'event_label': universityName
            // });
        });
    });
}

// Utility buttons (print, share, favorites)
function initUtilityButtons() {
    // Print page functionality
    const printButton = document.createElement('button');
    printButton.innerHTML = '<i class="fas fa-print"></i> Print This Page';
    printButton.className = 'btn btn-outline';
    printButton.style.margin = '20px auto';
    printButton.style.display = 'block';
    
    printButton.addEventListener('click', function() {
        window.print();
    });
    
    // Share functionality
    const shareButton = document.createElement('button');
    shareButton.innerHTML = '<i class="fas fa-share-alt"></i> Share This Page';
    shareButton.className = 'btn btn-outline';
    shareButton.style.margin = '10px auto';
    shareButton.style.display = 'block';
    
    shareButton.addEventListener('click', function() {
        if (navigator.share) {
            navigator.share({
                title: 'Xinjiang Medical University - MBBS Admission',
                text: 'Check out Xinjiang Medical University for MBBS in China',
                url: window.location.href
            });
        } else {
            // Fallback for browsers that don't support Web Share API
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Page URL copied to clipboard!');
            });
        }
    });
    
    // Add to favorites functionality
    const favoriteButton = document.createElement('button');
    favoriteButton.innerHTML = '<i class="far fa-heart"></i> Add to Favorites';
    favoriteButton.className = 'btn btn-outline';
    favoriteButton.style.margin = '10px auto';
    favoriteButton.style.display = 'block';
    
    favoriteButton.addEventListener('click', function() {
        const favorites = JSON.parse(localStorage.getItem('favoriteUniversities') || '[]');
        const university = {
            id: 'xinjiang-medical-university',
            name: 'Xinjiang Medical University',
            url: window.location.href,
            image: 'images/xjmu-main.webp',
            tuition: '30,000 CNY/Year'
        };
        
        if (!favorites.some(fav => fav.id === university.id)) {
            favorites.push(university);
            localStorage.setItem('favoriteUniversities', JSON.stringify(favorites));
            favoriteButton.innerHTML = '<i class="fas fa-heart"></i> Added to Favorites';
            favoriteButton.style.color = 'var(--classic-gold)';
            alert('University added to favorites!');
        } else {
            alert('University is already in your favorites!');
        }
    });
    
    // Add utility buttons before footer
    const footer = document.getElementById('footer');
    const utilityContainer = document.createElement('div');
    utilityContainer.className = 'utility-buttons';
    utilityContainer.style.textAlign = 'center';
    utilityContainer.style.padding = '20px 0';
    utilityContainer.style.background = 'var(--classic-light)';
    utilityContainer.style.borderTop = '1px solid var(--classic-border)';
    
    utilityContainer.appendChild(printButton);
    utilityContainer.appendChild(shareButton);
    utilityContainer.appendChild(favoriteButton);
    
    footer.parentNode.insertBefore(utilityContainer, footer);
}

// Scholarship interactions
function initScholarshipInteractions() {
    // Add click handlers for scholarship cards
    const scholarshipCards = document.querySelectorAll('.scholarship-card');
    
    scholarshipCards.forEach(card => {
        card.addEventListener('click', function() {
            const scholarshipName = this.querySelector('h3').textContent;
            const scholarshipAmount = this.querySelector('.scholarship-amount').textContent;
            
            // Track scholarship interest
            console.log(`Scholarship interest: ${scholarshipName} - ${scholarshipAmount}`);
            
            // Show more information about the scholarship
            this.classList.toggle('expanded');
        });
    });
}

// Global function to apply for scholarship
function applyForScholarship(scholarshipName) {
    const message = `Hello! I want to apply for ${scholarshipName} at Xinjiang Medical University. Please guide me about the application process.`;
    const whatsappUrl = `https://wa.me/923421580046?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
    
    // Track scholarship application click
    console.log(`Scholarship application: ${scholarshipName}`);
}