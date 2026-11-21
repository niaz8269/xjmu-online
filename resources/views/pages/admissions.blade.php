@extends('layouts.site')

@section('content')

<!-- ===================== HERO ===================== -->
<section class="adm-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Admissions</span>
        <h1 data-i18n="admissions.page_title">Admissions at Xinjiang Medical University</h1>
        <p>Requirements, application process, key dates and tuition for international students applying to XJMU.</p>
    </div>
</section>

<div class="container">
    <nav class="adm-crumb" aria-label="breadcrumb">
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>Admissions</span>
    </nav>
</div>

<main class="ds-section" style="padding-top:2rem">
    <div class="container">
        @if ($admissionsSections->isNotEmpty())
            @foreach ($admissionsSections as $sec)
                <section class="mb-4" id="{{ $sec->section_key }}">
                    @if (!empty($sec->heading))<h2 class="ds-title">{{ $sec->heading }}</h2>@endif
                    <div class="page-section-body">{!! $sec->body !!}</div>
                </section>
            @endforeach
        @endif

        <!-- Quick Stats Banner -->
        <div class="quick-stats-banner mb-5">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card text-center">
                        <div class="stat-number">2,000+</div>
                        <div class="stat-label" data-i18n="admissions.stat1_label">International Students</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card text-center">
                        <div class="stat-number">100+</div>
                        <div class="stat-label" data-i18n="admissions.stat2_label">Countries Represented</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card text-center">
                        <div class="stat-number">98%</div>
                        <div class="stat-label" data-i18n="admissions.stat3_label">Acceptance Rate</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card text-center">
                        <div class="stat-number">50+</div>
                        <div class="stat-label" data-i18n="admissions.stat4_label">Scholarship Programs</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion admissions-accordion" id="admissionsAccordion">
            <!-- Admissions Overview -->
            <div class="accordion-item" id="admissions-overview">
                <h2 class="accordion-header" id="headingOverview">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOverview" aria-expanded="true" aria-controls="collapseOverview"><span data-i18n="admissions.overview_btn">Admissions Overview</span></button>
                </h2>
                <div id="collapseOverview" class="accordion-collapse collapse show" aria-labelledby="headingOverview" data-bs-parent="#admissionsAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 admissions-section-text">
                                <p>
                                    Xinjiang Medical University welcomes students from all over the world to pursue excellence in medical education. 
                                    Our admissions process is designed to identify talented individuals who demonstrate academic potential, 
                                    commitment to healthcare, and cultural adaptability.
                                </p>
                                <div class="admissions-highlights">
                                    <h6><i class="fas fa-check-circle text-success me-2"></i><span data-i18n="admissions.key_features">Key Features:</span></h6>
                                    <ul>
                                        <li>Streamlined online application system</li>
                                        <li>Dedicated international student support team</li>
                                        <li>Multiple intake periods throughout the year</li>
                                        <li>Comprehensive pre-arrival guidance</li>
                                        <li>Visa and documentation assistance</li>
                                    </ul>
                                </div>
                                <div class="application-types mt-4">
                                    <h6><i class="fas fa-file-alt text-primary me-2"></i><span data-i18n="admissions.app_types">Application Types:</span></h6>
                                    <div class="row g-3 mt-2">
                                        <div class="col-md-4">
                                            <div class="type-card">
                                                <i class="fas fa-user-graduate type-icon"></i>
                                                <h6 data-i18n="admissions.undergraduate">Undergraduate</h6>
                                                <small data-i18n="admissions.bachelors">Bachelor's Programs</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="type-card">
                                                <i class="fas fa-user-md type-icon"></i>
                                                <h6 data-i18n="admissions.postgraduate">Postgraduate</h6>
                                                <small data-i18n="admissions.masters">Master's Programs</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="type-card">
                                                <i class="fas fa-flask type-icon"></i>
                                                <h6 data-i18n="admissions.doctoral">Doctoral</h6>
                                                <small data-i18n="admissions.phd">PhD Programs</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 admissions-media">
                                <div class="admission-card card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h5 class="card-title"><i class="fas fa-headset me-2 text-primary"></i><span data-i18n="admissions.admissions_contact">Admissions Office Contact</span></h5>
                                        <div class="contact-info mt-3">
                                            <p><i class="fas fa-phone me-2"></i> <strong>Phone:</strong> {{ \App\Support\Settings::get('contact_phone2', '+86 176 4041 2827') }} / {{ \App\Support\Settings::get('contact_phone_pk', '+92 300 400 9526') }}  </p>
                                            <p><i class="fas fa-envelope me-2"></i> <strong>Email:</strong> studyatxjmu@gmail.com</p>
                                            <p><i class="fas fa-map-marker-alt me-2"></i> <strong>Address:</strong> 393 Xinyi Road, Urumqi, Xinjiang, China</p>
                                        </div>
                                        <a href="contact" class="btn btn-primary btn-sm mt-3 w-100" target="_blank" rel="noopener noreferrer"><i class="fas fa-paper-plane me-2"></i><span data-i18n="admissions.contact_btn">Contact Admissions</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="ratio ratio-16x9 mb-3">
                                    <iframe src="{{ $videos['overview'] }}" title="XJMU Admissions Process Video" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admission Requirements -->
            <div class="accordion-item" id="requirements">
                <h2 class="accordion-header" id="headingRequirements">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRequirements" aria-expanded="false" aria-controls="collapseRequirements"><span data-i18n="admissions.requirements_btn">Admission Requirements</span></button>
                </h2>
                <div id="collapseRequirements" class="accordion-collapse collapse" aria-labelledby="headingRequirements" data-bs-parent="#admissionsAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 admissions-section-text">
                                <div class="requirements-grid">
                                    <!-- Undergraduate Requirements -->
                                    <div class="requirement-card">
                                        <h5><i class="fas fa-graduation-cap text-primary me-2"></i>Undergraduate Programs</h5>
                                            <ul class="mt-3">
                                                <li>High school diploma or equivalent with minimum 60% average</li>
                                                <li>Science background (Biology, Chemistry, Physics) for medical programs</li>
                                                <li>Chinese proficiency: HSK 4+ (for Chinese medium)</li>
                                                <li>Passport copy and passport-sized photos</li>
                                                <li>Medical examination certificate</li>
                                            </ul>
                                    </div>

                                    <!-- Postgraduate Requirements -->
                                    <div class="requirement-card mt-4">
                                        <h5><i class="fas fa-user-md text-success me-2"></i>Postgraduate Programs</h5>
                                            <ul class="mt-3">
                                                <li>Bachelor's degree in relevant field with minimum 75% average</li>
                                                <li>Two letters of recommendation from professors</li>
                                                <li>Statement of purpose (500-1000 words)</li>
                                                <li>Research proposal (for research-based programs)</li>
                                                <li>Relevant work experience (preferred for some programs)</li>
                                            </ul>
                                    </div>

                                    <!-- Doctoral Requirements -->
                                    <div class="requirement-card mt-4">
                                        <h5><i class="fas fa-flask text-info me-2"></i>Doctoral Programs</h5>
                                            <ul class="mt-3">
                                                <li>Master's degree in relevant field with excellent academic record</li>
                                                <li>Detailed research proposal</li>
                                                <li>Three letters of recommendation</li>
                                                <li>Proof of research experience and publications</li>
                                                <li>Interview with prospective supervisor</li>
                                            </ul>
                                    </div>
                                </div>

                                <div class="note-box mt-4">
                                    <h6><i class="fas fa-exclamation-circle text-warning me-2"></i>Important Notes:</h6>
                                    <ul class="mb-0">
                                        <li>All documents must be notarized and translated into English or Chinese</li>
                                        <li>Applications without required documents will not be processed</li>
                                        <li>Additional requirements may apply for specific programs</li>
                                        <li>Early application is recommended for scholarship consideration</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-5 admissions-media">
                                <div class="admission-card card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h5 class="card-title"><i class="fas fa-file-download me-2 text-primary"></i>Download Application Forms</h5>
                                            <div class="documents-list mt-3">
                                                <a href="downloads" class="document-item" target="_blank" rel="noopener noreferrer">
                                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                                    <span>Undergraduate Application Form</span>
                                                    <i class="fas fa-download ms-auto"></i>
                                                </a>
                                                <a href="downloads" class="document-item" target="_blank" rel="noopener noreferrer">
                                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                                    <span>Postgraduate Application Form</span>
                                                    <i class="fas fa-download ms-auto"></i>
                                                </a>
                                                <a href="downloads" class="document-item" target="_blank" rel="noopener noreferrer">
                                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                                    <span>Doctoral Application Form</span>
                                                    <i class="fas fa-download ms-auto"></i>
                                                </a>
                                                <a href="downloads" class="document-item" target="_blank" rel="noopener noreferrer">
                                                    <i class="fas fa-file-word text-primary me-2"></i>
                                                    <span>Medical Examination Form</span>
                                                    <i class="fas fa-download ms-auto"></i>
                                                </a>
                                            </div>
                                    </div>
                                </div>
                                <div class="ratio ratio-16x9 mb-3">
                                    <iframe src="{{ $videos['requirements'] }}" title="XJMU Admission Requirements Video" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Process -->
            <div class="accordion-item" id="process">
                <h2 class="accordion-header" id="headingProcess">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProcess" aria-expanded="false" aria-controls="collapseProcess">
                        Application Process
                    </button>
                </h2>
                <div id="collapseProcess" class="accordion-collapse collapse" aria-labelledby="headingProcess" data-bs-parent="#admissionsAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 admissions-section-text">
                                <div class="process-steps">
                                    <!-- Step 1 -->
                                    <div class="process-step">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <h6>Prepare Documents</h6>
                                            <p>Gather all required documents including academic transcripts, passport copy, language certificates, and personal statement.</p>
                                        </div>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="process-step">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <h6>Online Application</h6>
                                            <p>Complete the online application form on the university portal and upload all required documents.</p>
                                        </div>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="process-step">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <h6>Application Fee Payment</h6>
                                            <p>Pay the non-refundable application fee (1000¥) through approved payment methods.</p>
                                        </div>
                                    </div>

                                    <!-- Step 4 -->
                                    <div class="process-step">
                                        <div class="step-number">4</div>
                                        <div class="step-content">
                                            <h6>Application Review</h6>
                                            <p>The admissions committee reviews your application. This process takes 2-4 weeks.</p>
                                        </div>
                                    </div>

                                    <!-- Step 5 -->
                                    <div class="process-step">
                                        <div class="step-number">5</div>
                                        <div class="step-content">
                                            <h6>Interview (if required)</h6>
                                            <p>Some programs may require an online interview with faculty members.</p>
                                        </div>
                                    </div>

                                    <!-- Step 6 -->
                                    <div class="process-step">
                                        <div class="step-number">6</div>
                                        <div class="step-content">
                                            <h6>Receive Admission Letter</h6>
                                            <p>If accepted, you will receive an official admission letter and JW202 form for visa application.</p>
                                        </div>
                                    </div>

                                    <!-- Step 7 -->
                                    <div class="process-step">
                                        <div class="step-number">7</div>
                                        <div class="step-content">
                                            <h6>Apply for Student Visa</h6>
                                            <p>Use the admission documents to apply for an X1/X2 student visa at the Chinese embassy.</p>
                                        </div>
                                    </div>

                                    <!-- Step 8 -->
                                    <div class="process-step">
                                        <div class="step-number">8</div>
                                        <div class="step-content">
                                            <h6>Arrival & Registration</h6>
                                            <p>Arrive at the university before the registration deadline and complete the enrollment process.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 admissions-media">
                                <div class="admission-card card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h5 class="card-title"><i class="fas fa-laptop me-2 text-primary"></i>Online Application Portal</h5>
                                            <p class="card-text">Apply through our secure online application system:</p>
                                            <a href="login" target="_blank" rel="noopener noreferrer" class="btn btn-success w-100 mb-3">
                                                <i class="fas fa-external-link-alt me-2"></i>Go to Application Portal
                                            </a>
                                            <div class="portal-features">
                                                <h6>Portal Features:</h6>
                                                <ul class="mb-0">
                                                    <li>Secure document upload</li>
                                                    <li>Real-time application tracking</li>
                                                    <li>Direct communication with admissions</li>
                                                    <li>Online fee payment</li>
                                                    <li>Status updates and notifications</li>
                                                </ul>
                                            </div>
                                    </div>
                                </div>
                                <div class="ratio ratio-16x9 mb-3">
                                    <iframe src="{{ $videos['process'] }}" title="XJMU Application Process Video" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Dates -->
            <div class="accordion-item" id="dates">
                <h2 class="accordion-header" id="headingDates">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDates" aria-expanded="false" aria-controls="collapseDates">
                        Important Dates & Deadlines
                    </button>
                </h2>
                <div id="collapseDates" class="accordion-collapse collapse" aria-labelledby="headingDates" data-bs-parent="#admissionsAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 admissions-section-text">
                                <div class="dates-calendar">
                                    <!-- March/Spring Intake -->
                                    <div class="intake-period mb-4">
                                        <h5 class="intake-title"><i class="fas fa-seedling text-success me-2"></i>March/Spring Intake (February/March)</h5>
                                            <div class="timeline">
                                                <div class="timeline-item">
                                                    <div class="timeline-date">September 1</div>
                                                    <div class="timeline-content">
                                                        <h6>Application Opens</h6>
                                                        <p>Online application portal opens for March/Spring intake</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item">
                                                    <div class="timeline-date">December 15</div>
                                                    <div class="timeline-content">
                                                        <h6>Early Application Deadline</h6>
                                                        <p>Recommended deadline for scholarship consideration</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item">
                                                    <div class="timeline-date">January 15</div>
                                                    <div class="timeline-content">
                                                        <h6>Regular Application Deadline</h6>
                                                        <p>Final deadline for March/Spring intake applications</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item">
                                                    <div class="timeline-date">February 20</div>
                                                    <div class="timeline-content">
                                                        <h6>Registration & Orientation</h6>
                                                        <p>New student registration and orientation week</p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    <!-- September/Fall Intake -->
                                    <div class="intake-period">
                                        <h5 class="intake-title"><i class="fas fa-leaf text-warning me-2"></i>September/Fall Intake (September/October)</h5>
                                            <div class="timeline">
                                                <div class="timeline-item">
                                                    <div class="timeline-date">March 1</div>
                                                    <div class="timeline-content">
                                                        <h6>Application Opens</h6>
                                                        <p>Online application portal opens for September/Fall intake</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item">
                                                    <div class="timeline-date">June 30</div>
                                                    <div class="timeline-content">
                                                        <h6>Early Application Deadline</h6>
                                                        <p>Recommended deadline for scholarship consideration</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item">
                                                    <div class="timeline-date">August 15</div>
                                                    <div class="timeline-content">
                                                        <h6>Regular Application Deadline</h6>
                                                        <p>Final deadline for September/Fall intake applications</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-item">
                                                    <div class="timeline-date">September 10</div>
                                                    <div class="timeline-content">
                                                        <h6>Registration & Orientation</h6>
                                                        <p>New student registration and orientation week</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info mt-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Note:</strong> Dates may vary for different programs. Please check specific program pages for exact deadlines.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 admissions-media">
                                    <div class="admission-card card border-0 shadow-sm mb-4">
                                        <div class="card-body p-4">
                                            <h5 class="card-title"><i class="fas fa-calendar-alt me-2 text-primary"></i>Upcoming Events</h5>
                                            <div class="events-list mt-3">
                                                <div class="event-item">
                                                    <div class="event-date">
                                                        <span class="event-day">15</span>
                                                        <span class="event-month">Mar</span>
                                                    </div>
                                                    <div class="event-details">
                                                        <h6>Virtual Open Day</h6>
                                                        <p>Online campus tour and Q&A session</p>
                                                    </div>
                                                </div>
                                                <div class="event-item">
                                                    <div class="event-date">
                                                        <span class="event-day">30</span>
                                                        <span class="event-month">Apr</span>
                                                    </div>
                                                    <div class="event-details">
                                                        <h6>Scholarship Deadline</h6>
                                                        <p>Last day to apply for CSC scholarships</p>
                                                    </div>
                                                </div>
                                                <div class="event-item">
                                                    <div class="event-date">
                                                        <span class="event-day">10</span>
                                                        <span class="event-month">Jun</span>
                                                    </div>
                                                    <div class="event-details">
                                                        <h6>Pre-departure Briefing</h6>
                                                        <p>Online session for admitted students</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ratio ratio-16x9 mb-3">
                                        <iframe src="{{ $videos['dates'] }}" title="XJMU Important Dates Video" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tuition & Fees -->
            <div class="accordion-item" id="tuition">
                <h2 class="accordion-header" id="headingTuition">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTuition" aria-expanded="false" aria-controls="collapseTuition">
                        Tuition & Fees
                    </button>
                </h2>
                <div id="collapseTuition" class="accordion-collapse collapse" aria-labelledby="headingTuition" data-bs-parent="#admissionsAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 admissions-section-text">
                                <div class="tuition-tables">
                                    <!-- Undergraduate Fees -->
                                    <div class="fee-table-section mb-4">
                                        <h5><i class="fas fa-graduation-cap text-primary me-2"></i>Undergraduate Programs (per year)</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered tuition-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Program</th>
                                                            <th>Tuition Fee (¥)</th>
                                                            <th>Accommodation (¥)</th>
                                                            <th>Insurance (¥)</th>
                                                            <th>Total (¥)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>MBBS (English)</td>
                                                            <td>30,000</td>
                                                            <td>4,500</td>
                                                            <td>300</td>
                                                            <td>34,800</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Traditional Chinese Medicine</td>
                                                            <td>25,000</td>
                                                            <td>4,000</td>
                                                            <td>300</td>
                                                            <td>29,300</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Stomatology</td>
                                                            <td>28,000</td>
                                                            <td>4,000</td>
                                                            <td>300</td>
                                                            <td>32,300</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Pharmacy</td>
                                                            <td>22,000</td>
                                                            <td>4,000</td>
                                                            <td>300</td>
                                                            <td>26,300</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>

                                    <!-- Postgraduate Fees -->
                                    <div class="fee-table-section mb-4">
                                        <h5><i class="fas fa-user-md text-success me-2"></i>Postgraduate Programs (per year)</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered tuition-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Program</th>
                                                            <th>Tuition Fee (¥)</th>
                                                            <th>Accommodation (¥)</th>
                                                            <th>Insurance (¥)</th>
                                                            <th>Total (¥)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Master of Nursing</td>
                                                            <td>30,000</td>
                                                            <td>5,000</td>
                                                            <td>300</td>
                                                            <td>35,300</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Master of Public Health</td>
                                                            <td>32,000</td>
                                                            <td>5,000</td>
                                                            <td>300</td>
                                                            <td>37,300</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Clinical Medicine (MSc)</td>
                                                            <td>35,000</td>
                                                            <td>5,000</td>
                                                            <td>300</td>
                                                            <td>40,300</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>

                                    <!-- Doctoral Fees -->
                                    <div class="fee-table-section">
                                        <h5><i class="fas fa-flask text-info me-2"></i>Doctoral Programs (per year)</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered tuition-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Program</th>
                                                            <th>Tuition Fee (¥)</th>
                                                            <th>Accommodation (¥)</th>
                                                            <th>Insurance (¥)</th>
                                                            <th>Total (¥)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Doctorate in Clinical Medicine</td>
                                                            <td>35,000</td>
                                                            <td>6,000</td>
                                                            <td>300</td>
                                                            <td>41,300</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Doctorate in Biomedical Research</td>
                                                            <td>33,000</td>
                                                            <td>6,000</td>
                                                            <td>300</td>
                                                            <td>39,300</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>

                                    <div class="additional-fees mt-4">
                                        <h6><i class="fas fa-receipt text-warning me-2"></i>Additional Fees (One-time):</h6>
                                        <ul>
                                            <li><strong>Application Fee:</strong> 500¥ (non-refundable)</li>
                                            <li><strong>Registration Fee:</strong> 800¥ (first year only)</li>
                                            <li><strong>Medical Examination:</strong> 350¥ (first year in China)</li>
                                            <li><strong>Residence Permit:</strong> 800¥/year</li>
                                            <li><strong>Textbooks:</strong> Approximately 1,000-2,000¥/year</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 admissions-media">
                                <div class="admission-card card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h5 class="card-title"><i class="fas fa-calculator me-2 text-primary"></i>Fee Calculator</h5>
                                        <div class="calculator mt-3">
                                            <div class="mb-3">
                                                <label class="form-label">Select Program Level:</label>
                                                <select class="form-select" id="programLevel">
                                                    <option value="undergraduate">Undergraduate</option>
                                                    <option value="postgraduate">Postgraduate</option>
                                                    <option value="doctoral">Doctoral</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Select Program:</label>
                                                <select class="form-select" id="programSelect">
                                                    <option value="mbbs">MBBS (Clinical Medicine)</option>
                                                    <option value="tcm">Traditional Chinese Medicine</option>
                                                    <option value="stomatology">Stomatology</option>
                                                    <option value="pharmacy">Pharmacy</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Duration (Years):</label>
                                                <input type="number" class="form-control" id="duration" value="4" min="1" max="6">
                                            </div>
                                            <button class="btn btn-primary w-100" id="calculateBtn">
                                                <i class="fas fa-calculator me-2"></i>Calculate Total Cost
                                            </button>
                                            <div class="result mt-3 p-3 bg-light rounded d-none" id="calculationResult">
                                                <h6>Estimated Total Cost:</h6>
                                                <div class="total-cost display-6 fw-bold text-primary" id="totalCost">0 ¥</div>
                                                <small class="text-muted" id="costBreakdown"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.seo-faq', ['faqs' => config('seo_faqs.admissions'), 'faqTitle' => 'MBBS admission FAQs'])

<!-- Page Specific JS -->
<script src="{{ get_absolute_url('js/admissions.js') }}"></script>
@endsection
