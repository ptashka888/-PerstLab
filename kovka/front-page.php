<?php get_header(); ?>

<!-- ============================================================
     БЛОК 1: HERO — главная сцена
     ============================================================ -->
<section class="kv-hero">
    <div class="kv-hero__bg" style="background-image:url('<?= get_template_directory_uri() ?>/assets/img/hero-forge.jpg')"></div>
    <div class="kv-hero__overlay"></div>
    <div class="kv-hero__ember"></div>

    <div class="kv-container" style="display:flex;align-items:center;justify-content:space-between;gap:40px;width:100%;padding:100px 20px 80px">
        <div class="kv-hero__content">
            <div class="kv-hero__badge">
                ⚒️ Производство с 2005 года &nbsp;·&nbsp; 1 200+ реализованных проектов
            </div>
            <h1>
                Кованые изделия,<br>
                <span>которые живут</span><br>
                25 лет без ухода
            </h1>
            <p class="kv-hero__sub">
                Ворота, заборы, лестницы, мебель и художественная ковка.
                Изготовим по вашему эскизу или разработаем дизайн бесплатно.
                Монтаж под ключ. Гарантия письменная.
            </p>
            <div class="kv-btn-group">
                <a href="#kv-modal" class="kv-btn kv-btn--primary kv-btn--lg kv-modal-open">
                    Получить расчёт бесплатно
                </a>
                <a href="<?php echo home_url('/portfolio/'); ?>" class="kv-btn kv-btn--ghost kv-btn--lg">
                    Смотреть работы →
                </a>
            </div>
            <div class="kv-hero__stats">
                <div>
                    <span class="kv-hero__stat-val" data-counter="1200">0</span>
                    <span class="kv-hero__stat-label">проектов сдано</span>
                </div>
                <div>
                    <span class="kv-hero__stat-val">25</span>
                    <span class="kv-hero__stat-label">лет гарантии</span>
                </div>
                <div>
                    <span class="kv-hero__stat-val">7</span>
                    <span class="kv-hero__stat-label">дней от заявки до монтажа</span>
                </div>
                <div>
                    <span class="kv-hero__stat-val">4.9★</span>
                    <span class="kv-hero__stat-label">средний рейтинг</span>
                </div>
            </div>
        </div>

        <!-- Форма -->
        <div class="kv-hero__right">
            <div class="kv-hero__form-box">
                <h3>Расчёт за 15 минут</h3>
                <p>Скажите, что нужно — назовём точную цену</p>
                <?php echo kv_lead_form('hero', 'Рассчитать стоимость'); ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 2: ТРАСТОВАЯ ПОЛОСА
     ============================================================ -->
<div class="kv-trust">
    <div class="kv-container">
        <div class="kv-trust-list">
            <div class="kv-trust-item">
                <div class="kv-trust-item__icon">🏭</div>
                <div>
                    <div class="kv-trust-item__val">Собственное производство</div>
                    <div class="kv-trust-item__label">Никаких посредников</div>
                </div>
            </div>
            <div class="kv-trust-item">
                <div class="kv-trust-item__icon">📄</div>
                <div>
                    <div class="kv-trust-item__val">Договор + гарантийный талон</div>
                    <div class="kv-trust-item__label">Письменная гарантия 25 лет</div>
                </div>
            </div>
            <div class="kv-trust-item">
                <div class="kv-trust-item__icon">🚚</div>
                <div>
                    <div class="kv-trust-item__val">Монтаж по всей России</div>
                    <div class="kv-trust-item__label">Собственная бригада</div>
                </div>
            </div>
            <div class="kv-trust-item">
                <div class="kv-trust-item__icon">🎨</div>
                <div>
                    <div class="kv-trust-item__val">Дизайн-проект бесплатно</div>
                    <div class="kv-trust-item__label">3D-визуализация перед производством</div>
                </div>
            </div>
            <div class="kv-trust-item">
                <div class="kv-trust-item__icon">⚡</div>
                <div>
                    <div class="kv-trust-item__val">Срок от 7 дней</div>
                    <div class="kv-trust-item__label">Экспресс-заказ возможен</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     БЛОК 3: КАТАЛОГ КАТЕГОРИЙ
     ============================================================ -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Что мы делаем</span>
            <h2>Каталог кованых изделий</h2>
            <p class="kv-lead">Каждое изделие — уникально. Работаем по вашим размерам, эскизам и вкусу</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
            <?php
            $cats = kv_get_category_data();
            $cat_details = [
                'vorota'    => ['count' => '85+ моделей',  'desc' => 'Распашные, откатные, автоматические. Козырьки и навесы.'],
                'zabory'    => ['count' => '60+ вариантов', 'desc' => 'Секционные ограждения, кованые секции, опорные столбы.'],
                'lestnitsy' => ['count' => '40+ проектов', 'desc' => 'Маршевые, винтовые, модульные. Ограждения балконов.'],
                'mebel'     => ['count' => '120+ позиций', 'desc' => 'Столы, стулья, кровати, вешалки, подставки.'],
                'dekor'     => ['count' => '200+ изделий', 'desc' => 'Подсвечники, панно, вазы, подставки, аксессуары.'],
                'art'       => ['count' => 'Под заказ',    'desc' => 'Скульптуры, арт-объекты, подарки, корпоративные сувениры.'],
            ];
            foreach ($cats as $slug => $cat) :
                $detail = $cat_details[$slug];
            ?>
            <a href="<?= esc_url(home_url('/' . $slug . '/')) ?>" class="kv-cat-card" style="min-height:320px">
                <div style="position:absolute;inset:0;background:linear-gradient(135deg,<?= $cat['color'] ?>33,<?= $cat['color'] ?>11);z-index:1"></div>
                <div style="position:absolute;inset:0;background:linear-gradient(0deg,rgba(28,15,0,.85) 0%,rgba(28,15,0,.2) 60%,transparent 100%)"></div>
                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-60%);font-size:4rem;z-index:2;opacity:.15"><?= $cat['icon'] ?></div>
                <div class="kv-cat-card__arrow" style="z-index:3">→</div>
                <div class="kv-cat-card__body" style="z-index:3">
                    <div class="kv-cat-card__count"><?= esc_html($detail['count']) ?></div>
                    <div class="kv-cat-card__title"><?= esc_html($cat['name']) ?></div>
                    <p style="color:rgba(255,255,255,.65);font-size:.8rem;margin:0"><?= esc_html($detail['desc']) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="kv-text-center kv-mt-32">
            <a href="<?php echo get_post_type_archive_link('kv_product'); ?>" class="kv-btn kv-btn--secondary">
                Весь каталог →
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 4: ПОЧЕМУ МЫ (USP)
     ============================================================ -->
