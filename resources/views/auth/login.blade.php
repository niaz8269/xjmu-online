<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="xjmu-base" content="https://xjmu.online">
	<title>Xinjiang Medical University - Student Login</title>
	<link rel="canonical" href="https://xjmu.online/login">
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
		.text-jmu-primary { color: #E62129 !important; }
		.bg-jmu-primary { background-color: #E62129 !important; }
		.border-jmu-primary { border-color: #E62129 !important; }
    
		.navbar {
			border-bottom: 2px solid #E62129;
			min-height: 60px;
		}

		.logo-img img {
			max-width: 200px;
		}
		@media (max-width: 768px) { .logo-img img { max-width: 140px; } }

		.vertical-divider {
			background-color: black;
			width: 2px;
			height: 40px;
			opacity: 0.7;
		}

		/* Main background image with overlay */
		.main-container {
			background-image:url('assets/images/bg.jpeg');
			background-size: cover;
			background-position: center;
			min-height: calc(100vh - 60px); /* Matches navbar height */
		}

		/* Glassmorphism FAQ Card */
		.faq-card {
			background: rgba(255, 255, 255, 0.15);
			backdrop-filter: blur(10px);
			border: 1px solid rgba(255, 255, 255, 0.3);
		}
    
		/* FAQ Accordion Customization */
		.accordion-item { background: transparent; border: none; border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
		.accordion-button { background: transparent; color: white; box-shadow: none !important; font-weight: 600; }
		.accordion-button:not(.collapsed) { background: rgba(255, 255, 255, 0.2); color: white; }
		.accordion-button::after { filter: invert(1); }
		.accordion-body { color: #f0f0f0; }

		/* Custom Form Styling */
		.form-control:focus {
			border-color: #E62129;
			box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
		}

		.login-header-bg { background-color: #E62129; }
    
		.login-btn {
			background-color: #E62129;
			color: black;
			font-weight: 600;
			transition: all 0.3s ease;
		}
		.login-btn:hover {
			background-color: #E62129;
			color: white;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(0, 104, 55, 0.3);
		}

		/* Captcha Pattern */
		.captcha-container {
			background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0), 
									linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0);
			background-size: 10px 10px;
			background-position: 0 0, 5px 5px;
			letter-spacing: 5px;
			cursor: pointer;
			font-family: 'Courier New', monospace;
			color: #E62129;
		}
		.captcha-container:hover { transform: scale(1.05); }

		/* Animation */
		.animate-up { animation: fadeInUp 0.6s ease-out; }
		@keyframes fadeInUp {
			from { opacity: 0; transform: translateY(20px); }
			to { opacity: 1; transform: translateY(0); }
		}

		/* Footer Date Box */
		.news-date {
			min-width: 60px;
			height: 60px;
		}
	</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/portal-auth.css?v=3">
</head>
<body class="d-flex flex-column min-vh-100 portal-auth">

	<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm py-2">
		<div class="container">
			<div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
        
				<div class="d-flex flex-column flex-md-row align-items-center text-center text-md-start">
					<div class="logo-img">
						 <img src="assets/images/logo.webp" alt="Xinjiang Medical University" class="img-fluid">
					</div>
          
					<div class="vertical-divider d-none d-md-block mx-3"></div>
          
					<div class="mt-2 mt-md-0">
						<span class="fw-bold fs-5 d-block text-black" data-i18n="login.int_students">International Students</span>
						<span class="fs-6 d-block text-black" data-i18n="login.system_name">Centralized Admissions System (CAS)</span>
					</div>
				</div>

				<div class="mt-3 mt-md-0">
					<div class="dropdown">
						<button class="btn btn-outline-dark rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
				<div class="row align-items-center justify-content-between g-5">
            
						<div class="col-lg-7 col-md-12 text-center text-lg-start">
								 <p class="fs-5 mb-4 lh-base" data-i18n="login.cas_desc">
										 Our Centralized Admission System (CAS) ensures a seamless journey from submission to admission.
								 </p>

								 <div class="card faq-card rounded-4 shadow">
										 <div class="card-body p-4 text-start">
												 <h4 class="card-title fw-bold mb-3 text-white">
														 <i class="bi bi-question-circle me-2"></i><span data-i18n="login.faq_title">Frequently Asked Questions</span>
												 </h4>

												 <div class="accordion" id="faqAccordion">
														 <div class="accordion-item">
																 <h2 class="accordion-header" id="headingOne">
																		 <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
																				 <i class="bi bi-shield-check me-2 text-primary"></i> <span data-i18n="login.faq_q1">Is my personal data secure?</span>
																		 </button>
																 </h2>
																 <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
																		 <div class="accordion-body" data-i18n="login.faq_a1">
																				 Yes. Your personal information and documents are secured with advanced encryption and strict privacy controls within our system.
																		 </div>
																 </div>
														 </div>

														 <div class="accordion-item">
																 <h2 class="accordion-header" id="headingTwo">
																		 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
																				 <i class="bi bi-clock-history me-2 text-primary"></i> <span data-i18n="login.faq_q2">When can I access the portal?</span>
																		 </button>
																 </h2>
																 <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
																		 <div class="accordion-body" data-i18n="login.faq_a2">
																				 You can apply anytime, 24/7, from anywhere in the world. Our system also allows you to track your application status in real-time.
																		 </div>
																 </div>
														 </div>

														 <div class="accordion-item">
																 <h2 class="accordion-header" id="headingThree">
																		 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
																				 <i class="bi bi-headset me-2 text-primary"></i> <span data-i18n="login.faq_q3">Can I get help during the process?</span>
																		 </button>
																 </h2>
																 <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
																		 <div class="accordion-body" data-i18n="login.faq_a3">
																				 Absolutely. We have a dedicated international admissions support team available to provide personalized assistance throughout your application.
																		 </div>
																 </div>
														 </div>

														 <div class="accordion-item">
																 <h2 class="accordion-header" id="headingFour">
																		 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
																				 <i class="bi bi-file-earmark-text me-2 text-primary"></i> <span data-i18n="login.faq_q4">How does the application work?</span>
																		 </button>
																 </h2>
																 <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
																		 <div class="accordion-body" data-i18n="login.faq_a4">
																				 Our streamlined platform allows you to complete your application, upload all required documents, and submit requirements in one intuitive place.
																		 </div>
																 </div>
														 </div>
												 </div>
										 </div>
								 </div>
						</div>
            
						<div class="col-lg-5 col-md-12">
								<div class="d-flex justify-content-center">
										<div class="card border-0 shadow-lg animate-up overflow-hidden w-100" style="max-width: 400px;">
												<div class="card-header login-header-bg text-white text-center p-4">
														<h4 class="mb-0 fw-bold" data-i18n="login.sign_in">Sign In</h4>
														<p class="mb-0 small opacity-75" data-i18n="login.access_portal">Access your student portal</p>
												</div>

												<div class="card-body p-4">
														<form action="login.php" method="POST" onsubmit="return validateCaptcha()">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
																<div class="mb-3">
																		<div class="input-group">
																				<span class="input-group-text bg-light border-end-0"><i class="bi bi-person-fill text-jmu-primary"></i></span>
																				<input type="email" class="form-control border-start-0 ps-0" id="email" name="email" placeholder="Enter your registered email" data-i18n-placeholder="login.email_placeholder" required>
																		</div>
																</div>

																<div class="mb-3">
																		<div class="input-group">
																				<span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-jmu-primary"></i></span>
																				<input type="password" class="form-control border-start-0 border-end-0 ps-0" id="password-input" name="password" placeholder="Enter your password" data-i18n-placeholder="login.password_placeholder" required>
																				<span class="input-group-text bg-light border-start-0 cursor-pointer" onclick="togglePassword()" style="cursor: pointer;">
																						<i class="bi bi-eye-fill text-muted" id="toggle-icon"></i>
																				</span>
																		</div>
																</div>

																<div class="mb-4">
																		<div class="d-flex gap-2">
																				<div class="input-group flex-grow-1">
																						<span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-jmu-primary"></i></span>
																						<input type="text" class="form-control border-start-0 ps-0" id="captcha-input" name="captcha" placeholder="Enter code" data-i18n-placeholder="login.captcha_placeholder" required>
																				</div>
																				<div id="captcha-preview" onclick="generateCaptcha()" title="Click to refresh" data-i18n-title="login.captcha_refresh" class="captcha-container border rounded px-3 d-flex align-items-center justify-content-center fw-bold fs-5"></div>
																		</div>
																</div>

																<button type="submit" class="btn login-btn w-100 mb-3 py-2">
																		<span data-i18n="login.sign_in_btn">Sign In</span> <i class="bi bi-arrow-right ms-1"></i>
																</button>
														</form>

														<div class="d-flex justify-content-between align-items-center mt-4">
																<a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" class="small text-decoration-none text-jmu-primary fw-medium"><i class="bi bi-key me-1"></i> <span data-i18n="login.forgot_password">Forgot Password?</span>
																</a>
														</div>

														<div class="text-center mt-4 pt-3 border-top">
																<p class="small mb-0"><span data-i18n="login.new_to_xjmu">New to XJMU?</span> <a href="register" class="fw-bold text-decoration-none text-jmu-primary" data-i18n="login.create_account_link">Create your account</a></p>
														</div>
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
					<div class="fw-bold pb-2 mb-4 fs-5 text-jmu-primary border-bottom border-success border-opacity-50" data-i18n="login.contact_info_title">Contact Information</div>
					<ul class="list-unstyled small">
						<li class="mb-2"><i class="bi bi-geo-alt-fill me-2 text-jmu-primary"></i> <span data-i18n="login.contact_address">Xinjiang Medical University, Urumqi, Xinjiang, China</span></li>
						<li class="mb-2"><i class="bi bi-telephone-fill me-2 text-jmu-primary"></i> <span data-i18n="login.contact_phone">CN +86 176 4041 2827 / PK +92 300 400 9526</span> </li>
						<li class="mb-2"><i class="bi bi-envelope-fill me-2 text-jmu-primary"></i> studyatxjmu@gmail.com</li>
						<li><i class="bi bi-clock-fill me-2 text-jmu-primary"></i> <span data-i18n="login.contact_hours">Mon-Fri: 8:30 AM - 5:00 PM (CST)</span></li>
					</ul>
				</div>
			</div>

			<div class="text-center small py-4 mt-4 bg-light rounded">
				<p class="mb-0"><span data-i18n="login.copyright">© Copyright 2027 Xinjiang Medical University All Rights Reserved</span></p>
				<p class="mt-2 mb-0 text-muted" data-i18n="login.copyright_sub">Designed for International Students Admissions</p>
			</div>
		</div>
	</footer>

	<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header text-white" style="background: linear-gradient(135deg, #E62129 0%, #E62129 100%);">
					<h5 class="modal-title fw-bold"><i class="bi bi-key me-2"></i> <span data-i18n="login.reset_title">Reset Password</span></h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<p class="small mb-3 text-muted" data-i18n="login.reset_desc">Enter your registered email address below. We will send you a link to reset your password.</p>
					<form id="forgotPasswordForm" onsubmit="handleForgotSubmit(event)">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="mb-3">
							<label class="form-label small fw-bold" data-i18n="login.email_label">Email Address</label>
							<div class="input-group">
								<span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
								<input type="email" class="form-control" name="email" placeholder="Enter your registered email" data-i18n-placeholder="login.email_placeholder" required>
							</div>
						</div>
						<button type="submit" id="fp-btn" class="btn btn-primary w-100" data-i18n="login.send_reset_link">Send Reset Link</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		// Password toggle function
		function togglePassword() {
			const passwordInput = document.getElementById('password-input');
			const toggleIcon = document.getElementById('toggle-icon');

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

		// Captcha generation and validation
		function generateCaptcha() {
			const chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			let captcha = "";
			for (let i = 0; i < 4; i++) {
				captcha += chars[Math.floor(Math.random() * chars.length)];
			}
			document.getElementById('captcha-preview').textContent = captcha;
		}
    
		window.onload = generateCaptcha;
    
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

		// Forgot password form handling
				function handleForgotSubmit(e) {
					e.preventDefault();

					const form = e.target;
					const btn = document.getElementById('fp-btn');
					const originalText = btn.innerText;

					btn.innerText = "Sending...";
					btn.disabled = true;

					fetch('forgot_password.php', {
						method: 'POST',
						body: new FormData(form)
					})
					.then(res => res.json())
					.then(data => {
						alert(data.message);

						btn.innerText = originalText;
						btn.disabled = false;

						if (data.success) {
							const modal = bootstrap.Modal.getInstance(
								document.getElementById('forgotPasswordModal')
							);
							modal.hide();
							form.reset();
						}
					})
					.catch(err => {
						alert("Something went wrong. Please try again.");
						btn.innerText = originalText;
						btn.disabled = false;
					});
				}
	</script>
  
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="js/i18n.js"></script>
</body>
</html>
