// Load header and footer components
document.addEventListener('DOMContentLoaded', function() {
    // Load header
    fetch('components/header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header').innerHTML = data;
            initializeHeader();
        })
        .catch(error => console.error('Error loading header:', error));

    // Load footer
    fetch('components/footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer').innerHTML = data;
        })
        .catch(error => console.error('Error loading footer:', error));

    // Initialize all functionality after a short delay to ensure DOM is ready
    setTimeout(() => {
        initializeAll();
    }, 100);
});

// Initialize all functionality
function initializeAll() {
    console.log('Initializing all components...');
    
    // Initialize components in order of priority
    initializeBackToTop();
    initializeFAQAccordion();
    initializeStatsCounter();
    initContactModal();
    initApplicationForm();
    initNewsletterForm();
    initPageSpecificGallery();
    
    console.log('All components initialized successfully');
}

// Initialize header functionality
function initializeHeader() {
    console.log('Initializing header...');
    
    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            document.querySelector('nav').classList.toggle('active');
        });
    }

    // Populate universities dropdown
    populateUniversitiesDropdown();
    
    // Mobile dropdown toggle
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                const dropdown = this.closest('.dropdown');
                if (dropdown) {
                    dropdown.classList.toggle('active');
                }
            }
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Close mobile menu if open
                const nav = document.querySelector('nav');
                if (nav) {
                    nav.classList.remove('active');
                }
                
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Header background on scroll
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (header) {
            if (window.scrollY > 100) {
                header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.backgroundColor = 'var(--white)';
                header.style.backdropFilter = 'none';
            }
        }
    });
    
    console.log('Header initialized successfully');
}

// Populate universities dropdown menu
function populateUniversitiesDropdown() {
    // Wait a bit for universities.js to load if it's loaded after main.js
    setTimeout(() => {
        renderUniversitiesDropdown();
    }, 100);
    
    // Also try after a longer delay in case it loads later
    setTimeout(() => {
        if (document.getElementById('universities-list') && document.getElementById('universities-list').children.length === 0) {
            renderUniversitiesDropdown();
        }
    }, 1000);
}

// Render universities in dropdown
function renderUniversitiesDropdown() {
    const searchInput = document.getElementById('dropdown-search-input');
    const dropdownMenu = document.getElementById('universities-dropdown');
    
    if (!dropdownMenu) return;
    
    // Get universities from universities.js if available
    let universitiesList = [];
    if (typeof window.universities !== 'undefined' && Array.isArray(window.universities)) {
        universitiesList = window.universities;
    } else if (typeof universities !== 'undefined' && Array.isArray(universities)) {
        universitiesList = universities;
    } else {
        // Fallback: create a simple list
        universitiesList = [
            { name: "Peking University Health Science Center", slug: "peking-university-health-science-center" },
            { name: "Fudan University Shanghai Medical College", slug: "fudan-university-shanghai-medical-college" },
            { name: "Zhejiang University School of Medicine", slug: "zhejiang-university-school-of-medicine" },
            { name: "Xinjiang Medical University", slug: "xinjiang-medical-university" },
            { name: "Shanghai Jiao Tong University School of Medicine", slug: "shanghai-jiao-tong-university-school-of-medicine" }
        ];
    }
    
    // Sort universities alphabetically
    universitiesList = [...universitiesList].sort((a, b) => a.name.localeCompare(b.name));
    
    // Render universities
    function renderList(list) {
        // Find the dropdown menu
        const dropdownMenu = document.getElementById('universities-dropdown');
        if (!dropdownMenu) return;
        
        // Remove existing university items (keep search, divider, and "All Universities")
        const existingItems = dropdownMenu.querySelectorAll('li.university-item');
        existingItems.forEach(item => item.remove());
        
        // Add universities
        list.forEach(university => {
            const li = document.createElement('li');
            li.className = 'university-item';
            const slug = university.slug || university.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            li.innerHTML = `<a href="${slug}.html">${university.name}</a>`;
            // Insert before the last divider or at the end
            const lastDivider = dropdownMenu.querySelector('li.dropdown-divider:last-of-type');
            if (lastDivider) {
                dropdownMenu.insertBefore(li, lastDivider);
            } else {
                dropdownMenu.appendChild(li);
            }
        });
    }
    
    // Initial render
    renderList(universitiesList);
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filtered = universitiesList.filter(uni => 
                uni.name.toLowerCase().includes(searchTerm)
            );
            renderList(filtered);
        });
    }
}

