// Complete country list
const countryList = [
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", 
    "Antigua and Barbuda", "Argentina", "Armenia", "Australia", 
    "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", 
    "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", 
    "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", 
    "Bulgaria", "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde", 
    "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", 
    "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", 
    "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", 
    "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", 
    "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", 
    "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", 
    "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", 
    "Guyana", "Haiti", "Holy See", "Honduras", "Hungary", "Iceland", 
    "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", 
    "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", 
    "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", 
    "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", 
    "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", 
    "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", 
    "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", 
    "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", 
    "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", 
    "Norway", "Oman", "Pakistan", "Palau", "Palestine State", "Panama", 
    "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", 
    "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", 
    "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", 
    "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", 
    "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", 
    "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", 
    "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Tajikistan", 
    "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", 
    "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", 
    "United Arab Emirates", "United Kingdom", "United States of America", 
    "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", 
    "Zambia", "Zimbabwe"
];

// Complete language list
const languageList = [
    "Mandarin", "Spanish", "English", "Hindi", "Arabic", 
    "Bengali", "Portuguese", "Russian", "Japanese", "Punjabi", 
    "German", "Javanese", "Wu Chinese", "Malay", "Telugu", 
    "Vietnamese", "Korean", "French", "Marathi", "Tamil", 
    "Urdu", "Turkish", "Italian", "Yue Chinese", "Thai", 
    "Gujarati", "Jin Chinese", "Southern Min", "Persian", "Polish", 
    "Pashto", "Kannada", "Xiang Chinese", "Malayalam", "Sundanese", 
    "Hausa", "Odia", "Burmese", "Hakka", "Ukrainian", 
    "Bhojpuri", "Tagalog", "Yoruba", "Maithili", "Uzbek", 
    "Sindhi", "Amharic", "Fula", "Romanian", "Oromo", 
    "Igbo", "Azerbaijani", "Awadhi", "Gan Chinese", "Cebuano", 
    "Dutch", "Kurdish", "Serbo-Croatian", "Malagasy", "Saraiki", 
    "Nepali", "Sinhalese", "Chittagonian", "Zhuang", "Khmer", 
    "Turkmen", "Assamese", "Madurese", "Somali", "Marwari", 
    "Magahi", "Haryanvi", "Hungarian", "Chhattisgarhi", "Greek", 
    "Chewa", "Deccan", "Akan", "Kazakh", "Northern Min", 
    "Sylheti", "Zulu", "Czech", "Kinyarwanda", "Dhundhari", 
    "Haitian Creole", "Eastern Min", "Ilocano", "Quechua", "Kirundi", 
    "Swedish", "Hmong", "Shona", "Uyghur", "Hiligaynon", 
    "Mossi", "Xhosa", "Belarusian", "Balochi", "Konkani"
];

// Initialize when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns used by edit-details.php
    setupCountryInput("countryInput", "countryDropdown");
    setupCountryInput("nationalityInput", "nationalityDropdown");
    setupCountryInput("instituteInput", "instituteDropdown");
    setupLanguageInput();
});

// Dropdown setup functions
function setupCountryInput(inputId, dropdownId) {
    const input = document.getElementById(inputId);
    const dropdown = document.getElementById(dropdownId);

    if (!input || !dropdown) {
        console.error(`Elements not found: ${inputId} or ${dropdownId}`);
        return;
    }

    function showDropdown() {
        dropdown.innerHTML = countryList.map(country => 
            `<div class="dropdown-item" onclick="this.parentElement.previousElementSibling.value='${country}'; this.parentElement.style.display='none'">
                ${country}
            </div>`
        ).join('');
        dropdown.style.display = "block";
    }

    input.addEventListener("focus", showDropdown);

    input.addEventListener("input", function() {
        const filter = this.value.toLowerCase();
        const items = dropdown.querySelectorAll('.dropdown-item');
        items.forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(filter) ? "block" : "none";
        });
    });

    document.addEventListener("click", function(e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });
}