<section class="kv-section kv-section--subtle">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Наши преимущества</span>
            <h2>Почему выбирают нас</h2>
            <p class="kv-lead">19 лет в металле — не просто стаж, а репутация, подтверждённая тысячей объектов</p>
        </div>

        <div class="kv-grid-4">
            <?php
            $usps = [
                ['icon' => '🏭', 'title' => 'Собственное производство',    'text' => 'Завод площадью 1 800 м². Современное оборудование ЧПУ и ручная ковка. Полный цикл без субподрядчиков.'],
                ['icon' => '📐', 'title' => 'Точность до миллиметра',      'text' => 'Выезд замерщика бесплатно. 3D-визуализация до старта производства. Отклонение ≤ 1 мм.'],
                ['icon' => '🛡️', 'title' => 'Гарантия 25 лет',             'text' => 'Письменный договор. Гарантийный талон на сварные швы, покрытие и конструкцию. Без оговорок.'],
                ['icon' => '🎨', 'title' => 'Любой стиль и цвет',          'text' => 'Классика, модерн, лофт, ар-нуво, хай-тек. 120 цветов RAL. Патина, позолота, ржавый эффект.'],
                ['icon' => '💰', 'title' => 'Цена производителя',          'text' => 'Мы производим сами — экономия до 40% vs. перекупщиков. Рассрочка 0% на 6 месяцев.'],
                ['icon' => '🚀', 'title' => 'Срок от 7 дней',              'text' => 'Стандартный заказ — 14–21 день. Простые изделия — от 7 дней. Экспресс возможен.'],
                ['icon' => '🔧', 'title' => 'Монтаж под ключ',             'text' => 'Собственная монтажная бригада. Работаем в Москве, области и регионах России.'],
                ['icon' => '⭐', 'title' => 'Рейтинг 4.9 / 5',            'text' => 'Более 320 отзывов на Яндекс, Google, Avito. Индекс NPS — 87. Рекомендуют 94% клиентов.'],
            ];
            foreach ($usps as $u) : ?>
            <div class="kv-usp-card" data-aos="fade-up">
                <div class="kv-usp-card__icon"><?= $u['icon'] ?></div>
                <h3><?= esc_html($u['title']) ?></h3>
                <p><?= esc_html($u['text']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 5: СЧЁТЧИКИ
     ============================================================ -->
<section class="kv-section kv-section--dark">
    <div class="kv-container">
        <div class="kv-counter-grid">
            <div class="kv-counter-item">
                <span class="kv-counter-val" style="color:#FFAB40" data-counter="19">0</span>
                <span class="kv-counter-label">лет на рынке</span>
            </div>
            <div class="kv-counter-item">
                <span class="kv-counter-val" style="color:#FFAB40" data-counter="1200">0</span>
                <span class="kv-counter-label">реализованных проектов</span>
            </div>
            <div class="kv-counter-item">
                <span class="kv-counter-val" style="color:#FFAB40" data-counter="25">0</span>
                <span class="kv-counter-label">лет гарантии</span>
            </div>
            <div class="kv-counter-item">
                <span class="kv-counter-val" style="color:#FFAB40" data-counter="47">0</span>
                <span class="kv-counter-label">мастеров в команде</span>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 6: ПОРТФОЛИО
     ============================================================ -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Наши работы</span>
            <h2>Последние реализованные проекты</h2>
            <p class="kv-lead">Каждая работа — история доверия клиента и мастерства нашей команды</p>
        </div>

        <div class="kv-portfolio-grid">
            <?php
            $works = get_posts(['post_type' => 'kv_work', 'posts_per_page' => 5, 'meta_key' => 'kv_work_featured', 'meta_value' => '1']);
            if ($works) :
                foreach ($works as $i => $work) :
                    setup_postdata($work);
                    $img = get_the_post_thumbnail_url($work->ID, 'kv-wide') ?: get_template_directory_uri() . '/assets/img/placeholder.jpg';
                    $cats = get_the_terms($work->ID, 'kv_category');
                    $cat_name = $cats ? $cats[0]->name : '';
            ?>
            <div class="kv-port-item">
                <img src="<?= esc_url($img) ?>" alt="<?= esc_attr($work->post_title) ?>" loading="lazy">
                <div class="kv-port-item__overlay">
                    <div class="kv-port-item__cat"><?= esc_html($cat_name) ?></div>
                    <div class="kv-port-item__title"><?= esc_html($work->post_title) ?></div>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata();
            else : ?>
            <!-- Placeholder если нет работ -->
            <?php for ($i = 0; $i < 5; $i++) : ?>
            <div class="kv-port-item" style="background:linear-gradient(135deg,#2D1A00,#1C0F00);min-height:<?= $i === 0 ? '400px' : '190px' ?>">
                <div class="kv-port-item__overlay" style="opacity:1;background:rgba(0,0,0,.3)">
                    <div class="kv-port-item__cat">Добавьте работы в CPT «Портфолио»</div>
                    <div class="kv-port-item__title">Пример работы <?= $i+1 ?></div>
                </div>
            </div>
            <?php endfor;
            endif; ?>
        </div>

        <div class="kv-text-center kv-mt-32">
            <a href="<?php echo home_url('/portfolio/'); ?>" class="kv-btn kv-btn--secondary">
                Смотреть все работы (120+) →
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 7: КАК МЫ РАБОТАЕМ
     ============================================================ -->
<section class="kv-section kv-section--subtle">
    <div class="kv-container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center">
            <div>
                <span class="kv-section-label">Процесс</span>
                <h2>От заявки до монтажа за 7 шагов</h2>
                <p class="kv-lead kv-mt-16">
                    Мы выстроили процесс так, чтобы вы не беспокоились ни о чём.
                    Всё — от замера до покраски — под нашим контролем.
                </p>
                <div class="kv-mt-32">
                    <a href="#kv-modal" class="kv-btn kv-btn--primary kv-modal-open">Начать проект</a>
                </div>
            </div>

            <div class="kv-steps" style="display:flex;flex-direction:column;gap:4px">
                <?php
                $steps = [
                    ['title' => 'Заявка онлайн или по телефону',   'text' => 'Описываете задачу — мы отвечаем за 15 минут'],
                    ['title' => 'Бесплатный выезд замерщика',       'text' => 'Снимаем точные размеры, консультируем по стилю'],
                    ['title' => '3D-проект и согласование',         'text' => 'Показываем, как будет выглядеть. Правим до ОК'],
                    ['title' => 'Договор и предоплата 50%',         'text' => 'Фиксируем цену, сроки и гарантии письменно'],
                    ['title' => 'Производство на нашем заводе',     'text' => 'Фото и видео с цеха по запросу в любой момент'],
                    ['title' => 'Покраска и антикоррозийная защита','text' => 'Порошок, патина, горячий цинк — на ваш выбор'],
                    ['title' => 'Доставка и монтаж',                'text' => 'Устанавливаем, проверяем, подписываем акт приёмки'],
                ];
                foreach ($steps as $step) : ?>
                <div class="kv-step">
                    <div>
                        <div class="kv-step__title"><?= esc_html($step['title']) ?></div>
                        <div class="kv-step__text"><?= esc_html($step['text']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 8: МАТЕРИАЛЫ И ПОКРЫТИЯ
     ============================================================ -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Качество</span>
            <h2>Металл и покрытия — без компромиссов</h2>
            <p class="kv-lead">Используем только сертифицированный металлопрокат. Покрытие подбираем под условия эксплуатации</p>
        </div>

        <div class="kv-grid-3">
            <?php
            $materials = [
                ['icon' => '⚙️', 'name' => 'Кованый металл',         'desc' => 'Ст3сп, ст10 — классическая кузнечная ковка. Сечение прутка 12–40 мм. Пластичность + прочность.'],
                ['icon' => '🔩', 'name' => 'Профильная труба',        'desc' => 'Горячекатаная ст3 и ст20. Сечение 15×15 – 100×100 мм. Оптимальна для ворот и заборов.'],
                ['icon' => '🌟', 'name' => 'Нержавеющая сталь',       'desc' => 'AISI 304 и AISI 316L. Для влажных зон, бассейнов, морского климата.'],
                ['icon' => '🎨', 'name' => 'Порошковая окраска',      'desc' => '120 цветов RAL. Толщина слоя 60–80 мкм. Устойчивость к УФ, морозу −60°C, жаре +120°C.'],
                ['icon' => '🔥', 'name' => 'Горячее цинкование',      'desc' => 'Слой цинка 50–85 мкм. Защита от коррозии 40+ лет. Оптимально для уличных конструкций.'],
                ['icon' => '✨', 'name' => 'Патина и состаривание',   'desc' => 'Золото, серебро, медь, бронза. Ручная работа. Создаёт уникальный антикварный вид.'],
            ];
            foreach ($materials as $m) : ?>
            <div class="kv-material-card" data-aos="fade-up">
                <div class="kv-material-card__icon"><?= $m['icon'] ?></div>
                <div>
                    <h4><?= esc_html($m['name']) ?></h4>
                    <p><?= esc_html($m['desc']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 9: КАЛЬКУЛЯТОР (CTA)
     ============================================================ -->
<section class="kv-section" style="background:linear-gradient(135deg,#1C0F00,#2D1800)">
    <div class="kv-container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center">
            <div>
                <span class="kv-section-label" style="color:#FFAB40">Калькулятор</span>
                <h2 style="color:#fff">Узнайте цену за 2 минуты</h2>
                <p style="color:rgba(255,255,255,.7);font-size:1.05rem;margin:16px 0 28px">
                    Выберите тип изделия, укажите размеры и покрытие.
                    Калькулятор покажет ориентировочную стоимость — точную цену уточним после замера.
                </p>
                <div class="kv-badge-row">
                    <span class="kv-tag" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.7)">🚪 Ворота</span>
                    <span class="kv-tag" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.7)">🏠 Заборы</span>
                    <span class="kv-tag" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.7)">🪜 Лестницы</span>
                    <span class="kv-tag" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.7)">🪑 Мебель</span>
                </div>
                <div class="kv-mt-32">
                    <a href="<?php echo home_url('/calculator/'); ?>" class="kv-btn kv-btn--white kv-btn--lg">
                        Открыть полный калькулятор →
                    </a>
                </div>
            </div>

            <!-- Быстрый калькулятор -->
            <div style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:36px">
                <h3 style="color:#fff;margin-bottom:24px">Быстрый расчёт</h3>
                <div id="kv-quick-calc">
                    <div class="kv-form-group">
                        <label style="color:rgba(255,255,255,.7)">Тип изделия</label>
                        <select class="kv-select" id="qc-category">
                            <option value="gates">Ворота распашные</option>
                            <option value="fence">Забор / секция</option>
                            <option value="stairs">Лестница</option>
                            <option value="railing">Перила / ограждение</option>
                            <option value="furniture">Мебель</option>
                            <option value="decor">Декор</option>
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div class="kv-form-group">
                            <label style="color:rgba(255,255,255,.7)">Ширина (м)</label>
                            <input type="number" class="kv-input" id="qc-width" value="3" min="0.5" max="20" step="0.5">
                        </div>
                        <div class="kv-form-group">
                            <label style="color:rgba(255,255,255,.7)">Высота (м)</label>
                            <input type="number" class="kv-input" id="qc-height" value="2" min="0.5" max="10" step="0.5">
                        </div>
                    </div>
                    <div class="kv-form-group">
                        <label style="color:rgba(255,255,255,.7)">Покрытие</label>
                        <select class="kv-select" id="qc-coating">
                            <option value="powder">Порошковая окраска</option>
                            <option value="hot_zinc">Горячее цинкование (+35%)</option>
                            <option value="patina">Патина (+20%)</option>
                            <option value="none">Без покрытия (−15%)</option>
                        </select>
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:rgba(255,255,255,.7);margin-bottom:20px;font-size:.9rem">
                        <input type="checkbox" id="qc-install"> Включить монтаж (+25%)
                    </label>
                    <button class="kv-btn kv-btn--primary" style="width:100%" id="qc-submit">
                        Рассчитать
                    </button>
                    <div id="qc-result" style="display:none;margin-top:20px;background:rgba(230,81,0,.15);border:1px solid rgba(230,81,0,.4);border-radius:10px;padding:20px;text-align:center">
                        <div style="color:rgba(255,255,255,.6);font-size:.85rem;margin-bottom:6px">Ориентировочная стоимость</div>
                        <div id="qc-result-val" style="font-size:1.8rem;font-weight:800;color:#FFAB40;font-family:var(--kv-font-head)"></div>
                        <div style="color:rgba(255,255,255,.5);font-size:.78rem;margin-top:8px">Точную цену уточним после бесплатного замера</div>
                        <a href="#kv-modal" class="kv-btn kv-btn--primary kv-btn--sm kv-modal-open" style="margin-top:14px">Заказать замер</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 10: ПРАЙС-ТАБЛИЦА
     ============================================================ -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Стоимость</span>
            <h2>Ориентировочные цены на ковку</h2>
            <p class="kv-lead">Цена зависит от сложности, размера и покрытия. Точную стоимость рассчитываем бесплатно</p>
        </div>

        <div style="overflow-x:auto">
            <table class="kv-price-table" style="min-width:600px">
                <thead>
                    <tr>
                        <th>Изделие</th>
                        <th>Единица</th>
                        <th>Материал</th>
                        <th>Цена от</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $prices = [
                        ['Ворота распашные 3×2 м',       'шт.',    'Кованый металл',    '45 000 ₽'],
                        ['Ворота откатные 4×2 м',         'шт.',    'Профильная труба',  '38 000 ₽'],
                        ['Забор кованый (секция 2×1.5 м)','секция', 'Кованый металл',    '8 500 ₽'],
                        ['Перила лестницы',               'пог.м',  'Кованый металл',    '6 500 ₽'],
                        ['Лестница маршевая',             'ступень','Кованый металл',    '4 800 ₽'],
                        ['Кованый стол',                  'шт.',    'Металл + стекло',   '28 000 ₽'],
                        ['Кованый стул / кресло',         'шт.',    'Металл + ткань',    '12 000 ₽'],
                        ['Подсвечник',                    'шт.',    'Кованый металл',    '1 800 ₽'],
                        ['Кованое панно (1 м²)',          'шт.',    'Кованый металл',    '22 000 ₽'],
                        ['Кованая кровать',               'шт.',    'Кованый металл',    '65 000 ₽'],
                    ];
                    foreach ($prices as $row) : ?>
                    <tr>
                        <td><strong><?= esc_html($row[0]) ?></strong></td>
                        <td><?= esc_html($row[1]) ?></td>
                        <td><span class="kv-tag"><?= esc_html($row[2]) ?></span></td>
                        <td><?= esc_html($row[3]) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="kv-notice kv-mt-24">
            📌 Цены ориентировочные. Финальная стоимость зависит от размеров, сложности узора и покрытия.
            <strong>Замер и дизайн-проект — бесплатно.</strong>
            <a href="#kv-modal" class="kv-modal-open" style="color:var(--kv-accent)">Получить точный расчёт →</a>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 11: ОТЗЫВЫ
     ============================================================ -->
