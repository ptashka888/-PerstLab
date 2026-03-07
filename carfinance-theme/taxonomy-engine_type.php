<?php
/**
 * Template: Engine Type Taxonomy Archive
 * SILO Level 4 — URL: /catalog/dvigatel/{slug}/
 *
 * Chain: / → /avto-iz-{c}/ → /catalog/{brand}/?country={c} → /catalog/dvigatel/{e}/
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

$term    = get_queried_object();
$term_id = $term->term_id ?? 0;
$slug    = $term->slug    ?? '';

// ── Engine type defaults ──────────────────────────────────────────────────────
$engine_defaults = [
    'benzin' => [
        'emoji'    => '⛽',
        'h1'       => 'Автоподбор бензиновых автомобилей из-за рубежа',
        'subtitle' => 'Привезём бензиновый автомобиль из Кореи, Японии, Китая, США или ОАЭ под ключ',
        'intro'    => '<p>Бензиновые двигатели остаются самыми популярными на мировом авторынке — огромный выбор моделей из всех стран, хорошая динамика и отлаженная сервисная инфраструктура в России. На японских и корейских аукционах бензиновые автомобили составляют более 60% всего предложения.</p><p>Мы ежедневно участвуем в торгах на USS, TAA и корейских аукционах — и знаем, как выбрать бензиновый автомобиль с правильным пробегом и состоянием двигателя.</p>',
        'pros'     => [
            ['icon' => '🔧', 'text' => 'Наибольший выбор моделей на всех рынках'],
            ['icon' => '💰', 'text' => 'Самые доступные цены на обслуживание в РФ'],
            ['icon' => '⚡', 'text' => 'Широкий диапазон мощности — от 65 до 400+ л.с.'],
            ['icon' => '🌍', 'text' => 'Доступны из всех 5 стран: Корея, Япония, Китай, США, ОАЭ'],
            ['icon' => '📋', 'text' => 'Проверяем состояние двигателя до покупки на аукционе'],
            ['icon' => '🏎️', 'text' => 'Турбированные версии — мощность без роста расхода'],
        ],
        'popular'  => ['Toyota Camry', 'KIA Sportage', 'Honda CR-V', 'Hyundai Tucson', 'Tesla Model 3'],
    ],
    'gibrid' => [
        'emoji'    => '🔋⛽',
        'h1'       => 'Автоподбор гибридных автомобилей из-за рубежа',
        'subtitle' => 'Гибриды из Японии, Кореи и Китая — экономичные и надёжные',
        'intro'    => '<p>Японские гибриды Toyota и Honda — одно из самых выгодных вложений: расход 4–6 л/100 км, высокая надёжность, богатые комплектации. На японском рынке гибриды составляют более 40% продаж, поэтому выбор огромный — и цены ниже, чем в России.</p><p>Мы специализируемся на гибридах из Японии с 2018 года. Проверяем состояние батареи и системы рекуперации перед покупкой.</p>',
        'pros'     => [
            ['icon' => '💚', 'text' => 'Расход топлива 4–6 л/100 км vs 8–12 л у бензиновых'],
            ['icon' => '🔋', 'text' => 'Ресурс гибридной батареи Toyota — 300 000+ км'],
            ['icon' => '🌿', 'text' => 'Экологичность — пониженный транспортный налог'],
            ['icon' => '🏙️', 'text' => 'Идеально для города — рекуперация при торможении'],
            ['icon' => '💰', 'text' => 'Prius, Camry Hybrid из Японии на 40–50% дешевле РФ'],
            ['icon' => '🔍', 'text' => 'Диагностика батареи до покупки — защита от сюрпризов'],
        ],
        'popular'  => ['Toyota Prius', 'Toyota Camry Hybrid', 'Honda CR-V Hybrid', 'KIA Niro', 'Hyundai Ioniq'],
    ],
    'elektro' => [
        'emoji'    => '⚡',
        'h1'       => 'Автоподбор электромобилей из-за рубежа',
        'subtitle' => 'Tesla, BYD, KIA EV из США, Китая и Кореи под ключ',
        'intro'    => '<p>Электромобили из-за рубежа — один из самых быстрорастущих сегментов. Tesla из США, BYD и Zeekr из Китая, KIA EV6 и Hyundai IONIQ из Кореи — эти автомобили практически недоступны официально в России, но мы знаем, как их привезти законно.</p><p>Особенность электромобилей — пониженная таможенная пошлина до 15 сентября 2025 года. Сейчас — лучшее время для покупки.</p>',
        'pros'     => [
            ['icon' => '⚡', 'text' => 'Запас хода 400–600 км на одной зарядке'],
            ['icon' => '💰', 'text' => 'Пониженная таможенная пошлина 15% (до конца 2025)'],
            ['icon' => '🔧', 'text' => 'Минимальное обслуживание — нет масла, фильтров, ГРМ'],
            ['icon' => '🌿', 'text' => 'Экологично — нулевые выбросы, тихий ход'],
            ['icon' => '📱', 'text' => 'Tesla Autopilot, BYD DiLink — передовые технологии'],
            ['icon' => '🔋', 'text' => 'Диагностика батареи — проверяем ёмкость до покупки'],
        ],
        'popular'  => ['Tesla Model 3', 'Tesla Model Y', 'KIA EV6', 'BYD Seal', 'Hyundai IONIQ 5'],
    ],
    'dizel' => [
        'emoji'    => '🛢️',
        'h1'       => 'Автоподбор дизельных автомобилей из-за рубежа',
        'subtitle' => 'Надёжные дизели из Японии, Кореи и ОАЭ',
        'intro'    => '<p>Дизельные автомобили — выбор для тех, кто много ездит: низкий расход, высокий ресурс двигателя, отличная тяга. В Японии дизельные версии многих моделей недоступны на российском рынке — например, Mazda CX-5 Diesel или Toyota Land Cruiser 300 Diesel.</p>',
        'pros'     => [
            ['icon' => '💰', 'text' => 'Расход 5–7 л/100 км при активном использовании'],
            ['icon' => '🏋️', 'text' => 'Высокий крутящий момент — идеален для буксировки'],
            ['icon' => '⚙️', 'text' => 'Ресурс дизельного двигателя 500 000+ км'],
            ['icon' => '🌍', 'text' => 'Уникальные версии, недоступные у официальных дилеров'],
            ['icon' => '🛻', 'text' => 'Пикапы и внедорожники — особая специализация'],
            ['icon' => '📋', 'text' => 'Проверяем состояние ТНВД и форсунок до покупки'],
        ],
        'popular'  => ['Toyota Land Cruiser 300 Diesel', 'Mazda CX-5 Diesel', 'Haval H9 Diesel', 'Land Rover Defender'],
    ],
    'turbo' => [
        'emoji'    => '💨',
        'h1'       => 'Автоподбор турбированных автомобилей из-за рубежа',
        'subtitle' => 'Турбодвигатели с японских, корейских и китайских рынков',
        'intro'    => '<p>Современные турбированные двигатели объёмом 1.4–2.0 Turbo обеспечивают динамику 3-литровых моторов при вдвое меньшем расходе. KIA Sportage 1.6T, Hyundai Tucson 1.6T, Chery Tiggo 7 Pro 1.5T — популярные турбо-версии из-за рубежа.</p>',
        'pros'     => [
            ['icon' => '🏎️', 'text' => 'Динамика 3.0 л при расходе 1.6 л'],
            ['icon' => '🌿', 'text' => 'Пониженный налог благодаря меньшему объёму'],
            ['icon' => '⚙️', 'text' => 'Современные GDI-турбо выдерживают 200+ тыс. км без проблем'],
            ['icon' => '💰', 'text' => 'Доступны по ценам атмосферных аналогов'],
            ['icon' => '🔍', 'text' => 'Проверяем систему турбонаддува перед покупкой'],
            ['icon' => '📊', 'text' => 'Популярный выбор для KIA, Hyundai, Chery, Geely'],
        ],
        'popular'  => ['KIA Sportage 1.6T', 'Hyundai Tucson 1.6T', 'Chery Tiggo 7 Pro 1.5T', 'Mazda CX-30 2.0T'],
    ],
];

$d       = $engine_defaults[$slug] ?? [];
$emoji   = $d['emoji']   ?? '⚡';
$h1      = $d['h1']      ?? $term->name . ' — автоподбор из-за рубежа';
$sub     = $d['subtitle'] ?? 'Подбор и доставка авто с двигателем «' . $term->name . '» под ключ';
$intro   = $d['intro']   ?? (term_description($term_id) ?: '');
$pros    = $d['pros']    ?? [];
$popular = $d['popular'] ?? [];
?>

<?php cf_breadcrumbs(); ?>

<!-- ══════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════ -->
<section class="cf-section cf-section--compact cf-engine-hero">
    <div class="cf-container">
        <div class="cf-engine-hero__inner">
            <span class="cf-engine-hero__emoji"><?php echo esc_html($emoji); ?></span>
            <div>
                <h1 class="cf-engine-hero__h1"><?php echo esc_html($h1); ?></h1>
                <p class="cf-engine-hero__subtitle"><?php echo esc_html($sub); ?></p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($pros)) : ?>
<!-- PROS -->
<section class="cf-section cf-section--alt">
    <div class="cf-container">
        <h2 class="cf-section-header__title">Преимущества <?php echo esc_html(mb_strtolower($term->name)); ?> двигателя</h2>
        <div class="cf-brand-pros">
            <?php foreach ($pros as $pro) : ?>
                <div class="cf-brand-pros__item">
                    <span class="cf-brand-pros__icon"><?php echo esc_html($pro['icon'] ?? '✓'); ?></span>
                    <span class="cf-brand-pros__text"><?php echo esc_html($pro['text'] ?? ''); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($intro) : ?>
<section class="cf-section">
    <div class="cf-container cf-container--narrow">
        <div class="cf-content"><?php echo wp_kses_post($intro); ?></div>
    </div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════
     MODELS WITH THIS ENGINE TYPE
═══════════════════════════════════════════════════════ -->
<section class="cf-section">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">Каталог: <?php echo esc_html($term->name); ?></h2>
            <div class="cf-catalog__toolbar">
                <span class="cf-catalog__count">Найдено: <strong><?php echo esc_html($wp_query->found_posts); ?></strong></span>
                <!-- Filter by country -->
                <div class="cf-engine-country-filter">
                    <span>Страна:</span>
                    <?php
                    $all_countries = get_terms(['taxonomy' => 'car_country', 'hide_empty' => true]);
                    $cur_c = sanitize_text_field($_GET['country'] ?? '');
                    ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>"
                       class="cf-silo-tag <?php echo !$cur_c ? 'is-active' : ''; ?>">Все</a>
                    <?php foreach ($all_countries as $ct) : ?>
                        <a href="<?php echo esc_url(add_query_arg('country', $ct->slug, get_term_link($term))); ?>"
                           class="cf-silo-tag <?php echo $cur_c === $ct->slug ? 'is-active' : ''; ?>">
                            <?php echo esc_html($ct->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="cf-catalog__grid cf-grid cf-grid--4">
            <?php if (have_posts()) :
                while (have_posts()) : the_post();
                    cf_block('car-card', ['post_id' => get_the_ID()]);
                endwhile;
            else : ?>
                <div style="grid-column:1/-1;text-align:center;padding:48px 20px;">
                    <p>Автомобилей с таким типом двигателя пока нет.</p>
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

<?php if (!empty($popular)) : ?>
<!-- POPULAR MODELS OF THIS ENGINE TYPE -->
<div class="cf-brand-models-nav">
    <div class="cf-container">
        <p class="cf-brand-models-nav__label">Популярные модели с <?php echo esc_html(mb_strtolower($term->name)); ?> двигателем:</p>
        <div class="cf-brand-models-nav__list">
            <?php foreach ($popular as $model) : ?>
                <a href="<?php echo esc_url(get_post_type_archive_link('car_model') . '?search=' . urlencode($model)); ?>"
                   class="cf-brand-models-nav__item"><?php echo esc_html($model); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- SILO: Links to brands and countries with this engine -->
<section class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-silo-nav-box">
            <div class="cf-silo-nav-box__col">
                <h4><?php echo esc_html($term->name); ?> по странам:</h4>
                <div class="cf-silo-tag-cloud">
                    <?php foreach (['korea' => ['🇰🇷', 'Кореи'], 'japan' => ['🇯🇵', 'Японии'], 'china' => ['🇨🇳', 'Китая'], 'usa' => ['🇺🇸', 'США'], 'uae' => ['🇦🇪', 'ОАЭ']] as $c => [$flag, $from]) : ?>
                        <a href="<?php echo esc_url(add_query_arg('country', $c, get_term_link($term))); ?>"
                           class="cf-silo-tag"><?php echo esc_html($flag . ' Из ' . $from); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="cf-silo-nav-box__col">
                <h4>Другие типы двигателей:</h4>
                <?php
                $other_engines = get_terms(['taxonomy' => 'engine_type', 'hide_empty' => true, 'exclude' => [$term_id]]);
                if ($other_engines && !is_wp_error($other_engines)) :
                ?>
                    <div class="cf-silo-tag-cloud">
                        <?php foreach ($other_engines as $oe) : ?>
                            <a href="<?php echo esc_url(get_term_link($oe)); ?>" class="cf-silo-tag"><?php echo esc_html($oe->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CALCULATOR -->
<?php cf_block('calculator', ['variant' => 'turnkey']); ?>

<!-- TRUST -->
<div class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-trust-strip">
            <div class="cf-trust-strip__item"><strong>3100+</strong><span>авто доставлено</span></div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item"><strong>8 лет</strong><span>в автоимпорте</span></div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item"><strong>95%</strong><span>рекомендуют нас</span></div>
        </div>
    </div>
</div>

<!-- FAQ -->
<?php cf_block('faq', ['source' => 'engine']); ?>

<!-- CTA -->
<?php cf_block('cta-final', ['variant' => 'default']); ?>

<?php $seo_text = function_exists('get_field') ? get_field('cf_engine_seo_text', $term) : ''; ?>
<?php if ($seo_text) : ?>
<section class="cf-section">
    <div class="cf-container">
        <div class="cf-content cf-content--seo"><?php echo wp_kses_post($seo_text); ?></div>
    </div>
</section>
<?php endif; ?>

<?php cf_block('interlinking', ['position' => 'footer']); ?>
<?php get_footer(); ?>
