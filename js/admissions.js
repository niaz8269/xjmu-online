// admissions.js - Custom JavaScript for Admissions page

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap components
    initializeAccordion();
    initializeFeeCalculator();
    initializeApplicationForms();
    initializeTimelineAnimation();
    
    // Set up event listeners
    setupEventListeners();
});

// Initialize accordion functionality
function initializeAccordion() {
    const accordionButtons = document.querySelectorAll('.accordion-button');
    
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const accordionItem = this.closest('.accordion-item');
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Add animation class
            accordionItem.classList.toggle('expanded', isExpanded);
            
            // Update URL hash for deep linking
            const sectionId = accordionItem.id;
            if (isExpanded) {
                history.pushState(null, null, `#${sectionId}`);
            } else {
                history.pushState(null, null, window.location.pathname);
            }
        });
    });
    
    // Check for hash in URL and open corresponding accordion
    const hash = window.location.hash;
    if (hash) {
        const targetAccordion = document.querySelector(hash);
        if (targetAccordion) {
            const button = targetAccordion.querySelector('.accordion-button');
            if (button && button.getAttribute('aria-expanded') === 'false') {
                button.click();
            }
        }
    }
}

// Initialize fee calculator
function initializeFeeCalculator() {
    const programData = {
        undergraduate: {
            mbbs: { tuition: 30000, accommodation: 4500, insurance: 300 },
            tcm: { tuition: 25000, accommodation: 4000, insurance: 300 },
            stomatology: { tuition: 28000, accommodation: 4000, insurance: 300 },
            pharmacy: { tuition: 22000, accommodation: 4000, insurance: 300 }
        },
        postgraduate: {
            nursing: { tuition: 30000, accommodation: 5000, insurance: 300 },
            mph: { tuition: 32000, accommodation: 5000, insurance: 300 },
            clinical: { tuition: 35000, accommodation: 5000, insurance: 300 }
        },
        doctoral: {
            clinical: { tuition: 35000, accommodation: 6000, insurance: 300 },
            biomedical: { tuition: 33000, accommodation: 6000, insurance: 300 }
        }
    };
    
    const programSelect = document.getElementById('programSelect');
    const programLevel = document.getElementById('programLevel');
    const durationInput = document.getElementById('duration');
    const calculateBtn = document.getElementById('calculateBtn');
    const resultDiv = document.getElementById('calculationResult');
    const totalCostSpan = document.getElementById('totalCost');
    const costBreakdownSpan = document.getElementById('costBreakdown');
    
    // Update program options based on level
    programLevel.addEventListener('change', function() {
        const level = this.value;
        
        // Clear existing options
        programSelect.innerHTML = '';
        
        // Add options based on level
        if (level === 'undergraduate') {
            programSelect.innerHTML = `
                <option value="mbbs">MBBS (Clinical Medicine)</option>
                <option value="tcm">Traditional Chinese Medicine</option>
                <option value="stomatology">Stomatology</option>
                <option value="pharmacy">Pharmacy</option>
            `;
            durationInput.value = 4;
            durationInput.max = 6;
        } else if (level === 'postgraduate') {
            programSelect.innerHTML = `
                <option value="nursing">Master of Nursing</option>
                <option value="mph">Master of Public Health</option>
                <option value="clinical">Clinical Medicine (MSc)</option>
            `;
            durationInput.value = 2;
            durationInput.max = 3;
        } else if (level === 'doctoral') {
            programSelect.innerHTML = `
                <option value="clinical">Doctorate in Clinical Medicine</option>
                <option value="biomedical">Doctorate in Biomedical Research</option>
            `;
            durationInput.value = 3;
            durationInput.max = 4;
        }
    });
    
    // Calculate total cost
    calculateBtn.addEventListener('click', function() {
        const level = programLevel.value;
        const program = programSelect.value;
        const duration = parseInt(durationInput.value);
        
        if (!programData[level] || !programData[level][program]) {
            alert('Please select valid program options.');
            return;
        }
        
        const fees = programData[level][program];
        const annualTotal = fees.tuition + fees.accommodation + fees.insurance;
        const totalCost = annualTotal * duration;
        
        // Add one-time fees
        const oneTimeFees = 500 + 800 + 350 + (800 * duration) + (2000 * duration);
        const grandTotal = totalCost + oneTimeFees;
        
        // Display result
        totalCostSpan.textContent = `${grandTotal.toLocaleString()} ¥`;
        costBreakdownSpan.textContent = `Includes tuition, accommodation, insurance, and one-time fees for ${duration} years`;
        
        // Show result with animation
        resultDiv.classList.remove('d-none');
        resultDiv.style.opacity = '0';
        resultDiv.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
            resultDiv.style.transition = 'all 0.3s ease';
            resultDiv.style.opacity = '1';
            resultDiv.style.transform = 'translateY(0)';
        }, 10);
    });
}

