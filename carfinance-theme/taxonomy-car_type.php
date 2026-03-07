<?php
/**
 * Template: Car Type (Body Type) Taxonomy Archive
 * URL: /catalog/type/{type-slug}/
 * SILO Level 3 — Body Type Hub
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

$term    = get_queried_object();
$slug    = $term->slug ?? '';
$term_id = $term->term_id ?? 0;

/* =====================================================================
 * Static defaults per body type
 * ===================================================================== */
$body_type_defaults = [
    'krossover' => [
        'emoji'   => '🚙',
        'h1'      => 'Автоподбор кроссоверов из-за рубежа',
        'subtitle'=> 'KIA, Toyota, Hyundai, Mazda — лучшие SUV под ключ из Кореи, Японии и США',
        'intro'   => 'Кроссоверы — самый популярный кузов на вторичном рынке. Высокий клиренс, полный привод и просторный салон делают их универсальным выбором для российских дорог. Мы подбираем кроссоверы с японских и корейских аукционов с проверкой истории, фото 360° и гарантией доставки под ключ.',
        'pros'    => [
            ['icon' => '🛣️', 'title' => 'Высокий клиренс', 'desc' => '180–220 мм — справляется с любыми дорогами'],
            ['icon' => '🔄', 'title' => 'Полный привод', 'desc' => 'AWD/4WD для уверенного движения зимой'],
            ['icon' => '👨‍👩‍👧‍👦', 'title' => 'Просторный салон', 'desc' => 'Комфорт для семьи из 5–7 человек'],
            ['icon' => '💰', 'title' => 'Выгодная цена', 'desc' => 'На 30–40% дешевле аналогов из автосалонов'],
            ['icon' => '⛽', 'title' => 'Гибридные версии', 'desc' => 'Toyota RAV4, Hyundai Tucson — расход от 5 л/100 км'],
            ['icon' => '📈', 'title' => 'Высокая ликвидность', 'desc' => 'Кроссоверы держат цену лучше других кузовов'],
        ],
        'popular'    => ['KIA Sportage', 'Toyota RAV4', 'Hyundai Tucson', 'Mazda CX-5', 'Honda CR-V', 'Mitsubishi Outlander'],
        'price_from' => '1 200 000',
    ],
    'sedan' => [
        'emoji'   => '🚗',
        'h1'      => 'Автоподбор седанов из-за рубежа',
        'subtitle'=> 'Toyota, Hyundai, KIA, Honda — бизнес-седаны и семейные авто из Японии и Кореи',
        'intro'   => 'Седан — классика с превосходной аэродинамикой и комфортом. Корейские и японские производители предлагают богато укомплектованные версии по цене в 2–3 раза ниже российских дилеров. Идеальный выбор для города и трасс.',
        'pros'    => [
            ['icon' => '🪑', 'title' => 'Комфортный салон', 'desc' => 'Кожаный интерьер, панорама, вентиляция сидений'],
            ['icon' => '💨', 'title' => 'Низкий расход', 'desc' => 'Аэродинамичный кузов — от 6 л/100 км'],
            ['icon' => '🔇', 'title' => 'Тишина в салоне', 'desc' => 'Шумоизоляция на уровне бизнес-класса'],
            ['icon' => '🛡️', 'title' => 'Безопасность', 'desc' => '5 звёзд NCAP, 6+ подушек безопасности'],
            ['icon' => '💼', 'title' => 'Деловой имидж', 'desc' => 'Статусный вид за разумные деньги'],
            ['icon' => '🔧', 'title' => 'Низкое ТО', 'desc' => 'Японские и корейские авто — самые надёжные'],
        ],
        'popular'    => ['Toyota Camry', 'Hyundai Sonata', 'KIA K5', 'Honda Accord', 'Kia Stinger', 'Genesis G80'],
        'price_from' => '1 400 000',
    ],
    'minivan' => [
        'emoji'   => '🚐',
        'h1'      => 'Автоподбор минивэнов из-за рубежа',
        'subtitle'=> 'Toyota Alphard, Honda Odyssey, Kia Carnival — японские и корейские минивэны под ключ',
        'intro'   => 'Минивэны — выбор семей и бизнеса. Японские Toyota Alphard и Vellfire — культовые автомобили с бизнес-салоном за половину цены официала. Мы везём минивэны с аукционов Японии и Кореи с проверкой всех систем.',
        'pros'    => [
            ['icon' => '👨‍👩‍👧‍👦', 'title' => 'До 8 мест', 'desc' => 'Комфорт для большой семьи или корпоративного авто'],
            ['icon' => '🛋️', 'title' => 'VIP-салон', 'desc' => 'Alphard/Vellfire — диваны, шторки, мониторы'],
            ['icon' => '📦', 'title' => 'Огромный багажник', 'desc' => 'До 500 л при сложенном третьем ряду'],
            ['icon' => '🚪', 'title' => 'Электродвери', 'desc' => 'Sliding doors для удобной посадки'],
            ['icon' => '💰', 'title' => 'Выгода до 50%', 'desc' => 'Alphard из Японии в 2 раза дешевле офдилера'],
            ['icon' => '✅', 'title' => 'Проверка истории', 'desc' => 'Аукционный лист + перевод на русский'],
        ],
        'popular'    => ['Toyota Alphard', 'Toyota Vellfire', 'Honda Odyssey', 'Kia Carnival', 'Nissan Elgrand', 'Toyota Noah'],
        'price_from' => '2 200 000',
    ],
    'hetchbek' => [
        'emoji'   => '🚘',
        'h1'      => 'Автоподбор хэтчбеков из-за рубежа',
        'subtitle'=> 'Honda Fit, Toyota Vitz, Mazda 3 — практичные хэтчбеки из Японии под ключ',
        'intro'   => 'Хэтчбек — городской практичный автомобиль с отличной манёвренностью. Японские хэтчбеки известны сверхнадёжностью, экономичными двигателями и богатым оснащением даже в базе.',
        'pros'    => [
            ['icon' => '🏙️', 'title' => 'Городская манёвренность', 'desc' => 'Легко парковаться, узкие улицы не проблема'],
            ['icon' => '⛽', 'title' => 'Экономичность', 'desc' => 'От 5 л/100 км на гибридных версиях'],
            ['icon' => '🎒', 'title' => 'Практичный багажник', 'desc' => 'До 1000 л при сложенных сиденьях'],
            ['icon' => '🔧', 'title' => 'Дешёвое ТО', 'desc' => 'Запчасти от 500 руб., ТО раз в год'],
            ['icon' => '💴', 'title' => 'Низкая цена входа', 'desc' => 'Отличный авто от 700 000 руб. под ключ'],
            ['icon' => '🛡️', 'title' => 'Надёжность', 'desc' => 'Японские хэтчбеки ходят 200 000+ км без капиталки'],
        ],
        'popular'    => ['Honda Fit', 'Toyota Vitz', 'Mazda 3', 'Subaru Impreza', 'Honda Jazz', 'Nissan Note'],
        'price_from' => '750 000',
    ],
    'pickup' => [
        'emoji'   => '🛻',
        'h1'      => 'Автоподбор пикапов из-за рубежа',
        'subtitle'=> 'Toyota Hilux, Mitsubishi L200, Ford F-150 — надёжные пикапы из США и Японии',
        'intro'   => 'Пикапы из-за рубежа — оптимальный выбор для бизнеса, фермерства и активного отдыха. Американские и японские пикапы отличаются высокой грузоподъёмностью, надёжными рамными конструкциями и долгим сроком службы.',
        'pros'    => [
            ['icon' => '💪', 'title' => 'Высокая грузоподъёмность', 'desc' => 'До 1 тонны в кузове, буксировка до 3.5 т'],
            ['icon' => '🏔️', 'title' => 'Проходимость', 'desc' => 'Рамная конструкция + блокировки дифференциала'],
            ['icon' => '🔩', 'title' => 'Ремонтопригодность', 'desc' => 'Простая конструкция — ремонт в любом сервисе'],
            ['icon' => '🌍', 'title' => 'США и Япония', 'desc' => 'Лучшие пикапы мира доступны нам напрямую'],
            ['icon' => '📋', 'title' => 'Для бизнеса', 'desc' => 'Можно поставить на учёт как коммерческий транспорт'],
            ['icon' => '💰', 'title' => 'Ниже рынка на 40%', 'desc' => 'F-150 из США в 2 раза дешевле дилерских аналогов'],
        ],
        'popular'    => ['Toyota Hilux', 'Mitsubishi L200', 'Ford F-150', 'Chevrolet Silverado', 'Toyota Tacoma', 'Nissan Navara'],
        'price_from' => '2 500 000',
    ],
    'vnedorozhnik' => [
        'emoji'   => '🏔️',
        'h1'      => 'Автоподбор внедорожников из-за рубежа',
        'subtitle'=> 'Toyota Land Cruiser, Lexus LX, Land Rover — серьёзные рамные внедорожники',
        'intro'   => 'Рамные внедорожники для настоящего бездорожья. Toyota Land Cruiser 200/300, Lexus LX, Mitsubishi Pajero — легенды, которые ходят 300 000+ км и востребованы на рынке через 10 лет.',
        'pros'    => [
            ['icon' => '🏆', 'title' => 'Легендарная надёжность', 'desc' => 'Land Cruiser работает в самых жёстких условиях'],
            ['icon' => '🔗', 'title' => 'Рамная конструкция', 'desc' => 'Выдерживает перегрузки, легко восстанавливается'],
            ['icon' => '🗻', 'title' => 'Реальный офф-роуд', 'desc' => 'Блокировки, понижайка, 4WD с отключением'],
            ['icon' => '📈', 'title' => 'Дорожает со временем', 'desc' => 'LC200 — один из немногих авто, которые растут в цене'],
            ['icon' => '🌐', 'title' => 'Весь мир', 'desc' => 'Япония, ОАЭ, США — выбираем лучший экземпляр'],
            ['icon' => '🛡️', 'title' => 'Безопасность', 'desc' => 'Высокий клиренс + рама = максимальная защита'],
        ],
        'popular'    => ['Toyota Land Cruiser 200', 'Toyota Land Cruiser 300', 'Lexus LX 570', 'Mitsubishi Pajero', 'Land Rover Defender', 'Toyota 4Runner'],
        'price_from' => '3 500 000',
    ],
    'kupe' => [
        'emoji'   => '🏎️',
        'h1'      => 'Автоподбор купе из-за рубежа',
        'subtitle'=> 'Honda Civic Type R, Toyota GR86, Ford Mustang — спортивные купе под ключ',
        'intro'   => 'Купе — это стиль, динамика и удовольствие от вождения. Японские спортивные купе и американские масл-кары с документами и историей — доставим из любой точки мира.',
        'pros'    => [
            ['icon' => '🏁', 'title' => 'Спортивная динамика', 'desc' => 'Заниженный центр тяжести, острое рулевое'],
            ['icon' => '🎨', 'title' => 'Стильный дизайн', 'desc' => 'Купе всегда привлекает внимание'],
            ['icon' => '💺', 'title' => 'Спортивный интерьер', 'desc' => 'Ковши, руль с лепестками, панели карбон'],
            ['icon' => '🔑', 'title' => 'Редкость', 'desc' => 'На российском рынке таких авто немного'],
            ['icon' => '🇺🇸', 'title' => 'Масл-кары из США', 'desc' => 'Mustang, Camaro, Challenger — прямая доставка'],
            ['icon' => '🇯🇵', 'title' => 'JDM из Японии', 'desc' => 'GR86, WRX STI, Civic Type R — оригинальные версии'],
        ],
        'popular'    => ['Toyota GR86', 'Honda Civic Type R', 'Ford Mustang', 'Chevrolet Camaro', 'Subaru WRX STI', 'Mazda MX-5'],
        'price_from' => '1 800 000',
    ],
    'universal' => [
        'emoji'   => '🚗',
        'h1'      => 'Автоподбор универсалов из-за рубежа',
        'subtitle'=> 'Subaru Outback, Mazda 6 Wagon, Toyota Corolla Fielder — практичные универсалы',
        'intro'   => 'Универсал — идеальный компромисс между комфортом седана и практичностью кроссовера. Японские универсалы с большим багажником и надёжными моторами пользуются стабильным спросом в России.',
        'pros'    => [
            ['icon' => '📦', 'title' => 'Огромный багажник', 'desc' => 'До 600 л и более при сложенных сиденьях'],
            ['icon' => '🛣️', 'title' => 'Трассовый комфорт', 'desc' => 'Плавность хода на уровне седана'],
            ['icon' => '🔄', 'title' => 'AWD версии', 'desc' => 'Subaru Outback — полный привод как у кроссовера'],
            ['icon' => '⛽', 'title' => 'Экономичность', 'desc' => 'Меньше лобовое сопротивление, чем у SUV'],
            ['icon' => '🔧', 'title' => 'Простое ТО', 'desc' => 'Детали дешевле и доступнее, чем у кроссоверов'],
            ['icon' => '👨‍👩‍👧', 'title' => 'Семейный вариант', 'desc' => 'Практично, комфортно, безопасно'],
        ],
        'popular'    => ['Subaru Outback', 'Mazda 6 Wagon', 'Toyota Corolla Fielder', 'Honda Accord Wagon', 'Subaru Legacy', 'Toyota Caldina'],
        'price_from' => '1 100 000',
    ],
];

