// contact.js - Enhanced contact form for Xinjiang Medical University

document.addEventListener('DOMContentLoaded', function() {
    initContactForm();
    initMapFeatures();
    initSocialButtons();
});

// Enhanced contact form with dual submission options
let formData = {};

function initContactForm() {
    const form = document.getElementById('contactForm');
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');

    if (form) {
        // Character counter for message (400 character limit)
        if (messageTextarea && charCount) {
            messageTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = String(length);
                if (length > 400) {
                    charCount.style.color = '#dc3545';
                    charCount.innerHTML = `${length} <i class="fas fa-exclamation-triangle ms-1"></i>`;
                } else if (length > 350) {
                    charCount.style.color = '#f59e0b';
                } else {
                    charCount.style.color = '#6c757d';
                }
            });
        }

        // Form validation on input
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach((input) => {
            input.addEventListener('blur', validateField);
            input.addEventListener('input', clearValidation);
        });
        

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validateForm()) {
                submitContactForm();
            }
        });

        // Auto-detect country from phone input
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                const phone = this.value;
                if (phone.startsWith('+86')) {
                    const countryInput = document.getElementById('nationality');
                    if (countryInput && !countryInput.value) {
                        countryInput.value = 'China';
                    }
                }
            });
        }
    }
}

function validateField(e) {
    const field = e.target;
    const feedback = field.parentNode.querySelector('.form-feedback');
    let isValid = true;
    let message = '';

    // Required field validation
    if (field.hasAttribute('required') && !field.value.trim()) {
        isValid = false;
        message = 'This field is required';
    }

    // Email validation
    if (field.type === 'email' && field.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            isValid = false;
            message = 'Please enter a valid email address';
        }
    }

    // Phone validation (international format)
    if (field.type === 'tel' && field.value) {
        const phoneRegex = /^[\+]?[1-9][\d\-\(\)\.\s]{9,}$/;
        if (!phoneRegex.test(field.value.replace(/\s/g, ''))) {
            isValid = false;
            message = 'Please enter a valid international phone number';
        }
    }

    // Message length validation (400 character limit)
    if (field.type === 'textarea' && field.value.length > 400) {
        isValid = false;
        message = 'Message must be 400 characters or less';
    }

    // Update UI
    if (feedback) {
        feedback.textContent = message;
        feedback.className = `form-feedback ${isValid ? 'valid' : 'invalid'}`;
    }

    field.style.borderColor = isValid ? '#198754' : '#dc3545';
    if (!isValid && !message) {
        field.style.borderColor = '#ced4da';
    }
    return isValid;
}

function clearValidation(e) {
    const field = e.target;
    const feedback = field.parentNode.querySelector('.form-feedback');
    if (feedback) {
        feedback.textContent = '';
        feedback.className = 'form-feedback';
    }
    field.style.borderColor = '#ced4da';
}

function validateForm() {
    const form = document.getElementById('contactForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalidField = null;

    requiredFields.forEach((field) => {
        if (!validateField({ target: field })) {
            isValid = false;
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
        }
    });

    // Scroll to first invalid field
    if (firstInvalidField) {
        firstInvalidField.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        firstInvalidField.focus();
    }

    return isValid;
}

// Submit contact form to PHP handler
async function submitContactForm() {
    const form = document.getElementById('contactForm');
    const submitBtn = form.querySelector('.submit-button');
    const originalBtnText = submitBtn ? submitBtn.textContent : 'Send Message';
    
    // Disable submit button
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
    }
    
    try {
        // Create FormData object
        const formData = new FormData(form);
        
        // Send to PHP handler
        const response = await fetch('send_contact_email.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            // Show success message
            form.style.display = 'none';
            const successMessage = document.getElementById('successMessage');
            const successText = document.getElementById('successText');
            if (successMessage && successText) {
                successText.textContent = result.message;
                successMessage.style.display = 'block';
                
                // Scroll to success message
                setTimeout(() => {
                    successMessage.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 100);
            }
        } else {
            // Show error
            alert(result.message || 'Failed to send message. Please try again.');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        }
    } catch (error) {
        alert('An error occurred. Please try again or contact us directly.');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
        }
    }
}

function prepareMessage() {
    const form = document.getElementById('contactForm');
    
    // Collect form data
    const firstName = document.getElementById('firstName') ? document.getElementById('firstName').value : '';
    const lastName = document.getElementById('lastName') ? document.getElementById('lastName').value : '';
    const fullName = firstName && lastName ? `${firstName} ${lastName}` : (firstName || lastName || 'Not provided');
    
    formData = {
        name: fullName,
        firstName: firstName,
        lastName: lastName,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value || 'Not provided',
        nationality: document.getElementById('nationality').value,
        subject: document.getElementById('subject').value,
        program: document.getElementById('program').value || 'Not specified',
        message: document.getElementById('message').value,
        timestamp: new Date().toISOString(),
        source: 'XJMU Website Contact Form'
    };

    // Show submission options with animation
    document.getElementById('initialSubmit').style.display = 'none';
    const optionsDiv = document.getElementById('submissionOptions');
    optionsDiv.style.display = 'block';
    optionsDiv.style.animation = 'slideUp 0.4s ease';
    
    // Scroll to submission options
    setTimeout(() => {
        optionsDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }, 300);
}

