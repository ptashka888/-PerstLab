/**
 * CarFinance — Catalog AJAX Filter
 * Cascading dropdowns, AJAX search, Load More pagination
 */

(function () {
    'use strict';

    const filterForm = document.getElementById('cf-catalog-filter');
    const resultsContainer = document.getElementById('cf-catalog-results');
    const countEl = document.getElementById('cf-catalog-count');
    const loadMoreBtn = document.getElementById('cf-load-more');
    const sortSelect = document.getElementById('cf-sort');

    if (!filterForm || !resultsContainer) return;

    const ajaxUrl = typeof cfCalc !== 'undefined' ? cfCalc.ajax_url : '/wp-admin/admin-ajax.php';
    const nonce = typeof cfCalc !== 'undefined' ? cfCalc.nonce : '';

    let currentPage = 1;
    let isLoading = false;
    let debounceTimer = null;

    // ─── Fetch filtered results ───
    function fetchResults(append) {
        if (isLoading) return;
        isLoading = true;

        if (!append) {
            resultsContainer.classList.add('cf-loading');
            currentPage = 1;
        }

        const formData = new FormData(filterForm);
        formData.set('action', 'cf_catalog_filter');
        formData.set('nonce', nonce);
        formData.set('page', currentPage);

        if (sortSelect) {
            formData.set('sort', sortSelect.value);
        }

        fetch(ajaxUrl, { method: 'POST', body: formData })
            .then(function (res) { return res.json(); })
            .then(function (json) {
                if (!json.success) return;

                if (append) {
                    resultsContainer.insertAdjacentHTML('beforeend', json.data.html);
                } else {
                    resultsContainer.innerHTML = json.data.html;
                }

                if (countEl) countEl.textContent = json.data.found;

                // Load more button
                if (loadMoreBtn) {
                    loadMoreBtn.dataset.page = currentPage;
                    loadMoreBtn.dataset.max = json.data.max_pages;
                    loadMoreBtn.style.display = currentPage >= json.data.max_pages ? 'none' : '';
                }

                // Update URL without reload (noindex params)
                updateUrl();
            })
            .catch(function (err) {
                console.error('Filter error:', err);
            })
            .finally(function () {
                isLoading = false;
                resultsContainer.classList.remove('cf-loading');
            });
    }

    // ─── Update URL with filter params ───
    function updateUrl() {
        var params = new URLSearchParams(new FormData(filterForm));
        params.delete('action');
        params.delete('nonce');

        // Remove empty values
        var cleanParams = new URLSearchParams();
        params.forEach(function (val, key) {
            if (val) cleanParams.set(key, val);
        });

        var newUrl = cleanParams.toString()
            ? window.location.pathname + '?' + cleanParams.toString()
            : window.location.pathname;

        history.pushState(null, '', newUrl);
    }

    // ─── Cascading: Country → Brand ───
    function loadBrandsByCountry(countrySlug) {
        var brandSelect = filterForm.querySelector('[name="brand"]');
        if (!brandSelect) return;

        brandSelect.innerHTML = '<option value="">Загрузка...</option>';
        brandSelect.disabled = true;

        var formData = new FormData();
        formData.set('action', 'cf_get_brands_by_country');
        formData.set('nonce', nonce);
        formData.set('country', countrySlug);

        fetch(ajaxUrl, { method: 'POST', body: formData })
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

    // ─── Event Listeners ───

    // Filter form changes (debounced)
    filterForm.addEventListener('change', function (e) {
        // Cascading dropdown
        if (e.target.name === 'country') {
            loadBrandsByCountry(e.target.value);
        }

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            fetchResults(false);
        }, 300);
    });

    // Range inputs (debounced)
    filterForm.addEventListener('input', function (e) {
        if (e.target.type === 'number' || e.target.type === 'range') {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                fetchResults(false);
            }, 500);
        }
    });

    // Prevent form submit
    filterForm.addEventListener('submit', function (e) {
        e.preventDefault();
        fetchResults(false);
    });

    // Load More
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            currentPage++;
            fetchResults(true);
        });
    }

    // Sort change
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            fetchResults(false);
        });
    }

    // View toggle (grid/list)
    document.querySelectorAll('.cf-catalog__view').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.cf-catalog__view').forEach(function (b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            var view = btn.dataset.view;
            resultsContainer.classList.toggle('cf-catalog__grid--list', view === 'list');
        });
    });

    // Reset filter button
    document.querySelectorAll('.cf-filter-reset').forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterForm.reset();
            fetchResults(false);
        });
    });

    // Active filter tags removal
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('cf-filter-tag__remove')) {
            var name = e.target.dataset.filter;
            var input = filterForm.querySelector('[name="' + name + '"]');
            if (input) {
                input.value = '';
                fetchResults(false);
            }
            e.target.closest('.cf-filter-tag').remove();
        }
    });

    // Mobile: bottom sheet toggle
    var filterToggle = document.querySelector('.cf-catalog__filter-toggle');
    var sidebar = document.querySelector('.cf-catalog__sidebar');
    if (filterToggle && sidebar) {
        filterToggle.addEventListener('click', function () {
            sidebar.classList.toggle('cf-catalog__sidebar--open');
            document.body.classList.toggle('cf-body--filter-open');
        });
    }

    // Back/forward navigation
    window.addEventListener('popstate', function () {
        var params = new URLSearchParams(window.location.search);
        params.forEach(function (val, key) {
            var input = filterForm.querySelector('[name="' + key + '"]');
            if (input) input.value = val;
        });
        fetchResults(false);
    });

})();
