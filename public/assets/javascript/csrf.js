/*
 * Centralized CSRF token injector (audit #19).
 * Reads the per-session token from <meta name="csrf-token"> and attaches it as
 * an X-CSRF-Token header to every same-origin state-changing fetch()/XHR.
 * Must load before any script that issues AJAX requests.
 */
(function () {
  function csrfToken() {
    var m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute('content') : '';
  }
  var TOKEN = csrfToken();
  if (!TOKEN) return;

  function isUnsafe(method) {
    method = (method || 'GET').toUpperCase();
    return method !== 'GET' && method !== 'HEAD' && method !== 'OPTIONS' && method !== 'TRACE';
  }
  function sameOrigin(url) {
    if (!url) return true;
    if (url.indexOf('http://') !== 0 && url.indexOf('https://') !== 0) return true; // relative
    return url.indexOf(window.location.origin) === 0;
  }

  // --- Wrap fetch ---
  if (window.fetch) {
    var origFetch = window.fetch;
    window.fetch = function (input, init) {
      init = init || {};
      var url = (typeof input === 'string') ? input : (input && input.url) || '';
      var method = init.method || (typeof input !== 'string' && input && input.method) || 'GET';
      if (isUnsafe(method) && sameOrigin(url)) {
        var headers = new Headers(init.headers || (typeof input !== 'string' && input && input.headers) || {});
        if (!headers.has('X-CSRF-Token')) headers.set('X-CSRF-Token', TOKEN);
        init.headers = headers;
      }
      return origFetch.call(this, input, init);
    };
  }

  // --- Wrap XMLHttpRequest ---
  var origOpen = XMLHttpRequest.prototype.open;
  var origSend = XMLHttpRequest.prototype.send;
  XMLHttpRequest.prototype.open = function (method, url) {
    this.__csrfUnsafe = isUnsafe(method) && sameOrigin(url);
    return origOpen.apply(this, arguments);
  };
  XMLHttpRequest.prototype.send = function (body) {
    if (this.__csrfUnsafe) {
      try { this.setRequestHeader('X-CSRF-Token', TOKEN); } catch (e) {}
    }
    return origSend.apply(this, arguments);
  };
})();
