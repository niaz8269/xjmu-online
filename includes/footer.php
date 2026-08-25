<?php require_once __DIR__ . '/settings.php'; ?>
<!-- Shared Footer for Xinjiang Medical University -->
<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 data-i18n="footer.about_university">About University</h5>
                <ul class="footer-links">
                    <li><a href="/about#history" data-i18n="footer.history">History</a></li>
                    <li><a href="/about#leadership" data-i18n="footer.leadership">Leadership</a></li>
                    <li><a href="/about#vision" data-i18n="footer.vision_mission">Vision &amp; Mission</a></li>
                    <li><a href="/about#campus" data-i18n="footer.campus_tour">Campus Tour</a></li>
                    <li><a href="/about#ranking" data-i18n="footer.rankings">Rankings</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 data-i18n="footer.academics">Academics</h5>
                <ul class="footer-links">
                    <li><a href="/programs#undergraduate" data-i18n="footer.undergraduate">Undergraduate</a></li>
                    <li><a href="/programs#postgraduate" data-i18n="footer.postgraduate">Postgraduate</a></li>
                    <li><a href="/programs#doctoral" data-i18n="footer.doctoral">Doctoral</a></li>
                    <li><a href="/programs#research" data-i18n="footer.research_programs">Research Programs</a></li>
                    <li><a href="/programs#international" data-i18n="footer.international_programs">International Programs</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 data-i18n="footer.admissions">Admissions</h5>
                <ul class="footer-links">
                    <li><a href="/admissions#requirements" data-i18n="footer.requirements">Requirements</a></li>
                    <li><a href="/admissions#process" data-i18n="footer.application_process">Application Process</a></li>
                    <li><a href="/admissions#deadlines" data-i18n="footer.deadlines">Deadlines</a></li>
                    <li><a href="/admissions#fees" data-i18n="footer.tuition_fees">Tuition Fees</a></li>
                    <li><a href="/admissions#scholarships" data-i18n="footer.scholarships">Scholarships</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 data-i18n="footer.contact_info">Contact Info</h5>
                <div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> <?= setting_e('office_address', 'Ürümqi, Xinjiang, China') ?></p>
                    <p><i class="fas fa-phone"></i> <?= setting_e('contact_phone') ?>
                       <?php if (get_setting('contact_phone2')): ?><br><?= setting_e('contact_phone2') ?><?php endif; ?>
                    </p>
                    <p><i class="fas fa-envelope"></i> <?= setting_e('contact_email') ?></p>
                </div>
                <div class="social-footer mt-3">
                    <?php
                    $socials = [
                        'social_youtube'   => 'fa-youtube',
                        'social_linkedin'  => 'fa-linkedin',
                        'social_twitter'   => 'fa-twitter',
                        'social_instagram' => 'fa-instagram',
                        'social_tiktok'    => 'fa-tiktok',
                        'social_facebook'  => 'fa-facebook',
                    ];
                    foreach ($socials as $key => $icon):
                        $url = get_setting($key);
                        if (!$url) continue;
                    ?>
                        <a href="<?= htmlspecialchars($url, ENT_QUOTES) ?>" target="_blank" rel="noopener noreferrer"><i class="fab <?= $icon ?>"></i></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h5 data-i18n="footer.quick_links">Quick Links</h5>
                <div class="row">
                    <div class="col-6">
                        <ul class="footer-links">
                            <li><a href="/gallery" data-i18n="footer.photo_gallery">Photo Gallery</a></li>
                            <li><a href="/downloads" data-i18n="footer.downloads">Downloads</a></li>
                            <li><a href="/events" data-i18n="footer.news_events">News &amp; Events</a></li>
                            <li><a href="/blogs" data-i18n="footer.research">Research</a></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="footer-links">
                            <li><a href="/admissions" data-i18n="footer.international_students">International Students</a></li>
                            <li><a href="/be-our-partner" data-i18n="footer.careers">Careers</a></li>
                            <li><a href="/sitemap.xml" data-i18n="footer.sitemap">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h5 data-i18n="footer.newsletter">Newsletter</h5>
                <p data-i18n="footer.newsletter_desc">Subscribe to get updates on admissions and events</p>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Your Email" data-i18n-placeholder="footer.newsletter_placeholder">
                    <button class="btn btn-secondary-custom" type="button" data-i18n="footer.subscribe">Subscribe</button>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> <span data-i18n="footer.copyright">Xinjiang Medical University. All Rights Reserved.</span> | <a href="/privacy" style="color: white;" data-i18n="footer.privacy_policy">Privacy Policy</a> | <a href="/terms" style="color: white" data-i18n="footer.terms_of_use">Terms of Use</a></p>
            <p><b data-i18n="footer.powered_by">Powered By: <a href="https://cas.moe/" target="_blank" rel="noopener noreferrer" style="white-space: nowrap; text-decoration: none;">
    Consultancy for Abroad Study (CAS)