$d = $body_type_defaults[$slug] ?? [
    'emoji'      => '🚗',
    'h1'         => 'Автоподбор ' . ($term->name ?? 'автомобилей') . ' из-за рубежа',
    'subtitle'   => 'Подбираем и доставляем авто под ключ из Кореи, Японии, США и Китая',
    'intro'      => 'Мы подбираем ' . mb_strtolower($term->name ?? 'автомобили') . ' с проверенной историей и доставляем под ключ в любой город России.',
    'pros'       => [
        ['icon' => '✅', 'title' => 'Проверка истории', 'desc' => 'Аукционный отчёт, пробег, ДТП'],
        ['icon' => '🚢', 'title' => 'Доставка под ключ', 'desc' => 'Таможня, транспорт, постановка на учёт'],
        ['icon' => '💰', 'title' => 'Выгодная цена', 'desc' => 'На 30–50% дешевле автосалонов'],
        ['icon' => '📸', 'title' => 'Фото с аукциона', 'desc' => '100+ фото каждого автомобиля'],
        ['icon' => '🔒', 'title' => 'Гарантия сделки', 'desc' => 'Договор + страховка'],
        ['icon' => '📞', 'title' => 'Поддержка 24/7', 'desc' => 'Менеджер на связи на каждом этапе'],
    ],
    'popular'    => [],
    'price_from' => '900 000',
];

