/**
 * Kovka Theme — main.js
 * Burger, modals, FAQ, counters, lead forms, AOS, quick calc
 */
'use strict';

(function () {

  /* ============================================================
     DOM READY
     ============================================================ */
  document.addEventListener('DOMContentLoaded', function () {
    kvBurger();
    kvStickyHeader();
    kvModal();
    kvFaq();
    kvCounters();
    kvLeadForms();
    kvAos();
    kvToTop();
    kvQuickCalc();
    kvProductModalFill();
  });

  /* ============================================================
     BURGER / MOBILE MENU
     ============================================================ */
  function kvBurger() {
    var btn  = document.getElementById('kv-burger');
    var menu = document.getElementById('kv-mobile-menu');
    if (!btn || !menu) return;

    btn.addEventListener('click', function () {
      var open = menu.classList.toggle('open');
      btn.classList.toggle('open', open);
      btn.setAttribute('aria-expanded', String(open));
      document.body.style.overflow = open ? 'hidden' : '';
    });

    // Закрыть при клике вне меню
    document.addEventListener('click', function (e) {
      if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.remove('open');
        btn.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });
  }

  /* ============================================================
     STICKY HEADER
     ============================================================ */
  function kvStickyHeader() {
    var header = document.getElementById('kv-header');
    if (!header) return;
    window.addEventListener('scroll', function () {
      header.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive: true });
  }

  /* ============================================================
     MODAL
     ============================================================ */
  function kvModal() {
    var modal   = document.getElementById('kv-modal');
    if (!modal) return;

    function openModal() {
      modal.classList.add('open');
      document.body.style.overflow = 'hidden';
      var firstInput = modal.querySelector('input');
      if (firstInput) setTimeout(function () { firstInput.focus(); }, 100);
    }

    function closeModal() {
      modal.classList.remove('open');
      document.body.style.overflow = '';
    }

    // Открытие
    document.querySelectorAll('.kv-modal-open').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        openModal();
      });
    });

    // Закрытие
    document.querySelectorAll('.kv-modal-close').forEach(function (el) {
      el.addEventListener('click', closeModal);
    });

    // ESC
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeModal();
    });
  }

  /* ============================================================
     FAQ ACCORDION
     ============================================================ */
  function kvFaq() {
    document.querySelectorAll('.kv-faq-question').forEach(function (q) {
      q.addEventListener('click', function () {
        var item = this.parentElement;
        var isOpen = item.classList.contains('open');

        // Закрыть все в том же контейнере
        var container = item.parentElement;
        container.querySelectorAll('.kv-faq-item.open').forEach(function (i) {
          i.classList.remove('open');
          i.querySelector('.kv-faq-question').setAttribute('aria-expanded', 'false');
        });

        if (!isOpen) {
          item.classList.add('open');
          this.setAttribute('aria-expanded', 'true');
        }
      });

      // Keyboard
      q.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.click();
        }
      });
    });
  }

  /* ============================================================
     ANIMATE COUNTERS
     ============================================================ */
  function kvCounters() {
    var counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });

    counters.forEach(function (el) { observer.observe(el); });

    function animateCounter(el) {
      var target   = parseInt(el.dataset.counter, 10);
      var duration = 1800;
      var start    = Date.now();
      var suffix   = el.dataset.suffix || '';

      function update() {
        var elapsed  = Date.now() - start;
        var progress = Math.min(elapsed / duration, 1);
        var ease     = 1 - Math.pow(1 - progress, 3);
        var current  = Math.round(ease * target);

        el.textContent = current.toLocaleString('ru') + suffix;

        if (progress < 1) requestAnimationFrame(update);
        else el.textContent = target.toLocaleString('ru') + suffix;
      }

      requestAnimationFrame(update);
    }
  }

  /* ============================================================
     LEAD FORMS (AJAX)
     ============================================================ */
  function kvLeadForms() {
    document.querySelectorAll('.kv-lead-form').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        var btn    = form.querySelector('button[type="submit"]');
        var result = form.querySelector('.kv-form-result');
        var data   = new FormData(form);

        data.append('action', 'kv_lead');
        data.append('nonce', (kvAjax && kvAjax.nonce) || '');
        data.append('source', form.dataset.source || 'site');

        if (btn) {
          btn.disabled    = true;
          btn.textContent = 'Отправляем…';
        }

        fetch((kvAjax && kvAjax.url) || '/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: data,
        })
          .then(function (r) { return r.json(); })
          .then(function (resp) {
            if (result) {
              result.style.display = 'block';
              result.style.background = resp.success ? '#d1fae5' : '#fee2e2';
              result.style.color = resp.success ? '#065f46' : '#991b1b';
              result.textContent = resp.data ? resp.data.msg : 'Произошла ошибка';
            }
            if (resp.success) {
              form.reset();
              // GTM event
              if (window.dataLayer) window.dataLayer.push({ event: 'lead_form', source: form.dataset.source });
            }
          })
          .catch(function () {
            if (result) {
              result.style.display = 'block';
              result.style.background = '#fee2e2';
              result.style.color = '#991b1b';
              result.textContent = 'Ошибка сети. Позвоните нам по телефону.';
            }
          })
          .finally(function () {
            if (btn) {
              btn.disabled = false;
              btn.textContent = btn.dataset.label || 'Отправить';
            }
          });
      });

      // Сохраняем label кнопки
      var btn = form.querySelector('button[type="submit"]');
      if (btn) btn.dataset.label = btn.textContent;
    });

    // Маска телефона
    document.querySelectorAll('input[type="tel"]').forEach(function (input) {
      input.addEventListener('input', function () {
        var v = this.value.replace(/\D/g, '');
        if (v.startsWith('8')) v = '7' + v.slice(1);
        if (!v.startsWith('7') && v.length > 0) v = '7' + v;
        v = v.slice(0, 11);

        var parts = [];
        if (v.length > 0) parts.push('+' + v.slice(0, 1));
        if (v.length > 1) parts.push(' (' + v.slice(1, 4));
        if (v.length > 4) parts.push(') ' + v.slice(4, 7));
        if (v.length > 7) parts.push('-' + v.slice(7, 9));
        if (v.length > 9) parts.push('-' + v.slice(9, 11));

        this.value = parts.join('');
      });
    });
  }

  /* ============================================================
     SIMPLE AOS (Animate On Scroll)
     ============================================================ */
  function kvAos() {
    var elements = document.querySelectorAll('[data-aos]');
    if (!elements.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('aos-animate');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    elements.forEach(function (el, i) {
      el.style.transitionDelay = (i * 0.06) + 's';
      observer.observe(el);
    });
  }

  /* ============================================================
     TO-TOP BUTTON
     ============================================================ */
  function kvToTop() {
    var btn = document.getElementById('kv-totop');
    if (!btn) return;

    window.addEventListener('scroll', function () {
      btn.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });

    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ============================================================
     QUICK CALCULATOR (homepage inline)
     ============================================================ */
  function kvQuickCalc() {
    var btn = document.getElementById('qc-submit');
    if (!btn) return;

    var basePrices = {
      gates:     18000,
      fence:     4500,
      stairs:    22000,
      railing:   6500,
      furniture: 35000,
      decor:     12000,
    };

    btn.addEventListener('click', function () {
      var cat     = document.getElementById('qc-category').value;
      var width   = parseFloat(document.getElementById('qc-width').value) || 1;
      var height  = parseFloat(document.getElementById('qc-height').value) || 1;
      var coating = parseFloat(document.getElementById('qc-coating').value) || 1;
      var install = document.getElementById('qc-install').checked;

      var base  = basePrices[cat] || 5000;
      var total = base * width * height * coating;
      if (install) total *= 1.25;

      var min = Math.round(total * 0.9 / 1000) * 1000;
      var max = Math.round(total * 1.15 / 1000) * 1000;

      var resultEl = document.getElementById('qc-result');
      var valEl    = document.getElementById('qc-result-val');

      if (resultEl && valEl) {
        resultEl.style.display = 'block';
        valEl.textContent = 'от\u00A0' + min.toLocaleString('ru') + '\u00A0\u20BD до\u00A0' + max.toLocaleString('ru') + '\u00A0\u20BD';
      }
    });
  }

  /* ============================================================
     FILL MODAL WITH PRODUCT NAME
     ============================================================ */
  function kvProductModalFill() {
    document.querySelectorAll('.kv-modal-open[data-product]').forEach(function (el) {
      el.addEventListener('click', function () {
        var product = this.dataset.product;
        var textarea = document.querySelector('#kv-modal textarea[name="message"]');
        if (textarea && product && !textarea.value) {
          textarea.value = 'Интересует: ' + product + '. ';
          textarea.dispatchEvent(new Event('input'));
        }
      });
    });
  }

  /* ============================================================
     CALCULATOR TABS (calculator.php page)
     ============================================================ */
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.kv-calc-tab').forEach(function (tab) {
      tab.addEventListener('click', function () {
        var panelId = this.dataset.tab;

        document.querySelectorAll('.kv-calc-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.kv-calc__panel').forEach(p => p.classList.remove('active'));

        this.classList.add('active');
        var panel = document.querySelector('[data-panel="' + panelId + '"]');
        if (panel) panel.classList.add('active');
      });
    });

    // Range sliders
    document.querySelectorAll('input[type="range"]').forEach(function (range) {
      var valDisplay = document.getElementById(range.id + '-val');
      if (!valDisplay) return;
      range.addEventListener('input', function () {
        valDisplay.textContent = this.value;
      });
    });
  });

})();
