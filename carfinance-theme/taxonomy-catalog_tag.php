<?php
/**
 * Template: Catalog Tag Archive (SEO Tag Pages)
 * URL: /catalog/tags/{tag-slug}/
 * SILO Level 3 — Tag Hub (special characteristics)
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

$term    = get_queried_object();
$slug    = $term->slug ?? '';
$term_id = $term->term_id ?? 0;

/* =====================================================================
 * Static defaults per tag
 * ===================================================================== */
$tag_defaults = [
    'gibrid' => [
        'emoji'   => '🔋⛽',
        'h1'      => 'Купить гибридный автомобиль из-за рубежа',
        'subtitle'=> 'Toyota, Lexus, Hyundai — японские и корейские гибриды под ключ с доставкой',
        'intro'   => 'Гибридные автомобили из Японии — это эффективность, экономия и забота об окружающей среде. Toyota RAV4 Hybrid, Lexus NX 300h, Honda CR-V Hybrid — все эти модели доступны на японских аукционах по ценам, которые в 2 раза ниже официальных дилеров.',
        'pros'    => [
            ['icon' => '⛽', 'title' => 'Расход от 5 л/100 км', 'desc' => 'Экономия топлива до 40% по сравнению с бензином'],
            ['icon' => '🔋', 'title' => 'Батарея не требует зарядки', 'desc' => 'Заряжается от двигателя и при торможении'],
            ['icon' => '🛡️', 'title' => 'Надёжность', 'desc' => 'Японские гибриды Toyota работают 300 000+ км'],
            ['icon' => '🌿', 'title' => 'Низкие выбросы', 'desc' => 'CO₂ ниже 120 г/км — экологичный транспорт'],
            ['icon' => '💰', 'title' => 'Выгода на заправке', 'desc' => 'Экономия 50–80 000 руб/год vs бензинового авто'],
            ['icon' => '📈', 'title' => 'Высокая ликвидность', 'desc' => 'Гибриды хорошо держат цену на вторичном рынке'],
        ],
        'popular'    => ['Toyota RAV4 Hybrid', 'Toyota Prius', 'Lexus NX 300h', 'Honda CR-V Hybrid', 'Hyundai Tucson Hybrid', 'Toyota Camry Hybrid'],
        'price_from' => '1 500 000',
    ],
    'elektro' => [
        'emoji'   => '⚡',
        'h1'      => 'Купить электромобиль из-за рубежа',
        'subtitle'=> 'Tesla, BYD, Hyundai IONIQ — электрокары из США, Китая и Кореи под ключ',
        'intro'   => 'Электромобили из-за рубежа — это будущее, доступное сегодня. Tesla Model 3, BYD Han, Hyundai IONIQ 5 — мы доставляем электрокары с полным пакетом документов и адаптацией для российского рынка.',
        'pros'    => [
            ['icon' => '⚡', 'title' => 'Нулевые расходы на топливо', 'desc' => 'Зарядка от 200 руб. vs 3000 руб. на бензин'],
            ['icon' => '🔧', 'title' => 'Минимальное ТО', 'desc' => 'Нет масла, фильтров, ремня — меньше трат'],
            ['icon' => '🚀', 'title' => 'Мгновенный разгон', 'desc' => 'Электромотор выдаёт 100% момента сразу'],
            ['icon' => '🌱', 'title' => 'Нулевые выбросы', 'desc' => 'Экологичный транспорт для городской среды'],
            ['icon' => '📱', 'title' => 'Умные функции', 'desc' => 'OTA обновления, автопилот, смартфон-ключ'],
            ['icon' => '💰', 'title' => 'Выгода до 40%', 'desc' => 'BYD из Китая дешевле российских аналогов'],
        ],
        'popular'    => ['Tesla Model 3', 'Tesla Model Y', 'BYD Han', 'BYD Seal', 'Hyundai IONIQ 5', 'Hyundai IONIQ 6'],
        'price_from' => '2 500 000',
    ],
    'pravyj-rul' => [
        'emoji'   => '🚗🇯🇵',
        'h1'      => 'Купить праворульный автомобиль из Японии',
        'subtitle'=> 'Японские праворульные авто с аукционов — Toyota, Honda, Mazda под ключ',
        'intro'   => 'Праворульные автомобили из Японии — это высочайшее качество по минимальной цене. Японцы тщательно ухаживают за своими авто, регулярно проходят ТО и не допускают перепробегов. Мы везём праворульные авто напрямую с аукционов USS, TAA, JU.',
        'pros'    => [
            ['icon' => '🏆', 'title' => 'Японское качество', 'desc' => 'Строгие стандарты обслуживания и техосмотра'],
            ['icon' => '📊', 'title' => 'Аукционная оценка', 'desc' => 'От 3 до 5 баллов — только проверенные экземпляры'],
            ['icon' => '🔍', 'title' => '100+ фото', 'desc' => 'Полная фотофиксация каждого дефекта на аукционе'],
            ['icon' => '💴', 'title' => 'Самые низкие цены', 'desc' => 'Японские аукционы — дешевле всех мировых рынков'],
            ['icon' => '📋', 'title' => 'История обслуживания', 
             'desc' => 'Сервисная книжка, чеки ТО, пробег без скрутки'],
            ['icon' => '🚢', 'title' => 'Доставка 14–21 день', 'desc' => 'Владивосток, затем транспортировка по России'],
        ],
        'popular'    => ['Toyota Land Cruiser Prado', 'Honda Fit', 'Toyota Alphard', 'Mazda CX-5', 'Toyota Vitz', 'Subaru Forester'],
        'price_from' => '700 000',
    ],
    'bez-dtp' => [
        'emoji'   => '🛡️',
        'h1'      => 'Купить автомобиль без ДТП из-за рубежа',
        'subtitle'=> 'Чистая история, без аварий и скрытых дефектов — гарантируем честность сделки',
        'intro'   => 'Покупая автомобиль без ДТП, вы защищаете себя от скрытых проблем: деформации рамы, плохих ремонтов, повреждённой электроники. Мы проверяем каждый автомобиль по аукционному листу, отчётам R-Carfax и CarVX перед покупкой.',
        'pros'    => [
            ['icon' => '✅', 'title' => 'Аукционный лист', 'desc' => 'Официальный отчёт с оценкой состояния и дефектами'],
            ['icon' => '🔎', 'title' => 'Проверка по VIN', 'desc' => 'R-Carfax, CarVX — история ДТП и обслуживания'],
            ['icon' => '📏', 'title' => 'Замер геометрии', 'desc' => 'Контроль кузовных зазоров и ЛКП'],
            ['icon' => '🛡️', 'title' => 'Гарантия чистоты', 'desc' => 'Если нашли скрытое ДТП — вернём деньги'],
            ['icon' => '💰', 'title' => 'Сохранение стоимости', 'desc' => 'Авто без ДТП стоит на 20–30% дороже при продаже'],
            ['icon' => '🔒', 'title' => 'Безопасность', 'desc' => 'Неповреждённые подушки безопасности и рама'],
        ],
        'popular'    => ['Toyota RAV4', 'Hyundai Tucson', 'KIA Sportage', 'Honda CR-V', 'Mazda CX-5', 'Subaru Forester'],
        'price_from' => '1 200 000',
    ],
    's-auksiona' => [
        'emoji'   => '🔨',
        'h1'      => 'Автомобили с японского аукциона под ключ',
        'subtitle'=> 'Прямая поставка с аукционов USS, TAA, JU — проверенные авто без посредников',
        'intro'   => 'Покупка с японского аукциона — это самый выгодный способ приобрести качественный автомобиль. Мы работаем напрямую с аукционными домами USS (крупнейший в мире), TAA, JU и Aucnet — без посредников и скрытых наценок.',
        'pros'    => [
            ['icon' => '🏆', 'title' => 'Крупнейшие аукционы', 'desc' => 'USS — 120 000 авто в неделю, TAA, JU, Aucnet'],
            ['icon' => '📊', 'title' => 'Система оценки', 'desc' => 'От 1 до 5 — объективная оценка состояния'],
            ['icon' => '📸', 'title' => '100–200 фото', 'desc' => 'Полная фотодокументация каждого лота'],
            ['icon' => '⏱️', 'title' => 'Онлайн торги', 'desc' => 'Участвуем в торгах в режиме реального времени'],
            ['icon' => '💰', 'title' => 'Аукционная цена', 'desc' => 'Японская цена + наша комиссия — честно и прозрачно'],
            ['icon' => '📋', 'title' => 'Отчёт о сделке', 'desc' => 'Полный отчёт: когда купили, за сколько, как везли'],
        ],
        'popular'    => ['Toyota Land Cruiser', 'Toyota Alphard', 'Honda Fit', 'Toyota RAV4', 'Mazda CX-5', 'Toyota Prius'],
        'price_from' => '700 000',
    ],
    'novye' => [
        'emoji'   => '✨',
        'h1'      => 'Новые автомобили из-за рубежа под ключ',
        'subtitle'=> 'BYD, Haval, KIA, Genesis — новые авто из Китая и Кореи без дилерских наценок',
        'intro'   => 'Новые автомобили из Китая и Кореи — это полная заводская гарантия, нулевой пробег и комплектации, которых нет у российских дилеров. Мы доставляем новые авто с заводов и официальных складов.',
        'pros'    => [
            ['icon' => '✨', 'title' => 'Нулевой пробег', 'desc' => '0 км, заводская упаковка, первый владелец — вы'],
            ['icon' => '🛡️', 'title' => 'Заводская гарантия', 'desc' => '3–5 лет гарантии от производителя'],
            ['icon' => '🎨', 'title' => 'Любой цвет и комплектация', 'desc' => 'Заказываем нужную конфигурацию со склада'],
            ['icon' => '💰', 'title' => 'Ниже российского дилера', 'desc' => 'BYD из Китая на 20–40% дешевле официала'],
            ['icon' => '📦', 'title' => 'Полный пакет', 'desc' => 'СБКТС, ЭПТС, постановка на учёт включены'],
            ['icon' => '🇨🇳', 'title' => 'Китайские новинки', 'desc' => 'Модели, которых ещё нет на российском рынке'],
        ],
        'popular'    => ['BYD Han', 'BYD Seal', 'Haval Jolion', 'Chery Tiggo 8 Pro', 'KIA EV6', 'Genesis GV80'],
        'price_from' => '2 000 000',
    ],
];