<section class="kv-section kv-section--subtle">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Отзывы</span>
            <h2>Что говорят наши клиенты</h2>
            <p class="kv-lead">4.9 звезды из 5 на основании 320+ проверенных отзывов</p>
        </div>

        <div class="kv-grid-3">
            <?php
            $reviews = get_posts(['post_type' => 'kv_review', 'posts_per_page' => 3, 'meta_key' => 'kv_r_featured', 'meta_value' => '1']);
            if ($reviews) :
                foreach ($reviews as $rv) :
                    $rating = kv_field('kv_rating', $rv->ID) ?: 5;
                    $city   = kv_field('kv_city', $rv->ID);
                    $photo  = kv_field('kv_r_photo', $rv->ID) ?: get_template_directory_uri() . '/assets/img/avatar-placeholder.jpg';
            ?>
            <div class="kv-review-card" data-aos="fade-up">
                <?php echo kv_stars($rating); ?>
                <p class="kv-review-text"><?= esc_html($rv->post_content) ?></p>
                <div class="kv-review-author">
                    <img src="<?= esc_url($photo) ?>" alt="<?= esc_attr($rv->post_title) ?>" loading="lazy">
                    <div>
                        <div class="kv-review-author__name"><?= esc_html($rv->post_title) ?></div>
                        <div class="kv-review-author__meta"><?= esc_html($city) ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata();
            else : ?>
            <!-- Статические отзывы -->
            <?php
            $static_reviews = [
                ['name' => 'Андрей Соколов',   'city' => 'Москва',         'rating' => 5, 'text' => 'Заказывали ворота распашные 4×2 метра с калиткой. Всё сделали за 18 дней. Сварные швы идеальные, покраска равномерная. Уже 2 года — ни пятна ржавчины. Рекомендую без оговорок!'],
                ['name' => 'Марина Петрова',   'city' => 'Подмосковье',    'rating' => 5, 'text' => 'Делали лестницу в частном доме. Приехали замерщики, предложили 3 варианта дизайна, всё нарисовали на компьютере. Результат превзошёл ожидания — гости спрашивают, где делали.'],
                ['name' => 'Сергей Громов',    'city' => 'Санкт-Петербург','rating' => 5, 'text' => 'Забор с элементами художественной ковки для загородного дома. Ребята сделали эскиз прямо на встрече. Монтаж занял один день. Соседи уже несколько раз спрашивали контакты.'],
            ];
            foreach ($static_reviews as $rv) : ?>
            <div class="kv-review-card" data-aos="fade-up">
                <?php echo kv_stars($rv['rating']); ?>
                <p class="kv-review-text">"<?= esc_html($rv['text']) ?>"</p>
                <div class="kv-review-author">
                    <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--kv-accent),var(--kv-accent-dark));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-family:var(--kv-font-head)">
                        <?= mb_substr($rv['name'], 0, 1) ?>
                    </div>
                    <div>
                        <div class="kv-review-author__name"><?= esc_html($rv['name']) ?></div>
                        <div class="kv-review-author__meta"><?= esc_html($rv['city']) ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>

        <div class="kv-text-center kv-mt-32">
            <div style="display:flex;gap:20px;justify-content:center;flex-wrap:wrap">
                <a href="https://maps.google.com" target="_blank" rel="noopener" class="kv-btn kv-btn--secondary">
                    📍 Google — 4.9 ★
                </a>
                <a href="https://yandex.ru/maps" target="_blank" rel="noopener" class="kv-btn kv-btn--secondary">
                    🗺️ Яндекс — 4.8 ★
                </a>
                <a href="https://avito.ru" target="_blank" rel="noopener" class="kv-btn kv-btn--secondary">
                    📋 Avito — 5.0 ★
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 12: НАША МАСТЕРСКАЯ (О ПРОИЗВОДСТВЕ)
     ============================================================ -->
