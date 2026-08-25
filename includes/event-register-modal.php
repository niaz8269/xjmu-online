<!-- Event/Webinar registration modal (common for all events) - vanilla JS, no Bootstrap dependency -->
<div id="eventRegisterModal" class="event-register-modal" aria-hidden="true" style="display:none;">
    <div class="event-register-modal-backdrop"></div>
    <div class="event-register-modal-dialog">
        <div class="event-register-modal-content">
            <div class="event-register-modal-header">
                <h5 class="event-register-modal-title">
                    Xinjiang Medical University<br>
                    <span class="event-register-modal-subtitle">MBBS (English Taught) March 2026 intake</span>
                </h5>
                <button type="button" class="event-register-modal-close" aria-label="Close">&times;</button>
            </div>
            <div class="event-register-modal-body">
                <div id="eventRegisterEventInfo" class="event-register-event-info">
                    <strong>Webinar / Event:</strong><br>
                    <span id="eventRegisterTitle">—</span><br>
                    <span id="eventRegisterDate"></span><br>
                    <span id="eventRegisterTime"></span>
                </div>
                <p class="event-register-now">Register Now!</p>

                <div id="eventRegisterFormWrap">
                    <form id="eventRegisterForm">
                        <?php if (function_exists('csrf_field')) echo csrf_field(); ?>
                        <input type="hidden" name="event_title" id="eventRegisterInputTitle">
                        <input type="hidden" name="event_date" id="eventRegisterInputDate">
                        <input type="hidden" name="event_time" id="eventRegisterInputTime">
                        <div class="mb-3">
                            <label for="eventRegName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eventRegName" name="name" required placeholder="Your full name">
                        </div>
                        <div class="mb-3">
                            <label for="eventRegNationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="eventRegNationality" name="nationality" placeholder="e.g. Pakistan, India">
                        </div>
                        <div class="mb-3">
                            <label for="eventRegEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="eventRegEmail" name="email" required placeholder="your@email.com">
                        </div>
                        <div class="mb-3">
                            <label for="eventRegWhatsApp" class="form-label">WhatsApp</label>
                            <input type="text" class="form-control" id="eventRegWhatsApp" name="whatsapp" placeholder="+92 300 1234567">
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Submit</button>
                    </form>
                </div>
                <div id="eventRegisterSuccess" class="event-register-success" style="display:none;">
                    <p class="text-success fw-bold mb-0">Thank you for registering! We will contact you shortly.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.event-register-modal { position: fixed; inset: 0; z-index: 9999; overflow: auto; }
.event-register-modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.5); }
.event-register-modal-dialog { position: relative; min-height: 100%; display: flex; align-items: center; justify-content: center; padding: 20px; }
.event-register-modal-content { background: #fff; border-radius: 8px; max-width: 500px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
.event-register-modal-header { display: flex; align-items: flex-start; justify-content: space-between; padding: 20px 20px 0; }
.event-register-modal-title { text-align: center; flex: 1; font-size: 1.25rem; margin: 0; }
.event-register-modal-subtitle { font-size: 0.9rem; color: #6c757d; font-weight: normal; }
.event-register-modal-close { background: none; border: none; font-size: 28px; line-height: 1; cursor: pointer; color: #666; padding: 0 4px; }
.event-register-modal-close:hover { color: #000; }
.event-register-modal-body { padding: 16px 20px 24px; }
.event-register-event-info { background: #f8f9fa; padding: 10px 12px; border-radius: 6px; font-size: 0.9rem; margin-bottom: 12px; }
.event-register-now { text-align: center; font-weight: bold; margin-bottom: 16px; }
</style>
<script>
(function() {
    function runWhenReady(fn) {
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn); else fn();
    }
    runWhenReady(function() {
        var modalEl = document.getElementById('eventRegisterModal');
        if (!modalEl) return;
        var backdrop = modalEl.querySelector('.event-register-modal-backdrop');
        var formWrap = document.getElementById('eventRegisterFormWrap');
        var successEl = document.getElementById('eventRegisterSuccess');
        var form = document.getElementById('eventRegisterForm');
        var titleEl = document.getElementById('eventRegisterTitle');
        var dateEl = document.getElementById('eventRegisterDate');
        var timeEl = document.getElementById('eventRegisterTime');
        var inputTitle = document.getElementById('eventRegisterInputTitle');
        var inputDate = document.getElementById('eventRegisterInputDate');
        var inputTime = document.getElementById('eventRegisterInputTime');

        function showModal() { modalEl.style.display = 'block'; modalEl.setAttribute('aria-hidden', 'false'); document.body.style.overflow = 'hidden'; }
        function hideModal() { modalEl.style.display = 'none'; modalEl.setAttribute('aria-hidden', 'true'); document.body.style.overflow = ''; }

        function openModal(options) {
            options = options || {};
            var title = options.title || 'Event';
            var date = options.date || '';
            var time = options.time || '';
            if (titleEl) titleEl.textContent = title;
            if (dateEl) dateEl.textContent = date ? 'Date: ' + date : '';
            if (timeEl) { timeEl.textContent = time ? 'Time: ' + time + ' (Beijing Time)' : ''; timeEl.style.display = time ? '' : 'none'; }
            if (inputTitle) inputTitle.value = title;
            if (inputDate) inputDate.value = date;
            if (inputTime) inputTime.value = time;
            if (formWrap) formWrap.style.display = 'block';
            if (successEl) successEl.style.display = 'none';
            if (form) { form.reset(); form.querySelector('[name="event_title"]').value = title; form.querySelector('[name="event_date"]').value = date; form.querySelector('[name="event_time"]').value = time; }
            showModal();
        }

        modalEl.querySelector('.event-register-modal-close').addEventListener('click', hideModal);
        if (backdrop) backdrop.addEventListener('click', hideModal);

        document.querySelectorAll('.event-register-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal({
                    title: btn.getAttribute('data-event-title') || 'Xinjiang Medical University – MBBS March 2026 intake',
                    date: btn.getAttribute('data-event-date') || '15 February 2026',
                    time: btn.getAttribute('data-event-time') || '2PM to 5PM'
                });
            });
        });

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var submitBtn = form.querySelector('button[type="submit"]');
                var origText = submitBtn ? submitBtn.textContent : '';
                if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Sending…'; }
                var fd = new window.FormData(form);
                fetch('<?php echo (function_exists("get_absolute_url") ? get_absolute_url("register-event.php") : "register-event.php"); ?>', {
                    method: 'POST',
                    body: fd
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (formWrap) formWrap.style.display = 'none';
                    if (successEl) {
                        successEl.style.display = 'block';
                        var p = successEl.querySelector('p');
                        if (p) { p.textContent = data.message || (data.success ? 'Thank you for registering!' : 'Something went wrong.'); p.className = data.success ? 'text-success fw-bold mb-0' : 'text-danger fw-bold mb-0'; }
                    }
                }).catch(function() {
                    if (successEl) { successEl.style.display = 'block'; var p = successEl.querySelector('p'); if (p) { p.textContent = 'Network error. Please try again.'; p.className = 'text-danger fw-bold mb-0'; } }
                }).finally(function() {
                    if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = origText; }
                });
            });
        }
    });
})();
</script>