// Back to Top Button
function initializeBackToTop() {
    const backToTopButton = document.getElementById('backToTop');
    
    if (!backToTopButton) {
        console.warn('Back to top button element not found');
        return;
    }

    function toggleBackToTop() {
        const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollPosition > 300) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    }

    // Initial check
    toggleBackToTop();

    // Throttled scroll event for better performance
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (!scrollTimeout) {
            scrollTimeout = setTimeout(function() {
                scrollTimeout = null;
                toggleBackToTop();
            }, 100);
        }
    });

    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    console.log('Back to top button initialized successfully');
}

// FAQ Accordion
function initializeFAQAccordion() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', () => {
            // Close all other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            item.classList.toggle('active');
        });
    });
}

// Stats Counter Animation
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target + (element.textContent.includes('%') ? '%' : '+');
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start) + (element.textContent.includes('%') ? '%' : '+');
        }
    }, 16);
}

// Initialize counters when in view
function initializeStatsCounter() {
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target;
                const target = parseInt(statNumber.getAttribute('data-target'));
                animateCounter(statNumber, target);
                observer.unobserve(statNumber);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-number').forEach(statNumber => {
        observer.observe(statNumber);
    });
}

// Contact Modal
function initContactModal() {
    const fab = document.getElementById('contactFab');
    const modal = document.getElementById('contactModal');
    const overlay = document.getElementById('contactOverlay');
    const closeBtn = document.getElementById('contactClose');

    if (!fab || !modal) {
        console.warn('Contact modal elements not found');
        return;
    }

    const openModal = () => {
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    const closeModal = () => {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    fab.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    overlay?.addEventListener('click', closeModal);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('open')) {
            closeModal();
        }
    });
    
    console.log('Contact modal initialized successfully');
}

