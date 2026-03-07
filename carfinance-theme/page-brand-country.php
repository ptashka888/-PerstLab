<?php
/**
 * Template Name: Brand + Country Hub
 * SILO Level 3 — URL: /avto-iz-{country}/{brand}/
 *
 * Use for child pages of country landing pages.
 * Example: page "kia" as child of "avto-iz-korei"
 *   → URL: /avto-iz-korei/kia/
 *   → Title: Автоподбор KIA из Кореи
 *
 * The template auto-detects brand and country from page hierarchy.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

$post_id = get_the_ID();

// ── Detect country from parent page ──────────────────────────────────────────
$country_page_map = [
    'avto-iz-korei'   => 'korea',
    'avto-iz-yaponii' => 'japan',
    'avto-iz-kitaya'  => 'china',
    'avto-iz-usa'     => 'usa',
    'avto-iz-oae'     => 'uae',
];

$country_code = '';
$country_data = [];
$ancestors    = get_post_ancestors($post_id);
foreach ($ancestors as $ancestor_id) {
    $ancestor_slug = get_post_field('post_name', $ancestor_id);
    if (isset($country_page_map[$ancestor_slug])) {
        $country_code = $country_page_map[$ancestor_slug];
        $country_data = cf_get_country_data($country_code);
        break;
    }
}

// ── Detect brand from ACF or page slug ────────────────────────────────────────
$brand_slug = cf_get_field('cf_brand_slug', $post_id) ?: get_post_field('post_name', $post_id);
$brand_term = get_term_by('slug', $brand_slug, 'car_brand');
$brand_name = $brand_term ? $brand_term->name : (cf_get_field('cf_brand_name', $post_id) ?: get_the_title());

// ── Country name for display ──────────────────────────────────────────────────
$country_name  = $country_data['name'] ?? '';
$country_flag  = $country_data['flag'] ?? '';
$country_color = $country_data['color'] ?? 'var(--cf-primary)';

// ── Composed titles ───────────────────────────────────────────────────────────
$h1       = 'Автоподбор ' . $brand_name . ($country_name ? ' из ' . $country_name : '');
$subtitle = 'Привезём ' . $brand_name . ($country_name ? ' из ' . $country_name : '') . ' под ключ — таможня, доставка, документы включены';

// ── Content from ACF (optional) ───────────────────────────────────────────────
$intro    = cf_get_field('cf_bc_intro', $post_id)    ?: '';
$pros     = cf_get_field('cf_bc_pros', $post_id)     ?: [];
$cost_ex  = cf_get_field('cf_bc_cost', $post_id)     ?: [];
$seo_text = cf_get_field('cf_bc_seo_text', $post_id) ?: '';
$quote    = cf_get_field('cf_bc_quote', $post_id)    ?: '';

// ── Fallback intro from taxonomy description ──────────────────────────────────
if (empty($intro) && $brand_term) {
    $intro = term_description($brand_term->term_id, 'car_brand');
}

// ── Static pros fallback by brand + country ───────────────────────────────────
if (empty($pros)) {
    $pros_map = [
        'kia'     => [
            'korea' => [
                ['icon' => '🏭', 'text' => 'Прямые поставки с завода KIA в Хвасоне — минимальная цепочка'],
                ['icon' => '📋', 'text' => 'Полная сервисная история, корейский Carfax'],
                ['icon' => '⚡', 'text' => 'Доставка 14–18 дней — ближайшая страна к Владивостоку'],
                ['icon' => '💰', 'text' => 'На 30–40% дешевле российских официальных дилеров KIA'],
                ['icon' => '🔋', 'text' => 'Богатые комплектации: панорама, Harman/Kardon, вентиляция сидений'],
                ['icon' => '✅', 'text' => 'Гарантия до 5 лет от KIA на новые авто'],
            ],
            'china' => [
                ['icon' => '🇨🇳', 'text' => 'KIA China — специальные версии для китайского рынка с 8 подушками'],
                ['icon' => '💰', 'text' => 'На 35–45% дешевле официальных российских KIA'],
                ['icon' => '🔋', 'text' => 'Богатые комплектации: 12.3" экран, BOSE, панорама'],
                ['icon' => '⚡', 'text' => 'Доставка 18–25 дней'],
                ['icon' => '🛡️', 'text' => 'Все авто проверены на сертификацию СБКТС/ЭПТ'],
                ['icon' => '📸', 'text' => '100+ фото с завода или дилерского склада'],
            ],
        ],
        'hyundai' => [
            'korea' => [
                ['icon' => '🏭', 'text' => 'Завод Hyundai Motor Company в Ульсане — крупнейший автозавод мира'],
                ['icon' => '💰', 'text' => 'Hyundai из Кореи на 25–40% дешевле российских дилеров'],
                ['icon' => '🔋', 'text' => 'Hyundai Tucson Hybrid, Santa Fe, Ioniq 5 — широкий выбор'],
                ['icon' => '⚡', 'text' => 'Срок доставки: 14–20 дней во Владивосток'],
                ['icon' => '📋', 'text' => 'Полная корейская сервисная история и Carfax'],
                ['icon' => '✅', 'text' => 'СБКТС, ЭПТС, постановка на учёт включены'],
            ],
        ],
        'toyota' => [
            'japan' => [
                ['icon' => '🏆', 'text' => 'Toyota — самый надёжный производитель по версии J.D. Power'],
                ['icon' => '📊', 'text' => 'Аукционная оценка 4–5 баллов — только проверенные авто'],
                ['icon' => '💰', 'text' => 'Toyota из Японии на 30–45% дешевле официальных дилеров'],
                ['icon' => '🔍', 'text' => 'Аукционный лист: 100+ фото, история обслуживания'],
                ['icon' => '⚡', 'text' => 'Доставка из Японии: 21–35 дней'],
                ['icon' => '🔋', 'text' => 'Гибридные версии RAV4, Camry, Prius — экономия 40%'],
            ],
            'uae' => [
                ['icon' => '🏎️', 'text' => 'Toyota Land Cruiser из ОАЭ — премиальные комплектации'],
                ['icon' => '💰', 'text' => 'В 1.5–2 раза дешевле российских дилеров'],
                ['icon' => '☀️', 'text' => 'Проверены в условиях экстремальной эксплуатации'],
                ['icon' => '📋', 'text' => 'Арабская документация, легальная растаможка в РФ'],
                ['icon' => '⚡', 'text' => 'Доставка 25–40 дней'],
                ['icon' => '🛡️', 'text' => 'Богатые комплектации: кожа, панорама, LED'],
            ],
        ],
        'lexus' => [
            'japan' => [
                ['icon' => '👑', 'text' => 'Lexus — японский премиум с немецкими ценниками в РФ'],
                ['icon' => '💰', 'text' => 'Lexus из Японии на 40–60% дешевле официальных дилеров'],
                ['icon' => '📊', 'text' => 'Аукционная оценка 4–5 — идеальное состояние'],
                ['icon' => '🔋', 'text' => 'Гибриды Lexus RX, NX, ES — экономия 40% на топливе'],
                ['icon' => '⚡', 'text' => 'Доставка 21–35 дней'],
                ['icon' => '🛡️', 'text' => 'Проверка сервисной истории через Lexus Japan'],
            ],
            'uae' => [
                ['icon' => '🇦🇪', 'text' => 'Lexus из ОАЭ — GX, LX в арабских комплектациях'],
                ['icon' => '💰', 'text' => 'На 35–50% дешевле российских официалов'],
                ['icon' => '🏆', 'text' => 'Пробег подтверждён, история проверена'],
                ['icon' => '⚡', 'text' => 'Доставка 25–40 дней'],
                ['icon' => '🛡️', 'text' => 'Полный пакет документов для РФ'],
                ['icon' => '👑', 'text' => 'VIP комплектации: Mark Levinson, вентиляция, Panoramic'],
            ],
        ],
    ];

    // Generic fallbacks by country
    $country_pros = [
        'korea' => [
            ['icon' => '⚡', 'text' => 'Доставка 14–20 дней — самый быстрый маршрут'],
            ['icon' => '💰', 'text' => 'На 30–40% дешевле российских дилеров'],
            ['icon' => '📋', 'text' => 'Полная корейская сервисная история'],
            ['icon' => '🔋', 'text' => 'Богатые комплектации с завода'],
            ['icon' => '✅', 'text' => 'Гарантия производителя'],
            ['icon' => '🛡️', 'text' => 'СБКТС, ЭПТС, постановка на учёт включены'],
        ],
        'japan' => [
            ['icon' => '🏆', 'text' => 'Японское качество — аукционная оценка 3.5–5 баллов'],
            ['icon' => '💰', 'text' => 'На 30–50% дешевле российских аналогов'],
            ['icon' => '📊', 'text' => '100+ фото с аукциона USS, TAA или JU'],
            ['icon' => '🔍', 'text' => 'Подтверждённый пробег — техосмотр каждые 2 года'],
            ['icon' => '⚡', 'text' => 'Доставка 21–35 дней'],
            ['icon' => '🛡️', 'text' => 'Проверка VIN, ДТП, истории до покупки'],
        ],
        'china' => [
            ['icon' => '✨', 'text' => 'Новые авто с завода — нулевой пробег'],
            ['icon' => '💰', 'text' => 'На 20–40% дешевле официальных российских дилеров'],
            ['icon' => '🔋', 'text' => 'Гибриды и электромобили — технологии будущего'],
            ['icon' => '📦', 'text' => 'Доставка 14–21 день'],
            ['icon' => '🛡️', 'text' => 'Гарантия 3–5 лет от производителя'],
            ['icon' => '📋', 'text' => 'СБКТС, ЭПТС, все документы для РФ'],
        ],
        'usa' => [
            ['icon' => '🇺🇸', 'text' => 'Уникальные американские версии — больше опций за меньше денег'],
            ['icon' => '💰', 'text' => 'Выгода 30–50% vs российских цен'],
            ['icon' => '🏆', 'text' => 'Carfax и AutoCheck — полная история авто из США'],
            ['icon' => '⚡', 'text' => 'Доставка 35–50 дней'],
            ['icon' => '📸', 'text' => '100+ фото с аукциона Copart или IAAI'],
            ['icon' => '🛡️', 'text' => 'Полный пакет документов для РФ'],
        ],
        'uae' => [
            ['icon' => '🇦🇪', 'text' => 'Арабские комплектации — богаче российских базовых'],
            ['icon' => '💰', 'text' => 'Выгода 30–50% — нет НДС 20%'],
            ['icon' => '📋', 'text' => 'Полная история обслуживания, один-два владельца'],
            ['icon' => '⚡', 'text' => 'Доставка 25–40 дней'],
            ['icon' => '🔍', 'text' => 'Проверка состояния перед отправкой'],
            ['icon' => '✅', 'text' => 'СБКТС, ЭПТС, растаможка под ключ'],
        ],
    ];

    if (isset($pros_map[$brand_slug][$country_code])) {
        $pros = $pros_map[$brand_slug][$country_code];
    } elseif (isset($country_pros[$country_code])) {
        $pros = $country_pros[$country_code];
    } else {
        $pros = [
            ['icon' => '✅', 'text' => 'Полная проверка до покупки'],
            ['icon' => '🚢', 'text' => 'Доставка под ключ'],
            ['icon' => '💰', 'text' => 'Выгода 30–50% vs дилеров'],
            ['icon' => '📋', 'text' => 'Все документы РФ включены'],
            ['icon' => '🔒', 'text' => 'Договор и страховка'],
            ['icon' => '📞', 'text' => 'Поддержка на каждом этапе'],
        ];
    }
}

// ── Query cars filtered by brand + country ────────────────────────────────────
$tax_query = [['relation' => 'AND']];
if ($brand_term) {
    $tax_query[] = ['taxonomy' => 'car_brand',   'field' => 'id',   'terms' => $brand_term->term_id];
}
if ($country_code) {
    $country_term = get_term_by('slug', $country_code, 'car_country');
    if ($country_term) {
        $tax_query[] = ['taxonomy' => 'car_country', 'field' => 'id', 'terms' => $country_term->term_id];
    }
}
$car_query = new WP_Query([
    'post_type'      => 'car_model',
    'posts_per_page' => 12,
    'post_status'    => 'publish',
    'tax_query'      => $tax_query,
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'cf_price',
    'order'          => 'ASC',
]);

// ── Cost breakdown defaults by country ───────────────────────────────────────
$cost_defaults = [
    'korea' => ['auction' => '900 000 ₽', 'delivery' => '120 000 ₽', 'customs' => '250 000 ₽', 'total' => '1 270 000 ₽'],
    'japan' => ['auction' => '700 000 ₽', 'delivery' => '130 000 ₽', 'customs' => '220 000 ₽', 'total' => '1 050 000 ₽'],
    'china' => ['auction' => '750 000 ₽', 'delivery' => '150 000 ₽', 'customs' => '280 000 ₽', 'total' => '1 180 000 ₽'],
    'usa'   => ['auction' => '1 100 000 ₽', 'delivery' => '180 000 ₽', 'customs' => '320 000 ₽', 'total' => '1 600 000 ₽'],
    'uae'   => ['auction' => '1 500 000 ₽', 'delivery' => '180 000 ₽', 'customs' => '380 000 ₽', 'total' => '2 060 000 ₽'],
];
$cost = $cost_defaults[$country_code] ?? [];

// ── Schema.org ItemList + breadcrumb ─────────────────────────────────────────
$schema_items = [];
if ($car_query->have_posts()) {
    $idx = 0;
    foreach ($car_query->posts as $p) {
        $idx++;
        $price = get_post_meta($p->ID, 'cf_price', true);
        $schema_items[] = [
            '@type'    => 'ListItem',
            'position' => $idx,
            'url'      => get_permalink($p->ID),
            'name'     => get_the_title($p->ID) . ($price ? ' — ' . number_format((int)$price, 0, ',', ' ') . ' ₽' : ''),
        ];
    }
}
if ($schema_items) {
    echo '<script type="application/ld+json">' . wp_json_encode([
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => $h1,
        'itemListElement' => $schema_items,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}
?>

<?php cf_breadcrumbs(); ?>

<main class="cf-brand-country-page">

<!-- ══════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════ -->
<section class="cf-brand-hero cf-brand-hero--country" style="--hero-accent: <?php echo esc_attr($country_color); ?>">
    <div class="cf-container">
        <div class="cf-brand-hero__grid">
            <div class="cf-brand-hero__content">

                <!-- SILO breadcrumb trail -->
                <nav class="cf-brand-hero__trail" aria-label="SILO-путь">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                    <span>→</span>
                    <?php if ($country_code) :
                        $country_page = get_page_by_path(array_search($country_code, $country_page_map));
                    ?>
                        <?php if ($country_page) : ?>
                            <a href="<?php echo esc_url(get_permalink($country_page)); ?>">
                                <?php echo esc_html($country_flag . ' Автоподбор из ' . $country_name); ?>
                            </a>
                            <span>→</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($brand_term) : ?>
                        <a href="<?php echo esc_url(get_term_link($brand_term)); ?>">
                            <?php echo esc_html('Все ' . $brand_name); ?>
                        </a>
                        <span>→</span>
                    <?php endif; ?>
                    <span><?php echo esc_html($brand_name . ($country_name ? ' из ' . $country_name : '')); ?></span>
                </nav>

                <h1 class="cf-brand-hero__h1"><?php echo esc_html($h1); ?></h1>
                <p class="cf-brand-hero__subtitle"><?php echo esc_html($subtitle); ?></p>

                <div class="cf-brand-hero__meta">
                    <?php if ($country_flag && $country_name) : ?>
                        <span class="cf-brand-hero__meta-item"><?php echo esc_html($country_flag . ' ' . $country_name); ?></span>
                    <?php endif; ?>
                    <span class="cf-brand-hero__meta-item">
                        🚗 <?php echo esc_html($car_query->found_posts); ?> авто в наличии
                    </span>
                    <?php
                    $delivery = ['korea' => '14–25', 'japan' => '18–35', 'china' => '20–35', 'usa' => '35–50', 'uae' => '25–40'][$country_code] ?? '';
                    if ($delivery) : ?>
                        <span class="cf-brand-hero__meta-item">⚡ Доставка <?php echo esc_html($delivery); ?> дней</span>
                    <?php endif; ?>
                </div>

                <div class="cf-brand-hero__actions">
                    <a href="#cf-modal" class="cf-btn cf-btn--primary" data-modal="lead">
                        Подобрать <?php echo esc_html($brand_name . ($country_name ? ' из ' . $country_name : '')); ?>
                    </a>
                    <a href="#cf-bc-catalog" class="cf-btn cf-btn--outline">Смотреть в наличии</a>
                </div>
            </div>

            <div class="cf-brand-hero__logo-col">
                <div class="cf-brand-hero__logo-placeholder cf-brand-hero__logo-placeholder--country">
                    <span class="cf-brand-hero__logo-initials"><?php echo esc_html(mb_strtoupper(mb_substr($brand_name, 0, 2))); ?></span>
                    <span class="cf-brand-hero__logo-flag"><?php echo esc_html($country_flag); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PROS -->
<section class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-section-header cf-section-header--center">
            <h2 class="cf-section-header__title">Почему <?php echo esc_html($brand_name . ($country_name ? ' из ' . $country_name : '')); ?></h2>
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

<?php if ($intro) : ?>
<section class="cf-section">
    <div class="cf-container cf-container--narrow">
        <div class="cf-content"><?php echo wp_kses_post(wpautop($intro)); ?></div>
    </div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════
     CARS IN STOCK
═══════════════════════════════════════════════════════ -->
<section class="cf-section" id="cf-bc-catalog">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">
                <?php echo esc_html($brand_name); ?>
                <?php if ($country_name) : ?>из <?php echo esc_html($country_name); ?><?php endif; ?>
                в наличии
            </h2>
            <span class="cf-catalog__count">
                Найдено: <strong><?php echo esc_html($car_query->found_posts); ?></strong>
            </span>
        </div>

        <?php if ($car_query->have_posts()) : ?>
            <div class="cf-catalog__grid cf-grid cf-grid--4">
                <?php while ($car_query->have_posts()) : $car_query->the_post();
                    cf_block('car-card', ['post_id' => get_the_ID()]);
                endwhile; wp_reset_postdata(); ?>
            </div>
            <div class="cf-brand-country-page__more">
                <?php if ($brand_term) : ?>
                    <a href="<?php echo esc_url(add_query_arg('country', $country_code, get_term_link($brand_term))); ?>"
                       class="cf-btn cf-btn--outline">
                        Все <?php echo esc_html($brand_name . ($country_name ? ' из ' . $country_name : '')); ?> →
                    </a>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="cf-catalog__empty" style="text-align:center; padding:40px 20px">
                <p style="font-size:48px">🔍</p>
                <p>Пока нет <?php echo esc_html($brand_name); ?> в каталоге. Оставьте заявку — найдём под ваш запрос.</p>
                <a href="#cf-modal" class="cf-btn cf-btn--primary" data-modal="lead">Оставить заявку</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     COST BREAKDOWN
═══════════════════════════════════════════════════════ -->
<?php if (!empty($cost)) : ?>
<section class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-cost-example">
            <div class="cf-cost-example__header">
                <h2 class="cf-cost-example__title">Пример стоимости <?php echo esc_html($brand_name); ?> из <?php echo esc_html($country_name); ?></h2>
                <p class="cf-cost-example__subtitle">Все расходы до передачи ключей</p>
            </div>
            <div class="cf-cost-example__grid">
                <div class="cf-cost-example__row">
                    <span class="cf-cost-example__num">1</span>
                    <span class="cf-cost-example__label">Цена на аукционе / у дилера</span>
                    <span class="cf-cost-example__value"><?php echo esc_html($cost['auction']); ?></span>
                </div>
                <div class="cf-cost-example__row">
                    <span class="cf-cost-example__num">2</span>
                    <span class="cf-cost-example__label">Доставка до России</span>
                    <span class="cf-cost-example__value"><?php echo esc_html($cost['delivery']); ?></span>
                </div>
                <div class="cf-cost-example__row">
                    <span class="cf-cost-example__num">3</span>
                    <span class="cf-cost-example__label">Таможня + СБКТС + ЭПТС</span>
                    <span class="cf-cost-example__value"><?php echo esc_html($cost['customs']); ?></span>
                </div>
                <div class="cf-cost-example__row cf-cost-example__row--total">
                    <span class="cf-cost-example__label">Итого на руки</span>
                    <span class="cf-cost-example__value"><?php echo esc_html($cost['total']); ?></span>
                </div>
            </div>
            <p class="cf-cost-example__note">* Расчёт приблизительный. Точная стоимость зависит от модели, года, объёма двигателя и курса валют.</p>
            <a href="#cf-modal" class="cf-btn cf-btn--primary" data-modal="lead">Получить точный расчёт бесплатно</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CALCULATOR -->
<?php cf_block('calculator', ['variant' => 'turnkey', 'country' => $country_code]); ?>

<!-- STEPS -->
<?php cf_block('steps', ['variant' => 'country']); ?>

<?php if ($quote) : ?>
<!-- FOUNDER QUOTE -->
<div class="cf-country-founder-quote">
    <div class="cf-container cf-container--narrow">
        <blockquote class="cf-country-founder-quote__block">
            <p><?php echo esc_html($quote); ?></p>
            <footer><cite>Артем Бараниченко, CEO CarFinance MSK</cite></footer>
        </blockquote>
    </div>
</div>
<?php endif; ?>

<!-- TRUST -->
<div class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-trust-strip">
            <div class="cf-trust-strip__item"><strong>3100+</strong><span>авто доставлено</span></div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item"><strong>8 лет</strong><span>в автоимпорте</span></div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item"><strong>95%</strong><span>рекомендуют нас</span></div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item"><strong>0 ₽</strong><span>скрытых комиссий</span></div>
        </div>
    </div>
</div>

<!-- FAQ -->
<?php cf_block('faq', ['source' => 'brand']); ?>

<!-- SILO NAV: links to deeper levels -->
<section class="cf-section">
    <div class="cf-container">
        <div class="cf-silo-nav-box">
            <div class="cf-silo-nav-box__col">
                <h4>Марка <?php echo esc_html($brand_name); ?> по типу кузова:</h4>
                <?php
                $body_types = get_terms(['taxonomy' => 'car_type', 'hide_empty' => true]);
                if ($body_types && !is_wp_error($body_types)) :
                ?>
                    <div class="cf-silo-tag-cloud">
                        <?php foreach ($body_types as $bt) : ?>
                            <a href="<?php echo esc_url(get_post_type_archive_link('car_model') . '?brand=' . urlencode($brand_slug) . '&body_type=' . urlencode($bt->slug) . ($country_code ? '&country=' . urlencode($country_code) : '')); ?>"
                               class="cf-silo-tag">
                                <?php echo esc_html($bt->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($brand_term) : ?>
            <div class="cf-silo-nav-box__col">
                <h4><?php echo esc_html($brand_name); ?> из других стран:</h4>
                <div class="cf-silo-tag-cloud">
                    <?php if (!empty($country_code)) : ?>
                        <a href="<?php echo esc_url(get_term_link($brand_term)); ?>" class="cf-silo-tag">
                            <?php echo esc_html('Все ' . $brand_name); ?>
                        </a>
                    <?php endif; ?>
                    <?php foreach (['korea' => '🇰🇷 Корея', 'japan' => '🇯🇵 Япония', 'china' => '🇨🇳 Китай', 'usa' => '🇺🇸 США', 'uae' => '🇦🇪 ОАЭ'] as $c => $cl) :
                        if ($c === $country_code) continue;
                    ?>
                        <a href="<?php echo esc_url(add_query_arg('country', $c, get_term_link($brand_term))); ?>"
                           class="cf-silo-tag">
                            <?php echo esc_html($brand_name . ' из ' . array_merge(['korea' => 'Кореи', 'japan' => 'Японии', 'china' => 'Китая', 'usa' => 'США', 'uae' => 'ОАЭ'])[$c]); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<?php cf_block('cta-final', ['variant' => 'default']); ?>

<?php if ($seo_text) : ?>
<section class="cf-section">
    <div class="cf-container">
        <div class="cf-content cf-content--seo"><?php echo wp_kses_post($seo_text); ?></div>
    </div>
</section>
<?php endif; ?>

<?php cf_block('interlinking', ['position' => 'footer']); ?>
</main>

<?php get_footer(); ?>
