document.addEventListener('DOMContentLoaded', function () {
  populatePersonalData();
  populateEducationData();
  setupCollapseButtons();
  setupProgramForm();
  setupPersonalForm();
  setupEducationForm();
  setupDocumentsSection();
  setupDocumentsForm();
  setupSubmitButton();
  autoHideDocumentMessages();
  enforceSubmittedLock();
});

function populatePersonalData() {
  fetch('get_personal.php')
    .then(function (res) { return res.json(); })
    .then(function (response) {
      if (response.status !== 'success' || !response.data) return;

      var data = response.data;
      Object.keys(data).forEach(function (key) {
        var field = document.querySelector('[name="' + key + '"]');
        if (!field) return;

        if (field.type === 'radio' || field.type === 'checkbox') {
          field.checked = String(data[key]) === String(field.value);
        } else {
          field.value = data[key] == null ? '' : data[key];
        }
      });
    })
    .catch(function (err) {
      console.error('Failed to load personal data:', err);
    });
}

function populateEducationData() {
  fetch('get_education.php')
    .then(function (res) { return res.json(); })
    .then(function (response) {
      if (response.status !== 'success' || !Array.isArray(response.data)) return;

      var records = response.data;
      records.forEach(function (record, index) {
        var level = document.querySelectorAll('[name="education_level[]"]')[index];
        var fieldOfStudy = document.querySelectorAll('[name="field_of_study[]"]')[index];
        var country = document.querySelectorAll('[name="country[]"]')[index];
        var institute = document.querySelectorAll('[name="institute_name[]"]')[index];
        var qualification = document.querySelectorAll('[name="qualification[]"]')[index];
        var from = document.querySelectorAll('[name="from_date[]"]')[index];
        var to = document.querySelectorAll('[name="to_date[]"]')[index];

        if (level) level.value = record.education_level || '';
        if (fieldOfStudy) fieldOfStudy.value = record.field_of_study || '';
        if (country) country.value = record.country || '';
        if (institute) institute.value = record.institute_name || '';
        if (qualification) qualification.value = record.qualification || '';
        if (from) from.value = record.from_date || '';
        if (to) to.value = record.to_date || '';
      });
    })
    .catch(function (err) {
      console.error('Failed to load education data:', err);
    });
}

function setupCollapseButtons() {
  var collapseButtons = document.querySelectorAll('.collapse-btn');
  collapseButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      var content = this.nextElementSibling;
      var plus = this.querySelector('.plus');
      var minus = this.querySelector('.minus');

      if (content.style.display === 'none') {
        content.style.display = 'block';
        plus.style.display = 'none';
        minus.style.display = 'inline';
      } else {
        content.style.display = 'none';
        plus.style.display = 'inline';
        minus.style.display = 'none';
      }
    });
  });
}

function setupProgramForm() {
  var programForm = document.getElementById('programForm');
  if (!programForm) return;

  programForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    fetch('save_application_program.php', { method: 'POST', body: formData })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.status === 'success') {
          alert(data.message);
          window.location.href = 'edit-details.php';
        } else {
          alert(data.message || 'Failed to save.');
        }
      })
      .catch(function () { alert('Network error. Please try again.'); });
  });
}

function setupPersonalForm() {
  var personalForm = document.getElementById('personalDetailsForm');
  if (!personalForm) return;

  personalForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);

    fetch('save_personal.php', {
      method: 'POST',
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        var msg = document.getElementById('personalSaveMsg');
        if (!msg) return;
        if (data.status === 'success') {
          msg.className = 'alert alert-success small';
          msg.textContent = data.message || 'Saved successfully.';
        } else {
          msg.className = 'alert alert-danger small';
          msg.textContent = data.message || 'Something went wrong while saving.';
        }
        msg.style.display = 'block';
        setTimeout(function () {
          msg.style.display = 'none';
        }, 3500);
      })
      .catch(function () {
        var msg = document.getElementById('personalSaveMsg');
        if (!msg) return;
        msg.className = 'alert alert-danger small';
        msg.textContent = 'An unexpected error occurred.';
        msg.style.display = 'block';
        setTimeout(function () {
          msg.style.display = 'none';
        }, 3500);
      });
  });
}

function setupEducationForm() {
  var educationForm = document.getElementById('educationForm');
  if (!educationForm) return;

  educationForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);

    fetch('save_education.php', {
      method: 'POST',
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        var msg = document.getElementById('educationSaveMsg');
        if (!msg) return;
        if (data.status === 'success') {
          msg.className = 'alert alert-success small';
          msg.textContent = data.message || 'Saved successfully.';
        } else {
          msg.className = 'alert alert-danger small';
          msg.textContent = data.message || 'Something went wrong while saving.';
        }
        msg.style.display = 'block';
        setTimeout(function () {
          msg.style.display = 'none';
        }, 3500);
      })
      .catch(function () {
        var msg = document.getElementById('educationSaveMsg');
        if (!msg) return;
        msg.className = 'alert alert-danger small';
        msg.textContent = 'An unexpected error occurred.';
        msg.style.display = 'block';
        setTimeout(function () {
          msg.style.display = 'none';
        }, 3500);
      });
  });
}

