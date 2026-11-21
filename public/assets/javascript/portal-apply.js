// Application form helpers: per-section progress, overall progress bar and a "what is missing" line.
// Read-only on the form: it never changes field values or what is saved.
(function () {
  var sections = Array.prototype.slice.call(document.querySelectorAll('.p-apply .collapsable-section'));
  if (!sections.length) { return; }
  var names = ['Programme', 'Personal details', 'Education', 'Documents'];

  function fieldState(section) {
    var required = section.querySelectorAll('input[required], select[required], textarea[required]');
    var filled = 0;
    required.forEach(function (el) { if ((el.value || '').trim() !== '') { filled++; } });
    return { total: required.length, filled: filled };
  }

  function documentState(section) {
    var rows = section.querySelectorAll('#documentTableBody tr');
    var total = 0, filled = 0;
    rows.forEach(function (tr) {
      var cells = tr.children;
      if (cells.length < 4 || cells[2].textContent.trim().toLowerCase() !== 'yes') { return; }
      total++;
      if (cells[3].textContent.trim() !== '') { filled++; }
    });
    return { total: total, filled: filled };
  }

  function update() {
    var complete = 0, missing = [];
    sections.forEach(function (section, i) {
      var isDocs = !!section.querySelector('#documentTableBody');
      var st = isDocs ? documentState(section) : fieldState(section);
      var done = st.total > 0 && st.filled === st.total;
      var btn = section.querySelector('.collapse-btn');
      var badge = section.querySelector('.p-sec__badge');
      if (btn) { btn.classList.toggle('is-complete', done); }
      if (badge) {
        badge.textContent = done ? 'Complete' : (st.total ? (st.filled + ' of ' + st.total + (isDocs ? ' uploaded' : ' filled')) : '');
      }
      if (done) { complete++; } else { missing.push(names[i] || ('Section ' + (i + 1))); }
    });
    var pct = Math.round(complete / sections.length * 100);
    var set = function (id, text) { var el = document.getElementById(id); if (el) { el.textContent = text; } };
    set('applyProgressCount', complete + ' of ' + sections.length);
    set('applyProgressPct', pct + '%');
    var bar = document.getElementById('applyProgressBar');
    if (bar) { bar.style.width = pct + '%'; }
    set('applyMissing', missing.length ? 'Still to complete: ' + missing.join(', ') + '.' : '');
  }

  // After submission: show the download buttons at the top of the (locked) form.
  fetch('get_submission_status.php').then(function (r) { return r.json(); }).then(function (data) {
    if (!data || !data.is_submitted) { return; }
    var box = document.getElementById('applyDownload');
    if (box) { box.hidden = false; }
    var missing = document.getElementById('applyMissing');
    if (missing) { missing.hidden = true; }
  }).catch(function () {});

  document.addEventListener('input', update);
  document.addEventListener('change', update);
  // Saved values and uploaded files are loaded by the page after it opens, so re-check for a while.
  var checks = 0;
  var timer = setInterval(function () { update(); if (++checks > 20) { clearInterval(timer); } }, 500);
  update();
})();