function setupLanguageInput() {
    const input = document.getElementById("nativeLanguageInput");
    const dropdown = document.getElementById("nativeLanguageDropdown");

    if (!input || !dropdown) {
        console.error("Language input elements not found");
        return;
    }

    function showDropdown() {
        dropdown.innerHTML = languageList.map(lang => 
            `<div class="dropdown-item" onclick="this.parentElement.previousElementSibling.value='${lang}'; this.parentElement.style.display='none'">
                ${lang}
            </div>`
        ).join('');
        dropdown.style.display = "block";
    }

    input.addEventListener("focus", showDropdown);

    input.addEventListener("input", function() {
        const filter = this.value.toLowerCase();
        const items = dropdown.querySelectorAll('.dropdown-item');
        items.forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(filter) ? "block" : "none";
        });
    });

    document.addEventListener("click", function(e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });
}

// Form handling functions
function initializeFormHandlers() {
    // Personal Details Form
    const personalForm = document.getElementById('personalDetailsForm');
    if (personalForm) {
        personalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmission('personalDetailsForm', 'personal_details');
        });
    }

    // Education Background Form
    const educationForm = document.getElementById('educationBackgroundForm');
    if (educationForm) {
        educationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmission('educationBackgroundForm', 'education_background');
        });
    }

    // Final Submit Button
    const finalSubmitBtn = document.getElementById('finalSubmit');
    if (finalSubmitBtn) {
        finalSubmitBtn.addEventListener('click', handleFinalSubmission);
    }

    // Verify and Save buttons
    document.querySelectorAll('.verifySave').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            if (form) {
                const section = form.id.replace('Form', '');
                handleFormSubmission(form.id, section);
            }
        });
    });
}

async function handleFormSubmission(formId, section) {
    const form = document.getElementById(formId);
    if (!form) return;

    const saveBtn = form.querySelector('.verifySave');
    if (!saveBtn) return;

    // Validate form
    if (!validateForm(form)) {
        showError('Please fill in all mandatory fields');
        return;
    }

    try {
        // Prepare form data
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            if (key.startsWith('form_data[')) {
                const propName = key.match(/\[(.*?)\]/)[1];
                data[propName] = value;
            }
        });

        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

        // Send to server
        const response = await fetch('save_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ section, form_data: data })
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Server error: ${response.status} - ${errorText}`);
        }

        const result = await response.json();
        
        if (result.status !== 'success') {
            throw new Error(result.message || 'Failed to save data');
        }

        showSuccess('Data saved successfully!');
        await loadFormData(); // Refresh data
    } catch (error) {
        console.error('Save error:', error);
        showError(error.message);
    } finally {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Verify and Save';
    }
}

function validateForm(form) {
    let isValid = true;
    form.querySelectorAll('.mandatory').forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    return isValid;
}

async function handleFinalSubmission() {
    const finalSubmitBtn = document.getElementById('finalSubmit');
    if (!finalSubmitBtn) return;

    if (!confirm('Are you sure you want to submit your application? You won\'t be able to edit it after submission.')) {
        return;
    }

    try {
        // Disable button during submission
        finalSubmitBtn.disabled = true;
        finalSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';

        const response = await fetch('submit_application.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId })
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Server error: ${response.status} - ${errorText}`);
        }

        const result = await response.json();
        
        if (result.status !== 'success') {
            throw new Error(result.message || 'Submission failed');
        }

        // Disable all form fields
        document.querySelectorAll('input, select, textarea').forEach(el => {
            el.readOnly = true;
            el.disabled = true;
        });
        
        // Hide save and submit buttons
        document.querySelectorAll('.verifySave, #finalSubmit').forEach(btn => {
            btn.style.display = 'none';
        });

        showSuccess('Application submitted successfully!');
        setTimeout(() => window.location.href = 'application_status.php', 1500);
    } catch (error) {
        console.error('Submission error:', error);
        showError(error.message);
        finalSubmitBtn.disabled = false;
        finalSubmitBtn.textContent = 'Submit Application';
    }
}

