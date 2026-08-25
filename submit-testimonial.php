<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/session_config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/admin/includes/helpers.php'; // save_upload + IMG_EXT/IMG_MIME

$submitted = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rolling 1-hour window of this session's submissions (anti-flood).
    $now  = time();
    $hits = array_values(array_filter($_SESSION['tst_hits'] ?? [], function ($t) use ($now) {
        return $t > $now - 3600;
    }));

    if (!csrf_check()) {
        $error = 'Your session expired. Please refresh the page and try again.';
    } elseif (!empty($_POST['website'])) {
        // Honeypot filled by a bot — pretend success, save nothing.
        $submitted = true;
    } elseif (!empty($hits) && ($now - end($hits)) < 30) {
        $error = 'Please wait a few seconds before submitting again.';
    } elseif (count($hits) >= 5) {
        $error = 'You have submitted several testimonials recently. Please try again later.';
    } else {
        $name     = trim($_POST['name'] ?? '');
        $quote    = trim($_POST['quote'] ?? '');
        $role     = trim($_POST['role'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $category = (($_POST['category'] ?? 'student') === 'alumni') ? 'alumni' : 'student';

        // Server-side validation — never trust the client's maxlength or a scripted POST.
        if (mb_strlen($name) < 2 || $quote === '') {
            $error = 'Please enter your name and your testimonial.';
        } elseif (mb_strlen($quote) < 10) {
            $error = 'Please write a little more about your experience (at least 10 characters).';
        } elseif (mb_strlen($quote) > 1000) {
            $error = 'Your testimonial is too long (please keep it under 1000 characters).';
        } else {
            // Clamp remaining fields to their DB column limits.
            $name     = mb_substr($name, 0, 150);
            $role     = mb_substr($role, 0, 200);
            $location = mb_substr($location, 0, 200);
            try {
                $photo = save_upload('photo', 'testimonials', IMG_EXT(), IMG_MIME(), 4 * 1024 * 1024);
                $pdo = $GLOBALS['pdo'];
                // Saved as pending (is_published = 0) — appears live only after admin approval.
                $pdo->prepare("INSERT INTO testimonials (name, role, location, quote, photo, category, sort_order, is_published)
                               VALUES (?, ?, ?, ?, ?, ?, 0, 0)")
                    ->execute([$name, $role, $location, $quote, $photo, $category]);
                // Record this successful submission in the rolling window.
                $hits[] = $now;
                $_SESSION['tst_hits'] = $hits;
                $submitted = true;
            } catch (\Throwable $e) {
                // Log the real reason; never leak internals to the visitor.
                error_log('submit-testimonial: ' . $e->getMessage());
                $error = 'Sorry, we could not save your testimonial right now. Please try again later.';
            }
        }
    }
}

$page_title       = "Share Your Testimonial | Xinjiang Medical University";
$page_description = "Are you an XJMU student or graduate? Share your experience to be featured on the Xinjiang Medical University website.";
$page_url         = "https://xjmu.online/submit-testimonial";
$page_noindex     = true; // submission form — keep out of search results

$body_class = 'ds-page';
$page_css   = ['css/design-system.css', 'css/contact.css'];

include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="contact-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Testimonials</span>
        <h1>Share your XJMU experience</h1>
        <p>Are you a current student or graduate of Xinjiang Medical University? Tell us about your journey — your testimonial may be featured on our website.</p>
    </div>
</section>

<main class="contact-page-hubspot">
    <div class="container">
        <div class="form-map-container" style="grid-template-columns:1fr; max-width:720px; margin:0 auto;">
            <div class="form-column">

                <?php if ($submitted): ?>
                    <div class="success-message" style="display:block;">
                        <div class="success-content">
                            <i class="fas fa-check-circle"></i>
                            <h4>Thank you!</h4>
                            <p>Your testimonial has been submitted successfully. Our team will review it, and once approved it will appear on the website.</p>
                            <a href="<?= get_absolute_url('submit-testimonial') ?>" class="ds-btn ds-btn--outline">Submit another</a>
                            <a href="<?= get_absolute_url('index') ?>" class="ds-btn ds-btn--navy">Back to home</a>
                        </div>
                    </div>
                <?php else: ?>

                    <h2 class="section-heading">Submit your testimonial</h2>
                    <p class="section-description">It only takes a minute. Fields marked <span class="required">*</span> are required.</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert" style="border:1px solid #f3c0c0;background:#fdecec;color:#b02a2a;padding:.8rem 1rem;border-radius:6px;margin-bottom:1rem;"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form class="hubspot-contact-form" method="POST" action="" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <!-- Honeypot: hidden from humans, bots tend to fill it -->
                        <div style="position:absolute;left:-9999px;" aria-hidden="true">
                            <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                        </div>

                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" required maxlength="150" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group-half">
                                <label for="category">I am a <span class="required">*</span></label>
                                <select id="category" name="category" class="form-control">
                                    <option value="student"<?= (($_POST['category'] ?? '') === 'student') ? ' selected' : '' ?>>Current / Recently Admitted Student</option>
                                    <option value="alumni"<?= (($_POST['category'] ?? '') === 'alumni') ? ' selected' : '' ?>>Graduate / Alumni</option>
                                </select>
                            </div>
                            <div class="form-group-half">
                                <label for="role">Program &amp; Batch</label>
                                <input type="text" id="role" name="role" class="form-control" placeholder="MBBS Program | Batch 2025" maxlength="200" value="<?= htmlspecialchars($_POST['role'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="location">Location / Current Position</label>
                            <input type="text" id="location" name="location" class="form-control" placeholder="e.g. Lahore, Pakistan" maxlength="200" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="quote">Your Testimonial <span class="required">*</span></label>
                            <textarea id="quote" name="quote" class="form-control" rows="5" required maxlength="1000" placeholder="Share your experience studying at XJMU..."><?= htmlspecialchars($_POST['quote'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="photo">Your Photo <span class="text-muted" style="font-weight:400">(optional, JPG/PNG/WebP, max 4 MB)</span></label>
                            <input type="file" id="photo" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                        </div>

                        <div class="form-submit-wrapper">
                            <button type="submit" class="submit-button">Submit Testimonial</button>
                        </div>
                    </form>

                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