function setupDocumentsSection() {
  if (!document.getElementById('documentTableBody')) return;

  var documentData = [
    { id: 1, name: 'ID Photo', mandatory: 'Yes' },
    { id: 2, name: 'Certificates of Highest Education', mandatory: 'Yes' },
    { id: 3, name: 'Transcripts of Highest Education', mandatory: 'Yes' },
    { id: 4, name: 'Study Plan', mandatory: 'Yes' },
    { id: 5, name: 'Reference I', mandatory: 'No', addProvider: true },
    { id: 6, name: 'Reference II', mandatory: 'No', addProvider: true },
    { id: 7, name: 'Passport Home Page', mandatory: 'Yes' },
    { id: 8, name: 'Physical Examination Record for Foreigner', mandatory: 'Yes' },
    { id: 11, name: 'Self Introduction Video', mandatory: 'No' },
    { id: 12, name: 'Chinese Language Proficiency Certificate', mandatory: 'No' },
    { id: 13, name: 'English Language Proficiency Certificate', mandatory: 'No' },
    { id: 14, name: 'Pre-admission Letter', mandatory: 'No' },
    { id: 15, name: 'Non-Criminal Record Report', mandatory: 'Yes' }
  ];

  window.displayFileName = function (input, fileNameId) {
    var fileNameSpan = document.getElementById(fileNameId);
    if (!fileNameSpan) return;

    var errBox = document.getElementById('documentUploadError');
    if (errBox) {
      errBox.style.display = 'none';
      errBox.innerHTML = '';
    }

    var docIdMatch = (input.name || '').match(/^document_(\d+)\[\]$/);
    var documentId = docIdMatch ? parseInt(docIdMatch[1], 10) : null;
    var maxBytes = documentId === 11 ? (10 * 1024 * 1024) : (1 * 1024 * 1024);
    var maxLabel = documentId === 11 ? '10MB' : '1MB';

    var oversized = [];
    var invalidExtensions = [];
    for (var j = 0; j < input.files.length; j++) {
      if (input.files[j].size > maxBytes) {
        oversized.push(input.files[j].name);
      }

      var fileName = input.files[j].name || '';
      var ext = fileName.split('.').pop().toLowerCase();
      var allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi'];
      if (!ext || allowed.indexOf(ext) === -1) {
        invalidExtensions.push(fileName);
      }
    }

    if (oversized.length > 0) {
      if (errBox) {
        errBox.className = 'alert alert-danger small';
        errBox.innerHTML = 'File size exceeded for document ' + (documentId || '') + '. Maximum allowed is ' + maxLabel + '.<br>' + oversized.join('<br>');
        errBox.style.display = 'block';
      } else {
        alert('File size exceeded. Maximum allowed is ' + maxLabel + '.');
      }

      input.value = '';
      fileNameSpan.textContent = '';
      return;
    }

    if (invalidExtensions.length > 0) {
      if (errBox) {
        errBox.className = 'alert alert-danger small';
        errBox.innerHTML = 'Invalid file type selected. Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF, MP4, MOV, AVI.<br>' + invalidExtensions.join('<br>');
        errBox.style.display = 'block';
      } else {
        alert('Invalid file type selected.');
      }

      input.value = '';
      fileNameSpan.textContent = '';
      return;
    }

    if (input.files.length > 0) {
      var fileNames = [];
      for (var i = 0; i < input.files.length; i++) {
        fileNames.push(input.files[i].name);
      }
      fileNameSpan.textContent = fileNames.join(', ');
    } else {
      fileNameSpan.textContent = '';
    }
  };

  populateTable(documentData);
  loadExistingDocuments();
}

function populateTable(documentData) {
  var tableBody = document.getElementById('documentTableBody');
  if (!tableBody) return;
  tableBody.innerHTML = '';

  documentData.forEach(function (row) {
    var isVideo = row.id === 11;
    var acceptAttr = isVideo ? 'accept="video/*"' : '';

    var tr = document.createElement('tr');
    tr.innerHTML =
      '<td class="text-center">' + row.id + '</td>' +
      '<td>' + row.name + '</td>' +
      '<td class="text-center">' + row.mandatory + '</td>' +
      '<td><span id="fileName' + row.id + '" class="file-name"></span></td>' +
      '<td class="text-center">' +
      '  <button class="btn btn-light btn-upload" type="button" onclick="document.getElementById(\'fileInput' + row.id + '\').click()">' +
      '    <i class="fas fa-upload"></i> Upload' +
      '  </button>' +
      '  <input type="file" name="document_' + row.id + '[]" id="fileInput' + row.id + '" ' + acceptAttr + ' multiple style="display: none;" onchange="displayFileName(this, \'fileName' + row.id + '\')">' +
      '</td>';
    tableBody.appendChild(tr);
  });
}

