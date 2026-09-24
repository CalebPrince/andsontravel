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

  // Admin notification bell dropdown
  var bellBtn = document.getElementById('notif-bell-btn');
  var bellDropdown = document.getElementById('notif-dropdown');
  if (bellBtn && bellDropdown) {
    bellBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var isOpen = !bellDropdown.classList.contains('hidden');
      bellDropdown.classList.toggle('hidden');
      bellBtn.setAttribute('aria-expanded', String(!isOpen));
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