$d = $tag_defaults[$slug] ?? [
    'emoji'      => '🚗',
    'h1'         => ($term->name ?? 'Автомобили') . ' из-за рубежа под ключ',
    'subtitle'   => 'Подбираем и доставляем авто с нужными характеристиками из Кореи, Японии, США и Китая',
    'intro'      => 'Найдите идеальный автомобиль в нашем каталоге. Каждое авто проверено, документы оформлены, доставка под ключ.',
    'pros'       => [
        ['icon' => '✅', 'title' => 'Проверка', 'desc' => 'История, ДТП, пробег — всё проверяем'],
        ['icon' => '🚢', 'title' => 'Доставка', 'desc' => 'Под ключ в любой город России'],
        ['icon' => '💰', 'title' => 'Цена', 'desc' => 'На 30–50% дешевле автосалонов'],
        ['icon' => '📸', 'title' => 'Фото', 'desc' => '100+ фото каждого автомобиля'],
        ['icon' => '🔒', 'title' => 'Гарантия', 'desc' => 'Договор и страховка'],
        ['icon' => '📞', 'title' => 'Поддержка', 'desc' => 'Менеджер на связи 24/7'],
    ],
    'popular'    => [],
    'price_from' => '900 000',
];

// ACF override if available
if (function_exists('get_field')) {
    $d['h1']      = get_field('cf_tag_seo_title', $term) ?: $d['h1'];
    $d['intro']   = get_field('cf_tag_intro', $term) ?: $d['intro'];
}