function loadExistingDocuments() {
  fetch('get_documents.php')
    .then(function (res) { return res.json(); })
    .then(function (response) {
      if (response.status === 'success') {
        // Group documents by document_id
        var groupedDocs = {};
        response.data.forEach(function (doc) {
          if (!groupedDocs[doc.document_id]) {
            groupedDocs[doc.document_id] = [];
          }
          groupedDocs[doc.document_id].push(doc);
        });

        // Display all files for each document type
        Object.keys(groupedDocs).forEach(function (docId) {
          var fileSpan = document.getElementById('fileName' + docId);
          if (fileSpan) {
            var fileLinks = groupedDocs[docId].map(function (doc) {
              return '<a href="' + doc.file_path + '" target="_blank" rel="noopener noreferrer" class="file-link">' +
                     doc.file_name + '</a>';
            });
            fileSpan.innerHTML = fileLinks.join('<br>');
          }
        });
      } else if (response.status === 'empty') {
        // No documents uploaded yet.
      } else {
        console.error('Error fetching documents:', response.message);
      }
    })
    .catch(function (error) {
      console.error('Fetch error:', error);
    });
}

function setupDocumentsForm() {
  var documentForm = document.getElementById('documentForm');
  if (!documentForm) return;

  documentForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var errorMsg = document.getElementById('documentUploadError');
    var successMsg = document.getElementById('documentUploadSuccess');

    var hasSelectedFiles = false;
    Array.prototype.forEach.call(documentForm.querySelectorAll('input[type="file"]'), function (input) {
      if (input.files && input.files.length > 0) {
        hasSelectedFiles = true;
      }
    });

    if (!hasSelectedFiles) {
      if (successMsg) successMsg.style.display = 'none';
      if (errorMsg) {
        errorMsg.className = 'alert alert-danger small';
        errorMsg.textContent = 'Please choose at least one document before clicking Verify and Save.';
        errorMsg.style.display = 'block';
      }
      return;
    }

    fetch('save_documents.php', {
      method: 'POST',
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.status === 'success') {
          if (errorMsg) errorMsg.style.display = 'none';
          if (successMsg) {
            successMsg.className = 'alert alert-success small';
            successMsg.textContent = data.message || 'Uploaded successfully.';
            successMsg.style.display = 'block';
          }
          
          // Clear file inputs
          documentForm.reset();
          
          // Reload documents to reflect uploads
          loadExistingDocuments();
        } else if (data.status === 'error') {
          if (successMsg) successMsg.style.display = 'none';
          if (errorMsg) {
            var errorList = data.errors && Array.isArray(data.errors) 
              ? data.errors.join('<br>') 
              : (data.message || 'Upload failed');
            errorMsg.className = 'alert alert-danger small';
            errorMsg.innerHTML = errorList;
            errorMsg.style.display = 'block';
          }
        }
      })
      .catch(function (err) {
        console.error('Upload error:', err);
        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg) {
          errorMsg.className = 'alert alert-danger small';
          errorMsg.textContent = 'Network error during upload. Please try again.';
          errorMsg.style.display = 'block';
        }
      });
  });
}

function setupSubmitButton() {
  var submitBtn = document.getElementById('submitApplicationBtn');
  if (!submitBtn) return;

  submitBtn.addEventListener('click', function () {
    if (!confirm('Are you sure you want to submit? You won\'t be able to edit afterwards.')) return;

    fetch('submit_application.php', { method: 'POST' })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        alert(data.message);
        if (data.status === 'success') {
          window.location.href = 'edit-details.php';
        }
      })
      .catch(function (err) {
        console.error('Submission failed:', err);
        alert('Error submitting application.');
      });
  });
}

function autoHideDocumentMessages() {
  var docSuccess = document.getElementById('documentUploadSuccess');
  if (docSuccess) {
    setTimeout(function () {
      docSuccess.style.display = 'none';
    }, 3500);
  }

  var docError = document.getElementById('documentUploadError');
  if (docError) {
    setTimeout(function () {
      docError.style.display = 'none';
    }, 3500);
  }
}

function enforceSubmittedLock() {
  fetch('get_submission_status.php')
    .then(function (res) { return res.json(); })
    .then(function (data) {
      var rejected = (typeof isRejected !== 'undefined') && isRejected;
      if (data.is_submitted && !rejected) {
        document.querySelectorAll('input, select, textarea, button.btn-upload').forEach(function (el) {
          el.disabled = true;
        });

        document.querySelectorAll('button[type=submit], #submitApplicationBtn').forEach(function (btn) {
          btn.style.display = 'none';
        });

        var statusBar = document.createElement('div');
        statusBar.className = 'alert alert-success mt-3';
        statusBar.textContent = '✅ Application has been submitted and is now locked.';
        var content = document.querySelector('.content');
        if (content) {
          content.prepend(statusBar);
        }
      }
    });
}
