/* XJMU Homepage JS (Phase 2 redesign)
   - Smooth anchor scrolling
   - Count-up animation for hero/stat numbers
   - Cross-promo shadow-popup ad (preserved from the previous homepage)
   Carousel initialisers were removed because the redesigned homepage no
   longer uses Bootstrap carousels. */

/* ---- Measure header heights so the overlay nav lines up under the utility bar ---- */
(function () {
    function setHeaderVars() {
        var th = document.querySelector('.top-header'), bb = document.querySelector('.brand-bar');
        if (th) document.body.style.setProperty('--th-h', th.offsetHeight + 'px');
        if (bb && !bb.classList.contains('fixed')) document.body.style.setProperty('--bb-h', bb.offsetHeight + 'px');
    }
    setHeaderVars();
    document.addEventListener('DOMContentLoaded', setHeaderVars);
    window.addEventListener('load', setHeaderVars);
    window.addEventListener('resize', setHeaderVars);
})();

/* ---- Lazy-load hero carousel slides 2..N (defer their images past first paint) ---- */
(function () {
    function loadHeroSlides() {
        document.querySelectorAll('.home-hero__bg[data-bg]').forEach(function (el) {
            el.style.backgroundImage = "url('" + el.getAttribute('data-bg') + "')";
            el.removeAttribute('data-bg');
        });
    }
    // Load them once the page has finished loading (after the visible hero + content).
    if (document.readyState === 'complete') { loadHeroSlides(); }
    else { window.addEventListener('load', loadHeroSlides); }
    // Safety: also load as soon as the carousel is about to move to a slide.
    var hero = document.getElementById('heroCarousel');
    if (hero) hero.addEventListener('slide.bs.carousel', loadHeroSlides, { once: true });
})();

document.addEventListener('DOMContentLoaded', function () {
    /* ---- Smooth scroll for in-page anchors ---- */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var targetId = this.getAttribute('href');
            if (targetId === '#' || targetId.length < 2) return;
            var target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                window.scrollTo({ top: target.offsetTop - 100, behavior: 'smooth' });
            }
        });
    });

    /* ---- Count-up for [data-count] numbers ---- */
    var counters = document.querySelectorAll('[data-count]');
    if (counters.length && 'IntersectionObserver' in window) {
        var run = function (el) {
            var target = parseFloat(el.getAttribute('data-count')) || 0;
            var suffix = el.getAttribute('data-suffix') || '';
            var dur = 1200, start = null;
            var step = function (ts) {
                if (!start) start = ts;
                var p = Math.min((ts - start) / dur, 1);
                el.textContent = Math.floor(p * target).toLocaleString() + suffix;
                if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) { run(entry.target); io.unobserve(entry.target); }
            });
        }, { threshold: 0.4 });
        counters.forEach(function (c) { io.observe(c); });
    }
});

/* ---- Cross-promo popup (Shadow DOM, isolated styles) ---- */
class ShadowPopup extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
        this.shadowRoot.innerHTML = `
            <style>
                .popup { display:none; position:fixed; top:60%; left:3px; transform:translateY(-50%);
                    padding:0 20px 20px; border:none; z-index:1000;
                    font-family:system-ui,-apple-system,'Segoe UI',Roboto,sans-serif; }
                .ad-top { display:flex; justify-content:end; align-items:center; gap:10px; position:relative; }
                .ad-top h3 { color:#e62129; margin:0; }
                .close-btn { font-size:25px!important; border:none; background:none; color:#e62129; cursor:pointer; transition:all .3s ease-in-out; }
                .close-btn:hover { color:#b30e1f; transform:scale(1.3); }
                .logos-container { display:flex; flex-direction:column; align-items:center; text-align:center;
                    padding:0; gap:0; overflow:hidden; margin-bottom:20px; }
                .logos-container:last-child { margin-bottom:0; }
                h4 { text-align:center; margin:0; }
                .red-text { color:#b30e1f; } .green-text { color:#009c42; }
                abbr { display:inline-block; text-decoration:none; }
                a { display:inline-block; width:80px; height:80px; border-radius:50%; overflow:hidden; text-decoration:none; }
                img { width:100%; height:100%; object-fit:cover; }
                h2 { margin:0; padding:10px 0; }
            </style>
            <div class="popup" id="popup">
                <div class="ad-top"><button class="close-btn" id="closePopup">&times;</button></div>
                <div class="logos-container">
                    <h4 class="red-text"><abbr title="Comprehensive Hanyu Instruction Native Academy">Campus <br> China</abbr></h4>
                    <a href="https://campuschina.pk/" target="_blank" rel="noopener"><img src="images/campuschina.webp" alt="Campus China Logo"></a>
                </div>
                <div class="logos-container">
                    <h4 class="red-text"><abbr title="Study in China">CAS</abbr></h4>
                    <a href="https://cas.moe/" target="_blank" rel="noopener"><img src="images/cas-logo.webp" alt="CAS Study in China Logo"></a>
                </div>
            </div>`;
        this.popupElement = this.shadowRoot.getElementById('popup');
        this.closeBtn = this.shadowRoot.getElementById('closePopup');
        this.hidePopup = this.hidePopup.bind(this);
        this.handleEscape = this.handleEscape.bind(this);
    }
    connectedCallback() {
        if (this.closeBtn) this.closeBtn.addEventListener('click', this.hidePopup);
        document.addEventListener('keydown', this.handleEscape);
    }
    disconnectedCallback() {
        if (this.closeBtn) this.closeBtn.removeEventListener('click', this.hidePopup);
        document.removeEventListener('keydown', this.handleEscape);
    }
    showPopup() { if (this.popupElement) this.popupElement.style.display = 'block'; }
    hidePopup() { if (this.popupElement) this.popupElement.style.display = 'none'; }
    handleEscape(e) { if (e.key === 'Escape') this.hidePopup(); }
}
customElements.define('shadow-popup', ShadowPopup);

document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('popup-container');
    if (!container) return;
    var shadowPopup = document.createElement('shadow-popup');
    container.appendChild(shadowPopup);
    setTimeout(function () { shadowPopup.showPopup(); }, 10000);
});
