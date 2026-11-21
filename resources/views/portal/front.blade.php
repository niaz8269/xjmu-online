<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <meta name="xjmu-base" content="https://xjmu.online">
  <title>Application Status | Xinjiang Medical University</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/portal.css?v=1">
  <link rel="icon" href="assets/images/logo2.webp" type="image/x-icon">
  <link rel="shortcut icon" href="assets/images/logo2.webp" type="image/x-icon">
</head>
<body class="portal">

@include('portal._bar', ['active' => 'dashboard'])

  <main class="p-wrap">
    <div class="p-hello">
      <h1>Your application</h1>
      <p>{{ \App\Support\Cms::portal('dashboard_intro') }}</p>
      @if (\App\Support\Cms::portal('notice') !== '')
        <div class="alert alert-warning mt-3 mb-0" role="status">{{ \App\Support\Cms::portal('notice') }}</div>
      @endif
    </div>

    <div class="p-grid">
      <div>
        <section class="p-card" aria-labelledby="statusHeading">
          <span class="p-eyebrow" id="statusHeading" data-i18n="portal.app_status_heading">Application Status</span>
          <div class="p-status">
            <div class="p-status__v" id="applicationStatus">Unsubmitted</div>
            <span class="p-pill" id="statusPill">In progress</span>
          </div>

          <ol class="p-steps" id="statusSteps" aria-label="Application progress">
            <li data-n="1" class="is-now">Fill in the form</li>
            <li data-n="2">Submitted</li>
            <li data-n="3">Under review</li>
            <li data-n="4">Decision</li>
          </ol>

          <div class="p-remark" id="statusRemark" hidden></div>

          <div class="p-actions">
            <button id="editDetailsBtn" class="p-btn p-btn--primary">
              Click Here to Start Application
            </button>
            <button id="downloadFormBtn" class="p-btn p-btn--ghost" disabled data-i18n="portal.download_form_btn">
              Download My Application Form
            </button>
            <button id="downloadZipBtn" class="p-btn p-btn--ghost" hidden>
              <i class="bi bi-file-earmark-zip"></i> Download form and documents (ZIP)
            </button>
          </div>

          <p class="p-note" id="downloadReadyNote" hidden>{{ \App\Support\Cms::portal('download_note') }}</p>
          <p class="p-note" id="downloadLockedNote" data-i18n="portal.download_note">
            The application form can only be downloaded after the submission of all applications. If the account contains unsubmitted applications, the applicant will not be able to download the application form.
          </p>
        </section>

        <section class="p-card">
          <h2>What happens next</h2>
          <ul class="p-list">
            <li><i class="bi bi-1-circle-fill"></i><div>Complete the four sections of the form<small>Programme, personal details, education and documents. You can save each section and come back later.</small></div></li>
            <li><i class="bi bi-2-circle-fill"></i><div>Submit your application<small>After you submit, the admissions office reviews your details and documents.</small></div></li>
            <li><i class="bi bi-3-circle-fill"></i><div>Check this page for your result<small>Your status and any remark from the admissions office appear here.</small></div></li>
          </ul>
        </section>
      </div>

      <aside>
        <section class="p-card">
          <h2>Documents to prepare</h2>
          <ul class="p-list">
            <li><i class="bi bi-person-badge"></i><div>ID photo<small>White background</small></div><span class="p-tag is-req">Required</span></li>
            <li><i class="bi bi-passport"></i><div>Passport home page</div><span class="p-tag is-req">Required</span></li>
            <li><i class="bi bi-mortarboard"></i><div>Certificates and transcripts<small>Highest education</small></div><span class="p-tag is-req">Required</span></li>
            <li><i class="bi bi-file-text"></i><div>Study plan</div><span class="p-tag is-req">Required</span></li>
            <li><i class="bi bi-heart-pulse"></i><div>Physical examination record</div><span class="p-tag is-req">Required</span></li>
            <li><i class="bi bi-shield-check"></i><div>Non-criminal record report</div><span class="p-tag is-req">Required</span></li>
            <li><i class="bi bi-camera-video"></i><div>Self-introduction video</div><span class="p-tag">Optional</span></li>
          </ul>
          <p class="p-note">{{ \App\Support\Cms::portal('file_hint') }}</p>
        </section>

        <section class="p-card">
          <h2 data-i18n="login.contact_info_title">Contact Information</h2>
          <ul class="p-list">
            <li><i class="bi bi-geo-alt-fill"></i><div>Xinjiang Medical University, Urumqi, Xinjiang, China</div></li>
            <li><i class="bi bi-telephone-fill"></i><div>CN {{ \App\Support\Settings::get('contact_phone2', '+86 176 4041 2827') }}<small>PK {{ \App\Support\Settings::get('contact_phone_pk', '+92 300 400 9526') }}</small></div></li>
            <li><i class="bi bi-envelope-fill"></i><div>{{ \App\Support\Settings::get('contact_email', 'studyatxjmu@gmail.com') }}</div></li>
            <li><i class="bi bi-clock-fill"></i><div>Mon-Fri: 8:30 AM - 5:00 PM (CST)</div></li>
          </ul>
        </section>
      </aside>
    </div>
  </main>

  <footer class="p-foot">
    <span>{{ \App\Support\Cms::portal('copyright') }}</span>
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

          // textContent only, so database values can't inject HTML.
          statusText.textContent = statusDisplay;

          // Progress steps: 1 form, 2 submitted, 3 under review, 4 decision.
          const decided = /approv|accept|admit|reject|declin/.test(normalizedStatus);
          const rejected = /reject|declin/.test(normalizedStatus);
          let step = 1;
          if (data.is_submitted) { step = statusDisplay === 'Submitted' ? 2 : (decided ? 4 : 3); }
          document.querySelectorAll('#statusSteps li').forEach(function (li, i) {
            li.classList.remove('is-now', 'is-done');
            if (i + 1 < step || (step === 4 && i === 3 && !rejected)) { li.classList.add('is-done'); }
            else if (i + 1 === step) { li.classList.add('is-now'); }
          });
          const pill = document.getElementById('statusPill');
          pill.className = 'p-pill' + (rejected ? ' is-bad' : (step === 4 ? ' is-ok' : ''));
          pill.textContent = step === 1 ? 'In progress' : (step === 2 ? 'Received' : (step === 3 ? 'Being reviewed' : (rejected ? 'Not successful' : 'Decision made')));

          const remark = document.getElementById('statusRemark');
          if (data.is_submitted && data.admin_remark) {
            remark.textContent = '';
            const label = document.createElement('strong');
            label.textContent = "Remark: ";
            remark.appendChild(label);
            remark.appendChild(document.createTextNode(data.admin_remark));
            remark.hidden = false;
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
            // Same-tab navigation: the server answers with a file download, so the page stays open and
            // phone browsers do not block it as a pop-up.
            downloadBtn.disabled = false;
            downloadBtn.classList.remove('p-btn--ghost');
            downloadBtn.classList.add('p-btn--primary');
            downloadBtn.removeAttribute('data-i18n');
            downloadBtn.innerHTML = '<i class="bi bi-file-earmark-pdf"></i> Download my application (PDF)';
            downloadBtn.addEventListener("click", function () {
              window.location.href = "download_application?format=pdf";
            });
            const zipBtn = document.getElementById("downloadZipBtn");
            zipBtn.hidden = false;
            zipBtn.addEventListener("click", function () {
              window.location.href = "download_application";
            });
            editBtn.classList.remove('p-btn--primary');
            editBtn.classList.add('p-btn--ghost');
            document.getElementById('downloadReadyNote').hidden = false;
            document.getElementById('downloadLockedNote').hidden = true;
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