</a></b></p>
        </div>
    </div>
</footer>

<!-- Structured Data for Footer -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ContactPage",
    "name": "Contact Xinjiang Medical University",
    "description": "Contact information for Xinjiang Medical University admissions office",
    "url": "https://xjmu.online/contact",
    "mainEntity": {
        "@type": "Organization",
        "name": "Xinjiang Medical University",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+86-156-1750-0775",
            "contactType": "Admissions",
            "email": "studyatxjmu@gmail.com",
            "areaServed": "Worldwide",
            "availableLanguage": ["English", "Chinese"]
        }
    }
}
</script>

<?php
// Load configuration for path resolution
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} else {
    // Fallback if config.php doesn't exist. Guarded to avoid redeclare fatal
    // when header.php already defined it in the same request. Audit #15.
    if (!function_exists('get_absolute_url')) {
        function get_absolute_url($path) {
            $forwardedProto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
            $forwardedSsl = strtolower($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '');
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $forwardedProto === 'https' || $forwardedSsl === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'xjmu.online';
            return $protocol . '://' . $host . '/' . ltrim($path, '/');
        }
    }
}

// Include consultants modal on all pages
$consultants_path = __DIR__ . '/consultants.php';
if (file_exists($consultants_path)) {
    include $consultants_path;
}
?>
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Global JS -->
<script src="<?php echo get_absolute_url('js/global.js'); ?>"></script>

<!-- Auto-link the brand name "Xinjiang Medical University" to xjmu.online (visible text only) -->
<script>
(function () {
    var TARGET = 'Xinjiang Medical University', URL = 'https://xjmu.online';
    // Elements whose text must not be turned into links.
    var SKIP = { A:1, BUTTON:1, SCRIPT:1, STYLE:1, TEXTAREA:1, OPTION:1, SELECT:1, CODE:1, PRE:1, SVG:1, NOSCRIPT:1, TITLE:1 };
    function walk(node) {
        for (var child = node.firstChild; child; ) {
            var next = child.nextSibling;
            if (child.nodeType === 1) {
                if (!SKIP[child.tagName] && !child.isContentEditable && !child.closest('a')) walk(child);
            } else if (child.nodeType === 3 && child.nodeValue.indexOf(TARGET) !== -1) {
                linkify(child);
            }
            child = next;
        }
    }
    function linkify(textNode) {
        var text = textNode.nodeValue, frag = document.createDocumentFragment(), last = 0, idx;
        while ((idx = text.indexOf(TARGET, last)) !== -1) {
            if (idx > last) frag.appendChild(document.createTextNode(text.slice(last, idx)));
            var a = document.createElement('a');
            a.href = URL; a.className = 'xjmu-brandlink'; a.textContent = TARGET;
            frag.appendChild(a);
            last = idx + TARGET.length;
        }
        if (last < text.length) frag.appendChild(document.createTextNode(text.slice(last)));
        textNode.parentNode.replaceChild(frag, textNode);
    }
    if (document.body) walk(document.body);
})();
</script>
</body>
</html>
