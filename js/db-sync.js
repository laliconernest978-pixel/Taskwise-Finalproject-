/*
 * TaskWise MySQL bridge for the existing presentation prototype.
 * Shared data keys are loaded from /api/storage.php and writes are persisted
 * to MySQL. Login/session display keys remain local to this browser.
 * This is a local classroom-prototype bridge, not a production auth system.
 */
(() => {
  const API_URL = './api/storage.php';
  const SHARED_KEYS = new Set(['taskwiseUsers', 'taskwiseAssignments', 'taskwiseFiles']);
  const cache = new Map();
  let connected = false;

  function request(method, payload) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, API_URL, false); // Keep compatibility with the prototype's synchronous localStorage calls.
    xhr.setRequestHeader('Accept', 'application/json');
    if (method === 'POST') xhr.setRequestHeader('Content-Type', 'application/json');
    try {
      xhr.send(method === 'POST' ? JSON.stringify(payload) : null);
      const result = xhr.responseText ? JSON.parse(xhr.responseText) : {};
      if (xhr.status < 200 || xhr.status >= 300 || !result.ok) {
        throw new Error(result.error || `HTTP ${xhr.status}`);
      }
      return result;
    } catch (error) {
      console.error('TaskWise MySQL connection error:', error);
      return null;
    }
  }

  const initial = request('GET');
  if (initial && initial.data) {
    Object.entries(initial.data).forEach(([key, value]) => {
      if (SHARED_KEYS.has(key)) cache.set(key, value);
    });
    connected = true;
  }

  const originalGet = Storage.prototype.getItem;
  const originalSet = Storage.prototype.setItem;
  const originalRemove = Storage.prototype.removeItem;
  const originalClear = Storage.prototype.clear;

  Storage.prototype.getItem = function(key) {
    if (this === window.localStorage && SHARED_KEYS.has(String(key))) {
      return cache.has(String(key)) ? cache.get(String(key)) : null;
    }
    return originalGet.call(this, key);
  };

  Storage.prototype.setItem = function(key, value) {
    if (this === window.localStorage && SHARED_KEYS.has(String(key))) {
      const stringValue = String(value);
      const result = request('POST', { action: 'save', key: String(key), value: stringValue });
      if (result) {
        cache.set(String(key), stringValue);
        connected = true;
      } else {
        console.error('TaskWise: data was not saved to MySQL.');
      }
      return;
    }
    return originalSet.call(this, key, value);
  };

  Storage.prototype.removeItem = function(key) {
    if (this === window.localStorage && SHARED_KEYS.has(String(key))) {
      const result = request('POST', { action: 'remove', key: String(key) });
      if (result) cache.delete(String(key));
      return;
    }
    return originalRemove.call(this, key);
  };

  Storage.prototype.clear = function() {
    if (this === window.localStorage) {
      const result = request('POST', { action: 'clear' });
      if (result) cache.clear();
      // Clear local-only profile/session display values too.
      const keys = [];
      for (let i = 0; i < this.length; i++) keys.push(this.key(i));
      keys.forEach(key => { if (key) originalRemove.call(this, key); });
      return;
    }
    return originalClear.call(this);
  };

  window.addEventListener('DOMContentLoaded', () => {
    const note = document.createElement('div');
    note.id = 'taskwiseDbStatus';
    note.textContent = connected
      ? '✓ Connected to TaskWise MySQL database'
      : '⚠ MySQL not connected — start Apache/MySQL and check config/database.php';
    note.style.cssText = `position:fixed;right:14px;bottom:12px;z-index:99999;padding:9px 13px;border-radius:8px;font:12px Arial,sans-serif;box-shadow:0 2px 10px #0002;color:#fff;background:${connected ? '#15803d' : '#b45309'}`;
    document.body.appendChild(note);
    if (connected) setTimeout(() => note.remove(), 4500);
  });
})();
