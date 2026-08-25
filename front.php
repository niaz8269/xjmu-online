<?php
require 'session_config.php';
require 'db.php';
$page_noindex = true; // Private — keep out of search index

if (!isset($_SESSION['user_id'])) {
  if (ob_get_level()) ob_clean();
  header('Location: login');
  exit;
}

$full_name = $_SESSION['full_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <meta name="xjmu-base" content="https://xjmu.online">
  <title>Application Status | Xinjiang Medical University</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="icon" href="assets/images/logo2.webp" type="image/x-icon">
  <link rel="shortcut icon" href="assets/images/logo2.webp" type="image/x-icon">
  
  <style>
    :root {
      --jmu-primary: #E62129; /* Dark green */
      --jmu-secondary: #E62129; /* Red */
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }

    /* Navbar Customization */
    .navbar {
      background: white; /* Changed to white for black text contrast */
      border-bottom: 4px solid var(--jmu-primary);
      min-height: 100px;
    }

    /* Main Background Section */
    .hero-section {
      background-image:url('assets/images/main-image2.jpg');
      background-size: cover;
      background-position: center;
      min-height: calc(100vh - 100px);
    }

    /* Footer Branding */
    .news-date {
      background-color: var(--jmu-primary);
      min-width: 60px;
      height: 60px;
    }

    .hover-blue:hover {
      background-color: #0d6efd !important; 
      border-color: #0d6efd !important;
      color: white !important;
    }

    /* Button Override for specific brand red */
    .btn-brand-red {
        background-color: var(--jmu-secondary);
        color: white;
        border: none;
    }
    .btn-brand-red:hover {
        background-color: #c41c23;
        color: white;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

  <nav class="navbar navbar-expand-lg sticky-top py-0">
    <div class="container-fluid px-4">
      <a class="navbar-brand d-flex flex-column flex-md-row align-items-center mx-auto mx-md-0" href="index" target="_blank" rel="noopener noreferrer">

  <img
    src="assets/images/logo.webp"
    alt="  Xinjiang Medical University"
    width="230"
    class="mb-2 mb-md-0 mx-auto mx-md-0"
  >

  <div class="vr d-none d-md-block ms-3 me-3 opacity-100 bg-dark"
       style="height: 60px; width: 2px;"></div>

  <div class="text-center text-md-start lh-sm text-black">
    <span class="fw-bold fs-5 d-block">International Students</span>
    <span class="fw-bold fs-6 d-block">Centralized Admissions System (CAS)</span>
  </div>

</a>
      <div class="d-flex align-items-center ms-auto">
        <div class="dropdown me-4 mt-3 mt-lg-4">
          <button class="btn rounded-0 px-2 hover-blue dropdown-toggle" type="button" data-bs-toggle="dropdown">
             <i class="bi bi-globe2 me-1"></i> <span data-lang-toggle>English</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end rounded-0">
            <li><a class="dropdown-item active" href="#" data-lang-item="en">English</a></li>
            <li><a class="dropdown-item" href="#" data-lang-item="zh">中文 (Chinese)</a></li>
            <li><a class="dropdown-item" href="#" data-lang-item="fr">Français</a></li>
          </ul>
        </div>

        <div class="d-flex flex-column text-end lh-sm">
           <div class="mb-1 text-black">
               Welcome, <strong><?= htmlspecialchars($full_name) ?></strong>!
           </div>
           <div>
              <a href="index" class="btn btn-sm btn-outline-dark hover-blue rounded-0 me-1" target="_blank" rel="noopener noreferrer" data-i18n="portal.home_btn">Home</a>
              <a href="profile" class="btn btn-sm btn-outline-dark hover-blue rounded-0 me-1" target="_blank" rel="noopener noreferrer" data-i18n="portal.profile_btn">My Profile</a>
              <a href="logout" class="btn btn-sm btn-outline-dark hover-blue rounded-0" target="_blank" rel="noopener noreferrer" data-i18n="portal.logout_btn">Logout</a>
           </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="hero-section d-flex align-items-center py-5">
     <div class="container">
        <div class="row justify-content-end">
           <div class="col-md-8 col-lg-5">
              
              <div class="card border-0 shadow-lg rounded-0 p-4 bg-white">
                 <div class="text-center mb-4">
                    <h4 class="fw-bold" style="color: var(--jmu-secondary);" data-i18n="portal.app_status_heading">Application Status</h4>
                    <div class="fs-4 mt-2 text-dark" id="applicationStatus">Unsubmitted</div>
                 </div>

                 <hr class="text-secondary opacity-25 my-3">

                 <div class="d-grid gap-2">
                    <button id="editDetailsBtn" class="btn btn-brand-red rounded-0 py-2 fs-6">
                      Click Here to Start Application
                    </button>

                    <button id="downloadFormBtn" class="btn btn-secondary rounded-0 py-2 fs-6" disabled data-i18n="portal.download_form_btn">
                       Download My Application Form
                    </button>
                 </div>

                 <p class="text-muted small text-center mt-3 mb-0" data-i18n="portal.download_note">
                    The application form can only be downloaded after the submission of all applications. If the account contains unsubmitted applications, the applicant will not be able to download the application form.
                 </p>
              </div>

           </div>
        </div>
     </div>
  </div>

  <footer class="pt-5 bg-white border-top border-4" style="border-color: var(--jmu-primary) !important;">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mb-4">
          <div class="fw-bold pb-2 mb-4 fs-5 text-black border-bottom border-danger" data-i18n="login.news_title">News and Updates</div>
          
          <div class="d-flex align-items-start mb-3">
            <div class="news-date me-3 text-white rounded d-flex flex-column justify-content-center align-items-center">
              <span class="fw-bold small">February. 10</span>
              <span style="font-size: 0.8rem;">2026</span>
            </div>
            <div class="pt-1 lh-base text-black">
              <strong>Welcome to Xinjiang Medical University International Students CAS.</strong>
              <p class="small text-secondary mt-1 mb-0">Our new platform streamlines the application process with enhanced security.</p>
            </div>
          </div>
          
          <div class="d-flex align-items-start mb-3">
            <div class="news-date me-3 text-white rounded d-flex flex-column justify-content-center align-items-center">
              <span class="fw-bold small">February. 05</span>
              <span style="font-size: 0.8rem;">2026</span>
            </div>
            <div class="pt-1 lh-base text-black">
              <strong>2026 Intake Now Open</strong>
              <p class="small text-secondary mt-1 mb-0">Applications for the 2026 academic year are now being accepted. Early applications encouraged.</p>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4 mb-4">
          <div class="fw-bold pb-2 mb-4 fs-5 text-black border-bottom border-danger" data-i18n="login.contact_info_title">Contact Information</div>
          <ul class="list-unstyled small text-black">
            <li class="mb-3"><i class="bi bi-geo-alt-fill me-2" style="color: var(--jmu-primary);"></i>   Xinjiang Medical University,   Xinjiang City, China</li>
            <li class="mb-3"><i class="bi bi-telephone-fill me-2" style="color: var(--jmu-primary);"></i> CN +86 156 1750 0775 / PK +92 300 400 9526 </li>
            <li class="mb-3"><i class="bi bi-envelope-fill me-2" style="color: var(--jmu-primary);"></i> studyatxjmu@gmail.com</li>
            <li><i class="bi bi-clock-fill me-2" style="color: var(--jmu-primary);"></i> Mon-Fri: 8:30 AM - 5:00 PM (CST)</li>
          </ul>
        </div>
      </div>

      <div class="text-center small py-4 mt-4 bg-light text-muted">
        <p class="mb-0" data-i18n="login.copyright">© Copyright 2026 Xinjiang Medical University All Rights Reserved</p>
        <p class="mt-2 mb-0" data-i18n="login.copyright_sub">Designed for International Students Admissions</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      fetch("get_application_status.php")
        .then(res => res.json())
        .then(data => {
          const statusText = document.getElementById("applicationStatus");
          const rawStatus = (data.status || '').toString().trim();
          let statusDisplay = 'Unsubmitted';
          const normalizedStatus = rawStatus.toLowerCase();

          if (!data.is_submitted) {
            statusDisplay = 'Unsubmitted';
          } else if (normalizedStatus === '' || normalizedStatus === 'draft' || normalizedStatus === 'pending' || normalizedStatus === 'submitted') {
            statusDisplay = 'Submitted';
          } else if (rawStatus !== '') {
            statusDisplay = rawStatus;
          } else {
            statusDisplay = 'Submitted';
          }

          // Build with DOM APIs / textContent so DB-sourced values can't inject HTML (audit #30).
          statusText.textContent = '';
          const statusStrong = document.createElement('strong');
          statusStrong.textContent = statusDisplay;
          statusText.appendChild(statusStrong);

          if (statusDisplay === "Rejected" && data.admin_remark) {
            statusText.appendChild(document.createElement('br'));
            const remarkSpan = document.createElement('span');
            remarkSpan.className = "text-danger mt-2 d-block small";
            const remarkLabel = document.createElement('strong');
            remarkLabel.textContent = "Remark: ";
            remarkSpan.appendChild(remarkLabel);
            remarkSpan.appendChild(document.createTextNode(data.admin_remark));
            statusText.appendChild(remarkSpan);
          }

          const editBtn = document.getElementById("editDetailsBtn");

          if (!data.has_any_data) {
            editBtn.textContent = "Click Here to Start Application";
          } else if (!data.is_submitted) {
            editBtn.textContent = "Click Here to Edit Application";
          } else {
            editBtn.textContent = "Click Here to View Application";
          }
          editBtn.disabled = false;

          editBtn.addEventListener("click", function () {
            window.location.href = "edit-details";
          });

          const downloadBtn = document.getElementById("downloadFormBtn");
          if (data.is_submitted) {
            downloadBtn.disabled = false;
            downloadBtn.addEventListener("click", function () {
              window.open("download_application.php", "_blank");
            });
          }
        })
        .catch(err => {
          console.error("Error loading application status:", err);
        });
    });
  </script>

  <script src="js/i18n.js"></script>
</body>
</html>


