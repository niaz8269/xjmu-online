// Global JavaScript for Xinjiang Medical University

function initMobileMenu() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
    const mobileMenuPanel = document.querySelector('.mobile-menu-panel');
    const topHeader = document.querySelector('.top-header');
    const brandBar = document.querySelector('.brand-bar');
    const body = document.body;
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-links a');

    function openMobileMenu() {
        if (mobileMenuOverlay) mobileMenuOverlay.classList.add('show');
        if (mobileMenuPanel) mobileMenuPanel.classList.add('show');
        body.classList.add('menu-open');
        body.style.overflow = 'hidden';
        
        // Hide top header and brand bar on mobile menu open
        if (window.innerWidth <= 992) {
            if (topHeader) topHeader.style.display = 'none';
            if (brandBar) brandBar.style.display = 'none';
        }
    }

    function closeMobileMenu() {
        if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('show');
        if (mobileMenuPanel) mobileMenuPanel.classList.remove('show');
        body.classList.remove('menu-open');
        body.style.overflow = '';
        
        // Restore top header and brand bar on mobile menu close
        if (window.innerWidth <= 992) {
            if (topHeader) {
                topHeader.style.display = '';
                topHeader.classList.remove('hide');
            }
            if (brandBar) {
                brandBar.style.display = '';
                brandBar.classList.remove('fixed');
            }
        }
    }

    // Open mobile menu
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openMobileMenu);
    }
    
    // Close mobile menu
    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMobileMenu);
    }
    
    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    }

    // Close menu when clicking on mobile nav links
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });

    // Close menu on window resize and handle visibility
    window.addEventListener('resize', function() {
        const isMobile = window.innerWidth <= 992;
        
        if (!isMobile) {
            // On desktop: close mobile menu and ensure elements are visible
            closeMobileMenu();
            
            if (topHeader) {
                topHeader.style.display = '';
                topHeader.classList.remove('hide');
            }
            if (brandBar) {
                brandBar.style.display = '';
                brandBar.classList.remove('fixed');
            }
        } else {
            // On mobile: if menu is open, hide header elements
            if (mobileMenuPanel && mobileMenuPanel.classList.contains('show')) {
                if (topHeader) topHeader.style.display = 'none';
                if (brandBar) brandBar.style.display = 'none';
            } else {
                // If menu is closed, ensure elements are visible
                if (topHeader) topHeader.style.display = '';
                if (brandBar) brandBar.style.display = '';
            }
        }
    });
}

function initHeaderScroll() {
    const topHeader = document.querySelector('.top-header');
    const brandBar = document.querySelector('.brand-bar');
    const logo = document.querySelector('.logo');
    const mainNavbar = document.querySelector('.main-navbar');
    const navLinks = document.querySelector('.nav-links');
    const navInBrand = document.querySelector('.nav-in-brand');
    const mobileMenuPanel = document.querySelector('.mobile-menu-panel');

    if (topHeader && brandBar && logo) {
        let lastScrollTop = 0;

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Skip scroll effects if mobile menu is open
            if (mobileMenuPanel && mobileMenuPanel.classList.contains('show')) {
                return;
            }

            // Hide top header on scroll down (only if not on mobile menu)
            if (scrollTop > 100) {
                topHeader.classList.add('hide');

                // Fix brand bar to top
                if (scrollTop > 150) {
                    brandBar.classList.add('fixed');
                    if (logo) logo.classList.add('small');

                    // Move nav links to brand bar on larger screens
                    if (window.innerWidth > 992 && navLinks && navInBrand && mainNavbar) {
                        if (!navInBrand.querySelector('.nav-links')) {
                            const clonedNavLinks = navLinks.cloneNode(true);
                            navInBrand.innerHTML = '';
                            navInBrand.appendChild(clonedNavLinks);
                            mainNavbar.style.display = 'none';
                        }
                    }
                } else {
                    brandBar.classList.remove('fixed');
                    if (logo) logo.classList.remove('small');
                    if (window.innerWidth > 992 && mainNavbar && navInBrand) {
                        mainNavbar.style.display = '';
                        navInBrand.innerHTML = '';
                    }
                }
            } else {
                topHeader.classList.remove('hide');
                brandBar.classList.remove('fixed');
                if (logo) logo.classList.remove('small');
                if (window.innerWidth > 992 && mainNavbar && navInBrand) {
                    mainNavbar.style.display = '';
                    navInBrand.innerHTML = '';
                }
            }

            lastScrollTop = scrollTop;
        });
        
        // Initial check
        if (window.pageYOffset > 100) {
            topHeader.classList.add('hide');
            if (window.pageYOffset > 150) {
                brandBar.classList.add('fixed');
                if (logo) logo.classList.add('small');
            }
        }
    }
}

