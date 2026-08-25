<?php
// Load configuration
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

$page_title = "Contact XJMU | Admissions & Support";
$page_description = "Contact Xinjiang Medical University for admissions, program info, and support. Reach us by phone, email, or WhatsApp. Our team in Urumqi is ready to help.";
$page_keywords = "contact Xinjiang Medical University, XJMU contact, admissions contact, university address China, medical university contact, study in China contact";
$page_url = "https://xjmu.online/contact";
$page_image = "https://xjmu.online/images/logo.png";
$body_class = 'ds-page';
$page_css = ['css/design-system.css', 'css/contact.css'];
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

<!-- ===================== HERO ===================== -->
<section class="contact-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Contact</span>
        <h1 data-i18n="contact.page_title">Get in touch</h1>
        <p data-i18n="contact.subheading">Want to get in touch? We'd love to hear from you. Here's how you can reach us.</p>
    </div>
</section>

<div class="container">
    <nav class="contact-crumb" aria-label="breadcrumb">
        <a href="<?= get_absolute_url('index') ?>">Home</a> &rsaquo; <span>Contact</span>
    </nav>
</div>

<main class="contact-page-hubspot">
    <div class="container">

        <!-- Talk to Sales Section -->
        <section class="contact-section">
            <h2 class="section-heading" data-i18n="contact.talk_admissions">Talk to Admissions</h2>
            <p class="section-description">Interested in Xinjiang Medical University's programs? Just pick up the phone to chat with a member of our admissions team.</p>
            <?php require_once __DIR__ . '/includes/settings.php'; ?>
            <div class="contact-phone">
                <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', get_setting('contact_phone')), ENT_QUOTES) ?>" class="phone-link" target="_blank" rel="noopener noreferrer">
                    <i class="fas fa-phone-alt"></i>
                    <span><?= setting_e('contact_phone') ?></span>
                </a>
                <a href="tel:+923004009526" class="phone-link" target="_blank" rel="noopener noreferrer">
                    <i class="fas fa-phone-alt"></i>
                    <span>+92 300 400 9526</span>
                </a>
            </div>
        </section>

        <!-- Contact Customer Support Section -->
        <section class="contact-section">
            <h2 class="section-heading" data-i18n="contact.support_heading">Contact Customer Support</h2>
            <p class="section-description">Sometimes you need a little help from your friends. Or a Xinjiang Medical University support rep. Don't worry… we're here for you.</p>
            <a href="#contact-form" class="support-link" target="_blank" rel="noopener noreferrer">Contact Support</a>
        </section>

        <!-- Connect with Global Offices -->
        <section class="contact-section">
            <h2 class="section-heading" data-i18n="contact.offices_heading">Connect with one of our offices</h2>
            
            <!-- Main Campus Office -->
            <div class="office-card">
                <div class="office-header">
                    <h3 class="office-title" data-i18n="contact.main_campus">Main Campus</h3>
                </div>
                <div class="office-content">
                    <div class="office-address">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <p>393 Xinyi Road, Xinshi District<br />Urumqi, Xinjiang Uygur Autonomous Region<br />830011, People's Republic of China</p>
                        </div>
                    </div>
                    
                    <div class="office-contact">
                        <div class="contact-item">
                            <strong>Phone:</strong>
                            <div>
                                <a href="tel:+8618041566030" target="_blank" rel="noopener noreferrer">+86 180 4156 6030</a> (China)<br />
                                <a href="tel:+923004009526" target="_blank" rel="noopener noreferrer">+92 300 400 9526</a> (Pakistan)
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <strong>Email:</strong>
                            <div>
                                <a href="mailto:<?= setting_e('admissions_email', 'admissions@xjmu.online') ?>" target="_blank" rel="noopener noreferrer"><?= setting_e('admissions_email', 'admissions@xjmu.online') ?></a> (Admissions)<br />
                                <a href="mailto:<?= setting_e('contact_email', 'info@xjmu.online') ?>" target="_blank" rel="noopener noreferrer"><?= setting_e('contact_email', 'info@xjmu.online') ?></a> (General Inquiry)
                            </div>
                        </div>
                    </div>
                    
                    <div class="office-map-link">
                        <a href="https://goo.gl/maps/XYZ123" target="_blank" rel="noopener noreferrer" class="map-link-btn">
                            <i class="fas fa-map-marker-alt"></i>
                            <span data-i18n="contact.how_to_find">How to find this office</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form and Map Section (Combined) -->
        <section id="contact-form" class="contact-section form-map-section">
            <div class="form-map-container">
                <!-- Left Column: Contact Form -->
                <div class="form-column">
                    <h2 class="section-heading" data-i18n="contact.send_message">Send us a message</h2>
                    <p class="section-description">Fill out the form below and we'll get back to you as soon as possible.</p>
                    
                    <form id="contactForm" class="hubspot-contact-form" method="POST" action="<?php echo function_exists('get_absolute_url') ? get_absolute_url('send_contact_email') : 'send_contact_email.php'; ?>">
                <div class="form-group">
                    <label for="name"><span data-i18n="contact.name_label">Full Name</span> <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required />
                    <div class="form-feedback"></div>
                </div>
                
                <div class="form-group">
                    <label for="email"><span data-i18n="contact.email_label">Email</span> <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" required />
                    <div class="form-feedback"></div>
                </div>
                
                <div class="form-row">
                    <div class="form-group-half">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="+86 180 4156 6030" />
                        <div class="form-feedback"></div>
                    </div>
                    <div class="form-group-half">
                        <label for="nationality">Nationality <span class="required">*</span></label>
                        <input type="text" id="nationality" name="nationality" class="form-control" required placeholder="Your Nationality" />
                        <div class="form-feedback"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="subject">Inquiry Type <span class="required">*</span></label>
                    <select id="subject" name="subject" class="form-control" required>
                        <option value="">Select your inquiry type...</option>
                        <option value="MBBS Program Admission">MBBS Program Admission</option>
                        <option value="Other Undergraduate Programs">Other Undergraduate Programs</option>
                        <option value="Postgraduate Programs">Postgraduate Programs</option>
                        <option value="Doctoral Programs">Doctoral Programs</option>
                        <option value="Scholarship Information">Scholarship Information</option>
                        <option value="Tuition & Fees">Tuition & Fees Information</option>
                        <option value="Accommodation">Accommodation & Housing</option>
                        <option value="Application Process">Application Process</option>
                        <option value="Visa Assistance">Visa & Immigration Assistance</option>
                        <option value="Clinical Training">Clinical Training & Internships</option>
                        <option value="Research Collaboration">Research Collaboration</option>
                        <option value="Faculty Recruitment">Faculty Recruitment</option>
                        <option value="General Information">General Information</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="form-feedback"></div>
                </div>
                
                <div class="form-group">
                    <label for="program">Interested Program</label>
                    <select id="program" name="program" class="form-control">
                        <option value="">Select program (if applicable)...</option>
                        <option value="MBBS (Clinical Medicine)">MBBS (Clinical Medicine)</option>
                        <option value="Traditional Chinese Medicine">Traditional Chinese Medicine</option>
                        <option value="Stomatology (Dentistry)">Stomatology (Dentistry)</option>
                        <option value="Pharmacy">Pharmacy</option>
                        <option value="Nursing">Nursing</option>
                        <option value="Public Health">Public Health</option>
                        <option value="Clinical Medicine (Postgraduate)">Clinical Medicine (Postgraduate)</option>
                        <option value="Biomedical Research (PhD)">Biomedical Research (PhD)</option>
                        <option value="Not Sure Yet">Not Sure Yet</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message <span class="required">*</span></label>
                    <textarea id="message" name="message" class="form-control" required rows="5" maxlength="400" placeholder="Please provide details about your inquiry, including your educational background, interests, and any specific questions..."></textarea>
                    <div class="character-count"><span id="charCount">0</span>/400 characters</div>
                    <div class="form-feedback"></div>
                </div>
                
                <div class="form-checkbox-group">
                    <input type="checkbox" id="newsletter" name="newsletter" checked />
                    <label for="newsletter">Yes, subscribe me to your newsletter. <span class="required">*</span></label>
                </div>
                
                <div class="form-submit-wrapper">
                    <button type="submit" class="submit-button">Send Message</button>
                </div>
            </form>
            
                    <!-- Success Message -->
                    <div id="successMessage" class="success-message" style="display: none;">
                        <div class="success-content">
                            <i class="fas fa-check-circle"></i>
                            <h4>Message Sent Successfully!</h4>
                            <p id="successText">Thank you for contacting Xinjiang Medical University. We've received your message and will get back to you within 1-2 business days.</p>
                            <button class="btn btn-primary" onclick="resetForm()">Send Another Message</button>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Google Map -->
                <div class="map-column">
                    <h2 class="section-heading">Our Location</h2>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2891.112267640451!2d87.61885631549956!3d43.79819217912667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDPCsDQ3JzUzLjUiTiA4N8KwMzcnMTIuMCJF!5e0!3m2!1sen!2s!4v1641234567890!5m2!1sen!2s" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="map-actions">
                        <a href="https://goo.gl/maps/XYZ123" target="_blank" rel="noopener noreferrer" class="map-link-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Open in Google Maps
                        </a>
                        <a href="https://maps.apple.com/?q=Xinjiang+Medical+University" target="_blank" rel="noopener noreferrer" class="map-link-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Open in Apple Maps
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- Page Specific JS -->
<?php
if (function_exists('get_absolute_url')) {
    echo '<script src="' . get_absolute_url('js/contact.js') . '"></script>';
} else {
    echo '<script src="js/contact.js"></script>';
}
?>
<?php 
// Use absolute path for includes
$footer_path = __DIR__ . '/includes/footer.php';
if (file_exists($footer_path)) {
    include $footer_path;
} else {
    die('Error: Footer file not found. Please check file paths.');
}
?>
