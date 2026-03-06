document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. ШАПКА И НАВИГАЦИЯ ---
    const header = document.getElementById('header');
    const smartBanner = document.getElementById('smart-banner');
    let hasScrolled = false;

    window.addEventListener('scroll', function() {
        if (window.scrollY > 30) {
            header.classList.add('header-scrolled');
            if(!hasScrolled && smartBanner && !sessionStorage.getItem('bannerClosed')) {
                // Показываем smart banner при скролле
                smartBanner.classList.remove('translate-y-full');
                hasScrolled = true;
            }
        } else {
            header.classList.remove('header-scrolled');
        }
    });

    // Закрытие Smart Banner
    const closeBannerBtn = document.getElementById('close-banner');
    if(closeBannerBtn) {
        closeBannerBtn.addEventListener('click', () => {
            smartBanner.classList.add('translate-y-full');
            sessionStorage.setItem('bannerClosed', 'true');
        });
    }

    // Мобильное меню
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileCloseBtn = document.getElementById('mobile-menu-close');
    const mobileDrawer = document.getElementById('mobile-menu-drawer');
    const mobileOverlay = document.getElementById('mobile-menu-overlay');
    
    function toggleMenu(show) {
        if (show) {
            mobileOverlay.classList.remove('hidden');
            setTimeout(() => mobileOverlay.classList.remove('opacity-0'), 10);
            mobileDrawer.classList.remove('translate-x-full');
            document.body.style.overflow = 'hidden';
        } else {
            mobileOverlay.classList.add('opacity-0');
            mobileDrawer.classList.add('translate-x-full');
            document.body.style.overflow = '';
            setTimeout(() => mobileOverlay.classList.add('hidden'), 300);
        }
    }

    if (mobileBtn && mobileCloseBtn) {
        mobileBtn.addEventListener('click', () => toggleMenu(true));
        mobileCloseBtn.addEventListener('click', () => toggleMenu(false));
        mobileOverlay.addEventListener('click', (e) => {
            if(e.target === mobileOverlay) toggleMenu(false);
        });
        
        mobileDrawer.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => toggleMenu(false));
        });
    }


    // --- 2. КВИЗ ("Бахнуть лидов") ---
    const totalSteps = 4;
    let currentStep = 1;
    const nextBtn = document.getElementById('btn-next');
    const prevBtn = document.getElementById('btn-prev');
    const progressText = document.getElementById('current-step');
    const progressBar = document.getElementById('quiz-progress');
    const quizForm = document.getElementById('quiz-form');

    function updateQuiz() {
        for (let i = 1; i <= totalSteps; i++) {
            const step = document.getElementById(`step-${i}`);
            if(step) step.classList.add('hidden');
        }
        document.getElementById(`step-${currentStep}`).classList.remove('hidden');
        
        if (progressText) progressText.innerText = currentStep;
        if (progressBar) progressBar.style.width = `${(currentStep / totalSteps) * 100}%`;
        
        if (currentStep === 1) prevBtn.classList.add('hidden');
        else prevBtn.classList.remove('hidden');

        if (currentStep === totalSteps) nextBtn.classList.add('hidden');
        else nextBtn.classList.remove('hidden');
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const stepDiv = document.getElementById(`step-${currentStep}`);
            const radios = stepDiv.querySelectorAll('input[type="radio"]');
            if (radios.length > 0) {
                let checked = false;
                radios.forEach(r => { if (r.checked) checked = true; });
                if (!checked) radios[0].checked = true; // Автовыбор
            }
            if (currentStep < totalSteps) {
                currentStep++;
                updateQuiz();
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) { currentStep--; updateQuiz(); }
        });
    }

    // Автопереход при клике на картинку + Haptic Feedback
    document.querySelectorAll('.quiz-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            // Вибро-отклик при выборе (если поддерживается)
            if (window.navigator && window.navigator.vibrate) {
                window.navigator.vibrate(15);
            }
            // Если это не последний шаг с формой, делаем автопереход
            if(currentStep < totalSteps) {
                setTimeout(() => { if (nextBtn) nextBtn.click(); }, 350);
            }
        });
    });

    if (quizForm) {
        quizForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const phone = document.getElementById('user-phone').value.replace(/\D/g, '');
            if (phone.length >= 10) {
                alert('Спасибо! Ваш номер принят. Мы закрепили за вами скидку до 30 000 руб. и свяжемся в течение 10 минут.');
                currentStep = 1;
                quizForm.reset();
                updateQuiz();
            } else {
                alert('Введите корректный номер телефона.');
            }
        });
    }
    updateQuiz();


    // --- 3. ИНТЕРАКТИВНЫЙ ВИЗУАЛИЗАТОР МАТЕРИАЛОВ ---
    const visualizerBtns = document.querySelectorAll('.visualizer-btn');
    const visualizerImg = document.getElementById('visualizer-img');
    const visualizerTitle = document.getElementById('visualizer-title');
    const visualizerDesc = document.getElementById('visualizer-desc');
    const visualizerBadges = document.getElementById('visualizer-badges');

    const materialsData = {
        quartz: {
            img: 'https://images.unsplash.com/photo-1600607688969-a5bfcd646154?auto=format&fit=crop&w=1000&q=80',
            title: 'Кварцевый агломерат',
            desc: 'Идеальный выбор для кухонных столешниц. Абсолютно не впитывает влагу, не боится пятен вина и кофе.',
            badges: '<span class="bg-yellow-500 text-gray-900 text-xs font-bold px-2 py-1 rounded">Хит для кухни</span> <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded border border-gray-600">Avant / Caesarstone</span>'
        },
        granite: {
            img: 'https://images.unsplash.com/photo-1554162985-1d37e6bdf20a?auto=format&fit=crop&w=1000&q=80',
            title: 'Натуральный гранит',
            desc: 'Максимальная прочность и термостойкость до 800°C. Подходит для интенсивного использования и уличных зон.',
            badges: '<span class="bg-yellow-500 text-gray-900 text-xs font-bold px-2 py-1 rounded">Абсолютная прочность</span>'
        },
        marble: {
            img: 'https://images.unsplash.com/photo-1598387181032-a3103a2db5b3?auto=format&fit=crop&w=1000&q=80',
            title: 'Натуральный мрамор',
            desc: 'Премиальная эстетика с неповторимым природным рисунком. Идеален для ванных комнат, полов и облицовки каминов.',
            badges: '<span class="bg-yellow-500 text-gray-900 text-xs font-bold px-2 py-1 rounded">Премиум статус</span> <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded border border-gray-600">Calacatta</span>'
        },
        acrylic: {
            img: 'https://images.unsplash.com/photo-1584622781564-1d987f7333c1?auto=format&fit=crop&w=1000&q=80',
            title: 'Акриловый камень',
            desc: 'Создание изделий любой сложной формы без видимых швов. Теплый на ощупь, легко реставрируется.',
            badges: '<span class="bg-yellow-500 text-gray-900 text-xs font-bold px-2 py-1 rounded">Без швов</span> <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded border border-gray-600">Grandex</span>'
        }
    };

    visualizerBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Убираем активный класс у всех
            visualizerBtns.forEach(b => {
                b.classList.remove('active', 'border-yellow-500', 'bg-yellow-50');
                b.classList.add('border-gray-200');
            });
            // Добавляем активный класс нажатой
            this.classList.remove('border-gray-200');
            this.classList.add('active', 'border-yellow-500', 'bg-yellow-50');

            const matKey = this.getAttribute('data-mat');
            const data = materialsData[matKey];

            // Анимация смены картинки
            visualizerImg.style.opacity = '0.3';
            setTimeout(() => {
                visualizerImg.src = data.img;
                visualizerTitle.innerText = data.title;
                visualizerDesc.innerText = data.desc;
                if(visualizerBadges) visualizerBadges.innerHTML = data.badges;
                visualizerImg.style.opacity = '1';
            }, 300);
        });
    });


    // --- 4. ФЕЙКОВЫЕ УВЕДОМЛЕНИЯ (SOCIAL PROOF) ---
    const names = ['Александр', 'Елена', 'Иван', 'Мария', 'Дмитрий', 'Анна', 'Сергей'];
    const cities = ['из Москвы', 'из Балашихи', 'из Химок', 'из Одинцово', 'с Рублевки', 'из Красногорска'];
    const actions = ['только что заказал(а) расчет столешницы', 'прошел(ла) тест на скидку', 'скачал(а) прайс-лист', 'выбрал(а) кварцевый агломерат'];
    
    function showToast() {
        const container = document.getElementById('toast-container');
        if(!container) return;

        const name = names[Math.floor(Math.random() * names.length)];
        const city = cities[Math.floor(Math.random() * cities.length)];
        const action = actions[Math.floor(Math.random() * actions.length)];

        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 flex-shrink-0 border border-yellow-200">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div>
                <p class="text-sm text-gray-800"><strong class="font-bold">${name}</strong> ${city}</p>
                <p class="text-xs text-gray-500">${action}</p>
            </div>
        `;
        
        container.appendChild(toast);
        
        // Показ
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Скрытие и удаление
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }

    // Запускаем уведомления со случайным интервалом от 15 до 30 секунд
    setTimeout(function triggerRandomToast() {
        showToast();
        setTimeout(triggerRandomToast, Math.random() * 15000 + 15000);
    }, 12000);


    // --- 5. ФОРМА PDF ---
    const pdfForm = document.getElementById('pdf-form');
    if(pdfForm) {
        pdfForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Прайс-лист успешно отправлен на ваш E-mail. Проверьте папку "Входящие".');
            pdfForm.reset();
        });
    }

    // --- 6. ПЛАВНОЕ ПОЯВЛЕНИЕ БЛОКОВ ПРИ СКРОЛЛЕ ---
    const observerOptions = { 
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('section').forEach(section => {
        // Исключаем hero-секцию из анимации скролла, чтобы она была видна сразу
        if(section.id !== 'hero') {
            section.style.opacity = "0";
            section.style.transform = "translateY(20px)";
            section.style.transition = "opacity 0.8s ease-out, transform 0.8s ease-out";
            observer.observe(section);
        }
    });
});