// Data loading functions
async function loadFormData() {
    try {
        const response = await fetch(`get_data.php?user_id=${userId}`);
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Server error: ${response.status} - ${errorText}`);
        }

        const data = await response.json();
        
        if (data.status !== 'success') {
            throw new Error(data.message || 'Failed to load data');
        }

        // Load personal details
        if (data.personal_details) {
            Object.entries(data.personal_details).forEach(([key, value]) => {
                const input = document.querySelector(`[name="form_data[${key}]"]`);
                if (input) input.value = value || '';
            });
        }

        // Load education background
        if (data.education_background) {
            Object.entries(data.education_background).forEach(([key, value]) => {
                const input = document.querySelector(`[name="form_data[${key}]"]`);
                if (input) input.value = value || '';
            });
        }

        // Load documents
        if (data.documents) {
            data.documents.forEach(doc => {
                const fileNameSpan = document.getElementById(`fileName${doc.document_type_id}`);
                if (fileNameSpan) fileNameSpan.textContent = doc.file_name;
            });
        }

        // Check if application is submitted
        if (data.application_status === 'submitted') {
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.readOnly = true;
                el.disabled = true;
            });
            document.querySelectorAll('.verifySave, #finalSubmit').forEach(btn => {
                btn.style.display = 'none';
            });
        }
    } catch (error) {
        console.error('Load error:', error);
        throw error; // Re-throw for caller to handle
    }
}

// File upload handling
function displayFileName(input, spanId, docId) {
    const fileNameSpan = document.getElementById(spanId);
    if (!fileNameSpan || !input.files.length) return;

    fileNameSpan.textContent = input.files[0].name;
    
    // Upload immediately
    const formData = new FormData();
    formData.append('section', 'documents');
    formData.append('form_data[document_id]', docId);
    formData.append('document_file', input.files[0]);
    
    fetch('save_data.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Upload failed');
        return response.json();
    })
    .then(data => {
        if (data.status !== 'success') {
            throw new Error(data.message || 'Upload failed');
        }
        showSuccess('Document uploaded successfully!');
    })
    .catch(error => {
        console.error('Upload error:', error);
        fileNameSpan.textContent = '';
        input.value = '';
        showError('Failed to upload document: ' + error.message);
    });
}

// UI helper functions
function showError(message) {
    // Remove existing alerts first
    document.querySelectorAll('.alert').forEach(alert => alert.remove());

    const alertBox = document.createElement('div');
    alertBox.className = 'alert alert-danger alert-dismissible fade show';
    alertBox.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.container') || document.body;
    container.prepend(alertBox);
    
    setTimeout(() => alertBox.remove(), 5000);
}

function showSuccess(message) {
    // Remove existing alerts first
    document.querySelectorAll('.alert').forEach(alert => alert.remove());

    const alertBox = document.createElement('div');
    alertBox.className = 'alert alert-success alert-dismissible fade show';
    alertBox.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.container') || document.body;
    container.prepend(alertBox);
    
    setTimeout(() => alertBox.remove(), 3000);
}

// Load personal data into the form fields
window.addEventListener("DOMContentLoaded", function () {
    fetch("get_personal.php")
        .then(res => res.json())
        .then(response => {
            if (response.status === "success") {
                const data = response.data;

                // Fill each field using name=""
                for (const [key, value] of Object.entries(data)) {
                    const field = document.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === "radio" || field.type === "checkbox") {
                            field.checked = value == field.value;
                        } else if (field.tagName === "SELECT" || field.tagName === "INPUT" || field.tagName === "TEXTAREA") {
                            field.value = value;
                        }
                    }
                }
            }
        })
        .catch(err => {
            console.error("Error fetching personal data:", err);
        });
});

// Load education data into the form fields
window.addEventListener("DOMContentLoaded", function () {
    fetch("get_education.php")
        .then(res => res.json())
        .then(response => {
            if (response.status === "success") {
                const records = response.data;

                // Loop through records and fill inputs
                records.forEach((record, i) => {
                    const level = document.querySelectorAll('[name="education_level[]"]')[i];
                    const field = document.querySelectorAll('[name="field_of_study[]"]')[i];
                    const country = document.querySelectorAll('[name="country[]"]')[i];
                    const institute = document.querySelectorAll('[name="institute_name[]"]')[i];
                    const qualification = document.querySelectorAll('[name="qualification[]"]')[i];
                    const from = document.querySelectorAll('[name="from_date[]"]')[i];
                    const to = document.querySelectorAll('[name="to_date[]"]')[i];

                    if (level) level.value = record.education_level || '';
                    if (field) field.value = record.field_of_study || '';
                    if (country) country.value = record.country || '';
                    if (institute) institute.value = record.institute_name || '';
                    if (qualification) qualification.value = record.qualification || '';
                    if (from) from.value = record.from_date || '';
                    if (to) to.value = record.to_date || '';
                });
            }
        })
        .catch(err => {
            console.error("Failed to load education data:", err);
        });
});