// Initialize application forms download
function initializeApplicationForms() {
    const downloadLinks = document.querySelectorAll('.document-item');
    
    downloadLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const fileName = this.querySelector('span').textContent;
            
            // Simulate download with loading animation
            const icon = this.querySelector('.fa-download');
            const originalClass = icon.className;
            
            icon.className = 'fas fa-spinner fa-spin';
            
            // Show success message after delay
            setTimeout(() => {
                icon.className = originalClass;
                
                // Show toast notification
                showToast(`${fileName} download started`, 'success');
            }, 1000);
        });
    });
}

// Initialize timeline animation
function initializeTimelineAnimation() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    timelineItems.forEach(item => {
        observer.observe(item);
    });
}

// Show toast notification
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#198754' : '#0d6efd'};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s;
        animation-fill-mode: forwards;
    `;
    
    // Add keyframes for animation
    if (!document.querySelector('#toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes fadeOut {
                from {
                    opacity: 1;
                }
                to {
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(toast);
    
    // Remove toast after animation
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}

// Set up additional event listeners
function setupEventListeners() {
    // Contact button click
    const contactBtn = document.querySelector('a[href="contact"]');
    if (contactBtn) {
        contactBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Track contact button click (analytics could be added here)
            window.location.href = this.href;
        });
    }
    
    // Application portal button click
    const portalBtn = document.querySelector('a[href*="apply.xjmu.edu.cn"]');
    if (portalBtn) {
        portalBtn.addEventListener('click', function(e) {
            // Track portal click (analytics could be added here)
            console.log('Application portal clicked');
        });
    }
    
    // Print tuition tables
    const printButtons = document.querySelectorAll('.print-tuition');
    printButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            window.print();
        });
    });
    
    // Form validation for calculator
    const durationInput = document.getElementById('duration');
    if (durationInput) {
        durationInput.addEventListener('input', function() {
            const max = parseInt(this.max);
            const value = parseInt(this.value);
            
            if (value > max) {
                this.value = max;
                showToast(`Maximum duration for selected program is ${max} years`, 'info');
            }
            
            if (value < 1) {
                this.value = 1;
            }
        });
    }
}

// Add animation classes for timeline items
document.addEventListener('DOMContentLoaded', function() {
    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        .timeline-item {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.5s ease;
        }
        
        .timeline-item.animate-in {
            opacity: 1;
            transform: translateX(0);
        }
        
        .process-step {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        
        .process-step.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        
        .type-card {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        
        .type-card.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
    `;
    document.head.appendChild(style);
    
    // Observe process steps for animation
    const processSteps = document.querySelectorAll('.process-step');
    const typeCards = document.querySelectorAll('.type-card');
    
    const stepObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('animate-in');
                }, index * 100);
            }
        });
    }, {
        threshold: 0.1
    });
    
    processSteps.forEach(step => stepObserver.observe(step));
    typeCards.forEach(card => stepObserver.observe(card));
});

// Currency converter (optional feature)
function setupCurrencyConverter() {
    const currencyRates = {
        'USD': 0.14,
        'EUR': 0.13,
        'GBP': 0.11,
        'INR': 11.6,
        'RUB': 12.8
    };
    
    // Could be extended to show currency conversion
    // This is a placeholder for future implementation
    console.log('Currency converter ready for implementation');
}