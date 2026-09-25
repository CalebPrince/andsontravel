(function () {
  'use strict';

  // Mobile drawer menu
  var menuBtn = document.getElementById('mobile-menu-btn');
  var menuCloseBtn = document.getElementById('mobile-menu-close');
  var menu = document.getElementById('mobile-menu');
  var backdrop = document.getElementById('mobile-menu-backdrop');
  var drawerCloseTimer = null;

  function openDrawer() {
    if (!menu || !backdrop) return;
    clearTimeout(drawerCloseTimer);
    menu.removeAttribute('inert');
    menu.setAttribute('aria-hidden', 'false');
    backdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    requestAnimationFrame(function () {
      menu.classList.remove('translate-x-full');
      if (menuCloseBtn) menuCloseBtn.focus();
    });
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
  }

  function closeDrawer() {
    if (!menu || !backdrop) return;
    menu.classList.add('translate-x-full');
    menu.setAttribute('aria-hidden', 'true');
    menu.setAttribute('inert', '');
    document.body.classList.remove('overflow-hidden');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
    clearTimeout(drawerCloseTimer);
    drawerCloseTimer = setTimeout(function () {
      backdrop.classList.add('hidden');
    }, 300);
    if (menuBtn) menuBtn.focus();
  }

  if (menuBtn && menu && backdrop) {
    menu.setAttribute('aria-hidden', 'true');
    menu.setAttribute('inert', '');
    menuBtn.addEventListener('click', openDrawer);
    if (menuCloseBtn) menuCloseBtn.addEventListener('click', closeDrawer);
    backdrop.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeDrawer();
    });
  }

  // Admin notification bell dropdown, with a live feed so the count and list
  // stay current (new bookings/enquiries appear without a page reload).
  var bellBtn = document.getElementById('notif-bell-btn');
  var bellDropdown = document.getElementById('notif-dropdown');
  var notifList = document.getElementById('notif-list');
  var notifCount = document.getElementById('notif-count');
  var notifEmpty = document.getElementById('notif-empty');
  var notifMarkAll = document.getElementById('notif-mark-all');

  function notifCountNow() {
    return notifCount ? (parseInt(notifCount.dataset.count, 10) || 0) : 0;
  }

  function setNotifCount(n) {
    if (!notifCount) return;
    n = Math.max(0, n);
    notifCount.dataset.count = String(n);
    notifCount.classList.toggle('hidden', n === 0);
    notifCount.textContent = n > 9 ? '9+' : String(n);
  }

  function buildNotifItem(item) {
    var a = document.createElement('a');
    a.href = item.url;
    a.className = 'flex items-start gap-3 border-b border-navy-900/5 px-4 py-3 last:border-0 hover:bg-navy-900/[0.02]';
    a.dataset.notifType = item.type;
    a.dataset.notifId = String(item.id);

    var dot = document.createElement('span');
    dot.className = 'mt-1.5 h-2 w-2 shrink-0 rounded-full bg-brand-600';

    var body = document.createElement('span');
    body.className = 'min-w-0';

    var title = document.createElement('span');
    title.className = 'block truncate text-sm font-semibold text-navy-900';
    title.textContent = item.title;

    var sub = document.createElement('span');
    sub.className = 'block truncate text-xs text-navy-900/50';
    sub.textContent = item.subtitle + ' · ' + item.time_ago;

    body.appendChild(title);
    body.appendChild(sub);
    a.appendChild(dot);
    a.appendChild(body);
    return a;
  }

  function renderNotifFeed(data) {
    if (notifCount) {
      notifCount.dataset.count = String(data.count);
      notifCount.classList.toggle('hidden', data.count === 0);
      notifCount.textContent = data.count > 9 ? '9+' : String(data.count);
    }
    if (notifMarkAll) notifMarkAll.classList.toggle('hidden', data.count === 0);
    if (!notifList) return;
    notifList.querySelectorAll('a[data-notif-id]').forEach(function (el) { el.remove(); });
    if (notifEmpty) notifEmpty.classList.toggle('hidden', data.items.length > 0);
    data.items.forEach(function (item) { notifList.appendChild(buildNotifItem(item)); });
  }

  var notifBusy = false;
  function fetchNotifFeed() {
    if (notifBusy || !bellDropdown) return;
    notifBusy = true;
    fetch('/admin/notifications.php?action=feed', {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'fetch' }
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data && typeof data.count === 'number' && Array.isArray(data.items)) {
          renderNotifFeed(data);
        }
      })
      .catch(function () {})
      .then(function () { notifBusy = false; });
  }

  if (bellBtn && bellDropdown) {
    bellBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var opening = bellDropdown.classList.contains('hidden');
      bellDropdown.classList.toggle('hidden');
      bellBtn.setAttribute('aria-expanded', String(opening));
      if (opening) fetchNotifFeed();
    });
    document.addEventListener('click', function (e) {
      if (!bellDropdown.classList.contains('hidden') && !bellDropdown.contains(e.target) && e.target !== bellBtn) {
        bellDropdown.classList.add('hidden');
        bellBtn.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        bellDropdown.classList.add('hidden');
        bellBtn.setAttribute('aria-expanded', 'false');
      }
    });

    // Clicking a notification marks just that item read and decrements the badge.
    // Delegated on the dropdown so re-rendered items keep working.
    bellDropdown.addEventListener('click', function (e) {
      var link = e.target.closest ? e.target.closest('a[data-notif-id]') : null;
      if (!link || !bellDropdown.contains(link)) return;
      e.preventDefault();

      var navigated = false;
      function go() {
        if (navigated) return;
        navigated = true;
        window.location.href = link.href;
      }

      var body = new URLSearchParams();
      body.append('csrf_token', bellDropdown.dataset.csrf || '');
      body.append('action', 'mark_read');
      body.append('type', link.dataset.notifType);
      body.append('id', link.dataset.notifId);

      fetch('/admin/notifications.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'fetch' },
        body: body.toString(),
        credentials: 'same-origin'
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data && data.ok) {
            link.remove();
            setNotifCount(notifCountNow() - 1);
            if (notifMarkAll) notifMarkAll.classList.toggle('hidden', notifCountNow() === 0);
            if (notifEmpty && notifList && notifList.querySelectorAll('a[data-notif-id]').length === 0) {
              notifEmpty.classList.remove('hidden');
            }
          }
          go();
        })
        .catch(go);
      setTimeout(go, 3000);
    });

    // Refresh the moment the tab becomes visible/focused again. Browsers
    // throttle timers in hidden tabs, so the 30s poll can't be relied on
    // there; this makes the bell current the instant the admin returns.
    document.addEventListener('visibilitychange', function () {
      if (!document.hidden) fetchNotifFeed();
    });
    window.addEventListener('focus', fetchNotifFeed);

    // "Mark all as read" updates the dropdown live instead of reloading the page.
    if (notifMarkAll) {
      notifMarkAll.addEventListener('submit', function (e) {
        e.preventDefault();
        var body = new URLSearchParams();
        body.append('csrf_token', bellDropdown.dataset.csrf || '');
        body.append('action', 'mark_all_read');
        fetch('/admin/notifications.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'fetch' },
          body: body.toString(),
          credentials: 'same-origin'
        })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (data && data.ok) renderNotifFeed({ count: 0, items: [] });
          })
          .catch(function () {
            // Plain form post (full reload) as a fallback if the fetch call fails.
            notifMarkAll.submit();
          });
      });
    }

    fetchNotifFeed();
    setInterval(fetchNotifFeed, 30000);
  }

  // Rotating hero headline (progressive enhancement: first slide is fully
  // usable without JS since it's rendered server-side).
  var slides = document.querySelectorAll('[data-hero-slide]');
  if (slides.length > 1) {
    var current = 0;
    slides.forEach(function (el, i) { el.classList.toggle('hidden', i !== 0); });
    setInterval(function () {
      slides[current].classList.add('hidden');
      current = (current + 1) % slides.length;
      slides[current].classList.remove('hidden');
    }, 5000);
  }

  // Disable submit buttons on form submit to prevent duplicate applications.
  document.querySelectorAll('form[data-guard]').forEach(function (form) {
    form.addEventListener('submit', function () {
      var btn = form.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        btn.dataset.originalText = btn.dataset.originalText || btn.innerHTML;
        btn.innerHTML = 'Sending&hellip;';
      }
    });
  });
})();
