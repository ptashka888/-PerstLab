/**
 * Kovka Theme — calculator.js
 * Full calculator logic for calculator.php page
 */
'use strict';

/* ============================================================
   CALCULATOR PANELS
   ============================================================ */
window.kvCalcPanel = function (panel) {
  var result = document.getElementById(panel + '-result');
  var valEl  = document.getElementById(panel + '-result-val');
  if (!result || !valEl) return;

  var total = 0;

  switch (panel) {
    case 'gates': {
      var typeEl   = document.querySelector('[data-panel="gates"] .calc-type');
      var base     = typeEl ? parseInt(typeEl.selectedOptions[0].dataset.base, 10) : 18000;
      var width    = parseFloat(document.getElementById('gates-width').value)   || 3;
      var height   = parseFloat(document.getElementById('gates-height').value)  || 2;
      var coating  = parseFloat(document.getElementById('gates-coating').value) || 1;
      var design   = parseFloat(document.getElementById('gates-design').value)  || 1;
      var install  = document.getElementById('gates-install').checked;

      total = base * width * height * coating * design;
      if (install) total *= 1.25;
      break;
    }

    case 'fence': {
      var length   = parseFloat(document.getElementById('fence-length').value)  || 10;
      var height2  = parseFloat(document.getElementById('fence-height').value)  || 1.5;
      var ftype    = parseInt(document.getElementById('fence-type').value, 10)  || 4500;
      var posts    = parseInt(document.getElementById('fence-posts').value, 10) || 0;
      var fcoating = parseFloat(document.getElementById('fence-coating').value) || 1;
      var finstall = document.getElementById('fence-install').checked;
      var postCount = Math.ceil(length / 2.5);

      total = ftype * length * height2 * fcoating + posts * postCount;
      if (finstall) total *= 1.25;
      break;
    }

    case 'stairs': {
      var stype  = parseInt(document.getElementById('stairs-type').value, 10)     || 4800;
      var steps  = parseInt(document.getElementById('stairs-steps').value, 10)    || 12;
      var swidth = parseFloat(document.getElementById('stairs-width').value)      || 0.9;
      var stepMat= parseFloat(document.getElementById('stairs-step-mat').value)   || 1;
      var widthCoef = { '0.9': 1.0, '1.1': 1.1, '1.3': 1.2, '1.6': 1.4 };
      var wc = widthCoef[String(swidth)] || 1.0;

      total = stype * steps * wc * stepMat;
      break;
    }

    case 'furniture': {
      var furn  = parseInt(document.getElementById('furniture-type').value, 10)   || 28000;
      var qty   = parseInt(document.getElementById('furniture-qty').value, 10)    || 1;
      var fdesign = parseFloat(document.getElementById('furniture-design').value) || 1;

      total = furn * qty * fdesign;
      // Скидка за количество
      if (qty >= 5) total *= 0.9;
      else if (qty >= 3) total *= 0.95;
      break;
    }

    case 'decor': {
      var dtype   = parseInt(document.getElementById('decor-type').value, 10)    || 1800;
      var dqty    = parseInt(document.getElementById('decor-qty').value, 10)     || 1;
      var dcoat   = parseFloat(document.getElementById('decor-coating').value)   || 1;
      var dpack   = parseInt(document.getElementById('decor-pack').value, 10)    || 0;

      total = dtype * dqty * dcoat + dpack * dqty;
      if (dqty >= 10) total *= 0.85;
      else if (dqty >= 5) total *= 0.9;
      break;
    }
  }

  var min = Math.round(total * 0.9 / 500) * 500;
  var max = Math.round(total * 1.15 / 500) * 500;

  valEl.textContent = 'от\u00A0' + min.toLocaleString('ru') + '\u00A0\u20BD\u00A0до\u00A0' + max.toLocaleString('ru') + '\u00A0\u20BD';
  result.style.display = 'flex';
  result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

  // GTM / analytics
  if (window.dataLayer) {
    window.dataLayer.push({
      event:       'calculator_result',
      calc_panel:  panel,
      calc_min:    min,
      calc_max:    max,
    });
  }
};
