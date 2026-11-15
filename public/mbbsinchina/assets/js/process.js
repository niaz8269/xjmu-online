// Process Page Specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Document Checklist Accordion
    document.querySelectorAll('.document-header').forEach(header => {
        header.addEventListener('click', function() {
            this.classList.toggle('active');
            const content = this.nextElementSibling;
            content.classList.toggle('active');
        });
    });

    // Progress Tracker
    document.querySelectorAll('.progress-steps li').forEach(step => {
        step.addEventListener('click', function() {
            const section = this.getAttribute('data-section');
            document.getElementById(section).scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Update progress tracker on scroll
    window.addEventListener('scroll', function() {
        const sections = ['timeline', 'requirements', 'documents', 'whatsapp-cta'];
        const progressSteps = document.querySelectorAll('.progress-steps li');
        
        let currentSection = '';
        sections.forEach(section => {
            const element = document.getElementById(section);
            if (element) {
                const rect = element.getBoundingClientRect();
                if (rect.top <= 100 && rect.bottom >= 100) {
                    currentSection = section;
                }
            }
        });
        
        progressSteps.forEach(step => {
            step.classList.remove('active');
            if (step.getAttribute('data-section') === currentSection) {
                step.classList.add('active');
            }
        });
    });

    // Document checklist persistence
    document.querySelectorAll('.document-item input[type="checkbox"]').forEach(checkbox => {
        // Load saved state
        const savedState = localStorage.getItem(checkbox.id);
        if (savedState === 'checked') {
            checkbox.checked = true;
        }
        
        // Save state on change
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                localStorage.setItem(this.id, 'checked');
            } else {
                localStorage.removeItem(this.id);
            }
        });
    });

    // WhatsApp click tracking
    document.querySelectorAll('.btn-whatsapp').forEach(button => {
        button.addEventListener('click', function() {
            // In a real application, you would send this data to analytics
            console.log('WhatsApp button clicked:', this.textContent.trim());
        });
    });
});