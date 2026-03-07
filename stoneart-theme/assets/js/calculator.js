/**
 * StoneArt Stone Price Calculator
 *
 * Three modes:
 * 1. Quick: type + material + area → estimate
 * 2. Precise: detailed params → AJAX server calc
 * 3. Customs: N/A for stone (reserved for future)
 *
 * @package StoneArt
 */
(function () {
    'use strict';

    // ============================================================
    // Config
    // ============================================================

    /** Base price per m² by material slug */
    var BASE_PRICES = {
        mramor:    18000,
        granit:    14000,
        oniks:     45000,
        travertin: 16000,
        kvartsit:  22000,
        kvarts:    12000,
        akril:      9000,
    };

    /** Multiplier by product type slug */
    var TYPE_MULT = {
        stoleshnitsa: 1.0,
        podokonnik:   0.85,
        lestnitsa:    1.4,
        kamin:        2.0,
        pol:          0.85,
        rakoviny:     1.6,
        vanna:        3.0,
        fasad:        1.1,
    };

    /** Multiplier by edge type */
    var EDGE_MULT = {
        straight:  1.0,
        bevel:     1.05,
        round:     1.08,
        profiled:  1.15,
        carving:   1.25,
    };

    /** Multiplier by thickness */
    var THICKNESS_MULT = {
        '20': 1.0,
        '30': 1.1,
        '40': 1.2,
        '60': 1.35,
    };

    // ============================================================
    // Helpers
    // ============================================================

    function fmt(num) {
        return Math.round(num).toLocaleString('ru-RU') + ' ₽';
    }

    function getVal(selector) {
        var el = document.querySelector(selector);
        return el ? el.value : '';
    }

    function setHtml(selector, html) {
        var el = document.querySelector(selector);
        if (el) el.innerHTML = html;
    }

    function showEl(selector) {
        var el = document.querySelector(selector);
        if (el) el.hidden = false;
    }

    function hideEl(selector) {
        var el = document.querySelector(selector);
        if (el) el.hidden = true;
    }

    // ============================================================
    // Quick Calculator
    // ============================================================

    function calcQuick() {
        var type      = getVal('#calc-type');
        var material  = getVal('#calc-material');
        var length    = parseFloat(getVal('#calc-length')) || 0;
        var width     = parseFloat(getVal('#calc-width'))  || 0;
        var thickness = getVal('#calc-thickness') || '20';
        var edge      = getVal('#calc-edge')      || 'straight';

        if (!type || !material || length <= 0 || width <= 0) {
            setHtml('#calc-result', '');
            hideEl('#calc-result-block');
            return;
        }

        var base     = BASE_PRICES[material]    || 14000;
        var typeMult = TYPE_MULT[type]          || 1.0;
        var edgeMult = EDGE_MULT[edge]          || 1.0;
        var thkMult  = THICKNESS_MULT[thickness]|| 1.0;

        // Convert mm to m² (assume length/width in mm)
        var unit = parseFloat(getVal('#calc-unit')) || 0; // 0=mm, 1=cm, 2=m
        var area;
        if (unit === 2) {
            area = length * width;
        } else if (unit === 1) {
            area = (length / 100) * (width / 100);
        } else {
            area = (length / 1000) * (width / 1000);
        }
        area = Math.max(area, 0.1);

        var pricePerM2 = base * typeMult * edgeMult * thkMult;
        var total      = pricePerM2 * area;
        var totalMin   = Math.round(total * 0.9 / 1000) * 1000;
        var totalMax   = Math.round(total * 1.1 / 1000) * 1000;

        setHtml('#calc-result', [
            '<div style="font-size:0.9rem;color:var(--sa-gray-600);margin-bottom:0.25rem;">Площадь: <strong>' + area.toFixed(2) + ' м²</strong></div>',
            '<div style="font-size:0.9rem;color:var(--sa-gray-600);margin-bottom:0.5rem;">Цена за м²: <strong>' + Math.round(pricePerM2).toLocaleString('ru-RU') + ' ₽</strong></div>',
            '<div style="font-size:1.75rem;font-weight:900;color:var(--sa-primary);">',
            fmt(totalMin) + ' — ' + fmt(totalMax),
            '</div>',
            '<div style="font-size:0.8rem;color:var(--sa-gray-500);margin-top:0.25rem;">Ориентировочная стоимость. Точный расчёт — после замера.</div>',
        ].join(''));
        showEl('#calc-result-block');
    }

    // ============================================================
    // Initialisation
    // ============================================================

    function init() {
        // Quick calc: auto-calculate on any input change
        var calcFields = document.querySelectorAll(
            '#calc-type, #calc-material, #calc-length, #calc-width, #calc-thickness, #calc-edge, #calc-unit'
        );
        calcFields.forEach(function (el) {
            el.addEventListener('change', calcQuick);
            el.addEventListener('input', calcQuick);
        });

        // Submit button → scroll to form / show quiz
        var calcSubmitBtn = document.getElementById('calc-submit-btn');
        if (calcSubmitBtn) {
            calcSubmitBtn.addEventListener('click', function () {
                var target = document.getElementById('cta-form-section') || document.getElementById('quiz-section');
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        }

        // Tab switching (if multi-tab calculator)
        document.querySelectorAll('[data-calc-tab]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var tab = this.dataset.calcTab;
                document.querySelectorAll('[data-calc-tab]').forEach(function (b) {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');

                document.querySelectorAll('[data-calc-panel]').forEach(function (panel) {
                    panel.hidden = panel.dataset.calcPanel !== tab;
                });
            });
        });

        // Unit label update
        var unitSelect = document.getElementById('calc-unit');
        var lenLabel   = document.getElementById('calc-len-label');
        var widLabel   = document.getElementById('calc-wid-label');
        if (unitSelect) {
            unitSelect.addEventListener('change', function () {
                var suffix = ['мм', 'см', 'м'][parseInt(this.value)] || 'мм';
                if (lenLabel) lenLabel.textContent = 'Длина (' + suffix + ')';
                if (widLabel) widLabel.textContent = 'Ширина (' + suffix + ')';
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
