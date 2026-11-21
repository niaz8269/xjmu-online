<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="assets/javascript/csrf.js?v=1"></script>
  <title>Edit Personal Details</title>
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/portal.css?v=1">
  <link rel="stylesheet" href="css/portal-apply.css?v=2">
</head>
<body class="portal">

@include('portal._bar', ['active' => 'application'])



  <div class="container p-apply">
    <div class="p-hello">
      <h1>My application</h1>
      <p>{{ \App\Support\Cms::portal('form_intro') }}</p>
    </div>

    <div class="p-download" id="applyDownload" hidden>
      <div><strong>Your application is submitted.</strong><br><span>Download a copy for your records and your visa application.</span></div>
      <div class="p-actions">
        <a class="p-btn p-btn--primary" href="download_application?format=pdf"><i class="bi bi-file-earmark-pdf"></i> Download PDF</a>
        <a class="p-btn p-btn--ghost" href="download_application"><i class="bi bi-file-earmark-zip"></i> PDF and documents (ZIP)</a>
      </div>
    </div>

    <div class="p-progress" id="applyProgress" aria-live="polite">
      <div class="p-progress__top"><span><strong id="applyProgressCount">0 of 4</strong> sections complete</span><span id="applyProgressPct">0%</span></div>
      <div class="p-progress__bar"><span id="applyProgressBar" style="width:0%"></span></div>
    </div>

    <!-- Upload Success/Error Messages -->
    @if ($uploadSuccess)
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ $uploadSuccess }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    
    @if ($uploadErrors && is_array($uploadErrors))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Upload Errors:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($uploadErrors as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    
    <!-- Collapsible Section 0: Select Degree and Major Applying For -->
    <div class="collapsable-section">
      <button class="collapse-btn">
        <span class="plus">+</span>
        <span class="minus" style="display:none;">-</span>
        <span class="p-sec__t">Programme<small>Choose the degree and the major you want to study.</small></span><span class="p-sec__badge"></span>
      </button>
      <div class="content" style="display:none;">
        <form id="programForm" class="my-3" action="save_application_program" method="post">
          <p class="mt-2 text-center dotted-border">Degree and Major</p>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Degree Applying for <span style="color: red;">*</span></label>
              <select class="form-select mandatory" name="degree_applying_for" required>
                <option value="">Select</option>
                <option value="Bachelor's Degree"{!! $degree_applying_for === "Bachelor's Degree" ? ' selected' : '' !!}>Bachelor's Degree</option>
                <option value="Master's Degree"{!! $degree_applying_for === "Master's Degree" ? ' selected' : '' !!}>Master's Degree</option>
                <option value="Doctoral Degree"{!! $degree_applying_for === "Doctoral Degree" ? ' selected' : '' !!}>Doctoral Degree</option>
                <option value="Senior High School"{!! $degree_applying_for === "Senior High School" ? ' selected' : '' !!}>Senior High School</option>
                <option value="Other"{!! $degree_applying_for === "Other" ? ' selected' : '' !!}>Other</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Major Applying for <span style="color: red;">*</span></label>
              <input type="text" class="form-control mandatory" name="major_applying_for" placeholder="e.g. MBBS, Clinical Medicine, Nursing" value="{{ $major_applying_for }}" required>
            </div>
          </div>
          <div class="text-end mt-4">
            <button type="submit" class="btn text-white" style="background-color: #E62129;">Save</button>
          </div>
        </form>

        <hr>
      </div>
    </div>

    <!-- Collapsible Section 1: Personal Details -->
    <div class="collapsable-section">
      <button class="collapse-btn">
        <span class="plus">+</span>
        <span class="minus" style="display:none;">-</span>
        <span class="p-sec__t">Personal details<small>Your name as on your passport, contact details and an emergency contact.</small></span><span class="p-sec__badge"></span>
      </button>
      <div class="content" style="display:none;">
          <!-- Personal Information Section -->
      
          <form id="personalDetailsForm" class="my-3" action="save_personal" method="post">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Given Name (Passport) <span style="color: red;">*</span></label>
                    <input type="text" class="form-control mandatory" name="given_name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Surname (Passport) <span style="color: red;">*</span></label>
                    <input type="text" class="form-control mandatory" name="surname" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Chinese Name</label>
                    <input type="text" class="form-control" name="chinese_name">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth <span style="color: red;">*</span></label>
                    <input type="date" class="form-control mandatory" name="dob" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender <span style="color: red;">*</span></label>
                    <select class="form-select mandatory" name="gender" required>
                        <option value="">Select</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Marital Status <span style="color: red;">*</span></label>
                    <select class="form-select mandatory" name="marital_status" required>
                        <option value="">Select</option>
                        <option>Married</option>
                        <option>Single</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-md-4 position-relative">
                  <label class="form-label">Country of Birth <span style="color: red;">*</span></label>
                  <input type="text" id="countryInput" class="form-control mandatory" name="country_of_birth" placeholder="Select or type a country" required>
                  <div id="countryDropdown" class="dropdown-content"></div>
              </div>
              
              <div class="col-md-4">
                  <label class="form-label">City of Birth <span style="color: red;">*</span></label>
                  <input type="text" class="form-control mandatory" name="city_of_birth" required>
              </div>
              
              <div class="col-md-4">
                  <label class="form-label">Religion <span style="color: red;">*</span></label>
                  <input type="text" class="form-control mandatory" name="religion" required>
              </div>
              
              <div class="col-md-6 position-relative">
                  <label class="form-label">Nationality <span style="color: red;">*</span></label>
                  <input type="text" id="nationalityInput" class="form-control mandatory" name="nationality" placeholder="Select or type a nationality" required>
                  <div id="nationalityDropdown" class="dropdown-content"></div>
              </div>
              
              <div class="col-md-6 position-relative">
                <label class="form-label">Native Language<span style="color: red;">*</span></label>
                <input type="text" id="nativeLanguageInput" class="form-control mandatory" name="native_language" placeholder="Select or type a language" required>
                <div id="nativeLanguageDropdown" class="dropdown-content"></div>
            </div>
               <div class="col-md-6">
                    <label class="form-label">Passport No. <span style="color: red;">*</span></label>
                    <input type="text" class="form-control mandatory" name="passport_no" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date of Expiration</label>
                    <input type="date" class="form-control" name="passport_expiry">
                </div>
                <p class="note" style="color: red;">Note: The passport information you have provided will be used for admission documents (visa application documents), please ensure the accuracy of the information. If the validity period of the currently held passport is less than 12 months (calculated from the expected start date of study), please renew the passport and use it to complete the scholarship_001 application. Applicants with no Given 
                  Name or Surname on the passport,please input special character * as substitution.</p>
            </div>
            
            <p class="mt-4">Present Address <span style="color: red;">*</span></p>
            <textarea class="form-control mandatory" name="present_address" rows="3" placeholder="Enter your current residential address" required></textarea>
            
            <p class="mt-4">Permanent Address <span style="color: red;">*</span></p>
            <textarea class="form-control mandatory" name="permanent_address" rows="3" placeholder="Enter permanent address (if different from present address, otherwise same as above)" required></textarea>
            
            <p class="mt-4 text-center dotted-border">Personal Contact Information</p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tel<span style="color: red;">*</span></label>
                    <input type="text" class="form-control mandatory" name="tel" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email<span style="color: red;">*</span></label>
                    <input type="email" class="form-control mandatory" name="email" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Alternate Email</label>
                    <input type="email" class="form-control" name="alternate_email">
                </div>
                <div class="col-md-6">
                    <label class="form-label">WeChat ID</label>
                    <input type="text" class="form-control" name="wechat_id">
                </div>
                <div class="col-md-6">
                    <label class="form-label">SKYPE No</label>
                    <input type="number" class="form-control" name="skype_no">
                </div>

            </div>
            <p class="mt-4 text-center my-3 dotted-border">Emergency Contact Information</p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Emergency Contact Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control mandatory" name="emergency_contact_name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender <span style="color: red;">*</span></label>
                    <select class="form-select mandatory" name="emergency_gender" required>
                        <option value="">Select</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Relationship to you<span style="color: red;">*</span></label>
                  <select class="form-select mandatory" name="emergency_relationship" required>
                      <option value="">Select</option>
                      <option>Parent</option>
                      <option>Child</option>
                      <option>Sibling</option>
                      <option>Spouse</option>
                      <option>Teacher</option>
                      <option>Friend</option>
                      <option>Other</option>
                  </select>
              </div>
                <div class="col-md-6">
                    <label class="form-label">Tel <span style="color: red;">*</span></label>
                    <input type="text" class="form-control mandatory" name="emergency_tel" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span style="color: red;">*</span></label>
                    <input type="email" class="form-control mandatory" name="emergency_email" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Address <span style="color: red;">*</span></label>
                    <textarea class="form-control mandatory" name="emergency_address" rows="3" required></textarea>
                </div>
            </div>
        
            <div id="personalSaveMsg" class="alert small" style="display: none;"></div>
            <div class="text-end mt-4">
                <button type="submit" class="btn text-white" style="background-color: #E62129;">Verify and Save</button>
            </div>
        </form>
        <hr>
        


      </div>
    </div>

    <!-- Collapsible Section 2: Educational Background -->
    <div class="collapsable-section">
      <button class="collapse-btn">
        <span class="plus">+</span>
        <span class="minus" style="display:none;">-</span>
        <span class="p-sec__t">Education<small>Your two most recent schools or colleges.</small></span><span class="p-sec__badge"></span>
      </button>
      <div class="content" style="display:none;">
        <form id="educationForm" class="my-3" action="save_education" method="post">
        <p class="mt-4 text-center my-3 dotted-border">Highest/Current Education</p>
        <div class="row"> 
           <div class="col-md-6">
            <label class="form-label">Education Level <span style="color: red;">*</span></label>
            <select class="form-select mandatory" name="education_level[]" required>
             <option value="">Select</option>
              <option>Bachelor's Degree</option>
              <option>Master's Degree</option>
              <option>Doctoral Degree</option>
              <option>Senior High School</option>
              <option>Junior High School</option>
              <option>Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Field of Study <span style="color: red;">*</span></label>
            <input type="text" class="form-control mandatory" name="field_of_study[]" required>
          </div>
          <div class="col-md-4 position-relative mt-4">
            <label class="form-label">Country of Institute <span style="color: red;">*</span></label>
            <input type="text" id="instituteInput" class="form-control mandatory" name="country[]" placeholder="Select or type a country" required>
            <div id="instituteDropdown" class="dropdown-content"></div>
        </div>
        <div class="col-md-8 mt-4">
          <label class="form-label">Institute Name <span style="color: red;">*</span></label>
          <input type="text" class="form-control mandatory" name="institute_name[]" required>
        </div>
        <div class="col-md-4 mt-4">
          <label class="form-label">Qualification <span style="color: red;">*</span></label>
          <input type="text" class="form-control mandatory" name="qualification[]" required>
        </div> 
        <div class="col-md-4 mt-4">
          <label class="form-label">Year Attended(from) <span style="color: red;">*</span></label>
          <input type="date" class="form-control mandatory" name="from_date[]" required>
        </div>
        <div class="col-md-4 mt-4">
          <label class="form-label">Year Attended(to) <span style="color: red;">*</span></label>
          <input type="date" class="form-control mandatory" name="to_date[]" required>
        </div>
       </div>
       <!-- Other 1 -->
       <p class="mt-4 text-center my-3 dotted-border">Other Education</p>
        <div class="row"> 
           <div class="col-md-6">
            <label class="form-label">Education Level <span style="color: red;">*</span></label>
            <select class="form-select mandatory" name="education_level[]" required>
              <option value="">Select</option>
              <option>Bachelor's Degree</option>
              <option>Master's Degree</option>
              <option>Doctoral Degree</option>
              <option>Senior High School</option>
              <option>Junior High School</option>
              <option>Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Field of Study <span style="color: red;">*</span></label>
            <input type="text" class="form-control mandatory" name="field_of_study[]" required>
          </div>
          <div class="col-md-4 position-relative mt-4">
            <label class="form-label">Country of Institute <span style="color: red;">*</span></label>
            <input type="text" id="instituteInput" class="form-control mandatory" name="country[]" placeholder="Select or type a country" required>
            <div id="instituteDropdown" class="dropdown-content"></div>
        </div>
        <div class="col-md-8 mt-4">
          <label class="form-label">Institute Name <span style="color: red;">*</span></label>
          <input type="text" class="form-control mandatory" name="institute_name[]" required>
        </div>
        <div class="col-md-4 mt-4">
          <label class="form-label">Qualification <span style="color: red;">*</span></label>
          <input type="text" class="form-control mandatory" name="qualification[]" required>
        </div> 
        <div class="col-md-4 mt-4">
          <label class="form-label">Year Attended(from) <span style="color: red;">*</span></label>
          <input type="date" class="form-control mandatory" name="from_date[]" required>
        </div>
        <div class="col-md-4 mt-4">
          <label class="form-label">Year Attended(to) <span style="color: red;">*</span></label>
          <input type="date" class="form-control mandatory" name="to_date[]" required>
        </div>
       </div>
       <!-- other 2 -->
       <p class="mt-4 text-center my-3 dotted-border">Other Education(Optional)</p>
       <div class="row">  <div class="col-md-6">
            <label class="form-label">Education Level</label>
            <select class="form-select" name="education_level[]">
              <option value="">Select</option>
              <option>Bachelor's Degree</option>
              <option>Master's Degree</option>
              <option>Doctoral Degree</option>
              <option>Senior High School</option>
              <option>Junior High School</option>
              <option>Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Field of Study</label>
            <input type="text" class="form-control" name="field_of_study[]">
          </div>
          <div class="col-md-4 position-relative mt-4">
            <label class="form-label">Country of Institute</label>
            <input type="text" id="instituteInput" class="form-control" name="country[]" placeholder="Select or type a country">
            <div id="instituteDropdown" class="dropdown-content"></div>
        </div>
        <div class="col-md-8 mt-4">
          <label class="form-label">Institute Name</label>
          <input type="text" class="form-control" name="institute_name[]">
        </div>
        <div class="col-md-4 mt-4">
          <label class="form-label">Qualification</label>
          <input type="text" class="form-control" name="qualification[]">
        </div> 
        <div class="col-md-4 mt-4">
          <label class="form-label">Year Attended(from)</label>
          <input type="date" class="form-control" name="from_date[]">
        </div>
        <div class="col-md-4 mt-4">
          <label class="form-label">Year Attended(to)</label>
          <input type="date" class="form-control" name="to_date[]">
        </div>
       </div>
         <div id="educationSaveMsg" class="alert small" style="display: none;"></div>
         <div class="text-end mt-4">
                <button type="submit" class="btn text-white" style="background-color: #E62129;">Verify and Save</button>
            </div>
       </form>
       <hr>

    </div>
    </div>

 <!-- Collapsible Section 3:  Documents -->
    <div class="collapsable-section" style="margin-top: 15px !important;">
      <button class="collapse-btn">
        <span class="plus">+</span>
        <span class="minus" style="display:none;">-</span>
         <span class="p-sec__t">Documents<small>Upload clear scans or photos of each document.</small></span><span class="p-sec__badge"></span>
      </button>
      <div class="content" style="display:none;">
          <!-- Table -->
           <form id="documentForm" class="my-3 bg-transparent" action="save_documents" method="post" enctype="multipart/form-data" >
          <div class="alert alert-danger small" id="documentUploadError" style="display:none;">
          </div>

          <div class="alert alert-success small" id="documentUploadSuccess" style="display:none;">
          </div>
          <p class="p-filehint"><i class="bi bi-info-circle me-1"></i>{{ \App\Support\Cms::portal('file_hint') }} Documents marked <strong>Yes</strong> are required.</p>
          <div class="table-responsive mt-3">
            <table class="table align-middle p-doctable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Document List</th>
                        <th>Mandatory or Not</th>
                        <th>File Name</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody id="documentTableBody"></tbody>
            </table>
        </div>
        

         <div class="text-end mt-2">
          <button type="submit" class="btn text-white" style="background-color: #E62129;">Verify and Save</button>
      </div>
        </form>
      <hr>
    </div>


    </div><!-- /documents section -->

    <!-- Submit Button -->
    <div class="p-submit">
      <p id="applyMissing" class="p-note mb-2"></p>
      <button id="submitApplicationBtn" type="button" class="btn btn-custom text-white" style="background-color: #E62129;">Submit Application</button>
      <p class="p-note">After you submit, your application is locked and sent to the admissions office.</p>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/javascript/apply.js?v=20260217a"></script>
  <script src="assets/javascript/edit-details.js?v=20261122b"></script>
  <script src="assets/javascript/portal-apply.js?v=2"></script>


</div> 

<footer class="p-foot">
  <p class="mb-1">Need help? WhatsApp CN {{ \App\Support\Settings::get('contact_phone2', '+86 176 4041 2827') }} / PK {{ \App\Support\Settings::get('contact_phone_pk', '+92 300 400 9526') }} &middot; {{ \App\Support\Settings::get('contact_email', 'studyatxjmu@gmail.com') }}</p>
  <p class="mb-0">{{ \App\Support\Cms::portal('copyright') }} International Students Admissions.</p>
</footer>

</body>
</html>