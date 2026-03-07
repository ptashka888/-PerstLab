<?php
/**
 * Template: Car Brand Taxonomy Archive
 * SILO Level 2 (Brand Hub) — URL: /catalog/{brand-slug}/
 *
 * Chain: Главная → /avto-iz-{country}/ → /catalog/{brand}/?country={c} → /catalog/{model}/
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

$term    = get_queried_object();
$term_id = $term->term_id ?? 0;
$slug    = $term->slug    ?? '';

// ── ACF brand meta ────────────────────────────────────────────────────────────
$brand_logo = function_exists('get_field') ? get_field('cf_brand_logo', $term)  : '';
$brand_desc = function_exists('get_field') ? get_field('cf_brand_intro', $term) : '';
$brand_pros = function_exists('get_field') ? get_field('cf_brand_pros', $term)  : [];

// ── Static brand defaults ─────────────────────────────────────────────────────
$brand_defaults = [
    'kia' => [
        'origin'    => '🇰🇷 Корея',
        'tagline'   => 'Технологичность и стиль по разумной цене',
        'intro'     => '<p>KIA — один из лидеров мирового авторынка. После объединения с Hyundai Group качество марки выросло до уровня европейских производителей. Мы привозим KIA напрямую с корейского рынка — без скрытых наценок и пробега по России.</p>',
        'pros'      => [
            ['icon' => '🛡️', 'text' => '7-летняя гарантия производителя на новые модели'],
            ['icon' => '💰', 'text' => 'Выгода до 30% vs дилерские цены в РФ'],
            ['icon' => '⚙️', 'text' => 'Богатые комплектации: подогревы, камеры, ADAS'],
            ['icon' => '🔋', 'text' => 'Электромобили EV6, EV9 с запасом 500+ км'],
            ['icon' => '📋', 'text' => 'Проверка по базам Kornet (аналог Carfax для Кореи)'],
            ['icon' => '⚡', 'text' => 'Доставка из Кореи от 14 дней — паром Пусан→Владивосток'],
        ],
        'price_from'     => '1 200 000',
        'countries'      => ['korea' => '🇰🇷 Корея', 'china' => '🇨🇳 Китай'],
        'popular_models' => ['KIA Sportage', 'KIA K5', 'KIA Sorento', 'KIA EV6', 'KIA Carnival'],
    ],
    'hyundai' => [
        'origin'    => '🇰🇷 Корея',
        'tagline'   => 'Доступная роскошь с корейским качеством',
        'intro'     => '<p>Hyundai — одна из крупнейших автогрупп мира. Сочетает современный дизайн, технологии безопасности и конкурентные цены. С нашей помощью вы получите Hyundai из Кореи дешевле дилерских цен — с проверкой и полными документами.</p>',
        'pros'      => [
            ['icon' => '🏆', 'text' => 'Победитель рейтингов надёжности J.D. Power'],
            ['icon' => '💎', 'text' => 'Богатый выбор: от Solaris до Genesis GV80'],
            ['icon' => '🔋', 'text' => 'Электрические модели IONIQ 5, IONIQ 6'],
            ['icon' => '💰', 'text' => 'Экономия 25–40% vs официальные дилеры РФ'],
            ['icon' => '⚡', 'text' => 'Паром Пусан → Владивосток за 3 дня'],
            ['icon' => '🛡️', 'text' => 'Проверка по официальной базе HMC'],
        ],
        'price_from'     => '1 100 000',
        'countries'      => ['korea' => '🇰🇷 Корея', 'china' => '🇨🇳 Китай'],
        'popular_models' => ['Hyundai Tucson', 'Hyundai Santa Fe', 'Hyundai Elantra', 'Hyundai Sonata', 'IONIQ 5'],
    ],
    'toyota' => [
        'origin'    => '🇯🇵 Япония',
        'tagline'   => 'Легенда надёжности — прямо с японских аукционов',
        'intro'     => '<p>Toyota — синоним надёжности. Японские автомобили с аукционов USS, TAA, JU — реальный пробег, честная история, машины-долгожители. Мы участвуем в японских торгах ежедневно с 2016 года.</p>',
        'pros'      => [
            ['icon' => '🏆', 'text' => '№1 по надёжности в рейтинге Consumer Reports 20+ лет'],
            ['icon' => '🔋', 'text' => 'Гибриды: Prius, Camry Hybrid, Highlander Hybrid'],
            ['icon' => '📊', 'text' => 'Аукционный лист — оценка от А до С по состоянию'],
            ['icon' => '🌊', 'text' => 'Паром Япония → Владивосток за 3 дня'],
            ['icon' => '💰', 'text' => 'Экономия 35–50% vs официальные цены в РФ'],
            ['icon' => '🔧', 'text' => 'Запчасти — самые доступные и распространённые'],
        ],
        'price_from'     => '900 000',
        'countries'      => ['japan' => '🇯🇵 Япония'],
        'popular_models' => ['Toyota Camry', 'Toyota RAV4', 'Toyota Land Cruiser', 'Toyota Prius', 'Toyota Alphard'],
    ],
    'honda' => [
        'origin'    => '🇯🇵 Япония',
        'tagline'   => 'Инженерное совершенство с японских аукционов',
        'intro'     => '<p>Honda — инженерный гений Японии: точные двигатели, управляемость и нестареющий дизайн. CR-V, Accord, Stepwgn, Odyssey — популярны у российских покупателей. Привозим напрямую с аукционов.</p>',
        'pros'      => [
            ['icon' => '⚙️', 'text' => 'VTEC-двигатели — эталон по экономичности и ресурсу'],
            ['icon' => '🚗', 'text' => 'Stepwgn и Odyssey — лучшие минивэны для семьи'],
            ['icon' => '🔋', 'text' => 'Гибриды e:HEV на большинстве популярных моделей'],
            ['icon' => '📋', 'text' => 'Проверка по базам JEVIC и ASNET'],
            ['icon' => '💰', 'text' => 'Цены с аукциона на 30–45% ниже дилерских'],
            ['icon' => '⚡', 'text' => 'Схема «конструктор» — дополнительная экономия'],
        ],
        'price_from'     => '950 000',
        'countries'      => ['japan' => '🇯🇵 Япония'],
        'popular_models' => ['Honda CR-V', 'Honda Accord', 'Honda Stepwgn', 'Honda Vezel', 'Honda Odyssey'],
    ],
    'mazda' => [
        'origin'    => '🇯🇵 Япония',
        'tagline'   => 'Управление удовольствием — из японских аукционов',
        'intro'     => '<p>Mazda — японский бренд с уникальной философией Jinba Ittai («всадник и конь»). SKYACTIV-технологии делают Mazda экономичной, надёжной и азартной в управлении. CX-5, CX-8, Mazda6 — любимые модели тех, кто умеет ценить драйв.</p>',
        'pros'      => [
            ['icon' => '🏎️', 'text' => 'SKYACTIV — лучшие показатели экономичности в классе'],
            ['icon' => '💎', 'text' => 'KODO-дизайн — выглядит дороже своей цены'],
            ['icon' => '🔍', 'text' => 'Чистая история с аукционов USS — честный пробег'],
            ['icon' => '💰', 'text' => 'CX-5 из Японии от 1,5 млн — у дилера от 2,5 млн'],
            ['icon' => '⚙️', 'text' => 'Дизельные версии — редкость для РФ, доступны в Японии'],
            ['icon' => '⚡', 'text' => 'Доставка 18–30 дней через Владивосток'],
        ],
        'price_from'     => '1 100 000',
        'countries'      => ['japan' => '🇯🇵 Япония'],
        'popular_models' => ['Mazda CX-5', 'Mazda CX-8', 'Mazda6', 'Mazda CX-30', 'Mazda MX-30'],
    ],
    'tesla' => [
        'origin'    => '🇺🇸 США',
        'tagline'   => 'Электрическое будущее — доступно уже сегодня',
        'intro'     => '<p>Tesla из США — мировой лидер электромобилей. Получить Tesla в России официально практически невозможно. Мы привозим с американских аукционов Copart и IAAI с полным восстановлением и всеми документами.</p>',
        'pros'      => [
            ['icon' => '⚡', 'text' => 'Запас хода 500–600 км на одной зарядке'],
            ['icon' => '💰', 'text' => 'Model 3 из США от 2,8 млн. Серый рынок РФ — от 4,5 млн'],
            ['icon' => '🔧', 'text' => 'Полное восстановление после страховых случаев в нашем сервисе'],
            ['icon' => '📱', 'text' => 'OTA-обновления — автомобиль улучшается без сервиса'],
            ['icon' => '🛡️', 'text' => 'Autopilot и FSD — технологии без аналогов'],
            ['icon' => '📊', 'text' => 'Clean Title или Salvage + восстановление — оба варианта'],
        ],
        'price_from'     => '2 500 000',
        'countries'      => ['usa' => '🇺🇸 США'],
        'popular_models' => ['Tesla Model 3', 'Tesla Model Y', 'Tesla Model S', 'Tesla Model X'],
    ],
    'lexus' => [
        'origin'    => '🇯🇵 Япония / 🇦🇪 ОАЭ',
        'tagline'   => 'Японский премиум с минимальным пробегом из Дубая',
        'intro'     => '<p>Lexus из ОАЭ — почти новый премиальный автомобиль с минимальным пробегом по ценам значительно ниже российских дилеров. Резиденты Дубая меняют Lexus каждые 1–2 года — уникальная возможность для покупателей из России.</p>',
        'pros'      => [
            ['icon' => '☀️', 'text' => 'Нет соли, нет ржавчины — кузов и ходовая в идеале'],
            ['icon' => '💎', 'text' => 'LX 600, RX 500h, NX 350h — полный выбор флагманов'],
            ['icon' => '📱', 'text' => 'Онлайн-просмотр с нашим агентом в Дубае'],
            ['icon' => '🔍', 'text' => 'Проверка истории у официальных дилеров ОАЭ'],
            ['icon' => '💰', 'text' => 'Экономия 30–40% vs официальные дилеры РФ'],
            ['icon' => '🛳️', 'text' => 'Прямая линия Дубай → Новороссийск'],
        ],
        'price_from'     => '3 500 000',
        'countries'      => ['uae' => '🇦🇪 ОАЭ', 'japan' => '🇯🇵 Япония'],
        'popular_models' => ['Lexus RX 350', 'Lexus LX 600', 'Lexus NX 350h', 'Lexus ES 250', 'Lexus GX 460'],
    ],
    'chery' => [
        'origin'    => '🇨🇳 Китай',
        'tagline'   => 'Лучшее соотношение цена / качество из Китая',
        'intro'     => '<p>Chery — один из ведущих китайских производителей, чьи модели Tiggo 4 Pro, Tiggo 7 Pro и Tiggo 8 Pro бьют рекорды продаж. Богатое оснащение, современный дизайн и доступная цена делают Chery идеальным выбором для семьи.</p>',
        'pros'      => [
            ['icon' => '💰', 'text' => 'Новый Chery Tiggo 7 Pro из Китая — от 1,8 млн. В РФ — от 2,5 млн'],
            ['icon' => '⚙️', 'text' => 'Комплектации уровня европейских авто за вдвое меньшую цену'],
            ['icon' => '🔋', 'text' => 'Гибридные и электрические версии в широком ассортименте'],
            ['icon' => '📦', 'text' => 'Стабильные поставки — работаем с дилерами напрямую'],
            ['icon' => '🛡️', 'text' => 'Гарантия производителя или наша гарантия 1 год'],
            ['icon' => '⚡', 'text' => 'Доставка из Тяньцзиня за 20–30 дней'],
        ],
        'price_from'     => '1 600 000',
        'countries'      => ['china' => '🇨🇳 Китай'],
        'popular_models' => ['Chery Tiggo 4 Pro', 'Chery Tiggo 7 Pro', 'Chery Tiggo 8 Pro', 'Chery Arrizo 8'],
    ],
    'haval' => [
        'origin'    => '🇨🇳 Китай',
        'tagline'   => 'Китайский премиум для ценителей',
        'intro'     => '<p>Haval — суббренд Great Wall Motors, специализирующийся на премиальных кроссоверах. H9, Jolion, Dargo — современные автомобили с полным приводом, богатым оснащением и дизайном уровня европейских конкурентов.</p>',
        'pros'      => [
            ['icon' => '🏔️', 'text' => 'H9 с рамным шасси — конкурент Prado за треть цены'],
            ['icon' => '💎', 'text' => 'Кожаный салон, панорама, HUD в базовой версии'],
            ['icon' => '🔋', 'text' => 'PHEV-версии с электрическим запасом 60+ км'],
            ['icon' => '📦', 'text' => 'Новые авто прямо с завода в Баодине'],
            ['icon' => '💰', 'text' => 'Haval H9 из Китая — от 3,5 млн. Дилеры РФ — от 5,5 млн'],
            ['icon' => '🛡️', 'text' => 'Полный пакет: СБКТС, ЭПТС, ПТС, регистрация'],
        ],
        'price_from'     => '2 200 000',
        'countries'      => ['china' => '🇨🇳 Китай'],
        'popular_models' => ['Haval Jolion', 'Haval F7', 'Haval H9', 'Haval Dargo'],
    ],
];

$d               = $brand_defaults[$slug] ?? [];
$origin          = $d['origin']          ?? '—';
$tagline         = $d['tagline']         ?? 'Автомобили ' . $term->name . ' с доставкой под ключ';
$intro_html      = $brand_desc           ?: ($d['intro'] ?? '');
$pros            = !empty($brand_pros)   ? $brand_pros : ($d['pros'] ?? []);
$price_from      = $d['price_from']      ?? '';
$countries_src   = $d['countries']       ?? [];
$popular_models  = $d['popular_models']  ?? [];
$brand_name      = $term->name;

// ── Country context from GET ──────────────────────────────────────────────────
$active_country      = sanitize_text_field($_GET['country'] ?? '');
$active_country_data = $active_country ? cf_get_country_data($active_country) : [];

// Build h1/subtitle
if ($active_country && !empty($active_country_data['name'])) {
    $h1       = 'Автоподбор ' . $brand_name . ' из ' . $active_country_data['name'];
    $subtitle = 'Привезём ' . $brand_name . ' из ' . $active_country_data['name'] . ' под ключ — таможня, доставка, документы включены';
} else {
    $h1       = 'Автоподбор ' . $brand_name;
    $subtitle = $tagline;
}

// Country slug map for page URLs
$country_page_slugs = [
    'korea' => 'avto-iz-korei',
    'japan' => 'avto-iz-yaponii',
    'china' => 'avto-iz-kitaya',
    'usa'   => 'avto-iz-usa',
    'uae'   => 'avto-iz-oae',
];

$delivery_times = [
    'korea' => '14–25 дней',
    'japan' => '18–35 дней',
    'china' => '20–35 дней',
    'usa'   => '35–50 дней',
    'uae'   => '25–40 дней',
];
?>

<?php cf_breadcrumbs(); ?>

<!-- ══════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════ -->
<section class="cf-brand-hero">
    <div class="cf-container">
        <div class="cf-brand-hero__grid">

            <div class="cf-brand-hero__content">
                <?php if ($active_country && !empty($active_country_data)) :
                    $cp_page = get_page_by_path($country_page_slugs[$active_country] ?? '');
                ?>
                    <nav class="cf-brand-hero__trail" aria-label="Цепочка">
                        <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                        <span>→</span>
                        <?php if ($cp_page) : ?>
                            <a href="<?php echo esc_url(get_permalink($cp_page)); ?>">
                                <?php echo esc_html(($active_country_data['flag'] ?? '') . ' Автоподбор из ' . $active_country_data['name']); ?>
                            </a>
                            <span>→</span>
                        <?php endif; ?>
                        <span><?php echo esc_html($brand_name); ?></span>
                    </nav>
                <?php endif; ?>

                <h1 class="cf-brand-hero__h1"><?php echo esc_html($h1); ?></h1>
                <p class="cf-brand-hero__subtitle"><?php echo esc_html($subtitle); ?></p>

                <div class="cf-brand-hero__meta">
                    <span class="cf-brand-hero__meta-item">🌏 <?php echo esc_html($origin); ?></span>
                    <?php if ($price_from) : ?>
                        <span class="cf-brand-hero__meta-item">💰 от <?php echo esc_html($price_from); ?> ₽</span>
                    <?php endif; ?>
                    <?php if ($term->count) : ?>
                        <span class="cf-brand-hero__meta-item">🚗 <?php echo esc_html($term->count); ?> авто в каталоге</span>
                    <?php endif; ?>
                </div>

                <div class="cf-brand-hero__actions">
                    <a href="#cf-modal" class="cf-btn cf-btn--primary" data-modal="lead">
                        Подобрать <?php echo esc_html($brand_name); ?>
                    </a>
                    <a href="#cf-brand-catalog" class="cf-btn cf-btn--outline">Смотреть каталог ↓</a>
                </div>
            </div>

            <div class="cf-brand-hero__logo-col">
                <?php if ($brand_logo) : ?>
                    <img src="<?php echo esc_url($brand_logo); ?>"
                         alt="<?php echo esc_attr($brand_name); ?>"
                         class="cf-brand-hero__logo"
                         width="300" height="200" loading="eager">
                <?php else : ?>
                    <div class="cf-brand-hero__logo-placeholder">
                        <span><?php echo esc_html(mb_strtoupper(mb_substr($brand_name, 0, 2))); ?></span>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<?php if (!empty($countries_src)) : ?>
<!-- ══════════════════════════════════════════════════════
     COUNTRY TABS (если марка доступна из нескольких стран)
═══════════════════════════════════════════════════════ -->
<div class="cf-brand-countries">
    <div class="cf-container">
        <div class="cf-brand-countries__wrap">
            <span class="cf-brand-countries__label">Откуда привезти:</span>
            <div class="cf-brand-countries__tabs">
                <a href="<?php echo esc_url(get_term_link($term)); ?>"
                   class="cf-brand-countries__tab <?php echo !$active_country ? 'is-active' : ''; ?>">
                    🌍 Все страны
                </a>
                <?php foreach ($countries_src as $c_code => $c_label) :
                    $c_url = add_query_arg('country', $c_code, get_term_link($term));
                ?>
                    <a href="<?php echo esc_url($c_url); ?>"
                       class="cf-brand-countries__tab <?php echo ($active_country === $c_code) ? 'is-active' : ''; ?>">
                        <?php echo esc_html($c_label); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($pros)) : ?>
<!-- ══════════════════════════════════════════════════════
     WHY THIS BRAND
═══════════════════════════════════════════════════════ -->
<section class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-section-header cf-section-header--center">
            <h2 class="cf-section-header__title">Почему выбирают <?php echo esc_html($brand_name); ?></h2>
            <p class="cf-section-header__subtitle">Что делает эту марку особенной — и почему через нас выгоднее</p>
        </div>
        <div class="cf-brand-pros">
            <?php foreach ($pros as $pro) :
                $icon = is_array($pro) ? ($pro['icon'] ?? '✓') : '✓';
                $text = is_array($pro) ? ($pro['text'] ?? '') : (string)$pro;
            ?>
                <div class="cf-brand-pros__item">
                    <span class="cf-brand-pros__icon"><?php echo esc_html($icon); ?></span>
                    <span class="cf-brand-pros__text"><?php echo esc_html($text); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($intro_html) : ?>
<!-- INTRO TEXT -->
<section class="cf-section">
    <div class="cf-container cf-container--narrow">
        <div class="cf-content"><?php echo wp_kses_post($intro_html); ?></div>
    </div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════
     MODELS CATALOG
═══════════════════════════════════════════════════════ -->
<section class="cf-section" id="cf-brand-catalog">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">
                <?php if ($active_country && !empty($active_country_data['name'])) : ?>
                    <?php echo esc_html($brand_name . ' из ' . $active_country_data['name']); ?>
                <?php else : ?>
                    Каталог <?php echo esc_html($brand_name); ?>
                <?php endif; ?>
            </h2>
            <div class="cf-catalog__toolbar">
                <span class="cf-catalog__count">
                    Найдено: <strong><?php echo esc_html($wp_query->found_posts); ?></strong> авто
                </span>
                <select id="cf-sort" class="cf-form__select">
                    <option value="popular">По популярности</option>
                    <option value="price_asc">Цена ↑</option>
                    <option value="price_desc">Цена ↓</option>
                    <option value="year_desc">Сначала новые</option>
                </select>
            </div>
        </div>

        <div class="cf-catalog__grid cf-grid cf-grid--4">
            <?php if (have_posts()) :
                while (have_posts()) : the_post();
                    cf_block('car-card', ['post_id' => get_the_ID()]);
                endwhile;
            else : ?>
                <div class="cf-catalog__empty" style="grid-column: 1/-1; text-align:center; padding:48px 20px;">
                    <p style="font-size:48px; margin:0">🚗</p>
                    <p>Модели <?php echo esc_html($brand_name); ?> скоро появятся.</p>
                    <a href="#cf-modal" class="cf-btn cf-btn--primary" data-modal="lead">Оставить заявку на подбор</a>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($wp_query->max_num_pages > 1) : ?>
            <div class="cf-catalog__pagination">
                <?php echo paginate_links([
                    'total'     => $wp_query->max_num_pages,
                    'current'   => max(1, get_query_var('paged')),
                    'prev_text' => '← Назад',
                    'next_text' => 'Далее →',
                    'type'      => 'list',
                ]); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($popular_models)) : ?>
<!-- QUICK MODEL NAVIGATION -->
<div class="cf-brand-models-nav">
    <div class="cf-container">
        <p class="cf-brand-models-nav__label">Популярные модели <?php echo esc_html($brand_name); ?>:</p>
        <div class="cf-brand-models-nav__list">
            <?php foreach ($popular_models as $model) : ?>
                <a href="<?php echo esc_url(get_post_type_archive_link('car_model') . '?brand=' . urlencode($slug) . '&search=' . urlencode($model)); ?>"
                   class="cf-brand-models-nav__item">
                    <?php echo esc_html($model); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($countries_src)) : ?>
<!-- ══════════════════════════════════════════════════════
     SILO: BRAND × COUNTRY CARDS (Level 3 links)
═══════════════════════════════════════════════════════ -->
<section class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-section-header cf-section-header--center">
            <h2 class="cf-section-header__title">Откуда привозим <?php echo esc_html($brand_name); ?></h2>
            <p class="cf-section-header__subtitle">Каждая страна — своя специфика подбора, аукционов и доставки</p>
        </div>
        <div class="cf-brand-country-cards">
            <?php foreach ($countries_src as $c_code => $c_label) :
                $c_data  = cf_get_country_data($c_code);
                $c_url   = add_query_arg('country', $c_code, get_term_link($term));
                // Try to find dedicated brand+country child page under country page
                $country_page = get_page_by_path($country_page_slugs[$c_code] ?? '');
                $child_page   = $country_page ? get_page_by_path($slug, OBJECT, 'page', $country_page->ID) : null;
                $final_url    = $child_page ? get_permalink($child_page) : $c_url;
            ?>
                <a href="<?php echo esc_url($final_url); ?>" class="cf-brand-country-card">
                    <span class="cf-brand-country-card__flag"><?php echo esc_html($c_data['flag'] ?? ''); ?></span>
                    <div class="cf-brand-country-card__body">
                        <strong><?php echo esc_html($brand_name . ' из ' . ($c_data['name'] ?? $c_code)); ?></strong>
                        <span>Доставка: <?php echo esc_html($delivery_times[$c_code] ?? '—'); ?></span>
                    </div>
                    <span class="cf-brand-country-card__arrow">→</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CALCULATOR -->
<?php cf_block('calculator', ['variant' => 'turnkey']); ?>

<!-- TRUST STRIP -->
<div class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-trust-strip">
            <div class="cf-trust-strip__item"><strong>3100+</strong><span>авто доставлено</span></div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item"><strong>8 лет</strong><span>опыт в импорте</span></div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item"><strong>95%</strong><span>рекомендуют нас</span></div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item"><strong>0 ₽</strong><span>скрытых комиссий</span></div>
        </div>
    </div>
</div>

<!-- FAQ -->
<?php cf_block('faq', ['source' => 'brand']); ?>

<!-- OTHER BRANDS (SILO internal links) -->
<?php
$related = get_terms(['taxonomy' => 'car_brand', 'hide_empty' => true, 'exclude' => [$term_id], 'number' => 10]);
if ($related && !is_wp_error($related)) :
?>
<section class="cf-section">
    <div class="cf-container">
        <h3 class="cf-silo-nav__title">Другие марки в каталоге CarFinance MSK:</h3>
        <div class="cf-silo-tag-cloud">
            <?php foreach ($related as $rb) : ?>
                <a href="<?php echo esc_url(get_term_link($rb)); ?>" class="cf-silo-tag">
                    <?php echo esc_html($rb->name); ?>
                    <span class="cf-silo-tag__count"><?php echo esc_html($rb->count); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<?php cf_block('cta-final', ['variant' => 'default']); ?>

<!-- SEO TEXT -->
<?php $seo_text = function_exists('get_field') ? get_field('cf_brand_seo_text', $term) : ''; ?>
<?php if ($seo_text) : ?>
<section class="cf-section">
    <div class="cf-container">
        <div class="cf-content cf-content--seo"><?php echo wp_kses_post($seo_text); ?></div>
    </div>
</section>
<?php endif; ?>

<?php cf_block('interlinking', ['position' => 'footer']); ?>
<?php get_footer(); ?>