function initActiveNav() {
    // Active navigation link for desktop
    const currentPage = window.location.pathname.split('/').pop();
    const navLinksAll = document.querySelectorAll('.nav-links a, .mobile-nav-links a');

    navLinksAll.forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === currentPage ||
            (currentPage === '' && linkPage === 'index.php') ||
            (currentPage === 'index.php' && linkPage === 'index.php')) {
            link.classList.add('active');
        }

        link.addEventListener('click', function() {
            navLinksAll.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

function initDropdownHover() {
    // Dropdown hover for desktop
    if (window.innerWidth > 992) {
        const dropdowns = document.querySelectorAll('.dropdown');

        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('mouseenter', function() {
                const menu = this.querySelector('.dropdown-menu');
                if (menu) menu.classList.add('show');
            });

            dropdown.addEventListener('mouseleave', function() {
                const menu = this.querySelector('.dropdown-menu');
                if (menu) menu.classList.remove('show');
            });
        });
    }
}

function initApplyButtons() {
    const applyButtons = document.querySelectorAll('.apply-now-btn');
    
    // Removed JS event to prevent double opening. Navigation handled by anchor tag only.
}

// Floating Buttons Functions
function initBackToTop() {
    const backToTopBtn = document.getElementById('backToTop');
    if (!backToTopBtn) return;
    
    // Show/hide button based on scroll position
    const handleScroll = function() {
        if (window.scrollY > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    };
    
    window.addEventListener('scroll', handleScroll);
    
    // Smooth scroll to top when clicked
    backToTopBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Initial check
    handleScroll();
}

// WeChat Widget with Modal
function initWeChatWidget() {
    const wechatBtn = document.getElementById('wechatWidget');
    if (!wechatBtn) return;
    
    // Track if modal is currently open
    let isModalOpen = false;
    
    wechatBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Stop event from bubbling up
        
        // Check if modal already exists
        const existingModal = document.querySelector('.wechat-modal-backdrop');
        if (existingModal && existingModal.classList.contains('show')) {
            // Close existing modal
            existingModal.classList.remove('show');
            setTimeout(() => {
                if (existingModal.parentNode) {
                    existingModal.parentNode.removeChild(existingModal);
                }
            }, 300);
            isModalOpen = false;
            return;
        }
        
        // If we're in the middle of closing, don't reopen
        if (isModalOpen && existingModal) {
            return;
        }
        
        isModalOpen = true;
        
        // Create modal backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'wechat-modal-backdrop';
        
        // Create modal content
        backdrop.innerHTML = `
            <div class="wechat-modal" id="wechatModalContent">
                <h5>Official WeChat</h5>
                <p>Scan the QR code below to connect with Xinjiang Medical University</p>
                <img src="/images/wechat.png" alt="WeChat QR Code" />
                <button class="btn-close" type="button"> <i class="fa-solid fa-xmark"></i></button>
            </div>
        `;
        
        // Add to DOM
        document.body.appendChild(backdrop);
        
        // Show with animation
        setTimeout(() => backdrop.classList.add('show'), 10);
        
        // Close functionality
        const closeModal = function() {
            console.log('Closing modal');
            if (!backdrop.classList.contains('show')) return;
            
            backdrop.classList.remove('show');
            setTimeout(() => {
                if (backdrop.parentNode) {
                    backdrop.parentNode.removeChild(backdrop);
                }
                isModalOpen = false;
                
                // Clean up event listeners
                document.removeEventListener('keydown', handleEscapeKey);
                document.removeEventListener('click', handleOutsideClick);
            }, 300);
        };
        
        const closeBtn = backdrop.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeModal();
            });
        }
        
        // Handle clicking outside modal
        const handleOutsideClick = function(e) {
            if (backdrop.contains(e.target) && e.target !== backdrop) {
                return; // Click was inside modal content, not outside
            }
            if (e.target === wechatBtn) {
                return; // Click was on the WeChat button itself
            }
            closeModal();
        };
        
        // Handle Escape key
        const handleEscapeKey = function(e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                closeModal();
            }
        };
        
        // Add event listeners
        document.addEventListener('keydown', handleEscapeKey);
        backdrop.addEventListener('click', function(e) {
            if (e.target === backdrop) {
                closeModal();
            }
        });
        
        // Also listen for clicks on document (with delay to avoid immediate close)
        setTimeout(() => {
            document.addEventListener('click', handleOutsideClick);
        }, 100);
    });
}
function initWhatsAppWidget() {
    const whatsappBtn = document.getElementById('whatsappWidget');
    if (!whatsappBtn) return;
    
    // The actual link is in the HTML href attribute
    // No additional functionality needed
}

// Helper function to check if element is in viewport
function isInViewport(element) {
    if (!element) return false;
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

function initGlobalUI() {
    initMobileMenu();
    initHeaderScroll();
    initActiveNav();
    initDropdownHover();
    initApplyButtons();
    initBackToTop();
    initWeChatWidget();
    initWhatsAppWidget();
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initGlobalUI();
});

// Make functions available globally if needed
window.FloatingButtons = {
    initBackToTop: initBackToTop,
    initWeChatWidget: initWeChatWidget,
    initWhatsAppWidget: initWhatsAppWidget,
    isInViewport: isInViewport
};