// Country terms for filter
$country_terms   = get_terms(['taxonomy' => 'car_country', 'hide_empty' => true]);
$current_country = get_query_var('country', '');

// Related tags
$related_tags = get_terms(['taxonomy' => 'catalog_tag', 'hide_empty' => true, 'exclude' => [$term_id], 'number' => 12]);
?>

<?php cf_breadcrumbs(); ?>

<!-- HERO -->
<section class="cf-engine-hero cf-tag-hero">
    <div class="cf-container">
        <div class="cf-body-hero__inner">
            <div class="cf-body-hero__emoji"><?php echo $d['emoji']; ?></div>
            <div class="cf-body-hero__content">
                <h1 class="cf-body-hero__title"><?php echo esc_html($d['h1']); ?></h1>
                <p class="cf-body-hero__subtitle"><?php echo esc_html($d['subtitle']); ?></p>
                <div class="cf-body-hero__meta">
                    <span class="cf-badge cf-badge--white">📦 Под ключ</span>
                    <span class="cf-badge cf-badge--white">✅ Все налоги включены</span>
                    <span class="cf-badge cf-badge--white">от <?php echo esc_html($d['price_from']); ?> ₽</span>
                    <?php if ($term->count ?? 0): ?>
                        <span class="cf-badge cf-badge--white"><?php echo esc_html($term->count); ?> авто</span>
                    <?php endif; ?>
                </div>
                <div class="cf-body-hero__actions">
                    <a href="#cf-modal" class="cf-btn cf-btn--primary cf-btn--lg" data-modal="lead">Подобрать авто</a>
                    <a href="<?php echo esc_url(home_url('/calculator/')); ?>" class="cf-btn cf-btn--light cf-btn--lg">Рассчитать стоимость</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADVANTAGES GRID -->
