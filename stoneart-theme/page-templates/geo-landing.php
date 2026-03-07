<?php
/**
 * Template Name: Геолендинг
 * Template Post Type: page
 *
 * City/region landing page for local SEO.
 * e.g. /stoleshnitsy-moscow/ /lestnitsy-spb/
 *
 * @package StoneArt
 */
get_header();

$city       = function_exists('get_field') ? get_field('sa_geo_city')    : 'Москва';
$product    = function_exists('get_field') ? get_field('sa_geo_product') : '';
$geo_phone  = function_exists('get_field') ? get_field('sa_geo_phone')   : sa_phone();
$geo_map    = function_exists('get_field') ? get_field('sa_geo_map')     : '';

$phone_href = 'tel:' . preg_replace('/[^\d+]/', '', $geo_phone ?: sa_phone());
?>

<div class="sa-page-header">
    <div class="sa-container">
        <?php sa_breadcrumbs(); ?>
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p class="sa-page-header__subtitle">
            <i class="fa-solid fa-location-dot sa-text-primary mr-2"></i>
            <?php echo esc_html($city); ?> · Собственное производство · Выезд замерщика бесплатно
        </p>
    </div>
</div>

<main class="sa-main">

    <!-- Hero block with local info -->
    <section class="sa-section sa-section--white">
        <div class="sa-container">
            <div style="display:grid;grid-template-columns:3fr 2fr;gap:3rem;align-items:start;" class="sa-geo-layout">
                <div>
                    <div class="sa-prose">
                        <?php the_content(); ?>
                    </div>

                    <!-- Local benefits -->
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:2rem;">
                        <?php
                        $benefits = [
                            ['icon' => 'fa-solid fa-ruler-combined', 'title' => 'Бесплатный замер',  'text' => 'Выезд замерщика в ' . esc_html($city) . ' — бесплатно'],
                            ['icon' => 'fa-solid fa-truck',          'title' => 'Своя доставка',     'text' => 'Доставка в ' . esc_html($city) . ' собственным транспортом'],
                            ['icon' => 'fa-solid fa-shield-halved',  'title' => 'Гарантия 10 лет',   'text' => 'Официальный договор с фиксированной ценой'],
                            ['icon' => 'fa-solid fa-clock',          'title' => 'Срок 7-14 дней',    'text' => 'Быстрое изготовление на ЧПУ-оборудовании'],
                        ];
                        foreach ($benefits as $b) : ?>
                            <div style="display:flex;gap:0.75rem;padding:1rem;background:var(--sa-gray-50);border-radius:var(--sa-radius);border:1px solid var(--sa-gray-200);">
                                <i class="<?php echo esc_attr($b['icon']); ?>" style="color:var(--sa-primary);font-size:1.5rem;flex-shrink:0;margin-top:0.1rem;"></i>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;"><?php echo esc_html($b['title']); ?></div>
                                    <div style="font-size:0.8rem;color:var(--sa-gray-600);"><?php echo esc_html($b['text']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- CTA card -->
                <div>
                    <div class="sa-card" style="padding:2rem;background:var(--sa-gray-50);border:1px solid var(--sa-gray-200);border-radius:var(--sa-radius-lg);">
                        <h2 style="font-size:1.25rem;font-weight:800;margin-bottom:0.25rem;">Получить расчёт</h2>
                        <p style="font-size:0.9rem;color:var(--sa-gray-600);margin-bottom:1.5rem;">Оставьте контакт — перезвоним в течение 10 минут</p>

                        <form class="sa-contact-form" id="geo-form" method="post" novalidate>
                            <div style="margin-bottom:1rem;">
                                <label class="sa-label">Ваше имя</label>
                                <input type="text" name="name" class="sa-input" placeholder="Иван Иванов" required>
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label class="sa-label">Телефон</label>
                                <input type="tel" name="phone" class="sa-input" placeholder="+7 (___) ___-__-__" required>
                            </div>
                            <div style="margin-bottom:1.5rem;">
                                <label class="sa-label">Изделие</label>
                                <input type="text" name="product" class="sa-input" placeholder="Например: столешница на кухню" value="<?php echo esc_attr($product); ?>">
                            </div>
                            <button type="submit" class="sa-btn sa-btn--primary" style="width:100%;">
                                Получить расчёт
                            </button>
                            <p style="font-size:0.75rem;color:var(--sa-gray-500);margin-top:0.75rem;text-align:center;">
                                Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности
                            </p>
                            <?php wp_nonce_field('sa_nonce', 'nonce'); ?>
                            <input type="hidden" name="form_type" value="contact">
                            <input type="hidden" name="city" value="<?php echo esc_attr($city); ?>">
                        </form>

                        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--sa-gray-200);">
                            <p style="font-size:0.8rem;color:var(--sa-gray-600);margin-bottom:0.5rem;">Или позвоните нам:</p>
                            <a href="<?php echo esc_attr($phone_href); ?>" style="font-size:1.25rem;font-weight:800;color:var(--sa-primary);">
                                <?php echo esc_html($geo_phone ?: sa_phone()); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio in this city -->
    <?php
    $city_portfolio = new WP_Query([
        'post_type'      => 'sa_portfolio',
        'posts_per_page' => 6,
        's'              => $city,
    ]);
    if ($city_portfolio->have_posts()) : ?>
        <section class="sa-section sa-section--light">
            <div class="sa-container">
                <h2 class="sa-section__title">Наши работы в <?php echo esc_html($city); ?></h2>
                <div class="sa-portfolio-grid" style="margin-top:2rem;">
                    <?php while ($city_portfolio->have_posts()) : $city_portfolio->the_post(); ?>
                        <div class="sa-portfolio-item">
                            <?php if (has_post_thumbnail()) :
                                the_post_thumbnail('sa-portfolio', ['class' => 'sa-portfolio-item__img', 'loading' => 'lazy']);
                            endif; ?>
                            <div class="sa-portfolio-item__overlay">
                                <h3 class="sa-portfolio-item__title"><?php the_title(); ?></h3>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Map -->
    <?php if ($geo_map) : ?>
        <section class="sa-section sa-section--white" style="padding-bottom:0;">
            <div class="sa-container">
                <h2 class="sa-section__title">Зона выезда: <?php echo esc_html($city); ?></h2>
            </div>
            <div style="margin-top:1.5rem;height:400px;overflow:hidden;">
                <iframe src="<?php echo esc_url($geo_map); ?>"
                        width="100%" height="400" style="border:0;display:block;"
                        loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </section>
    <?php endif; ?>

    <!-- Reviews -->
    <?php get_template_part('template-parts/reviews'); ?>

</main>

<?php get_footer(); ?>
