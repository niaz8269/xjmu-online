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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    *{
      text-decoration: none !important;
    }
    body {
      font-family: Arial, sans-serif;
      background:url('assets/images/bg2.jpeg');
    }
   .container {
  max-width: 1000px;
  margin: 50px auto;
  padding: 10px;
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

    .collapsable-section {
      margin-bottom: 15px;
    }
    .collapse-btn {
      width: 100%;
      text-align: left;
      padding: 5px;
      font-size: 16px;
      background-color: #f1f1f1;
      border: none;
      cursor: pointer;
    }
    .collapse-btn .plus, .collapse-btn .minus {
      font-size: 20px;
      margin-right: 10px;
    }
    .content {
      padding: 30px;
      margin-top: 5px;
    }
    .btn-custom {
      width: 100%;
      margin-top: 10px;
      margin-bottom: 10px !important;
      padding: 10px;
      font-size: 16px;
    }
    input, select, textarea, button{
      border-radius: 0% !important;
    }
    .dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ccc;
    width: 100%; /* Matches the input width */
    z-index: 1000;
    border-radius: 5px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }
    .dropdown-item {
    padding: 8px;
    cursor: pointer;
    }
    .dropdown-item:hover {
        background-color: #f1f1f1;
    }
    .dotted-border {
  border-top: 2px dotted #ccc;
  border-bottom: 2px dotted #ccc; 
  padding: 10px; 
  text-align: center;
}       
a.file-link {
  text-decoration: none;
  color: #007bff; /* or any color you like */
}