<section class="cf-section cf-section--light">
    <div class="cf-container">
        <h2 class="cf-section__title">Преимущества: <?php echo esc_html($term->name ?? ''); ?></h2>
        <div class="cf-brand-pros">
            <?php foreach ($d['pros'] as $pro): ?>
                <div class="cf-brand-pros__item">
                    <span class="cf-brand-pros__icon"><?php echo $pro['icon']; ?></span>
                    <strong class="cf-brand-pros__title"><?php echo esc_html($pro['title']); ?></strong>
                    <p class="cf-brand-pros__desc"><?php echo esc_html($pro['desc']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- INTRO TEXT -->
<section class="cf-section">
    <div class="cf-container">
        <div class="cf-content cf-content--intro">
            <p><?php echo esc_html($d['intro']); ?></p>
        </div>
    </div>
</section>

<!-- POPULAR MODELS -->
<?php if (!empty($d['popular'])): ?>
<section class="cf-section cf-section--tight">
    <div class="cf-container">
        <h3 class="cf-section__subtitle">Популярные модели в этой категории</h3>
        <div class="cf-brand-models-nav">
            <?php foreach ($d['popular'] as $model_name): ?>
                <span class="cf-brand-models-nav__item"><?php echo esc_html($model_name); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- COUNTRY FILTER TABS -->
<?php if ($country_terms && !is_wp_error($country_terms)): ?>
<section class="cf-section cf-section--tight">
    <div class="cf-container">
        <h3 class="cf-section__subtitle">Фильтр по стране</h3>
        <div class="cf-brand-countries">
            <a href="<?php echo esc_url(get_term_link($term)); ?>"
               class="cf-brand-countries__tab<?php echo !$current_country ? ' cf-brand-countries__tab--active' : ''; ?>">
                🌍 Все страны
            </a>
            <?php foreach ($country_terms as $ct):
                $cd = cf_get_country_data($ct->slug);
            ?>
                <a href="<?php echo esc_url(add_query_arg('country', $ct->slug, get_term_link($term))); ?>"
                   class="cf-brand-countries__tab<?php echo $current_country === $ct->slug ? ' cf-brand-countries__tab--active' : ''; ?>">
                    <?php echo esc_html(($cd['flag'] ?? '') . ' ' . ($cd['name'] ?? $ct->name)); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CAR CATALOG GRID -->
<div class="cf-section">
    <div class="cf-container">
        <?php
        $tax_query = [['taxonomy' => 'catalog_tag', 'field' => 'term_id', 'terms' => $term_id]];
        if ($current_country) {
            $tax_query[] = ['taxonomy' => 'car_country', 'field' => 'slug', 'terms' => sanitize_key($current_country)];
        }
        $q = new WP_Query([
            'post_type'      => 'car_model',
            'post_status'    => 'publish',
            'posts_per_page' => 12,
            'paged'          => max(1, get_query_var('paged')),
            'tax_query'      => $tax_query,
        ]);
        ?>
        <div class="cf-catalog__toolbar">
            <div class="cf-catalog__count">
                <?php if ($q->found_posts): ?>
                    Найдено: <strong><?php echo esc_html($q->found_posts); ?></strong> авто
                <?php else: ?>
                    Актуальные предложения загружаются...
                <?php endif; ?>
            </div>
            <div class="cf-catalog__sort">
                <select class="cf-form__select" onchange="window.location.href=this.value">
                    <option value="">По популярности</option>
                </select>
            </div>
        </div>

        <?php if ($q->have_posts()): ?>
            <div class="cf-catalog__grid cf-grid cf-grid--4">
                <?php while ($q->have_posts()): $q->the_post(); ?>
                    <?php cf_block('car-card', ['post_id' => get_the_ID()]); ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
            <?php if ($q->max_num_pages > 1): ?>
                <div class="cf-catalog__pagination">
                    <?php echo paginate_links([
                        'total'     => $q->max_num_pages,
                        'current'   => max(1, get_query_var('paged')),
                        'prev_text' => '← Назад',
                        'next_text' => 'Далее →',
                        'type'      => 'list',
                    ]); ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="cf-catalog__empty">
                <p>Автомобили в категории «<?php echo esc_html($term->name ?? ''); ?>» появятся в ближайшее время.</p>
                <a href="<?php echo esc_url(home_url('/catalog/')); ?>" class="cf-btn cf-btn--primary">Смотреть весь каталог</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- RELATED TAGS (SILO CROSS-LINKS) -->
<?php if ($related_tags && !is_wp_error($related_tags)): ?>
<section class="cf-section cf-section--light">
    <div class="cf-container">
        <h3 class="cf-section__subtitle">Похожие категории автомобилей</h3>
        <div class="cf-silo-tag-cloud">
            <?php foreach ($related_tags as $rt):
                $rt_emoji = $tag_defaults[$rt->slug]['emoji'] ?? '🚗';
            ?>
                <a href="<?php echo esc_url(get_term_link($rt)); ?>" class="cf-silo-tag">
                    <?php echo $rt_emoji; ?> <?php echo esc_html($rt->name); ?>
                    <span class="cf-silo-tag__count"><?php echo esc_html($rt->count); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- TRUST STRIP -->
<section class="cf-section cf-section--dark">
    <div class="cf-container">
        <div class="cf-trust-strip">
            <div class="cf-trust-strip__item">
                <span class="cf-trust-strip__value">3100+</span>
                <span class="cf-trust-strip__label">Автомобилей доставлено</span>
            </div>
            <div class="cf-trust-strip__item">
                <span class="cf-trust-strip__value">8 лет</span>
                <span class="cf-trust-strip__label">На рынке автоимпорта</span>
            </div>
            <div class="cf-trust-strip__item">
                <span class="cf-trust-strip__value">95%</span>
                <span class="cf-trust-strip__label">Рекомендуют нас</span>
            </div>
            <div class="cf-trust-strip__item">
                <span class="cf-trust-strip__value">4 офиса</span>
                <span class="cf-trust-strip__label">По всей России</span>
            </div>
        </div>
    </div>
</section>

<?php
cf_block('calculator', ['variant' => 'turnkey']);
cf_block('faq', ['source' => 'catalog_tag']);
cf_block('interlinking', ['position' => 'footer']);
cf_block('cta-final', ['variant' => 'default']);

get_footer();
