<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="xjmu-base" content="https://xjmu.online">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Xinjiang Medical University Application Portal</title>
  <meta name="description" content="Create your account on the Xinjiang Medical University application portal to apply for MBBS and other programs, upload documents and track your application online.">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://xjmu.online/register">
  @verbatim<script type="application/ld+json">{"@context":"https://schema.org","@graph":[{"@type":"WebPage","@id":"https://xjmu.online/register","url":"https://xjmu.online/register","name":"Register","description":"Create your account on the Xinjiang Medical University application portal to apply for MBBS and other programs, upload documents and track your application online.","inLanguage":"en","isPartOf":{"@type":"WebSite","name":"Xinjiang Medical University Admissions","url":"https://xjmu.online/"},"about":{"@type":"CollegeOrUniversity","name":"Xinjiang Medical University","url":"https://xjmu.online/"}},{"@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://xjmu.online/"},{"@type":"ListItem","position":2,"name":"Register","item":"https://xjmu.online/register"}]}]}</script>@endverbatim
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="icon" href="assets/images/logo2.webp" type="image/x-icon">
  <link rel="shortcut icon" href="assets/images/logo2.webp" type="image/x-icon">
  
  <style>
    *{
      border-radius: 0% !important;
    }
    body {
      background-color: #f8f9fa;
    }
    /* --- Custom Utility Classes for Branding --- */
    .text-jmu-primary { color: #E62129 !important; }
    .bg-jmu-primary { background-color: #E62129 !important; }
    .text-jmu-secondary { color: #E62129 !important; }
    
    /* Navbar specific overrides */
    .navbar {
      border-bottom: 2px solid #E62129;
      min-height: 60px;
    }
    
    .vertical-divider {
      background-color: black;
      width: 2px;
      height: 60px;
      opacity: 0.7;
    }

    /* Main Background */
    .main-container {
      background-image:url('assets/images/bg.jpeg');
      background-size: cover;
      background-position: center;
      min-height: calc(100vh - 80px); /* Matches navbar height */
    }

    /* Form Styling Overrides */
    .form-control:focus {
      border-color: #E62129;
      box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
    }

    .btn-jmu {
      background-color: #E62129;
      border-color: #E62129;
      color: white;
      font-weight: 600;
      transition: all 0.3s;
    }
    .btn-jmu:hover {
      background-color: #E62129;
      border-color: #E62129;
      color: white;
    }

    /* Captcha Pattern */
    .captcha-container {
      background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0), 
                  linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0);
      background-size: 10px 10px;
      background-position: 0 0, 5px 5px;
      letter-spacing: 5px;
      font-family: 'Courier New', monospace;
      color: #E62129;
      cursor: pointer;
    }

    /* News Date Box */
    .news-date {
      min-width: 60px;
      height: 60px;
    }
    
    /* Animation */
    .animate-up { animation: fadeInUp 0.5s ease-out; }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/portal-auth.css?v=3">
</head>
<body class="d-flex flex-column min-vh-100 portal-auth">

  <nav class="navbar navbar-expand-lg sticky-top bg-light shadow-sm py-2">
    <div class="container">
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
        
        <div class="d-flex flex-column flex-md-row align-items-center text-center text-md-start">
          <div class="logo-img" style="max-width: 240px;">
            <img src="assets/images/logo.webp" alt="Xinjiang Medical University" class="img-fluid" style="max-width: 200px;">
          </div>
          
          <div class="vertical-divider d-none d-md-block mx-3"></div>
          
          <div class="mt-2 mt-md-0">
            <span class="fw-bold fs-5 d-block"><span data-i18n="register.int_students">International Students</span></span>
            <span class="fs-6 d-block"><span data-i18n="register.system_name">Centralized Admissions System (CAS)</span></span>
          </div>
        </div>

        <div class="mt-3 mt-md-0">
          <div class="dropdown">
            <button class="btn btn-outline-dark rounded-pill px-3 dropdown-toggle bg-opacity-10 border-opacity-50" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-globe2 me-1"></i> <span data-lang-toggle>English</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item active" href="#" data-lang-item="en"><i class="bi bi-translate me-2"></i><span data-i18n="header.lang_en">English</span></a></li>
              <li><a class="dropdown-item" href="#" data-lang-item="zh"><i class="bi bi-translate me-2"></i><span data-i18n="header.lang_zh">中文 (Chinese)</span></a></li>
              <li><a class="dropdown-item" href="#" data-lang-item="fr"><i class="bi bi-translate me-2"></i><span data-i18n="header.lang_fr">Français</span></a></li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </nav>

  <div class="main-container d-flex align-items-center py-5">
    <div class="container">
      <div class="row justify-content-center align-items-center g-4">
        
        <div class="col-lg-5 mb-4 d-none d-lg-block text-white">
          <h1 class="display-6 fw-bold mb-4 text-jmu-primary" data-i18n="register.create_account_heading">Create Your Account</h1>
          <p class="fs-5 mb-4 lh-base shadow-text">
            Join thousands of international students who have chosen Xinjiang Medical University for their medical education. Create your account to begin your application journey.
          </p>
          
          <div class="bg-white text-dark p-4 rounded shadow">
            <h5 class="fw-bold mb-3 text-jmu-primary" data-i18n="register.why_title">Why Create an Account?</h5>
            <ul class="list-unstyled mb-0">
              <li class="mb-2 d-flex align-items-center"><i class="bi bi-check-circle-fill me-2 text-jmu-secondary"></i> <span data-i18n="register.why1">Save and track your application progress</span></li>
              <li class="mb-2 d-flex align-items-center"><i class="bi bi-check-circle-fill me-2 text-jmu-secondary"></i> <span data-i18n="register.why2">Upload required documents securely</span></li>
              <li class="mb-2 d-flex align-items-center"><i class="bi bi-check-circle-fill me-2 text-jmu-secondary"></i> <span data-i18n="register.why3">Receive admission updates</span></li>
              <li class="mb-2 d-flex align-items-center"><i class="bi bi-check-circle-fill me-2 text-jmu-secondary"></i> <span data-i18n="register.why4">Access to resources and guides</span></li>
              <li class="d-flex align-items-center"><i class="bi bi-check-circle-fill me-2 text-jmu-secondary"></i> <span data-i18n="register.why5">Connect with our admissions team</span></li>
            </ul>
          </div>
        </div>
        
        <div class="col-lg-5 col-xl-5">
          <div class="card border-0 shadow-lg animate-up overflow-hidden mx-auto" style="max-width: 450px;">
            <div class="card-header bg-jmu-primary text-white text-center p-4">
              <h4 class="mb-0 fw-bold" data-i18n="register.create_account_btn">Create Account</h4>
            </div>

            <div class="card-body p-4">
              <form action="register" method="POST" onsubmit="return validateForm()">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="mb-3">
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-person-vcard-fill text-jmu-primary"></i></span>
                    <input type="text" class="form-control border-start-0 shadow-none" id="full_name" name="full_name" placeholder="Enter your full name" data-i18n-placeholder="register.name_placeholder" required>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-envelope-fill text-jmu-primary"></i></span>
                    <input type="email" class="form-control border-start-0 shadow-none" id="email" name="email" placeholder="Enter your email" data-i18n-placeholder="register.email_placeholder" required>
                  </div>
                  <div class="form-text small" data-i18n="register.email_hint">We'll send important updates to this email</div>
                </div>
                
                <div class="mb-3">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-jmu-primary"></i></span>
                    <input type="password" class="form-control border-start-0 border-end-0 shadow-none" 
                           id="password-input" name="password" placeholder="Create Password" data-i18n-placeholder="register.password_placeholder" required 
                           oninput="checkPasswordStrength(); checkMatch();">
                    <span class="input-group-text bg-white border-start-0 cursor-pointer" onclick="togglePassword('password-input', 'toggle-icon')" style="cursor: pointer;">
                      <i class="bi bi-eye-fill text-secondary" id="toggle-icon"></i>
                    </span>
                  </div>
                  
                  <div class="progress mt-1 rounded-0" style="height: 4px;">
                     <div id="strength-bar" class="progress-bar rounded-0" role="progressbar" style="width: 0%; transition: width 0.3s ease;"></div>
                  </div>
                  <div id="password-feedback" class="small mt-1 text-danger fw-bold" style="font-size: 0.75rem; display: none;">
                    Min 8 chars, incl. numbers, symbols & letters.
                  </div>
                </div>

                <div class="mb-3">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-jmu-primary"></i></span>
                    <input type="password" class="form-control border-start-0 border-end-0 shadow-none" 
                           id="confirm-password-input" name="confirm_password" placeholder="Confirm Password" data-i18n-placeholder="register.confirm_placeholder" required 
                           oninput="checkMatch()">
                    <span class="input-group-text bg-white border-start-0 cursor-pointer" onclick="togglePassword('confirm-password-input', 'toggle-icon-confirm')" style="cursor: pointer;">
                      <i class="bi bi-eye-fill text-secondary" id="toggle-icon-confirm"></i>
                    </span>
                  </div>
                  <div id="match-feedback" class="small mt-1 fw-bold" style="font-size: 0.75rem; color: #dc3545; display: none;">
                    Passwords do not match.
                  </div>
                </div>

                <div class="mb-4">
                  <div class="d-flex gap-2">
                    <div class="input-group flex-grow-1">
                      <span class="input-group-text bg-light"><i class="bi bi-shield-check text-jmu-primary"></i></span>
                      <input type="text" class="form-control" id="captcha-input" name="captcha" placeholder="Enter code" data-i18n-placeholder="register.captcha_placeholder" required>
                    </div>
                    <div id="captcha-preview" onclick="generateCaptcha()" title="Click to refresh" class="captcha-container rounded border d-flex align-items-center justify-content-center fw-bold fs-5 px-3"></div>
                  </div>
                </div>

                <div class="form-check mb-4">
                  <input class="form-check-input" type="checkbox" id="terms" required>
                  <label class="form-check-label small text-muted" for="terms">
                    <span data-i18n="register.terms_text">I agree to the</span> <a href="terms" class="text-decoration-none text-jmu-primary fw-bold" target="_blank" rel="noopener noreferrer"><span data-i18n="register.terms_link">Terms of Service</span></a> and <a href="privacy" class="text-decoration-none text-jmu-primary fw-bold" target="_blank" rel="noopener noreferrer"><span data-i18n="register.privacy_link">Privacy Policy</span></a>
                  </label>
                </div>

                <button type="submit" class="btn btn-jmu w-100 mb-3 py-2">
                  <span data-i18n="register.create_btn">Create Account</span> <i class="bi bi-person-plus-fill ms-1"></i>
                </button>
              </form>

              <div class="text-center mt-4 pt-3 border-top">
                <p class="small mb-2 text-muted"><span data-i18n="register.already_account">Already have an account?</span> <a href="login" class="text-jmu-primary fw-bold text-decoration-none" data-i18n="register.sign_in_link">Sign In here</a></p>
                <p class="small mb-0 text-muted">
                  <span data-i18n="register.link_expired">Link expired?</span>
                  <a href="#" class="fw-bold text-decoration-none" style="color: var(--jmu-warning);" data-bs-toggle="modal" data-bs-target="#resendModal"><span data-i18n="register.resend_verification">Resend Verification</span>
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="pt-4 bg-white border-top border-3 border-jmu-primary">
    <div class="container">
      <div class="row">
        <div class="col-12 mb-2 p-footer-contact">
          <div class="fw-bold pb-2 mb-4 fs-5 text-jmu-primary border-bottom border-success border-opacity-50"><span data-i18n="register.need_help">Need Help?</span></div>
          <ul class="list-unstyled small">
            <li class="mb-2"><i class="bi bi-question-circle-fill me-2 text-jmu-primary"></i> <a href="#" class="text-decoration-none text-secondary"><span data-i18n="register.reg_guide">Registration Guide</span></a></li>
            <li class="mb-2"><i class="bi bi-file-earmark-text-fill me-2 text-jmu-primary"></i> <a href="#" class="text-decoration-none text-secondary"><span data-i18n="register.required_docs">Required Documents</span></a></li>
            <li class="mb-2"><i class="bi bi-telephone-fill me-2 text-jmu-primary"></i> <span data-i18n="register.admissions_phone">Admissions Office: +86 176 4041 2827</span></li>
            <li><i class="bi bi-envelope-fill me-2 text-jmu-primary"></i>  studyatxjmu@gmail.com</li>
          </ul>
        </div>
      </div>

      <div class="text-center small py-4 mt-4 bg-light rounded">
        <p class="mb-0"><span data-i18n="register.copyright">© Copyright 2027 Xinjiang Medical University All Rights Reserved</span></p>
        <p class="mt-2 mb-0 text-muted" data-i18n="register.copyright_sub">International Students Centralized Admissions System</p>
      </div>
    </div>
  </footer>

  <div class="modal fade" id="resendModal" tabindex="-1" aria-labelledby="resendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white bg-jmu-primary">
          <h5 class="modal-title" id="resendModalLabel"><i class="bi bi-envelope-paper-fill me-2"></i><span data-i18n="register.resend_modal_title">Resend Verification Link</span></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="resend_code" method="POST">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="modal-body p-4">
            <p class="small text-muted mb-3" data-i18n="register.resend_modal_desc">Enter the email address you used during registration. We will send you a fresh verification link.</p>
            <div class="mb-3">
              <label for="resend_email" class="form-label fw-bold small" data-i18n="register.registered_email">Registered Email</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control" id="resend_email" name="email" placeholder="student@example.com" required>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light border-top-0">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><span data-i18n="register.cancel">Cancel</span></button>
            <button type="submit" class="btn btn-jmu btn-sm"><span data-i18n="register.send_link">Send Link</span> <i class="bi bi-send-fill ms-1"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // --- Global Validation State ---
    let isPasswordValid = false;
    let isMatchValid = false;

    // --- Password Toggle Function ---
    function togglePassword(inputId, iconId) {
      const passwordInput = document.getElementById(inputId);
      const toggleIcon = document.getElementById(iconId);

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye-fill');
        toggleIcon.classList.add('bi-eye-slash-fill');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash-fill');
        toggleIcon.classList.add('bi-eye-fill');
      }
    }

    // --- Password Match Logic ---
    function checkMatch() {
        const password = document.getElementById('password-input').value;
        const confirm = document.getElementById('confirm-password-input').value;
        const feedback = document.getElementById('match-feedback');

        if (confirm.length > 0) {
            if (password !== confirm) {
                feedback.style.display = 'block';
                feedback.textContent = "Passwords do not match.";
                feedback.style.color = "#dc3545"; // Red
                isMatchValid = false;
            } else {
                feedback.style.display = 'block';
                feedback.textContent = "Passwords match.";
                feedback.style.color = "#198754"; // Green
                isMatchValid = true;
            }
        } else {
            feedback.style.display = 'none';
            isMatchValid = false;
        }
    }

    // --- Password Strength Logic ---
    function checkPasswordStrength() {
      const input = document.getElementById('password-input');
      const bar = document.getElementById('strength-bar');
      const feedback = document.getElementById('password-feedback');
      const password = input.value;

      // Reset classes
      bar.className = 'progress-bar rounded-0';
      feedback.style.display = 'block';
      
      // Criteria Regex
      const hasNumbers = /\d/.test(password);
      const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
      const hasLetters = /[a-zA-Z]/.test(password);
      const isLongEnough = password.length >= 8;

      if (password.length === 0) {
        bar.style.width = '0%';
        feedback.style.display = 'none';
        isPasswordValid = false;
        return;
      }

      // Check Criteria combinations
      const criteriaCount = (hasNumbers ? 1 : 0) + (hasSpecial ? 1 : 0) + (hasLetters ? 1 : 0);

      if (isLongEnough && hasNumbers && hasSpecial && hasLetters) {
        // STRONG: Green
        bar.style.width = '100%';
        bar.classList.add('bg-success');
        feedback.textContent = "Strong password";
        feedback.style.color = "#198754";
        isPasswordValid = true;
      } else if (password.length >= 6 && criteriaCount >= 2) {
        // MEDIUM: Yellow
        bar.style.width = '66%';
        bar.classList.add('bg-warning');
        feedback.textContent = "Medium strength (Add more variety)";
        feedback.style.color = "#ffc107"; 
        isPasswordValid = false; 
      } else {
        // WEAK: Red
        bar.style.width = '33%';
        bar.classList.add('bg-danger');
        feedback.textContent = "Weak: Min 8 chars, use #, numbers & letters.";
        feedback.style.color = "#dc3545";
        isPasswordValid = false;
      }
    }

    // --- Captcha Logic ---
    function generateCaptcha() {
      const chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      let captcha = "";
      for (let i = 0; i < 4; i++) {
        captcha += chars[Math.floor(Math.random() * chars.length)];
      }
      document.getElementById('captcha-preview').textContent = captcha;
    }
    
    function validateCaptcha() {
      const input = document.getElementById('captcha-input');
      const preview = document.getElementById('captcha-preview');
      if (input.value.toUpperCase() !== preview.textContent) {
        alert("Incorrect verification code. Please try again.");
        generateCaptcha();
        input.value = '';
        input.focus();
        return false;
      }
      return true;
    }

    // --- Master Form Validation ---
    function validateForm() {
      // 1. Check Terms agreement
      const terms = document.getElementById('terms');
      if (!terms.checked) {
        alert("Please agree to the Terms of Service and Privacy Policy.");
        terms.focus();
        return false;
      }
      
      // 2. Check Captcha
      if (!validateCaptcha()) return false;

      // 3. Check Password strict requirements
      const password = document.getElementById('password-input').value;
      const hasNumbers = /\d/.test(password);
      const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
      const hasLetters = /[a-zA-Z]/.test(password);
      const isLongEnough = password.length >= 8;
      
      if (!isLongEnough || !hasNumbers || !hasSpecial || !hasLetters) {
        alert("Please ensure your password meets all requirements:\n\n- Minimum 8 characters\n- Include at least one number\n- Include at least one special character\n- Include at least one letter");
        document.getElementById('password-input').focus();
        return false;
      }

      // 4. Check Password Match
      const confirm = document.getElementById('confirm-password-input').value;
      if (password !== confirm) {
        alert("Passwords do not match. Please re-enter.");
        document.getElementById('confirm-password-input').focus();
        return false;
      }

      return true;
    }

    window.onload = generateCaptcha;
  </script>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/i18n.js"></script>
</body>
</html>