// Make functions globally available
window.sendViaWhatsApp = function() {
    // Get form data
    const form = document.getElementById('contactForm');
    const name = document.getElementById('name') ? document.getElementById('name').value : '';
    const email = document.getElementById('email') ? document.getElementById('email').value : '';
    const phone = document.getElementById('phone') ? document.getElementById('phone').value : 'Not provided';
    const nationality = document.getElementById('nationality') ? document.getElementById('nationality').value : '';
    const subject = document.getElementById('subject') ? document.getElementById('subject').value : '';
    const program = document.getElementById('program') ? document.getElementById('program').value : 'Not specified';
    const message = document.getElementById('message') ? document.getElementById('message').value : '';
    
    // Create formatted WhatsApp message for XJMU
    let whatsappMessage = `*📋 XINJIANG MEDICAL UNIVERSITY - CONTACT FORM SUBMISSION*%0A%0A`;
    whatsappMessage += `*👤 Applicant Information:*%0A`;
    whatsappMessage += `• *Name:* ${encodeURIComponent(name)}%0A`;
    whatsappMessage += `• *Email:* ${encodeURIComponent(email)}%0A`;
    whatsappMessage += `• *Phone:* ${encodeURIComponent(phone)}%0A`;
    whatsappMessage += `• *Nationality:* ${encodeURIComponent(nationality)}%0A%0A`;
    
    whatsappMessage += `*🎓 Academic Interest:*%0A`;
    whatsappMessage += `• *Inquiry Type:* ${encodeURIComponent(subject)}%0A`;
    whatsappMessage += `• *Program Interest:* ${encodeURIComponent(program)}%0A%0A`;
    
    whatsappMessage += `*💬 Message:*%0A${encodeURIComponent(message)}%0A%0A`;
    
    whatsappMessage += `*📅 Submitted:* ${new Date().toLocaleString()}%0A`;
    whatsappMessage += `*🌐 Source:* XJMU Website Contact Form`;

    const whatsappNumber = '8618041566030'; // XJMU Admissions WhatsApp
    const whatsappURL = `https://wa.me/${whatsappNumber}?text=${whatsappMessage}`;
    
    // Open WhatsApp in new tab
    window.open(whatsappURL, '_blank', 'noopener,noreferrer');
    
    // Show success message after delay
    setTimeout(() => {
        showSuccessMessage('WhatsApp', 'Our admissions team will respond within 24 hours.');
    }, 1000);
};

window.sendViaEmail = function() {
    // This function is kept for compatibility but form now submits directly via PHP
    submitContactForm();
};

window.showWeChatQR = function() {
    const modal = document.getElementById('wechatModal');
    modal.style.display = 'flex';
    
    // Add event listener for ESC key
    document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
            closeWeChatQR();
            document.removeEventListener('keydown', escHandler);
        }
    });
    
    // Add click outside to close
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeWeChatQR();
        }
    });
};

window.closeWeChatQR = function() {
    const modal = document.getElementById('wechatModal');
    modal.style.display = 'none';
};

window.editMessage = function() {
    document.getElementById('submissionOptions').style.display = 'none';
    document.getElementById('initialSubmit').style.display = 'block';
    
    // Scroll to form top
    document.getElementById('contactForm').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
};

function showSuccessMessage(method, additionalInfo = '') {
    const form = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    
    // Hide form
    if (form) form.style.display = 'none';
    
    // Update success message
    let message = `Thank you for contacting Xinjiang Medical University via ${method}! `;
    message += `Your inquiry has been submitted successfully. `;
    message += additionalInfo;
    message += ` Our admissions team will contact you within 1-2 business days.`;
    
    if (successMessage && successText) {
        successText.textContent = message;
        successMessage.style.display = 'block';
        successMessage.style.animation = 'slideUp 0.5s ease';
        
        // Scroll to success message
        setTimeout(() => {
            successMessage.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 300);
    }
}

window.resetForm = function() {
    const form = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');
    const submitBtn = form.querySelector('.submit-button');
    
    // Reset form
    form.reset();
    form.style.display = 'block';
    
    // Reset submit button
    submitBtn.disabled = false;
    submitBtn.textContent = 'Send Message';
    
    // Hide success message
    successMessage.style.display = 'none';
    
    // Reset character count
    const charCount = document.getElementById('charCount');
    if (charCount) {
        charCount.textContent = '0';
        charCount.style.color = '#6c757d';
    }
    
    // Clear validation feedback
    const feedbacks = document.querySelectorAll('.form-feedback');
    feedbacks.forEach((feedback) => {
        feedback.textContent = '';
        feedback.className = 'form-feedback';
    });
    
    // Reset field borders
    const inputs = document.querySelectorAll('#contactForm input, #contactForm select, #contactForm textarea');
    inputs.forEach((input) => {
        input.style.borderColor = '#ced4da';
    });
    
    // Scroll to top of form
    form.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
};

// Initialize map features
function initMapFeatures() {
    const mapIframe = document.querySelector('.map-wrapper iframe');
    if (mapIframe) {
        // Add loading indicator
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'map-loading';
        loadingDiv.innerHTML = '<div class="spinner-border text-primary"></div><p class="mt-2">Loading map...</p>';
        loadingDiv.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
        `;
        
        mapIframe.parentNode.style.position = 'relative';
        mapIframe.parentNode.appendChild(loadingDiv);
        
        mapIframe.addEventListener('load', function() {
            loadingDiv.style.display = 'none';
        });
    }
    
    // Add map interaction events
    const mapActions = document.querySelectorAll('.map-actions a');
}

// Initialize social buttons
function initSocialButtons() {
    const socialButtons = document.querySelectorAll('.social-btn');
    socialButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const platform = this.className.includes('facebook') ? 'Facebook' :
                           this.className.includes('twitter') ? 'Twitter' :
                           this.className.includes('linkedin') ? 'LinkedIn' :
                           this.className.includes('youtube') ? 'YouTube' :
                           this.className.includes('instagram') ? 'Instagram' : 'Weibo';
            
            // Optional: Add analytics tracking here
            // Example: gtag('event', 'social_click', { 'platform': platform });
        });
    });
}

// Add keyboard shortcuts
document.addEventListener('keydown', 