// Application Form - UPDATED for PHP backend
function initApplicationForm() {
    const applicationForm = document.getElementById('applicationForm');
    const statusBlock = document.getElementById('applicationStatus');

    if (!applicationForm || !statusBlock) {
        console.warn('Application form elements not found');
        return;
    }

    const setStatus = (message, type = 'success') => {
        statusBlock.textContent = message;
        statusBlock.className = 'form-status show ' + type;
        
        // Auto-hide success messages after 8 seconds
        if (type === 'success') {
            setTimeout(() => {
                statusBlock.classList.remove('show');
            }, 8000);
        }
    };

    // Enhanced form validation matching PHP requirements
    function validateForm(form) {
        const requiredFields = [
            'first_name', 'last_name', 'email', 'phone', 
            'nationality', 'preferred_university', 'intake'
        ];
        
        let isValid = true;
        
        requiredFields.forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field && !field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                
                // Add error message
                if (!field.nextElementSibling?.classList.contains('field-error')) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'field-error';
                    errorMsg.textContent = 'This field is required';
                    errorMsg.style.cssText = 'color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;';
                    field.parentNode.insertBefore(errorMsg, field.nextSibling);
                }
            } else if (field) {
                field.classList.remove('error');
                // Remove existing error message
                const existingError = field.nextElementSibling;
                if (existingError?.classList.contains('field-error')) {
                    existingError.remove();
                }
            }
            
            // Email validation
            if (fieldName === 'email' && field && field.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    isValid = false;
                    field.classList.add('error');
                }
            }
            
            // Phone validation
            if (fieldName === 'phone' && field && field.value) {
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                if (!phoneRegex.test(field.value.replace(/[\s\-\(\)]/g, ''))) {
                    isValid = false;
                    field.classList.add('error');
                }
            }
        });
        
        return isValid;
    }

    applicationForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        console.log('Application form submission started...');

        // Validate form
        if (!validateForm(applicationForm)) {
            setStatus('Please fill in all required fields correctly.', 'error');
            return;
        }

        setStatus('Submitting your application...', 'success');

        // Prepare form data according to PHP backend requirements
        const formData = new FormData();
        
        // Required fields
        formData.append('first_name', applicationForm.querySelector('[name="first_name"]').value.trim());
        formData.append('last_name', applicationForm.querySelector('[name="last_name"]').value.trim());
        formData.append('email', applicationForm.querySelector('[name="email"]').value.trim());
        formData.append('phone', applicationForm.querySelector('[name="phone"]').value.trim());
        formData.append('nationality', applicationForm.querySelector('[name="nationality"]').value.trim());
        formData.append('preferred_university', applicationForm.querySelector('[name="preferred_university"]').value.trim());
        formData.append('intake', applicationForm.querySelector('[name="intake"]').value.trim());
        
        // Optional fields
        const englishProficiency = applicationForm.querySelector('[name="english_proficiency"]');
        const academicBackground = applicationForm.querySelector('[name="academic_background"]');
        const message = applicationForm.querySelector('[name="message"]');
        
        if (englishProficiency && englishProficiency.value) {
            formData.append('english_proficiency', englishProficiency.value);
        }
        if (academicBackground && academicBackground.value) {
            formData.append('academic_background', academicBackground.value);
        }
        if (message && message.value) {
            formData.append('message', message.value.trim());
        }
        
        // Additional tracking data
        formData.append('page_source', getCurrentPage());
        formData.append('page_url', window.location.href);

        try {
            // Submit to PHP backend
            const response = await fetch('backend/apply.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            let result;
            try {
                result = await response.json();
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                throw new Error('Invalid response from server. Please try again.');
            }

            if (response.ok && result.success) {
                // Success - reset form and show success message
                applicationForm.reset();
                setStatus(result.message || 'Application submitted successfully! Our team will contact you within 24 hours.', 'success');
                
                // Track successful submission
                trackConversion('application');
                
            } else {
                // Handle server-side validation errors
                throw new Error(result.message || 'Submission failed. Please try again.');
            }
            
        } catch (error) {
            console.error('Form submission error:', error);
            
            // Fallback: Save to localStorage for later recovery
            saveToLocalStorage(formData);
            
            setStatus(
                'Application saved offline! We will process it soon. ' +
                'Our team will contact you shortly.', 
                'success'
            );
        }
    });

    // Real-time validation
    applicationForm.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('blur', () => {
            validateForm(applicationForm);
        });
        
        field.addEventListener('input', () => {
            field.classList.remove('error');
            // Remove error message
            const existingError = field.nextElementSibling;
            if (existingError?.classList.contains('field-error')) {
                existingError.remove();
            }
        });
    });
    
    console.log('Application form initialized successfully');
}

// Fallback: Save form data to localStorage
function saveToLocalStorage(formData) {
    try {
        const formObject = {
            type: 'application',
            timestamp: new Date().toISOString(),
            data: {}
        };
        
        for (let [key, value] of formData.entries()) {
            formObject.data[key] = value;
        }
        
        const pendingSubmissions = JSON.parse(localStorage.getItem('pendingSubmissions') || '[]');
        pendingSubmissions.push(formObject);
        
        localStorage.setItem('pendingSubmissions', JSON.stringify(pendingSubmissions));
        console.log('Application saved to localStorage for later recovery');
        
        // Try to sync pending submissions
        syncPendingSubmissions();
        
    } catch (error) {
        console.error('Failed to save to localStorage:', error);
    }
}

// Sync pending submissions when back online
function syncPendingSubmissions() {
    const pendingSubmissions = JSON.parse(localStorage.getItem('pendingSubmissions') || '[]');
    
    if (pendingSubmissions.length === 0) return;
    
    console.log(`Found ${pendingSubmissions.length} pending submissions to sync`);
    
    // Process submissions in reverse order (oldest first)
    const successfulSubmissions = [];
    
    pendingSubmissions.forEach(async (submission, index) => {
        try {
            if (submission.type === 'application') {
                const formData = new FormData();
                Object.entries(submission.data).forEach(([key, value]) => {
                    formData.append(key, value);
                });
                
                const response = await fetch('backend/apply.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    successfulSubmissions.push(index);
                    console.log('Successfully synced pending application');
                }
            }
        } catch (error) {
            console.error('Failed to sync pending submission:', error);
        }
    });
    
    // Remove successfully synced submissions
    if (successfulSubmissions.length > 0) {
        const updatedSubmissions = pendingSubmissions.filter((_, index) => 
            !successfulSubmissions.includes(index)
        );
        localStorage.setItem('pendingSubmissions', JSON.stringify(updatedSubmissions));
    }
}