// Ensure mobile menu closes when clicking on non-menu areas
document.addEventListener('click', function(event) {
    const mobileMenuPanel = document.querySelector('.mobile-menu-panel');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    
    if (mobileMenuPanel && mobileMenuPanel.classList.contains('show')) {
        // Check if click is outside the mobile menu panel and not on the menu button
        if (!mobileMenuPanel.contains(event.target) && 
            !mobileMenuBtn.contains(event.target) &&
            !event.target.classList.contains('mobile-menu-btn')) {
            
            const topHeader = document.querySelector('.top-header');
            const brandBar = document.querySelector('.brand-bar');
            const body = document.body;
            const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
            
            if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('show');
            mobileMenuPanel.classList.remove('show');
            body.classList.remove('menu-open');
            body.style.overflow = '';
            
            // Restore elements on mobile
            if (window.innerWidth <= 992) {
                if (topHeader) {
                    topHeader.style.display = '';
                    topHeader.classList.remove('hide');
                }
                if (brandBar) {
                    brandBar.style.display = '';
                    brandBar.classList.remove('fixed');
                }
            }
        }
    }
});

// Handle page load with hash (scroll to section after header loads)
window.addEventListener('load', function() {
    if (window.location.hash) {
        setTimeout(() => {
            const element = document.querySelector(window.location.hash);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }, 500);
    }
});



// Consultant Agents - Simplified Version
document.addEventListener('DOMContentLoaded', function() {
    initConsultantAgents();
});

function initConsultantAgents() {
    const agents = document.querySelectorAll('.consultant-agent');
    const modals = document.querySelectorAll('.agent-modal');
    const closeButtons = document.querySelectorAll('.close-modal');
    let activeModal = null;
    let hoverTimer;

    // Desktop: Hover to show, click to close
    // Mobile: Click to toggle
    
    agents.forEach(agent => {
        // Mouse events for desktop
        agent.addEventListener('mouseenter', function() {
            if (window.innerWidth > 768) { // Desktop
                clearTimeout(hoverTimer);
                const agentId = this.dataset.agent;
                showModal(agentId);
            }
        });

        agent.addEventListener('mouseleave', function() {
            if (window.innerWidth > 768) { // Desktop
                hoverTimer = setTimeout(() => {
                    if (activeModal && !activeModal.matches(':hover')) {
                        hideModal();
                    }
                }, 300);
            }
        });

        // Click event for both desktop and mobile
        agent.addEventListener('click', function(e) {
            e.stopPropagation();
            const agentId = this.dataset.agent;
            
            if (activeModal && activeModal.id === `${agentId}-modal`) {
                // Clicking same agent - toggle close
                hideModal();
            } else {
                // Clicking different agent - show new modal
                showModal(agentId);
            }
        });
    });

    // Modal mouse events
    modals.forEach(modal => {
        modal.addEventListener('mouseenter', function() {
            if (window.innerWidth > 768) {
                clearTimeout(hoverTimer);
            }
        });

        modal.addEventListener('mouseleave', function() {
            if (window.innerWidth > 768) {
                hoverTimer = setTimeout(() => {
                    hideModal();
                }, 300);
            }
        });
    });

    // Close button click
    closeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            hideModal();
        });
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.consultant-agent') && 
            !e.target.closest('.agent-modal') &&
            !e.target.closest('.close-modal')) {
            hideModal();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideModal();
        }
    });

    // Function to show modal
    function showModal(agentId) {
        // Hide current modal
        if (activeModal) {
            activeModal.classList.remove('active');
        }
        
        // Show new modal
        const modal = document.getElementById(`${agentId}-modal`);
        if (modal) {
            activeModal = modal;
            modal.classList.add('active');
        }
    }

    // Function to hide modal
    function hideModal() {
        if (activeModal) {
            activeModal.classList.remove('active');
            activeModal = null;
        }
    }

    // Touch events for mobile
    if ('ontouchstart' in window) {
        let touchStartX;
        let touchStartY;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        });

        document.addEventListener('touchend', function(e) {
            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;
            const diffX = Math.abs(touchEndX - touchStartX);
            const diffY = Math.abs(touchEndY - touchStartY);

            // If it's a tap (not swipe) and not on an agent or modal
            if (diffX < 10 && diffY < 10 && 
                !e.target.closest('.consultant-agent') && 
                !e.target.closest('.agent-modal')) {
                hideModal();
            }
        });
    }

var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6967822f015fb1197cae4b5e/1jeu5933a';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})(); }
/* Language dropdown: open on hover (desktop) with a close delay so moving the
   cursor onto an option doesn't dismiss it. Click still works (touch/mobile). */
document.addEventListener('DOMContentLoaded', function () {
    if (!window.bootstrap || !bootstrap.Dropdown) return;
    var isDesktop = function () { return window.matchMedia('(min-width: 992px)').matches; };
    document.querySelectorAll('.xjmu-lang-dd').forEach(function (dd) {
        var toggle = dd.querySelector('[data-bs-toggle="dropdown"]');
        if (!toggle) return;
        var inst = bootstrap.Dropdown.getOrCreateInstance(toggle);
        var timer;
        dd.addEventListener('mouseenter', function () { if (isDesktop()) { clearTimeout(timer); inst.show(); } });
        dd.addEventListener('mouseleave', function () { if (isDesktop()) { timer = setTimeout(function () { inst.hide(); }, 200); } });
    });
});