<section class="kv-section">
    <div class="kv-container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center">
            <div>
                <span class="kv-section-label">Производство</span>
                <h2>Наша кузница — 1 800 м² мастерства</h2>
                <p class="kv-lead kv-mt-16">
                    Мы не посредники. Вы работаете напрямую с производством.
                    Это означает честную цену, контроль на каждом этапе и личную ответственность мастера.
                </p>
                <ul style="margin-top:24px;display:flex;flex-direction:column;gap:14px">
                    <?php
                    $facts = [
                        '47 кузнецов и слесарей с опытом от 8 лет',
                        'ЧПУ-станки + ручная художественная ковка',
                        'Покрасочная камера с климат-контролем',
                        'Испытания на прочность перед отгрузкой',
                        'Видеосъёмка производства по запросу клиента',
                    ];
                    foreach ($facts as $f) : ?>
                    <li style="display:flex;gap:12px;align-items:flex-start;font-size:.95rem">
                        <span style="color:var(--kv-accent);font-size:1.1rem;flex-shrink:0">✓</span>
                        <?= esc_html($f) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="kv-mt-32 kv-btn-group">
                    <a href="<?php echo home_url('/about/'); ?>" class="kv-btn kv-btn--primary">Подробнее о нас</a>
                    <a href="<?php echo home_url('/portfolio/'); ?>" class="kv-btn kv-btn--secondary">Смотреть работы</a>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                <div style="border-radius:12px;overflow:hidden;aspect-ratio:1;background:linear-gradient(135deg,#2D1A00,#1C0F00);display:flex;align-items:center;justify-content:center;font-size:3rem;opacity:.5">
                    ⚒️
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 13: ГАРАНТИЯ
     ============================================================ -->
