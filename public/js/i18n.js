/*!
 * XJMU i18n — lightweight multilingual engine
 * Attributes used in HTML:
 *   data-i18n="key"              → el.textContent = translation
 *   data-i18n-html="key"         → el.innerHTML   = translation (use sparingly)
 *   data-i18n-placeholder="key"  → el.placeholder = translation
 *   data-i18n-title="key"        → el.title       = translation
 *
 * Switcher elements:
 *   data-lang-item="en|zh|fr"    → clickable item (dropdown li/a)
 *   data-lang-btn="en|zh|fr"     → clickable button
 *   data-lang-toggle             → the visible label that shows current language name
 *
 * Language preference persisted in localStorage under 'xjmu_lang'.
 */
(function () {
  'use strict';

  var STORAGE_KEY  = 'xjmu_lang';
  var DEFAULT_LANG = 'en';
  var SUPPORTED    = ['en', 'zh', 'fr'];
  var LABELS       = { en: 'English', zh: '中文', fr: 'Français' };

  var currentLang = DEFAULT_LANG;
  var cache = {};   // { en: {…}, zh: {…}, fr: {…} }

  /* ── Resolve dot-notation key ─────────────────── */
  function resolve(obj, dotKey) {
    if (!obj || !dotKey) return undefined;
    return dotKey.split('.').reduce(function (o, k) {
      return (o !== null && o !== undefined) ? o[k] : undefined;
    }, obj);
  }

  /* ── Determine base URL for lang/ folder ─────── */
  function getBase() {
    var meta = document.querySelector('meta[name="xjmu-base"]');
    if (meta && meta.content) return meta.content.replace(/\/$/, '');
    // Fallback: root of the current origin
    return window.location.origin;
  }

  /* ── Fetch and cache a translation file ──────── */
  function load(lang) {
    if (cache[lang]) {
      return Promise.resolve(cache[lang]);
    }
    return fetch(getBase() + '/lang/' + lang + '.json?v=1')
      .then(function (res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
      })
      .then(function (data) {
        cache[lang] = data;
        return data;
      })
      .catch(function (e) {
        console.warn('[XJMU i18n] Could not load "' + lang + '": ', e);
        return null;
      });
  }

  /* ── Apply dict to every marked DOM element ──── */
  function applyDict(dict) {
    if (!dict) return;

    // Text content
    document.querySelectorAll('[data-i18n]').forEach(function (el) {
      var v = resolve(dict, el.getAttribute('data-i18n'));
      if (v !== undefined) el.textContent = v;
    });

    // Inner HTML (for strings that contain safe markup like <strong>)
    document.querySelectorAll('[data-i18n-html]').forEach(function (el) {
      var v = resolve(dict, el.getAttribute('data-i18n-html'));
      if (v !== undefined) el.innerHTML = v;
    });

    // Input placeholders
    document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
      var v = resolve(dict, el.getAttribute('data-i18n-placeholder'));
      if (v !== undefined) el.setAttribute('placeholder', v);
    });

    // Title attributes
    document.querySelectorAll('[data-i18n-title]').forEach(function (el) {
      var v = resolve(dict, el.getAttribute('data-i18n-title'));
      if (v !== undefined) el.setAttribute('title', v);
    });

    // Update <html lang="…">
    document.documentElement.lang = currentLang === 'zh' ? 'zh-CN' : currentLang;
  }

  /* ── Update switcher UI to reflect active lang ─ */
  function updateSwitcherUI(lang) {
    // Label text on toggle buttons
    document.querySelectorAll('[data-lang-toggle]').forEach(function (el) {
      el.textContent = LABELS[lang] || LABELS[DEFAULT_LANG];
    });

    // Active class on dropdown items and standalone buttons
    ['data-lang-item', 'data-lang-btn'].forEach(function (attr) {
      document.querySelectorAll('[' + attr + ']').forEach(function (el) {
        var isActive = el.getAttribute(attr) === lang;
        el.classList.toggle('active', isActive);
        if (el.tagName === 'A') {
          // Bootstrap dropdown item aria
          el.setAttribute('aria-selected', isActive ? 'true' : 'false');
        }
      });
    });
  }

  /* ── Public: switch language ─────────────────── */
  function setLanguage(lang) {
    if (SUPPORTED.indexOf(lang) === -1) lang = DEFAULT_LANG;
    currentLang = lang;
    localStorage.setItem(STORAGE_KEY, lang);
    return load(lang).then(function (dict) {
      applyDict(dict);
      updateSwitcherUI(lang);
    });
  }

  /* ── Bind all switcher elements ──────────────── */
  function bindSwitchers() {
    ['data-lang-item', 'data-lang-btn'].forEach(function (attr) {
      document.querySelectorAll('[' + attr + ']').forEach(function (el) {
        el.addEventListener('click', function (e) {
          e.preventDefault();
          setLanguage(this.getAttribute(attr));
        });
      });
    });
  }

  /* ── Init ────────────────────────────────────── */
  function init() {
    var saved = localStorage.getItem(STORAGE_KEY);
    var lang  = (SUPPORTED.indexOf(saved) !== -1) ? saved : DEFAULT_LANG;
    currentLang = lang;

    bindSwitchers();
    updateSwitcherUI(lang);

    // Only fetch non-English (English is already in the HTML as default)
    if (lang !== DEFAULT_LANG) {
      load(lang).then(function (dict) {
        applyDict(dict);
      });
    }
  }

  /* ── Public API ──────────────────────────────── */
  window.XJMU_i18n = {
    setLanguage: setLanguage,
    getCurrentLang: function () { return currentLang; },
    t: function (key) {
      var dict = cache[currentLang] || cache[DEFAULT_LANG];
      if (!dict) return key;
      var v = resolve(dict, key);
      return v !== undefined ? v : key;
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
