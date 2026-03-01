/**
 * CarFinance Customs Calculator
 *
 * Handles:
 * 1. Customs duty calculation (Japan/Korea/China/USA/UAE)
 * 2. Ownership cost calculator
 * 3. Constructor calculator (Japan)
 *
 * Uses AJAX for server-side calculation, falls back to client-side.
 */

(function () {
  'use strict';

  // =========================================================================
  // Shipping costs by country (RUB)
  // =========================================================================
  var SHIPPING = {
    korea: 120000,
    japan: 100000,
    china: 150000,
    usa:   350000,
    uae:   250000
  };

  // Domestic delivery by city (from Vladivostok)
  var DOMESTIC = {
    vladivostok:  0,
    moscow:       120000,
    krasnodar:    110000,
    novosibirsk:  60000,
    ekaterinburg: 80000,
    sochi:        115000
  };

  var currentCountry = 'korea';

  // =========================================================================
  // Utility: format price
  // =========================================================================
  function formatPrice(n) {
    return Math.round(n).toLocaleString('ru-RU') + ' \u20BD';
  }

  // =========================================================================
  // Customs Duty Calculation (client-side)
  // =========================================================================
  function calcCustomsDuty(priceFob, engineCc, year, fuelType) {
    var age = new Date().getFullYear() - year;

    // Electric vehicles — flat rate
    if (fuelType === 'electric') {
      return priceFob * 0.15;
    }

    var duty = 0;

    if (age <= 3) {
      // New cars
      if (priceFob <= 730000)       duty = Math.max(priceFob * 0.54, engineCc * 2.5);
      else if (priceFob <= 1500000) duty = Math.max(priceFob * 0.48, engineCc * 3.5);
      else if (priceFob <= 3000000) duty = Math.max(priceFob * 0.48, engineCc * 5.5);
      else if (priceFob <= 6500000) duty = Math.max(priceFob * 0.48, engineCc * 7.5);
      else                          duty = Math.max(priceFob * 0.48, engineCc * 20);
    } else if (age <= 5) {
      // 3-5 years
      if (engineCc <= 1000)      duty = engineCc * 1.5;
      else if (engineCc <= 1500) duty = engineCc * 1.7;
      else if (engineCc <= 1800) duty = engineCc * 2.5;
      else if (engineCc <= 2300) duty = engineCc * 2.7;
      else if (engineCc <= 3000) duty = engineCc * 3.0;
      else                       duty = engineCc * 3.6;
    } else {
      // Over 5 years
      if (engineCc <= 1000)      duty = engineCc * 3.0;
      else if (engineCc <= 1500) duty = engineCc * 3.2;
      else if (engineCc <= 1800) duty = engineCc * 3.5;
      else if (engineCc <= 2300) duty = engineCc * 4.8;
      else if (engineCc <= 3000) duty = engineCc * 5.0;
      else                       duty = engineCc * 5.7;
    }

    return duty;
  }

  // =========================================================================
  // Utilization Fee
  // =========================================================================
  function calcUtilFee(engineCc, year) {
    var age = new Date().getFullYear() - year;
    var base = 20000;
    var coeff = 1;

    if (engineCc <= 1000)        coeff = age <= 3 ? 0.17 : 0.26;
    else if (engineCc <= 2000)   coeff = age <= 3 ? 4.2  : 15.69;
    else if (engineCc <= 3000)   coeff = age <= 3 ? 6.3  : 24.01;
    else if (engineCc <= 3500)   coeff = age <= 3 ? 5.73 : 28.5;
    else                         coeff = age <= 3 ? 9.08 : 35.01;

    return base * coeff;
  }

  // =========================================================================
  // Full Calculation
  // =========================================================================
  function calculate(formData) {
    var priceFob  = parseFloat(formData.price_fob) || 0;
    var year      = parseInt(formData.year) || new Date().getFullYear();
    var engineCc  = parseInt(formData.engine_cc) || 2000;
    var fuelType  = formData.fuel_type || 'gasoline';
    var country   = formData.country || currentCountry;
    var city      = formData.city || 'moscow';

    var freight    = SHIPPING[country] || 120000;
    var domestic   = DOMESTIC[city] || 0;
    var customsDuty = calcCustomsDuty(priceFob, engineCc, year, fuelType);
    var utilFee    = calcUtilFee(engineCc, year);
    var sbkts      = 25000;
    var epts       = 600;
    var broker     = 30000;
    var commission = Math.max(priceFob * 0.05, 50000);

    var total = priceFob + freight + domestic + customsDuty + utilFee + sbkts + epts + broker + commission;

    return {
      price_fob:    Math.round(priceFob),
      freight:      Math.round(freight),
      domestic:     Math.round(domestic),
      customs_duty: Math.round(customsDuty),
      util_fee:     Math.round(utilFee),
      sbkts:        Math.round(sbkts),
      epts:         Math.round(epts),
      broker:       Math.round(broker),
      commission:   Math.round(commission),
      total:        Math.round(total)
    };
  }

  // =========================================================================
  // Display Result
  // =========================================================================
  function displayResult(result) {
    var resultEl  = document.getElementById('cf-calc-result');
    var totalEl   = document.getElementById('calc-total');
    var breakEl   = document.getElementById('calc-breakdown');

    if (!resultEl || !totalEl || !breakEl) return;

    totalEl.textContent = formatPrice(result.total);

    var items = [
      { label: 'Стоимость авто',          value: result.price_fob },
      { label: 'Доставка (международная)', value: result.freight },
    ];

    if (result.domestic > 0) {
      items.push({ label: 'Доставка по России', value: result.domestic });
    }

    items.push(
      { label: 'Таможенная пошлина',   value: result.customs_duty },
      { label: 'Утилизационный сбор',   value: result.util_fee },
      { label: 'СБКТС',                value: result.sbkts },
      { label: 'ЭПТС',                 value: result.epts },
      { label: 'Таможенный брокер',     value: result.broker },
      { label: 'Наша комиссия',         value: result.commission }
    );

    breakEl.innerHTML = '';
    items.forEach(function (item) {
      var li = document.createElement('li');
      li.innerHTML = '<span>' + item.label + '</span><span>' + formatPrice(item.value) + '</span>';
      breakEl.appendChild(li);
    });

    resultEl.style.display = 'block';
    resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  // =========================================================================
  // Country Tab Switching
  // =========================================================================
  function initCountryTabs() {
    var tabs = document.querySelectorAll('[data-calc-country]');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function (e) {
        e.preventDefault();
        var parent = tab.closest('.cf-calculator__tabs') || tab.parentElement;
        parent.querySelectorAll('.cf-calculator__tab').forEach(function (t) {
          t.classList.remove('cf-calculator__tab--active');
        });
        tab.classList.add('cf-calculator__tab--active');
        currentCountry = tab.getAttribute('data-calc-country');
      });
    });
  }

  // =========================================================================
  // Calculator Type Tab Switching (customs / ownership / constructor)
  // =========================================================================
  function initCalcTypeTabs() {
    var tabs = document.querySelectorAll('[data-calc-type]');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function (e) {
        e.preventDefault();
        var parent = tab.closest('.cf-calculator__tabs');
        parent.querySelectorAll('.cf-calculator__tab').forEach(function (t) {
          t.classList.remove('cf-calculator__tab--active');
        });
        tab.classList.add('cf-calculator__tab--active');

        var type = tab.getAttribute('data-calc-type');
        document.querySelectorAll('.cf-calc-panel').forEach(function (panel) {
          panel.style.display = 'none';
        });
        var target = document.getElementById('calc-' + type);
        if (target) target.style.display = 'block';
      });
    });
  }

  // =========================================================================
  // Form Submission
  // =========================================================================
  function initCalcForm() {
    var form = document.getElementById('cf-calc-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var formData = {};
      new FormData(form).forEach(function (value, key) {
        formData[key] = value;
      });

      if (!formData.country) {
        formData.country = currentCountry;
      }

      var result = calculate(formData);
      displayResult(result);
    });
  }

  // =========================================================================
  // Ownership Calculator
  // =========================================================================
  function initOwnershipCalc() {
    var form = document.getElementById('cf-ownership-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var fd = new FormData(form);
      var annualKm    = parseInt(fd.get('annual_km')) || 15000;
      var consumption = parseFloat(fd.get('consumption')) || 8;
      var fuelType    = fd.get('fuel_type') || 'gasoline';
      var region      = fd.get('region') || 'moscow';

      // Fuel prices (RUB/liter)
      var fuelPrices = {
        gasoline:   56,
        gasoline95: 60,
        diesel:     62,
        electric:   5  // per kWh
      };

      var fuelCostPerLiter = fuelPrices[fuelType] || 56;
      var fuelTotal = (annualKm / 100) * consumption * fuelCostPerLiter;

      // Insurance (OSAGO + KASKO approx)
      var osago = 12000;
      var kasko = 60000;

      // Maintenance (2 times a year)
      var maintenance = 30000;

      // Transport tax (approx)
      var tax = 10000;

      // Tires
      var tires = 8000;

      // Parking (Moscow)
      var parking = region === 'moscow' ? 36000 : (region === 'spb' ? 24000 : 6000);

      // Wash
      var wash = 12000;

      var total = fuelTotal + osago + kasko + maintenance + tax + tires + parking + wash;

      // Display
      var resultEl = document.getElementById('cf-ownership-result');
      var totalEl  = document.getElementById('ownership-total');
      var breakEl  = document.getElementById('ownership-breakdown');

      if (!resultEl || !totalEl || !breakEl) return;

      totalEl.textContent = formatPrice(total);

      var items = [
        { label: 'Топливо (' + annualKm.toLocaleString('ru') + ' км)', value: Math.round(fuelTotal) },
        { label: 'ОСАГО',        value: osago },
        { label: 'КАСКО',        value: kasko },
        { label: 'ТО и ремонт',  value: maintenance },
        { label: 'Транспортный налог', value: tax },
        { label: 'Шины',         value: tires },
        { label: 'Парковка',     value: parking },
        { label: 'Мойка',        value: wash },
      ];

      breakEl.innerHTML = '';
      items.forEach(function (item) {
        var li = document.createElement('li');
        li.innerHTML = '<span>' + item.label + '</span><span>' + formatPrice(item.value) + '</span>';
        breakEl.appendChild(li);
      });

      resultEl.style.display = 'block';
      resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
  }

  // =========================================================================
  // Constructor Calculator
  // =========================================================================
  function initConstructorCalc() {
    var form = document.getElementById('cf-constructor-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var fd = new FormData(form);
      var priceJpy = parseInt(fd.get('price_jpy')) || 500000;
      var engineCc = parseInt(fd.get('engine_cc')) || 2000;

      // JPY to RUB rate (approximate)
      var rate = 0.65;
      var priceRub = priceJpy * rate;

      // Full car import
      var fullDuty   = calcCustomsDuty(priceRub, engineCc, new Date().getFullYear() - 6);
      var fullUtil   = calcUtilFee(engineCc, new Date().getFullYear() - 6);
      var fullTotal  = priceRub + SHIPPING.japan + fullDuty + fullUtil + 25000 + 600 + 30000;

      // Constructor (parts import — lower duty)
      var constrDuty  = engineCc * 0.5; // simplified
      var constrShip  = SHIPPING.japan * 0.7;
      var constrWork  = 80000; // assembly work
      var constrTotal = priceRub + constrShip + constrDuty + 3400 + constrWork + 30000;

      var compEl = document.getElementById('constructor-comparison');
      var resultEl = document.getElementById('cf-constructor-result');
      if (!compEl || !resultEl) return;

      compEl.innerHTML =
        '<h3 style="margin-bottom:16px;">Сравнение</h3>' +
        '<table class="cf-compare-table">' +
        '<thead><tr><th>Статья</th><th>Целый авто</th><th>Конструктор</th></tr></thead>' +
        '<tbody>' +
        '<tr><td>Цена авто</td><td>' + formatPrice(priceRub) + '</td><td>' + formatPrice(priceRub) + '</td></tr>' +
        '<tr><td>Доставка</td><td>' + formatPrice(SHIPPING.japan) + '</td><td>' + formatPrice(constrShip) + '</td></tr>' +
        '<tr><td>Пошлина</td><td>' + formatPrice(fullDuty) + '</td><td>' + formatPrice(constrDuty) + '</td></tr>' +
        '<tr><td>Утильсбор</td><td>' + formatPrice(fullUtil) + '</td><td>3 400 \u20BD</td></tr>' +
        '<tr><td>Сборка</td><td>—</td><td>' + formatPrice(constrWork) + '</td></tr>' +
        '<tr><td><strong>ИТОГО</strong></td>' +
        '<td><strong>' + formatPrice(fullTotal) + '</strong></td>' +
        '<td><strong>' + formatPrice(constrTotal) + '</strong></td></tr>' +
        '<tr><td colspan="2"></td><td style="color:var(--cf-accent);font-weight:700;">' +
        (constrTotal < fullTotal ? 'Экономия: ' + formatPrice(fullTotal - constrTotal) : 'Целый авто выгоднее') +
        '</td></tr>' +
        '</tbody></table>' +
        '<p style="margin-top:16px;font-size:0.8125rem;color:var(--cf-gray-500);">* Расчёт приблизительный. Конструктор подходит не для всех моделей.</p>';

      resultEl.style.display = 'block';
      resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
  }

  // =========================================================================
  // Init
  // =========================================================================
  document.addEventListener('DOMContentLoaded', function () {
    initCountryTabs();
    initCalcTypeTabs();
    initCalcForm();
    initOwnershipCalc();
    initConstructorCalc();
  });

})();
