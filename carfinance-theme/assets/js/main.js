/**
 * CarFinance MSK — Main JavaScript
 *
 * Handles:
 * - Mobile burger menu
 * - FAQ accordion
 * - Modal (lead form)
 * - Smooth scroll
 * - Lazy loading images
 * - Counter animation
 * - Lead form AJAX submission
 */

(function () {
  'use strict';

  // =========================================================================
  // Mobile Burger Menu
  // =========================================================================
  function initBurger() {
    var burger = document.getElementById('cf-burger');
    var nav = document.getElementById('cf-main-nav');
    if (!burger || !nav) return;

    burger.addEventListener('click', function () {
      var isOpen = nav.classList.toggle('cf-nav--open');
      burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target) && !burger.contains(e.target)) {
        nav.classList.remove('cf-nav--open');
        burger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // =========================================================================
  // FAQ Accordion
  // =========================================================================
  function initFaq() {
    var items = document.querySelectorAll('.cf-faq__item');
    items.forEach(function (item) {
      var btn = item.querySelector('.cf-faq__question');
      if (!btn) return;

      btn.addEventListener('click', function () {
        var isOpen = item.classList.contains('cf-faq__item--open');

        // Close all others in same list
        var parent = item.closest('.cf-faq__list');
        if (parent) {
          parent.querySelectorAll('.cf-faq__item--open').forEach(function (openItem) {
            openItem.classList.remove('cf-faq__item--open');
            var openBtn = openItem.querySelector('.cf-faq__question');
            if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
          });
        }

        if (!isOpen) {
          item.classList.add('cf-faq__item--open');
          btn.setAttribute('aria-expanded', 'true');
        }
      });
    });
  }

  // =========================================================================
  // Modal
  // =========================================================================
  function initModal() {
    // Open triggers
    document.querySelectorAll('[data-modal="lead"]').forEach(function (trigger) {
      trigger.addEventListener('click', function (e) {
        e.preventDefault();
        var modal = document.getElementById('cf-lead-modal');
        if (modal) {
          modal.style.display = 'flex';
          document.body.style.overflow = 'hidden';
          var firstInput = modal.querySelector('input');
          if (firstInput) firstInput.focus();
        }
      });
    });

    // Close triggers
    document.querySelectorAll('[data-modal-close]').forEach(function (closeBtn) {
      closeBtn.addEventListener('click', function () {
        var modal = closeBtn.closest('.cf-modal');
        if (modal) {
          modal.style.display = 'none';
          document.body.style.overflow = '';
        }
      });
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        var openModal = document.querySelector('.cf-modal[style*="flex"]');
        if (openModal) {
          openModal.style.display = 'none';
          document.body.style.overflow = '';
        }
      }
    });
  }

  // =========================================================================
  // Counter Animation (Intersection Observer)
  // =========================================================================
  function initCounters() {
    var counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(function (counter) {
      observer.observe(counter);
    });
  }

  function animateCounter(el) {
    var target = parseInt(el.getAttribute('data-counter')) || 0;
    var suffix = el.textContent.replace(/[\d\s]/g, '').trim();
    var duration = 1500;
    var start = 0;
    var startTime = null;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var current = Math.floor(progress * target);
      el.textContent = current.toLocaleString('ru-RU') + (suffix ? ' ' + suffix : '');
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.textContent = target.toLocaleString('ru-RU') + (suffix ? ' ' + suffix : '');
      }
    }

    requestAnimationFrame(step);
  }

  // =========================================================================
  // Smooth Scroll for Anchor Links
  // =========================================================================
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
      link.addEventListener('click', function (e) {
        var href = link.getAttribute('href');
        if (href === '#' || href.startsWith('#cf-lead')) return; // skip modal triggers

        var target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          var offset = document.querySelector('.cf-header') ?
                       document.querySelector('.cf-header').offsetHeight + 20 : 80;
          var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
          window.scrollTo({ top: top, behavior: 'smooth' });
        }
      });
    });
  }

  // =========================================================================
  // Lead Form Submission
  // =========================================================================
  function initLeadForms() {
    document.querySelectorAll('[data-lead-form]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        var btn = form.querySelector('button[type="submit"]');
        var originalText = btn ? btn.textContent : '';
        if (btn) {
          btn.textContent = 'Отправка...';
          btn.disabled = true;
        }

        // Simulate form submission (replace with real AJAX in production)
        setTimeout(function () {
          // Clear form
          form.reset();

          // Show success
          if (btn) {
            btn.textContent = 'Заявка отправлена!';
            btn.style.background = 'var(--cf-accent)';
          }

          // Reset button after 3 seconds
          setTimeout(function () {
            if (btn) {
              btn.textContent = originalText;
              btn.disabled = false;
              btn.style.background = '';
            }
            // Close modal if inside one
            var modal = form.closest('.cf-modal');
            if (modal) {
              modal.style.display = 'none';
              document.body.style.overflow = '';
            }
          }, 3000);
        }, 1000);
      });
    });
  }

  // =========================================================================
  // Modal CSS (injected since it's needed for JS functionality)
  // =========================================================================
  function injectModalStyles() {
    if (document.getElementById('cf-modal-styles')) return;

    var style = document.createElement('style');
    style.id = 'cf-modal-styles';
    style.textContent = [
      '.cf-modal { display:none; position:fixed; top:0; left:0; right:0; bottom:0;',
      '  z-index:10000; align-items:center; justify-content:center; }',
      '.cf-modal__overlay { position:absolute; top:0; left:0; right:0; bottom:0;',
      '  background:rgba(0,0,0,0.6); }',
      '.cf-modal__content { position:relative; background:#fff; border-radius:12px;',
      '  padding:40px; max-width:480px; width:90%; max-height:90vh; overflow-y:auto; z-index:1; }',
      '.cf-modal__close { position:absolute; top:16px; right:16px; background:none;',
      '  border:none; font-size:1.75rem; cursor:pointer; color:#6b7280; line-height:1; }',
      '.cf-modal__close:hover { color:#111827; }',
      '.cf-modal .cf-input { width:100%; padding:12px 16px; border:1px solid #d1d5db;',
      '  border-radius:8px; font-size:1rem; margin-bottom:12px; font-family:inherit; }',
      '.cf-modal .cf-input:focus { outline:none; border-color:#1a56db;',
      '  box-shadow:0 0 0 3px #e8eefb; }',
    ].join('\n');
    document.head.appendChild(style);
  }

  // =========================================================================
  // Init Everything
  // =========================================================================
  document.addEventListener('DOMContentLoaded', function () {
    injectModalStyles();
    initBurger();
    initFaq();
    initModal();
    initCounters();
    initSmoothScroll();
    initLeadForms();
  });

})();
