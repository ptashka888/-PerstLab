<?php
/**
 * Template Name: Author / Team Member Profile
 * URL: /o-nas/{slug}/ — Detailed profile for company spokesperson or team member.
 * Supports Schema.org Person markup.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

// ── Data sources ─────────────────────────────────────────────────────────────
$post_id    = get_the_ID();
$photo_id   = get_post_thumbnail_id($post_id);
$photo_url  = $photo_id ? wp_get_attachment_image_url($photo_id, 'large') : '';

// Try cf_team CPT fields first, then page ACF fields
$name       = cf_get_field('cf_team_name', $post_id)       ?: cf_get_field('cf_author_name', $post_id)       ?: get_the_title();
$role       = cf_get_field('cf_team_role', $post_id)        ?: cf_get_field('cf_author_role', $post_id)       ?: '';
$experience = cf_get_field('cf_team_experience', $post_id)  ?: cf_get_field('cf_author_experience', $post_id) ?: '';
$city       = cf_get_field('cf_team_city', $post_id)        ?: cf_get_field('cf_author_city', $post_id)       ?: '';
$bio        = cf_get_field('cf_team_bio', $post_id)         ?: cf_get_field('cf_author_bio', $post_id)        ?: get_the_content();
$quote      = cf_get_field('cf_team_quote', $post_id)       ?: cf_get_field('cf_author_quote', $post_id)      ?: '';
$telegram   = cf_get_field('cf_team_telegram', $post_id)    ?: '';
$whatsapp   = cf_get_field('cf_team_whatsapp', $post_id)    ?: '';
$instagram  = cf_get_field('cf_team_instagram', $post_id)   ?: '';
$email      = cf_get_field('cf_team_email', $post_id)       ?: '';
$expertise  = cf_get_field('cf_author_expertise', $post_id) ?: [];
$stats      = cf_get_field('cf_author_stats', $post_id)     ?: [];
$cases      = cf_get_field('cf_author_cases', $post_id)     ?: [];

// ── Career timeline ───────────────────────────────────────────────────────────
$timeline = cf_get_field('cf_author_timeline', $post_id) ?: [];

// ── Fallback for Артем Бараниченко (CEO) ──────────────────────────────────────
$slug = get_post_field('post_name', $post_id);
if ($slug === 'artem-baranichenko' || empty($role)) {
    $name       = $name       ?: 'Артем Бараниченко';
    $role       = $role       ?: 'Генеральный директор CarFinance MSK';
    $experience = $experience ?: '8 лет';
    $city       = $city       ?: 'Москва';

    if (empty($bio)) {
        $bio = '<p>Артем Бараниченко — основатель и генеральный директор CarFinance MSK. Более 8 лет специализируется на подборе и импорте автомобилей из Кореи, Японии, Китая, США и ОАЭ.</p>
<p>Начал карьеру в 2016 году, работая с японскими аукционами USS и TAA. В 2018 году основал CarFinance MSK — компанию с принципиально другим подходом: максимальная прозрачность, честные цены и полное юридическое сопровождение каждой сделки.</p>
<p>Под руководством Артема компания выросла до 28 сотрудников, открыла офисы во Владивостоке, Москве, Краснодаре и Сочи. За 8 лет работы — более 3100 доставленных автомобилей и 95% клиентов, готовых рекомендовать нас своим знакомым.</p>
<p>Регулярно выступает экспертом в СМИ по вопросам авторынка, таможенного законодательства и выбора автомобиля за рубежом.</p>';
    }

    if (empty($quote)) {
        $quote = 'Я создал CarFinance MSK потому, что сам не нашёл честной компании, когда хотел купить авто из Японии. Восемь лет спустя мы помогли более чем трём тысячам семей — и каждая история для нас важна.';
    }

    if (empty($expertise)) {
        $expertise = [
            'Подбор авто на японских аукционах',
            'Таможенное оформление и СБКТС',
            'Корейский авторынок (KIA, Hyundai, Genesis)',
            'Финансирование и кредитование',
            'Антикризисные стратегии закупки',
        ];
    }

    if (empty($stats)) {
        $stats = [
            ['value' => '3100+', 'label' => 'Авто доставлено'],
            ['value' => '8 лет', 'label' => 'Опыт в импорте'],
            ['value' => '95%',   'label' => 'Рекомендуют нас'],
            ['value' => '4',     'label' => 'Офиса в России'],
        ];
    }

    if (empty($timeline)) {
        $timeline = [
            ['year' => '2016', 'title' => 'Начало карьеры', 'desc' => 'Первые поставки с японских аукционов USS и TAA. Изучение таможенного законодательства и логистики.'],
            ['year' => '2018', 'title' => 'Основание CarFinance MSK', 'desc' => 'Открыл компанию с принципиально новым подходом: максимальная прозрачность и честные цены.'],
            ['year' => '2019', 'title' => 'Офис во Владивостоке', 'desc' => 'Открыли первый офис — ключевая точка для работы с японскими и корейскими поставками.'],
            ['year' => '2021', 'title' => 'Офисы в Москве и Краснодаре', 'desc' => 'Расширение до трёх городов. В команде — 15 специалистов по подбору и таможне.'],
            ['year' => '2022', 'title' => '1000+ авто доставлено', 'desc' => 'Преодолели тысячный рубеж. Начало работы с китайским рынком — BYD, Haval, Chery.'],
            ['year' => '2024', 'title' => 'Офис в Сочи. 3100+ клиентов', 'desc' => 'Четвёртый офис, 28 сотрудников. 95% клиентов рекомендуют нас друзьям и знакомым.'],
        ];
    }
}

// ── Schema.org Person JSON-LD ─────────────────────────────────────────────────
$schema_person = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Person',
    'name'        => $name,
    'jobTitle'    => $role,
    'worksFor'    => [
        '@type' => 'Organization',
        'name'  => 'CarFinance MSK',
        'url'   => home_url('/'),
    ],
    'url'         => get_permalink($post_id),
    'description' => $quote ?: '',
];
if ($photo_url) $schema_person['image'] = $photo_url;
if ($city)      $schema_person['address'] = ['@type' => 'PostalAddress', 'addressLocality' => $city, 'addressCountry' => 'RU'];
if ($telegram)  $schema_person['sameAs'][] = $telegram;
if ($instagram) $schema_person['sameAs'][] = $instagram;
?>

<script type="application/ld+json"><?php echo wp_json_encode($schema_person, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<?php cf_breadcrumbs(); ?>

<main class="cf-author-page">

    <!-- ── Hero ──────────────────────────────────────────────── -->
    <section class="cf-author-hero">
        <div class="cf-container">
            <div class="cf-author-hero__grid">

                <div class="cf-author-hero__photo-col">
                    <?php if ($photo_url) : ?>
                        <img class="cf-author-hero__photo"
                             src="<?php echo esc_url($photo_url); ?>"
                             alt="<?php echo esc_attr($name); ?>"
                             width="480" height="600"
                             loading="eager">
                    <?php else : ?>
                        <div class="cf-author-hero__photo-placeholder">
                            <span class="cf-author-hero__initials">
                                <?php echo esc_html(implode('', array_map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice(explode(' ', $name), 0, 2)))); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="cf-author-hero__content">
                    <p class="cf-author-hero__overtitle">Команда CarFinance MSK</p>
                    <h1 class="cf-author-hero__name"><?php echo esc_html($name); ?></h1>
                    <?php if ($role) : ?>
                        <p class="cf-author-hero__role"><?php echo esc_html($role); ?></p>
                    <?php endif; ?>

                    <div class="cf-author-hero__meta">
                        <?php if ($experience) : ?>
                            <span class="cf-author-hero__meta-item">⚡ <?php echo esc_html($experience); ?> в автоимпорте</span>
                        <?php endif; ?>
                        <?php if ($city) : ?>
                            <span class="cf-author-hero__meta-item">📍 <?php echo esc_html($city); ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($stats)) : ?>
                        <div class="cf-author-hero__stats">
                            <?php foreach ($stats as $stat) : ?>
                                <div class="cf-author-hero__stat">
                                    <span class="cf-author-hero__stat-value"><?php echo esc_html($stat['value']); ?></span>
                                    <span class="cf-author-hero__stat-label"><?php echo esc_html($stat['label']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($quote) : ?>
                        <blockquote class="cf-author-hero__quote">
                            <p><?php echo esc_html($quote); ?></p>
                        </blockquote>
                    <?php endif; ?>

                    <div class="cf-author-hero__actions">
                        <a href="#cf-modal" class="cf-btn cf-btn--primary" data-modal="lead">Задать вопрос</a>
                        <?php if ($telegram) : ?>
                            <a href="<?php echo esc_url($telegram); ?>" class="cf-btn cf-btn--outline" target="_blank" rel="noopener">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161l-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
                                Telegram
                            </a>
                        <?php endif; ?>
                        <?php if ($whatsapp) : ?>
                            <a href="<?php echo esc_url($whatsapp); ?>" class="cf-btn cf-btn--outline" target="_blank" rel="noopener">WhatsApp</a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── Bio ───────────────────────────────────────────────── -->
    <?php if ($bio) : ?>
    <section class="cf-author-bio cf-section">
        <div class="cf-container cf-container--narrow">
            <h2 class="cf-author-bio__title">О себе</h2>
            <div class="cf-author-bio__text cf-content">
                <?php echo wp_kses_post($bio); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── Career Timeline ───────────────────────────────────── -->
    <?php if (!empty($timeline)) : ?>
    <section class="cf-author-timeline cf-section cf-section--alt">
        <div class="cf-container">
            <h2 class="cf-section-header__title">Путь в автоимпорте</h2>
            <p class="cf-section-header__subtitle">Как <?php echo esc_html(explode(' ', $name)[0]); ?> строил CarFinance MSK</p>
            <div class="cf-career-timeline">
                <?php foreach ($timeline as $step) : ?>
                    <div class="cf-career-timeline__step">
                        <div class="cf-career-timeline__year"><?php echo esc_html($step['year'] ?? ''); ?></div>
                        <div class="cf-career-timeline__body">
                            <strong class="cf-career-timeline__title"><?php echo esc_html($step['title'] ?? ''); ?></strong>
                            <p class="cf-career-timeline__desc"><?php echo esc_html($step['desc'] ?? ''); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── Expertise ─────────────────────────────────────────── -->
    <?php if (!empty($expertise)) : ?>
    <section class="cf-author-expertise cf-section cf-section--alt">
        <div class="cf-container">
            <h2 class="cf-section-header__title">Экспертиза</h2>
            <p class="cf-section-header__subtitle">Ключевые компетенции</p>
            <ul class="cf-author-expertise__list">
                <?php foreach ($expertise as $item) : ?>
                    <li class="cf-author-expertise__item">
                        <span class="cf-author-expertise__check">✓</span>
                        <?php echo esc_html(is_array($item) ? ($item['text'] ?? '') : $item); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── Cases / Reviews by this author ────────────────────── -->
    <?php
    $author_cases = new WP_Query([
        'post_type'      => 'case_study',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'meta_key'       => 'cf_case_manager',
        'meta_value'     => $name,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    ?>
    <?php if ($author_cases->have_posts()) : ?>
    <section class="cf-author-cases cf-section">
        <div class="cf-container">
            <h2 class="cf-section-header__title">Кейсы клиентов</h2>
            <p class="cf-section-header__subtitle">Автомобили, привезённые с участием <?php echo esc_html($name); ?></p>
            <div class="cf-cases-grid">
                <?php while ($author_cases->have_posts()) : $author_cases->the_post(); ?>
                    <?php cf_block('car-card', ['post_id' => get_the_ID(), 'type' => 'case']); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── Blog Posts by Author ──────────────────────────────── -->
    <?php
    // Find WP user by display name or login
    $wp_user = get_user_by('display_name', $name);
    if (!$wp_user) {
        // Try by slug
        $user_query = get_users(['search' => '*' . sanitize_text_field(explode(' ', $name)[0]) . '*', 'search_columns' => ['display_name'], 'number' => 1]);
        $wp_user = $user_query ? $user_query[0] : null;
    }
    $author_posts = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 3,
        'author'         => $wp_user ? $wp_user->ID : -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    ?>
    <?php if ($author_posts->have_posts()) : ?>
    <section class="cf-author-blog cf-section">
        <div class="cf-container">
            <h2 class="cf-section-header__title">Статьи <?php echo esc_html(explode(' ', $name)[0]); ?></h2>
            <p class="cf-section-header__subtitle">Экспертные материалы от первого лица</p>
            <div class="cf-author-blog__grid cf-grid cf-grid--3">
                <?php while ($author_posts->have_posts()) : $author_posts->the_post(); ?>
                    <article class="cf-author-blog__item">
                        <a href="<?php the_permalink(); ?>" class="cf-author-blog__link">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', ['class' => 'cf-author-blog__img', 'loading' => 'lazy']); ?>
                            <?php endif; ?>
                            <div class="cf-author-blog__text">
                                <time class="cf-author-blog__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
                                    <?php echo esc_html(get_the_date('d.m.Y')); ?>
                                </time>
                                <h3 class="cf-author-blog__title"><?php the_title(); ?></h3>
                                <p class="cf-author-blog__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                            </div>
                        </a>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div style="text-align:center;margin-top:24px;">
                <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="cf-btn cf-btn--outline">Все статьи в блоге →</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── Media / Press mentions ─────────────────────────────── -->
    <?php $media_links = cf_get_field('cf_author_media', $post_id); ?>
    <?php if (!empty($media_links)) : ?>
    <section class="cf-author-media cf-section cf-section--alt">
        <div class="cf-container">
            <h2 class="cf-section-header__title">СМИ о нас</h2>
            <div class="cf-author-media__grid">
                <?php foreach ($media_links as $media) : ?>
                    <a href="<?php echo esc_url($media['url'] ?? '#'); ?>" class="cf-author-media__item" target="_blank" rel="noopener">
                        <span class="cf-author-media__source"><?php echo esc_html($media['source'] ?? ''); ?></span>
                        <span class="cf-author-media__title"><?php echo esc_html($media['title'] ?? ''); ?></span>
                        <span class="cf-author-media__date"><?php echo esc_html($media['date'] ?? ''); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── CTA ───────────────────────────────────────────────── -->
    <section class="cf-author-cta cf-section">
        <div class="cf-container cf-container--narrow">
            <div class="cf-author-cta__box">
                <h2 class="cf-author-cta__title">Хотите работать с <?php echo esc_html(explode(' ', $name)[0]); ?>?</h2>
                <p class="cf-author-cta__text">Оставьте заявку — свяжемся с вами в течение 15 минут</p>
                <a href="#cf-modal" class="cf-btn cf-btn--primary cf-btn--lg" data-modal="lead">
                    Оставить заявку
                </a>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