<section class="kv-section kv-section--gold">
    <div class="kv-container">
        <div style="display:grid;grid-template-columns:auto 1fr;gap:60px;align-items:center">
            <div style="text-align:center">
                <div style="font-size:5rem;line-height:1;font-family:var(--kv-font-head);font-weight:800;color:#FFAB40">25</div>
                <div style="font-size:1.2rem;color:rgba(255,255,255,.8)">лет</div>
                <div style="font-size:.9rem;color:rgba(255,255,255,.6);margin-top:4px">гарантии</div>
            </div>
            <div>
                <h2 style="color:#FFAB40;margin-bottom:16px">Железная гарантия — буквально</h2>
                <p style="color:rgba(255,255,255,.8);font-size:1.05rem;margin-bottom:24px">
                    Мы даём письменную гарантию 25 лет на все конструкции. Это не маркетинг — это юридически закреплённое обязательство в договоре.
                    При горячем цинковании покрытие служит 40+ лет.
                </p>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
                    <?php
                    $guarantees = [
                        ['icon' => '🔩', 'title' => 'Сварные швы',    'desc' => 'Проверяем каждый шов. Гарантия на разрыв — 25 лет.'],
                        ['icon' => '🎨', 'title' => 'Покрытие',        'desc' => 'Порошок держится 15+ лет. Горячий цинк — 40+ лет.'],
                        ['icon' => '🏗️', 'title' => 'Конструкция',    'desc' => 'Расчёт нагрузок. Не гнётся, не провисает.'],
                    ];
                    foreach ($guarantees as $g) : ?>
                    <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:20px">
                        <div style="font-size:1.5rem;margin-bottom:8px"><?= $g['icon'] ?></div>
                        <div style="font-family:var(--kv-font-head);font-weight:700;color:#fff;margin-bottom:6px"><?= esc_html($g['title']) ?></div>
                        <div style="font-size:.82rem;color:rgba(255,255,255,.6)"><?= esc_html($g['desc']) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 14: РАССРОЧКА И ОПЛАТА
     ============================================================ -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Оплата</span>
            <h2>Удобная оплата без переплат</h2>
        </div>
        <div class="kv-grid-4">
            <?php
            $payments = [
                ['icon' => '0️⃣', 'title' => 'Рассрочка 0%',        'text' => 'До 6 месяцев без процентов через Сбербанк и Тинькофф'],
                ['icon' => '💳', 'title' => 'Картой онлайн',         'text' => 'Visa, MasterCard, МИР — безопасный эквайринг'],
                ['icon' => '🏦', 'title' => 'Наличные и перевод',    'text' => 'Наличные в офисе или перевод на расчётный счёт'],
                ['icon' => '📑', 'title' => 'Для юрлиц',            'text' => 'Договор, счёт, закрывающие документы. НДС 20%.'],
            ];
            foreach ($payments as $p) : ?>
            <div class="kv-usp-card">
                <div class="kv-usp-card__icon"><?= $p['icon'] ?></div>
                <h3><?= esc_html($p['title']) ?></h3>
                <p><?= esc_html($p['text']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 15: КОМАНДА
     ============================================================ -->
<section class="kv-section kv-section--subtle">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Команда</span>
            <h2>Мастера с душой — не просто металл</h2>
            <p class="kv-lead">47 специалистов. Средний стаж — 14 лет. Победители всероссийских конкурсов кузнецов.</p>
        </div>

        <div class="kv-grid-4">
            <?php
            $team = get_posts(['post_type' => 'kv_team', 'posts_per_page' => 4]);
            if ($team) :
                foreach ($team as $member) :
                    $role  = kv_field('kv_role', $member->ID);
                    $exp   = kv_field('kv_experience', $member->ID);
                    $photo = kv_field('kv_t_photo', $member->ID) ?: get_the_post_thumbnail_url($member->ID, 'kv-square');
            ?>
            <div class="kv-usp-card" style="text-align:center">
                <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin:0 auto 16px;background:var(--kv-bg)">
                    <?php if ($photo) : ?>
                    <img src="<?= esc_url($photo) ?>" alt="<?= esc_attr($member->post_title) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                    <?php else : ?>
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--kv-accent),var(--kv-accent-dark));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;font-family:var(--kv-font-head);font-weight:700">
                        <?= mb_substr($member->post_title, 0, 1) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <h3 style="font-size:.95rem"><?= esc_html($member->post_title) ?></h3>
                <p style="color:var(--kv-accent);font-size:.8rem;margin-bottom:4px"><?= esc_html($role) ?></p>
                <?php if ($exp) : ?>
                <p style="font-size:.78rem">Стаж: <?= esc_html($exp) ?> лет</p>
                <?php endif; ?>
            </div>
            <?php endforeach; wp_reset_postdata();
            else :
            $static_team = [
                ['name' => 'Иван Кузнецов',     'role' => 'Главный кузнец',      'exp' => 22, 'init' => 'И'],
                ['name' => 'Пётр Железнов',      'role' => 'Мастер художественной ковки', 'exp' => 17, 'init' => 'П'],
                ['name' => 'Алексей Сварной',    'role' => 'Сварщик 6-го разряда', 'exp' => 14, 'init' => 'А'],
                ['name' => 'Марина Дизайнова',   'role' => 'Дизайнер проектов',   'exp' => 9,  'init' => 'М'],
            ];
            foreach ($static_team as $m) : ?>
            <div class="kv-usp-card" style="text-align:center">
                <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--kv-accent),var(--kv-accent-dark));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;font-family:var(--kv-font-head);font-weight:700;margin:0 auto 16px">
                    <?= $m['init'] ?>
                </div>
                <h3 style="font-size:.95rem"><?= esc_html($m['name']) ?></h3>
                <p style="color:var(--kv-accent);font-size:.8rem;margin-bottom:4px"><?= esc_html($m['role']) ?></p>
                <p style="font-size:.78rem">Стаж: <?= $m['exp'] ?> лет</p>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 16: FAQ
     ============================================================ -->