// Get country terms for filter tabs
$country_terms   = get_terms(['taxonomy' => 'car_country', 'hide_empty' => true]);
$current_country = get_query_var('country', '');
?>

<?php cf_breadcrumbs(); ?>

<!-- HERO -->
<section class="cf-engine-hero cf-body-hero">
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
                        <span class="cf-badge cf-badge--white"><?php echo esc_html($term->count); ?> авто в каталоге</span>
                    <?php endif; ?>
                </div>
                <div class="cf-body-hero__actions">
                    <a href="#cf-modal" class="cf-btn cf-btn--primary cf-btn--lg" data-modal="lead">
                        Подобрать <?php echo esc_html(mb_strtolower($term->name ?? 'авто')); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/calculator/')); ?>" class="cf-btn cf-btn--light cf-btn--lg">
                        Рассчитать стоимость
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADVANTAGES GRID -->
<section class="cf-section cf-section--light">
    <div class="cf-container">
        <h2 class="cf-section__title">Почему выбирают <?php echo esc_html($term->name ?? 'этот тип кузова'); ?></h2>
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

<!-- POPULAR MODELS CHIPS -->
<?php if (!empty($d['popular'])): ?>
<section class="cf-section cf-section--tight">
    <div class="cf-container">
        <h3 class="cf-section__subtitle">Популярные модели</h3>
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
        <h3 class="cf-section__subtitle"><?php echo esc_html($term->name ?? 'Кузов'); ?> по странам</h3>
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
<section class="cf-section">
    <div class="cf-container">
        <?php
        $tax_query = [['taxonomy' => 'car_type', 'field' => 'term_id', 'terms' => $term_id]];
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
                    Найдено: <strong><?php echo esc_html($q->found_posts); ?></strong> <?php echo esc_html($term->name ?? 'автомобилей'); ?>
                <?php else: ?>
                    Загружаем актуальные предложения...
                <?php endif; ?>
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
                <p>Автомобили типа «<?php echo esc_html($term->name ?? ''); ?>» появятся в ближайшее время.</p>
                <a href="<?php echo esc_url(home_url('/catalog/')); ?>" class="cf-btn cf-btn--primary">Смотреть весь каталог</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- SILO CROSS-LINKS -->