// Newsletter Form - UPDATED for PHP backend
function initNewsletterForm() {
    const newsletterForm = document.getElementById('newsletterForm');
    const statusNode = document.getElementById('newsletterStatus');

    if (!newsletterForm || !statusNode) {
        console.warn('Newsletter form elements not found');
        return;
    }

    const setStatus = (message, type = 'success') => {
        statusNode.textContent = message;
        statusNode.className = 'form-status show ' + type;
    };

    newsletterForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        if (!newsletterForm.checkValidity()) {
            setStatus('Please complete all required fields.', 'error');
            return;
        }

        const formData = new FormData(newsletterForm);
        formData.append('page_url', window.location.href);
        formData.append('page_source', getCurrentPage());

        try {
            const response = await fetch('backend/subscribe.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json().catch(() => ({
                success: false,
                message: 'Unexpected server response. Please try again.'
            }));

            if (response.ok && result.success) {
                newsletterForm.reset();
                setStatus(result.message || 'You are subscribed! Watch for weekly MBBS briefings.', 'success');
                
                // Track conversion
                trackConversion('newsletter');
                
            } else {
                throw new Error(result.message || 'Unable to subscribe at the moment.');
            }
        } catch (error) {
            console.error(error);
            setStatus(error.message || 'Subscription failed. Please retry shortly.', 'error');
        }
    });
    
    console.log('Newsletter form initialized successfully');
}

// Track conversions for analytics
function trackConversion(type) {
    const analyticsData = {
        type: type,
        page_url: window.location.href,
        page_title: document.title,
        timestamp: new Date().toISOString()
    };
    
    // Send to analytics endpoint if available
    fetch('backend/analytics.php', {
        method: 'POST',
        body: JSON.stringify(analyticsData),
        headers: {
            'Content-Type': 'application/json'
        }
    }).catch(error => {
        console.log('Analytics tracking failed:', error);
    });
}

// Page Specific Gallery Functionality
function initPageSpecificGallery() {
    const galleryMount = document.getElementById('pageGalleryMount');
    if (!galleryMount) {
        console.log('No page gallery mount found');
        return;
    }

    const pageGalleries = {
        'index': {
            title: 'Student Life & Campus Gallery',
            description: 'Explore life at Chinese medical universities through student experiences',
            items: [
                {
                    title: 'Modern Campus Facilities',
                    excerpt: 'State-of-the-art infrastructure and learning environments',
                    category: 'Campus',
                    photo: 'images/gallery/campus-1.jpg'
                },
                {
                    title: 'Clinical Training',
                    excerpt: 'Hands-on experience in affiliated hospitals',
                    category: 'Clinical',
                    photo: 'images/gallery/clinical-1.jpg'
                }
            ]
        }
    };

    const currentPage = getCurrentPage();
    const galleryData = pageGalleries[currentPage];

    if (galleryData) {
        renderPageGallery(galleryData);
    }
    
    console.log('Page gallery initialized successfully');
}

// Get current page name
function getCurrentPage() {
    const path = window.location.pathname;
    const page = path.split('/').pop() || 'index.html';
    
    if (page.includes('admissions')) return 'admissions';
    if (page.includes('blog')) return 'blog';
    return 'index';
}

// Error handling for images
document.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG') {
        console.warn('Image failed to load:', e.target.src);
        // Set a placeholder image
        e.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBhdmFpbGFibGU8L3RleHQ+PC9zdmc+';
    }
}, true);

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
});

// Try to sync any pending submissions when page loads
window.addEventListener('load', function() {
    setTimeout(syncPendingSubmissions, 2000);
});

// Export functions for global access
window.initializeHeader = initializeHeader;
window.populateUniversitiesDropdown = populateUniversitiesDropdown;
window.initPageSpecificGallery = initPageSpecificGallery;
window.initApplicationForm = initApplicationForm;