<section class="kv-section">
    <div class="kv-container">
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:80px">
            <div>
                <span class="kv-section-label">Вопросы и ответы</span>
                <h2>Часто спрашивают</h2>
                <p class="kv-lead kv-mt-16">
                    Не нашли ответа? Задайте вопрос по телефону или в чате — ответим за 5 минут.
                </p>
                <div class="kv-mt-24">
                    <?php $phone = get_theme_mod('kv_phone', '+7 (800) 555-00-00'); ?>
                    <a href="tel:<?= preg_replace('/\D/', '', $phone) ?>" class="kv-btn kv-btn--primary">
                        📞 <?= esc_html($phone) ?>
                    </a>
                </div>
            </div>

            <div>
                <?php
                $faqs_db = get_posts(['post_type' => 'kv_faq', 'posts_per_page' => 8]);
                $faqs_static = [
                    ['q' => 'Сколько стоит ковка под ключ?',
                     'a' => 'Стоимость зависит от типа изделия, размеров, сложности узора и покрытия. Ориентировочно: ворота — от 38 000 ₽, забор — от 4 500 ₽/пм, лестница — от 4 800 ₽/ступень. Точную цену рассчитаем после бесплатного замера.'],
                    ['q' => 'Как долго делается заказ?',
                     'a' => 'Стандартные изделия — 14–21 рабочий день. Простые декоративные элементы — от 7 дней. При срочном заказе возможно ускорение за дополнительную плату. Срок указываем в договоре.'],
                    ['q' => 'Делаете монтаж?',
                     'a' => 'Да, у нас собственная монтажная бригада. Работаем в Москве, Подмосковье и регионах России. Монтаж ворот, заборов, лестниц, мебели — всё под ключ. Стоимость монтажа рассчитывается отдельно по замеру.'],
                    ['q' => 'Какое покрытие лучше всего?',
                     'a' => 'Для улицы рекомендуем горячее цинкование + порошковая окраска — это самая долговечная комбинация (40+ лет). Для интерьера — порошок RAL или патина. Подберём оптимальный вариант под ваши условия.'],
                    ['q' => 'Можно ли изготовить по моему эскизу?',
                     'a' => 'Да, именно так и работаем! Принимаем фото, рисунки, ссылки на примеры. Дизайнер дорабатывает эскиз в 3D, согласуем с вами, затем запускаем в производство. Изменения до запуска — бесплатно.'],
                    ['q' => 'Какая гарантия?',
                     'a' => 'Письменная гарантия 25 лет на конструкцию и сварные швы. На покрытие — 5–15 лет в зависимости от типа. Всё прописывается в договоре.'],
                    ['q' => 'Есть ли рассрочка?',
                     'a' => 'Да, рассрочка 0% на 6 месяцев через Сбербанк и Тинькофф. Первый взнос — от 20% стоимости. Оформление занимает 15 минут на сайте банка.'],
                    ['q' => 'Что если не понравится результат?',
                     'a' => 'Мы показываем 3D-визуализацию до старта производства и согласуем все детали. В процессе изготовления присылаем фото с цеха. Если при сдаче есть замечания — устраняем бесплатно в гарантийный период.'],
                ];

                $items = $faqs_db ?: $faqs_static;
                foreach ($items as $i => $faq) :
                    $q = is_array($faq) ? $faq['q'] : $faq->post_title;
                    $a = is_array($faq) ? $faq['a'] : wp_strip_all_tags($faq->post_content);
                ?>
                <div class="kv-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <div class="kv-faq-question" tabindex="0" role="button" aria-expanded="false">
                        <span itemprop="name"><?= esc_html($q) ?></span>
                        <div class="kv-faq-icon">+</div>
                    </div>
                    <div class="kv-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <span itemprop="text"><?= esc_html($a) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 17: БЛОГ
     ============================================================ -->