a.file-link:hover {
  text-decoration: underline; /* optional on hover */
}

   :root {
      --jmu-primary: #E62129; /* Dark green from logo */
      --jmu-secondary: #E62129; /* Lighter green */
      --jmu-accent: #0090C8; /* Blue for accents */
      --jmu-light: #E8F5E9; /* Light green background */
      --jmu-dark: #E62129; /* Darker green */
    }
    .navbar {
      min-height: 60px;
      border-bottom: 2px solid #E62129;
    }

    .logo-container {
      display: flex;
      align-items: center;
      padding: 10px 0;
    }

    .logo-img {
      max-width: 180px;
      height: auto;
    }
    .vertical-divider {
      background-color: black;
      width: 2px;
      height: 80px;
      margin: 0 20px;
    }
    footer {
      background-color: white;
      border-top: 3px solid var(--jmu-primary);
    }

    .news-date {
      background-color: var(--jmu-primary);
      color: white;
      border-radius: 4px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-width: 60px;
      height: 60px;
    }

    .copyright {
      color: #666;
      background-color: #f8f9fa;
    }

    .footer-title {
      color: var(--jmu-primary);
      border-bottom: 2px solid var(--jmu-secondary);
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
.hover-blue {
    border: 1px solid black !important;
    background-color: transparent !important;
    transition: all 0.3s ease; 
}

.hover-blue:hover {
    background-color: #0d6efd !important; 
    border-color: #0d6efd !important;
    color: white !important;
}

.dropdown-item:hover {
    background-color: #0d6efd !important;
    color: white !important; 
}
.language-btn{
  margin-top:22px;
  background-color: transparent !important;
  transition: all 0.3s ease; 
}.language-btn:hover {
    background-color: #0d6efd !important; 
    border-color: #0d6efd !important;
    color: white !important;
}
@media (max-width: 991.98px) {
  .disable-sticky-sm {
    position: static !important;
    top: auto !important;
  }
}

  </style>
</head>
<body>
  <nav class="navbar navbar-light p-0 sticky-top disable-sticky-sm">
  <div class="container-fluid flex-column flex-lg-row align-items-center align-items-lg-center py-2">

    <!-- Logo + Title -->
    <div class="d-flex flex-column flex-lg-row align-items-center text-center text-lg-start">
      
      <img src="assets/images/logo.webp" alt="Xinjiang Medical University"
           width="270"
           class="d-inline-block mb-2 mb-lg-0">

      <div class="vr d-none d-lg-block mx-3 opacity-100"></div>

      <div class="d-flex flex-column lh-sm text-black">
        <span class="fw-bold fs-4">International Students</span>
        <span class="fw-bold fs-4">Centralized Admissions System (CAS)</span>
      </div>
    </div>

    <!-- Right Section -->
    <div class="d-flex flex-column flex-lg-row align-items-center mt-3 mt-lg-0">
      <div class="dropdown mb-2 mb-lg-0 me-lg-4 language-btn">
        <button class="btn dropdown-toggle rounded-0" type="button" data-bs-toggle="dropdown">
          <i class="bi bi-globe2 me-1"></i> English
        </button>
        <ul class="dropdown-menu dropdown-menu-end rounded-0">
          <li><a class="dropdown-item active" href="#" target="_blank" rel="noopener noreferrer">English</a></li>
          <li><a class="dropdown-item" href="#" target="_blank" rel="noopener noreferrer">中文 (Chinese)</a></li>
          <li><a class="dropdown-item" href="#" target="_blank" rel="noopener noreferrer">Français</a></li>
        </ul>
      </div>
      <div class="d-flex flex-column text-center text-lg-start">
        <div class="mb-1">
          Welcome, <strong>{{ $full_name }}</strong>!
        </div>

        <div class="d-flex flex-wrap justify-content-center justify-content-lg-start">
          <a href="index" class="btn btn-sm hover-blue rounded-0 me-1 mb-1" target="_blank" rel="noopener noreferrer">Home</a>
          <a href="profile" class="btn btn-sm hover-blue rounded-0 me-1 mb-1" target="_blank" rel="noopener noreferrer">My Profile</a>
          <a href="front" class="btn btn-sm hover-blue rounded-0 me-1 mb-1" target="_blank" rel="noopener noreferrer">Dashboard</a>
          <a href="logout" class="btn btn-sm hover-blue rounded-0 mb-1" target="_blank" rel="noopener noreferrer">Logout</a>
        </div>
      </div>

    </div>

  </div>
</nav>


  <div class="container">
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
        Select Degree and Major Applying For
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
        Personal Details
      </button>
      <div class="content" style="display:none;">
          <!-- Personal Information Section -->
      
          <form id="personalDetailsForm" class="my-3" action="save_personal" method="post">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Given Name (Passport) <span style="color: red;">*</span></label>
                    <input type="text" class="form-control mandatory" name="given_name" placeholder="Mandatory" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Surname (Passport) <span style="color: red;">*</span></label>
                    <input type="text" class="form-control mandatory" name="surname" placeholder="Mandatory" required>
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
                  <input type="text" class="form-control mandatory" name="city_of_birth" placeholder="Mandatory" required>
              </div>
              
              <div class="col-md-4">
                  <label class="form-label">Religion <span style="color: red;">*</span></label>
                  <input type="text" class="form-control mandatory" name="religion" placeholder="Mandatory" required>
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
                    <input type="text" class="form-control mandatory" name="passport_no" placeholder="Mandatory" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date of Expiration</label>
                    <input type="date" class="form-control" name="passport_expiry">
                </div>
                <p class="note" style="color: red;">Note: The passport information you have provided will be used for admission documents (visa application documents), please ensure the accuraccy of the information. If the validity period of the currently held passport is less than 12 months (calculated from the expected start date of study), please renew the passport and use it to complete the scholarship_001 application. Applicants with no Given 
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
                    <input type="text" class="form-control mandatory" name="tel" placeholder="Mandatory" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email<span style="color: red;">*</span></label>
                    <input type="email" class="form-control mandatory" name="email" placeholder="Mandatory" required>
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
                    <input type="text" class="form-control mandatory" name="emergency_contact_name" placeholder="Mandatory" required>
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
                    <input type="text" class="form-control mandatory" name="emergency_tel" placeholder="Mandatory" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span style="color: red;">*</span></label>
                    <input type="email" class="form-control mandatory" name="emergency_email" placeholder="Mandatory" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Address <span style="color: red;">*</span></label>
                    <textarea class="form-control mandatory" name="emergency_address" rows="3" placeholder="Mandatory" required></textarea>
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
        Educational Background
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
         Documents
      </button>
      <div class="content" style="display:none;">
          <!-- Table -->
           <form id="documentForm" class="my-3 bg-transparent" action="save_documents" method="post" enctype="multipart/form-data" >
          <div class="alert alert-danger small" id="documentUploadError" style="display:none;">
          </div>

          <div class="alert alert-success small" id="documentUploadSuccess" style="display:none;">
          </div>
          <div class="table-responsive mt-3">
            <table class="table align-middle">
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


    <!-- Submit Button -->
    <button id="submitApplicationBtn" type="button" class="btn btn-custom text-white" style="background-color: #E62129;">Submit Application</button>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/javascript/apply.js?v=20260217a"></script>
  <script src="assets/javascript/edit-details.js?v=20260217e"></script>


</div> 

<footer class="pt-4 w-100" style="width: 100% !important;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8 mb-4">
          <div class="footer-title fw-bold pb-2 mb-4 fs-5">News and Updates</div>
          
          <div class="d-flex align-items-start mb-3">
            <div class="news-date me-3">
              <span class="fw-bold" style="font-size: 0.9rem;">February. 10</span>
              <span style="font-size: 0.8rem;">2026</span>
            </div>
            <div class="pt-1 lh-base">
              <strong>Welcome to Xinjiang Medical University International Students Centralized Admission System (CAS).</strong>
              <p class="small mt-1 mb-0">Our new platform streamlines the application process for international students worldwide with enhanced security and user experience.</p>
            </div>
          </div>
          
          <div class="d-flex align-items-start mb-3">
            <div class="news-date me-3">
              <span class="fw-bold" style="font-size: 0.9rem;">February. 05</span>
              <span style="font-size: 0.8rem;">2026</span>
            </div>
            <div class="pt-1 lh-base">
              <strong>2027 Intake Now Open</strong>
              <p class="small mt-1 mb-0">Applications for the 2027 academic year are now being accepted through the CAS portal. Early applications are encouraged.</p>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4 mb-4">
          <div class="footer-title fw-bold pb-2 mb-4 fs-5">Contact Information</div>
          <ul class="list-unstyled small">
            <li class="mb-2"><i class="bi bi-geo-alt-fill me-2" style="color: var(--jmu-primary);"></i> Xinjiang Medical University, Xinjiang City, China</li>
            <li class="mb-2"><i class="bi bi-telephone-fill me-2" style="color: var(--jmu-primary);"></i>  CN +86 156 1750 0775 / PK +92 300 400 9526 </li>
            <li class="mb-2"><i class="bi bi-envelope-fill me-2" style="color: var(--jmu-primary);"></i>  studyatxjmu@gmail.com</li>
            <li><i class="bi bi-clock-fill me-2" style="color: var(--jmu-primary);"></i> Mon-Fri: 8:30 AM - 5:00 PM (CST)</li>
          </ul>
        </div>
      </div>

      <div class="copyright text-center small py-4 mt-4 rounded">
        <p class="mb-0">© Copyright 2026 <strong class="px-1" style="color: var(--jmu-primary);">Xinjiang Medical University</strong> All Rights Reserved</p>
        <p class="mt-2 mb-0">Designed for International Students Admissions</p>
      </div>
    </div>
</footer>

</body>
</html>