<?php
$other_types = get_terms(['taxonomy' => 'car_type', 'hide_empty' => true, 'exclude' => [$term_id], 'number' => 10]);
if ($other_types && !is_wp_error($other_types)):
?>
<section class="cf-section cf-section--light">
    <div class="cf-container">
        <h3 class="cf-section__subtitle">Другие типы кузовов</h3>
        <div class="cf-silo-tag-cloud">
            <?php foreach ($other_types as $ot):
                $ot_emoji = $body_type_defaults[$ot->slug]['emoji'] ?? '🚗';
            ?>
                <a href="<?php echo esc_url(get_term_link($ot)); ?>" class="cf-silo-tag">
                    <?php echo $ot_emoji; ?> <?php echo esc_html($ot->name); ?>
                    <span class="cf-silo-tag__count"><?php echo esc_html($ot->count); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($country_terms && !is_wp_error($country_terms)): ?>
            <h3 class="cf-section__subtitle" style="margin-top:2rem;">
                <?php echo esc_html($term->name ?? 'Кузов'); ?> по странам
            </h3>
            <div class="cf-silo-nav-box">
                <?php foreach ($country_terms as $ct):
                    $cd = cf_get_country_data($ct->slug);
                ?>
                    <a href="<?php echo esc_url(add_query_arg('country', $ct->slug, get_term_link($term))); ?>"
                       class="cf-silo-nav-box__item">
                        <span class="cf-silo-nav-box__flag"><?php echo esc_html($cd['flag'] ?? '🌍'); ?></span>
                        <span><?php echo esc_html($term->name ?? ''); ?> из <?php echo esc_html($cd['name'] ?? $ct->name); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
                <span class="cf-trust-strip__label">Владивосток, Москва, Краснодар, Сочи</span>
            </div>
        </div>
    </div>
</section>

<?php
cf_block('calculator', ['variant' => 'turnkey']);
cf_block('faq', ['source' => 'bodytype']);
cf_block('interlinking', ['position' => 'footer']);
cf_block('cta-final', ['variant' => 'default']);

get_footer();