<section class="kv-section kv-section--subtle">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Блог</span>
            <h2>Полезное о ковке</h2>
            <p class="kv-lead">Советы по выбору, уходу, вдохновение и обзоры готовых проектов</p>
        </div>

        <div class="kv-grid-3">
            <?php
            $posts = get_posts(['posts_per_page' => 3, 'post_status' => 'publish']);
            if ($posts) :
                foreach ($posts as $post) :
                    setup_postdata($post);
                    $img = get_the_post_thumbnail_url($post->ID, 'kv-wide');
                    $cats = get_the_category($post->ID);
            ?>
            <a href="<?= get_permalink($post->ID) ?>" class="kv-blog-card" style="text-decoration:none;display:block">
                <?php if ($img) : ?>
                <img src="<?= esc_url($img) ?>" alt="<?= esc_attr($post->post_title) ?>" loading="lazy">
                <?php else : ?>
                <div style="aspect-ratio:16/9;background:linear-gradient(135deg,#2D1A00,#1C0F00);display:flex;align-items:center;justify-content:center;font-size:3rem">⚒️</div>
                <?php endif; ?>
                <div class="kv-blog-card__body">
                    <?php if ($cats) : ?>
                    <div class="kv-blog-card__cat"><?= esc_html($cats[0]->name) ?></div>
                    <?php endif; ?>
                    <div class="kv-blog-card__title"><?= esc_html($post->post_title) ?></div>
                    <div class="kv-blog-card__meta"><?= get_the_date('d.m.Y', $post->ID) ?></div>
                </div>
            </a>
            <?php endforeach; wp_reset_postdata();
            else : ?>
            <?php
            $static_posts = [
                ['title' => 'Как выбрать ворота: распашные vs откатные — плюсы и минусы',     'cat' => 'Ворота и калитки',    'date' => '05.03.2026'],
                ['title' => 'Покрытие для уличной ковки: порошок или горячий цинк?',           'cat' => 'Материалы',           'date' => '01.03.2026'],
                ['title' => 'Кованая мебель в интерьере 2026: 15 вдохновляющих идей',          'cat' => 'Дизайн',              'date' => '25.02.2026'],
            ];
            foreach ($static_posts as $p) : ?>
            <div class="kv-blog-card">
                <div style="aspect-ratio:16/9;background:linear-gradient(135deg,#2D1A00,#1C0F00);display:flex;align-items:center;justify-content:center;font-size:3rem">⚒️</div>
                <div class="kv-blog-card__body">
                    <div class="kv-blog-card__cat"><?= esc_html($p['cat']) ?></div>
                    <div class="kv-blog-card__title"><?= esc_html($p['title']) ?></div>
                    <div class="kv-blog-card__meta"><?= $p['date'] ?></div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>

        <div class="kv-text-center kv-mt-32">
            <a href="<?php echo home_url('/blog/'); ?>" class="kv-btn kv-btn--secondary">Читать все статьи →</a>
        </div>
    </div>
</section>

<!-- ============================================================
     БЛОК 18: ФИНАЛЬНЫЙ CTA + ФОРМА
     ============================================================ -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-cta-banner">
            <div>
                <h2>Готовы начать ваш проект?</h2>
                <p>Бесплатный замер + 3D-проект + точный расчёт за 24 часа</p>
                <div class="kv-badge-row kv-mt-16">
                    <span class="kv-tag kv-tag--accent">✓ Без предоплаты до договора</span>
                    <span class="kv-tag kv-tag--accent">✓ Замерщик бесплатно</span>
                    <span class="kv-tag kv-tag--accent">✓ Ответ за 15 минут</span>
                </div>
            </div>
            <div class="kv-cta-banner__actions">
                <?php $phone = get_theme_mod('kv_phone', '+7 (800) 555-00-00'); ?>
                <a href="tel:<?= preg_replace('/\D/', '', $phone) ?>" class="kv-btn kv-btn--white kv-btn--lg">
                    📞 <?= esc_html($phone) ?>
                </a>
                <a href="#kv-modal" class="kv-btn kv-btn--ghost kv-modal-open">
                    Написать онлайн
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
