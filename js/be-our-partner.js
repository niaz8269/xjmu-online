document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('partnerRegistrationForm');
  var submitBtn = document.getElementById('partnerSubmitBtn');
  var alertBox = document.getElementById('partnerFormAlert');

  if (!form || !submitBtn || !alertBox) {
    return;
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    alertBox.className = 'partner-alert';
    alertBox.style.display = 'none';
    alertBox.textContent = '';

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form)
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.status === 'success') {
          alertBox.className = 'partner-alert success';
          alertBox.textContent = data.message || 'Registration submitted successfully.';
          form.reset();
        } else {
          alertBox.className = 'partner-alert error';
          alertBox.textContent = data.message || 'Failed to submit registration.';
        }
      })
      .catch(function () {
        alertBox.className = 'partner-alert error';
        alertBox.textContent = 'Network error. Please try again.';
      })
      .finally(function () {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Register';
      });
  });
});
