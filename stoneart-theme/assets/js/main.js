document.addEventListener('DOMContentLoaded', function() {

    // --- 1. HEADER & NAVIGATION ---
    const header = document.getElementById('header');
    const smartBanner = document.getElementById('smart-banner');
    let hasScrolled = false;

    window.addEventListener('scroll', function() {
        if (window.scrollY > 30) {
            if (header) header.classList.add('scrolled');
            if (!hasScrolled && smartBanner && !sessionStorage.getItem('bannerClosed')) {
                smartBanner.classList.add('show');
                hasScrolled = true;
            }
        } else {
            if (header) header.classList.remove('scrolled');
        }
    });

    // Close Smart Banner
    const closeBannerBtn = document.getElementById('close-banner');
    if (closeBannerBtn) {
        closeBannerBtn.addEventListener('click', function() {
            smartBanner.classList.remove('show');
            sessionStorage.setItem('bannerClosed', 'true');
        });
    }

    // Mobile Menu
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileCloseBtn = document.getElementById('mobile-menu-close');
    const mobileOverlay = document.getElementById('mobile-menu-overlay');

    function toggleMenu(show) {
        if (!mobileOverlay) return;
        if (show) {
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (mobileBtn) mobileBtn.addEventListener('click', function() { toggleMenu(true); });
    if (mobileCloseBtn) mobileCloseBtn.addEventListener('click', function() { toggleMenu(false); });
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function(e) {
            if (e.target === mobileOverlay) toggleMenu(false);
        });
        mobileOverlay.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() { toggleMenu(false); });
        });
    }

    // --- 2. QUIZ ---
    const totalSteps = 4;
    let currentStep = 1;
    const nextBtn = document.getElementById('btn-next');
    const prevBtn = document.getElementById('btn-prev');
    const progressText = document.getElementById('current-step');
    const progressBar = document.getElementById('quiz-progress');
    const quizForm = document.getElementById('quiz-form');

    function updateQuiz() {
        for (let i = 1; i <= totalSteps; i++) {
            var step = document.getElementById('step-' + i);
            if (step) step.classList.add('hidden');
        }
        var activeStep = document.getElementById('step-' + currentStep);
        if (activeStep) activeStep.classList.remove('hidden');

        if (progressText) progressText.innerText = currentStep;
        if (progressBar) progressBar.style.width = ((currentStep / totalSteps) * 100) + '%';

        if (prevBtn) prevBtn.style.display = currentStep === 1 ? 'none' : '';
        if (nextBtn) nextBtn.style.display = currentStep === totalSteps ? 'none' : '';
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            var stepDiv = document.getElementById('step-' + currentStep);
            if (stepDiv) {
                var radios = stepDiv.querySelectorAll('input[type="radio"]');
                if (radios.length > 0) {
                    var checked = false;
                    radios.forEach(function(r) { if (r.checked) checked = true; });
                    if (!checked) radios[0].checked = true;
                }
            }
            if (currentStep < totalSteps) {
                currentStep++;
                updateQuiz();
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateQuiz();
            }
        });
    }

    // Auto-advance on radio selection
    document.querySelectorAll('.quiz-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (window.navigator && window.navigator.vibrate) {
                window.navigator.vibrate(15);
            }
            if (currentStep < totalSteps) {
                setTimeout(function() { if (nextBtn) nextBtn.click(); }, 350);
            }
        });
    });

    // Quiz form submit
    if (quizForm) {
        quizForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var phoneInput = document.getElementById('user-phone');
            var phone = phoneInput ? phoneInput.value.replace(/\D/g, '') : '';

            if (phone.length >= 10) {
                // AJAX submission
                if (typeof saAjax !== 'undefined') {
                    var formData = new FormData();
                    formData.append('action', 'sa_form_submit');
                    formData.append('nonce', saAjax.nonce);
                    formData.append('form_type', 'quiz');
                    formData.append('phone', phoneInput.value);

                    // Collect quiz answers
                    var product = document.querySelector('input[name="product"]:checked');
                    var formShape = document.querySelector('input[name="form_shape"]:checked');
                    var sink = document.querySelector('input[name="sink"]:checked');
                    var contactMethod = document.querySelector('input[name="contact_method"]:checked');

                    if (product) formData.append('product', product.value);
                    if (formShape) formData.append('form_shape', formShape.value);
                    if (sink) formData.append('sink', sink.value);
                    if (contactMethod) formData.append('contact_method', contactMethod.value);

                    fetch(saAjax.url, { method: 'POST', body: formData })
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            alert('Спасибо! Ваш номер принят. Мы закрепили за вами скидку до 30 000 руб. и свяжемся в течение 10 минут.');
                        })
                        .catch(function() {
                            alert('Спасибо! Мы свяжемся с вами в ближайшее время.');
                        });
                } else {
                    alert('Спасибо! Ваш номер принят. Мы закрепили за вами скидку до 30 000 руб. и свяжемся в течение 10 минут.');
                }

                currentStep = 1;
                quizForm.reset();
                updateQuiz();
            } else {
                alert('Введите корректный номер телефона.');
            }
        });
    }

    updateQuiz();

    // --- 3. MATERIAL VISUALIZER ---
    var visualizerBtns = document.querySelectorAll('.sa-visualizer__btn');
    var visualizerImg = document.getElementById('visualizer-img');
    var visualizerTitle = document.getElementById('visualizer-title');
    var visualizerDesc = document.getElementById('visualizer-desc');
    var visualizerBadges = document.getElementById('visualizer-badges');

    visualizerBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            visualizerBtns.forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');

            var img = this.getAttribute('data-img');
            var title = this.getAttribute('data-title');
            var desc = this.getAttribute('data-desc');
            var badge1 = this.getAttribute('data-badge1');
            var badge2 = this.getAttribute('data-badge2');

            if (visualizerImg) {
                visualizerImg.style.opacity = '0.3';
                setTimeout(function() {
                    visualizerImg.src = img;
                    if (visualizerTitle) visualizerTitle.innerText = title;
                    if (visualizerDesc) visualizerDesc.innerText = desc;
                    if (visualizerBadges) {
                        var html = '';
                        if (badge1) html += '<span class="sa-visualizer__badge sa-visualizer__badge--primary">' + badge1 + '</span>';
                        if (badge2) html += '<span class="sa-visualizer__badge sa-visualizer__badge--dark">' + badge2 + '</span>';
                        visualizerBadges.innerHTML = html;
                    }
                    visualizerImg.style.opacity = '1';
                }, 300);
            }
        });
    });

    // --- 4. SOCIAL PROOF TOASTS ---
    var names = ['Александр', 'Елена', 'Иван', 'Мария', 'Дмитрий', 'Анна', 'Сергей'];
    var cities = ['из Москвы', 'из Балашихи', 'из Химок', 'из Одинцово', 'с Рублевки', 'из Красногорска'];
    var actions = ['только что заказал(а) расчет столешницы', 'прошел(ла) тест на скидку', 'скачал(а) прайс-лист', 'выбрал(а) кварцевый агломерат'];

    function showToast() {
        var container = document.getElementById('toast-container');
        if (!container) return;

        var name = names[Math.floor(Math.random() * names.length)];
        var city = cities[Math.floor(Math.random() * cities.length)];
        var action = actions[Math.floor(Math.random() * actions.length)];

        var toast = document.createElement('div');
        toast.className = 'sa-toast';
        toast.innerHTML =
            '<div class="sa-toast__icon"><i class="fa-solid fa-bell"></i></div>' +
            '<div>' +
                '<p class="sa-toast__name"><strong>' + name + '</strong> ' + city + '</p>' +
                '<p class="sa-toast__action">' + action + '</p>' +
            '</div>';

        container.appendChild(toast);
        setTimeout(function() { toast.classList.add('show'); }, 100);
        setTimeout(function() {
            toast.classList.remove('show');
            setTimeout(function() { toast.remove(); }, 500);
        }, 5000);
    }

    setTimeout(function triggerRandomToast() {
        showToast();
        setTimeout(triggerRandomToast, Math.random() * 15000 + 15000);
    }, 12000);

    // --- 5. PDF FORM ---
    var pdfForm = document.getElementById('pdf-form');
    if (pdfForm) {
        pdfForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var emailInput = pdfForm.querySelector('input[type="email"]');

            if (typeof saAjax !== 'undefined' && emailInput) {
                var formData = new FormData();
                formData.append('action', 'sa_form_submit');
                formData.append('nonce', saAjax.nonce);
                formData.append('form_type', 'pdf');
                formData.append('email', emailInput.value);

                fetch(saAjax.url, { method: 'POST', body: formData })
                    .then(function(r) { return r.json(); })
                    .then(function() {
                        alert('Прайс-лист успешно отправлен на ваш E-mail. Проверьте папку "Входящие".');
                    })
                    .catch(function() {
                        alert('Прайс-лист отправлен на ваш E-mail.');
                    });
            } else {
                alert('Прайс-лист успешно отправлен на ваш E-mail. Проверьте папку "Входящие".');
            }
            pdfForm.reset();
        });
    }

    // --- 6. CONTACT FORM ---
    var contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (typeof saAjax !== 'undefined') {
                var formData = new FormData();
                formData.append('action', 'sa_form_submit');
                formData.append('nonce', saAjax.nonce);
                formData.append('form_type', 'contact');
                formData.append('name', contactForm.querySelector('[name="name"]').value);
                formData.append('phone', contactForm.querySelector('[name="phone"]').value);
                formData.append('email', contactForm.querySelector('[name="email"]').value);
                formData.append('message', contactForm.querySelector('[name="message"]').value);

                fetch(saAjax.url, { method: 'POST', body: formData })
                    .then(function(r) { return r.json(); })
                    .then(function() {
                        alert('Спасибо! Ваше сообщение отправлено. Мы свяжемся с вами в ближайшее время.');
                    })
                    .catch(function() {
                        alert('Спасибо! Мы свяжемся с вами в ближайшее время.');
                    });
            } else {
                alert('Спасибо! Ваше сообщение отправлено.');
            }
            contactForm.reset();
        });
    }

    // --- 7. FAQ ACCORDION ---
    document.querySelectorAll('.sa-faq__question').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var item = this.closest('.sa-faq__item');
            var isActive = item.classList.contains('active');

            // Close all
            document.querySelectorAll('.sa-faq__item').forEach(function(el) {
                el.classList.remove('active');
                el.querySelector('.sa-faq__question').setAttribute('aria-expanded', 'false');
            });

            // Toggle current
            if (!isActive) {
                item.classList.add('active');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // --- 8. PORTFOLIO FILTER ---
    document.querySelectorAll('.sa-portfolio-filter').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var filter = this.getAttribute('data-filter');

            document.querySelectorAll('.sa-portfolio-filter').forEach(function(b) {
                b.classList.remove('sa-btn--primary');
                b.classList.add('sa-btn--outline');
            });
            this.classList.remove('sa-btn--outline');
            this.classList.add('sa-btn--primary');

            document.querySelectorAll('.sa-portfolio-item').forEach(function(item) {
                if (filter === 'all' || item.getAttribute('data-categories').indexOf(filter) !== -1) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // --- 9. SCROLL ANIMATIONS ---
    var observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.sa-animate').forEach(function(el) {
        observer.observe(el);
    });
});
