/**
 * CarFinance — Catalog AJAX Filter v2
 * Horizontal panel, cascading dropdowns, chips, mobile drawer, Load More.
 */

(function () {
    'use strict';

    var filterForm        = document.getElementById('cf-catalog-filter');
    var resultsContainer  = document.getElementById('cf-catalog-results');
    var countEl           = document.getElementById('cf-catalog-count');
    var loadMoreBtn       = document.getElementById('cf-load-more');
    var sortSelect        = document.getElementById('cf-sort');
    var chipsContainer    = document.getElementById('cf-filter-chips');
    var activeCountEl     = document.getElementById('cf-active-count');
    var mobileCountEl     = document.getElementById('cf-mobile-active-count');
    var expandBtn         = document.getElementById('cf-filter-expand');
    var extendedRow       = document.getElementById('cf-filter-extended');
    var resetBtn          = document.getElementById('cf-filter-reset-btn');
    var seoToggle         = document.getElementById('cf-seo-toggle');

    // Mobile drawer
    var drawer            = document.getElementById('cf-filter-drawer');
    var drawerOverlay     = document.getElementById('cf-drawer-overlay');
    var drawerClose       = document.getElementById('cf-drawer-close');
    var drawerApplyBtn    = document.getElementById('cf-drawer-apply');
    var drawerCountEl     = document.getElementById('cf-drawer-count');
    var mobileOpenBtn     = document.getElementById('cf-mobile-filter-open');
    var mobileSortSelect  = document.getElementById('cf-sort-mobile');

    if (!filterForm || !resultsContainer) return;

    var ajaxUrl = (typeof cfCatalog !== 'undefined' && cfCatalog.ajaxUrl)
        ? cfCatalog.ajaxUrl
        : '/wp-admin/admin-ajax.php';
    var nonce = (typeof cfCatalog !== 'undefined' && cfCatalog.nonce)
        ? cfCatalog.nonce
        : '';

    var currentPage   = 1;
    var isLoading     = false;
    var debounceTimer = null;

    var filterLabels = {
        country      : 'Страна',
        brand        : 'Марка',
        model        : 'Модель',
        year_from    : 'Год от',
        year_to      : 'Год до',
        price_from   : 'Цена от',
        price_to     : 'Цена до',
        mileage_from : 'Пробег от',
        mileage_to   : 'Пробег до',
        engine_from  : 'Объём от',
        engine_to    : 'Объём до',
        power_from   : 'Мощность от',
        power_to     : 'Мощность до',
        steering     : 'Руль',
        seats        : 'Мест',
        accident_free: 'Без ДТП'
    };

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ─── Fetch filtered results ───
    function fetchResults(append) {
        if (isLoading) return;
        isLoading = true;

        if (!append) {
            resultsContainer.innerHTML = '<div class="cf-catalog-filter__spinner"><span class="cf-spinner"></span></div>';
            currentPage = 1;
        }

        var formData = new FormData(filterForm);
        formData.set('action', 'cf_catalog_filter');
        formData.set('nonce', nonce);
        formData.set('page', currentPage);

        var activeSortSelect = sortSelect || mobileSortSelect;
        if (activeSortSelect) {
            formData.set('sort', activeSortSelect.value);
        }

        fetch(ajaxUrl, {method: 'POST', body: formData})
            .then(function (res) { return res.json(); })
            .then(function (json) {
                if (!json.success) return;

                if (append) {
                    resultsContainer.insertAdjacentHTML('beforeend', json.data.html);
                } else {
                    resultsContainer.innerHTML = json.data.html;
                }

                if (countEl) countEl.textContent = json.data.found;
                if (drawerCountEl) drawerCountEl.textContent = json.data.found;

                if (loadMoreBtn) {
                    loadMoreBtn.dataset.page = currentPage;
                    loadMoreBtn.dataset.max  = json.data.max_pages;
                    loadMoreBtn.style.display = (currentPage >= json.data.max_pages) ? 'none' : '';
                }

                updateUrl();
            })
            .catch(function (err) {
                console.error('Filter error:', err);
            })
            .finally(function () {
                isLoading = false;
            });
    }

    // ─── Update URL ───
    function updateUrl() {
        var params = new URLSearchParams(new FormData(filterForm));
        var clean  = new URLSearchParams();

        params.forEach(function (val, key) {
            if (val && key !== 'action' && key !== 'nonce') {
                clean.append(key, val);
            }
        });

        var newUrl = clean.toString()
            ? window.location.pathname + '?' + clean.toString()
            : window.location.pathname;

        history.replaceState(null, '', newUrl);
    }

    // ─── Render active chips ───
    function renderChips() {
        if (!chipsContainer) return;

        chipsContainer.innerHTML = '';
        var data  = new FormData(filterForm);
        var count = 0;
        var skip  = new Set(['action', 'nonce', 'sort', 'condition']);

        data.forEach(function (val, key) {
            if (skip.has(key) || !val || val === 'all' || val === '0') return;

            count++;
            var label   = filterLabels[key] || key;
            var display = val;

            if (key === 'price_from' || key === 'price_to') {
                display = Number(val).toLocaleString('ru-RU') + ' ₽';
            } else if (key === 'mileage_from' || key === 'mileage_to') {
                display = Number(val).toLocaleString('ru-RU') + ' км';
            } else if (key === 'engine_from' || key === 'engine_to') {
                display = val + ' л';
            } else if (key === 'power_from' || key === 'power_to') {
                display = val + ' л.с.';
            } else if (key === 'year_from' || key === 'year_to') {
                display = val + ' г.';
            } else if (key === 'accident_free') {
                label   = '';
                display = 'Без ДТП';
            } else if (key === 'steering') {
                display = val === 'left' ? 'Левый руль' : 'Правый руль';
            }

            var chip = document.createElement('span');
            chip.className = 'cf-filter-chip';
            chip.innerHTML =
                (label ? '<span>' + escHtml(label) + ': </span>' : '') +
                '<span>' + escHtml(display) + '</span>' +
                '<button class="cf-filter-chip__remove" data-filter-key="' + escHtml(key) + '" data-filter-val="' + escHtml(val) + '" aria-label="Удалить фильтр">✕</button>';

            chipsContainer.appendChild(chip);
        });

        if (activeCountEl) {
            activeCountEl.textContent = count > 0 ? String(count) : '';
            activeCountEl.style.display = count > 0 ? '' : 'none';
        }
        if (mobileCountEl) {
            mobileCountEl.textContent = count > 0 ? String(count) : '';
            mobileCountEl.style.display = count > 0 ? '' : 'none';
        }
        if (resetBtn) {
            resetBtn.style.display = count > 0 ? '' : 'none';
        }
    }

    // ─── Cascading: Country → Brand ───
    function loadBrandsByCountry(countrySlug) {
        var brandSelect = filterForm.querySelector('[name="brand"]');
        if (!brandSelect) return;

        brandSelect.innerHTML = '<option value="">Загрузка...</option>';
        brandSelect.disabled  = true;

        var fd = new FormData();
        fd.set('action', 'cf_get_brands_by_country');
        fd.set('nonce', nonce);
        fd.set('country', countrySlug);

        fetch(ajaxUrl, {method: 'POST', body: fd})
            .then(function (res) { return res.json(); })
            .then(function (json) {
                brandSelect.innerHTML = '<option value="">Все марки</option>';
                if (json.success && json.data) {
                    json.data.forEach(function (brand) {
                        var opt = document.createElement('option');
                        opt.value = brand.slug;
                        opt.textContent = brand.name + ' (' + brand.count + ')';
                        brandSelect.appendChild(opt);
                    });
                }
            })
            .finally(function () {
                brandSelect.disabled = false;
            });
    }

    // ─── Cascading: Brand → Model ───
    function loadModelsByBrand(brandSlug) {
        var modelSelect = filterForm.querySelector('[name="model"]');
        if (!modelSelect) return;

        if (!brandSlug) {
            modelSelect.innerHTML = '<option value="">Выберите марку</option>';
            modelSelect.disabled  = true;
            return;
        }

        modelSelect.innerHTML = '<option value="">Загрузка...</option>';
        modelSelect.disabled  = true;

        var fd = new FormData();
        fd.set('action', 'cf_get_models_by_brand');
        fd.set('nonce', nonce);
        fd.set('brand', brandSlug);

        fetch(ajaxUrl, {method: 'POST', body: fd})
            .then(function (res) { return res.json(); })
            .then(function (json) {
                modelSelect.innerHTML = '<option value="">Все модели</option>';
                if (json.success && json.data && json.data.length) {
                    json.data.forEach(function (model) {
                        var opt = document.createElement('option');
                        opt.value = model.slug;
                        opt.textContent = model.title;
                        modelSelect.appendChild(opt);
                    });
                    modelSelect.disabled = false;
                } else {
                    modelSelect.innerHTML = '<option value="">Нет моделей</option>';
                }
            });
    }

    // ─── Expand/collapse extended filters ───
    if (expandBtn && extendedRow) {
        expandBtn.addEventListener('click', function () {
            var expanded = expandBtn.getAttribute('aria-expanded') === 'true';
            expandBtn.setAttribute('aria-expanded', String(!expanded));
            extendedRow.hidden = expanded;
        });
    }

    // ─── SEO text toggle ───
    if (seoToggle) {
        var seoInner = document.querySelector('.cf-catalog__seo-text-inner');
        seoToggle.addEventListener('click', function () {
            var expanded = seoToggle.getAttribute('aria-expanded') === 'true';
            seoToggle.setAttribute('aria-expanded', String(!expanded));
            if (seoInner) seoInner.classList.toggle('cf-is-expanded', !expanded);
            seoToggle.textContent = expanded ? 'Читать полностью ▼' : 'Свернуть ▲';
        });
    }

    // ─── Mobile drawer ───
    function openDrawer() {
        if (!drawer) return;
        drawer.classList.add('cf-is-open');
        drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        if (!drawer) return;
        drawer.classList.remove('cf-is-open');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    if (mobileOpenBtn)  mobileOpenBtn.addEventListener('click', openDrawer);
    if (drawerOverlay)  drawerOverlay.addEventListener('click', closeDrawer);
    if (drawerClose)    drawerClose.addEventListener('click', closeDrawer);
    if (drawerApplyBtn) {
        drawerApplyBtn.addEventListener('click', function () {
            closeDrawer();
            fetchResults(false);
        });
    }

    // ─── Form change events ───
    filterForm.addEventListener('change', function (e) {
        var name = e.target.name;

        if (name === 'country') loadBrandsByCountry(e.target.value);
        if (name === 'brand')   loadModelsByBrand(e.target.value);

        renderChips();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            fetchResults(false);
        }, 300);
    });

    filterForm.addEventListener('input', function (e) {
        if (e.target.type === 'number' || e.target.type === 'range') {
            renderChips();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                fetchResults(false);
            }, 500);
        }
    });

    filterForm.addEventListener('submit', function (e) {
        e.preventDefault();
        fetchResults(false);
    });

    // ─── Chip removal ───
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('cf-filter-chip__remove')) return;

        var key = e.target.dataset.filterKey;
        var val = e.target.dataset.filterVal;
        if (!key) return;

        // Checkbox arrays (fuel[], body_type[], etc.)
        var checkInputs = filterForm.querySelectorAll('[name="' + key + '[]"]');
        if (checkInputs.length) {
            checkInputs.forEach(function (inp) {
                if (inp.value === val) inp.checked = false;
            });
        } else {
            var inp = filterForm.querySelector('[name="' + key + '"]');
            if (inp) {
                inp.type === 'checkbox' ? (inp.checked = false) : (inp.value = '');
            }
        }

        renderChips();
        fetchResults(false);
    });

    // ─── Reset all filters ───
    document.querySelectorAll('.cf-filter-reset').forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterForm.reset();
            var modelSel = filterForm.querySelector('[name="model"]');
            if (modelSel) {
                modelSel.innerHTML = '<option value="">Выберите марку</option>';
                modelSel.disabled  = true;
            }
            renderChips();
            fetchResults(false);
        });
    });

    // ─── Load More ───
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            currentPage++;
            fetchResults(true);
        });
    }

    // ─── Sort selects ───
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            if (mobileSortSelect) mobileSortSelect.value = this.value;
            fetchResults(false);
        });
    }
    if (mobileSortSelect) {
        mobileSortSelect.addEventListener('change', function () {
            if (sortSelect) sortSelect.value = this.value;
            fetchResults(false);
        });
    }

    // ─── View toggle (grid/list) ───
    document.querySelectorAll('.cf-catalog__view').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.cf-catalog__view').forEach(function (b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');
            resultsContainer.classList.toggle('cf-catalog__grid--list', btn.dataset.view === 'list');
        });
    });

    // ─── Back/forward navigation ───
    window.addEventListener('popstate', function () {
        var params = new URLSearchParams(window.location.search);
        params.forEach(function (val, key) {
            var input = filterForm.querySelector('[name="' + key + '"]');
            if (input) input.value = val;
        });
        renderChips();
        fetchResults(false);
    });

    // ─── Initial setup ───
    renderChips();

})();
