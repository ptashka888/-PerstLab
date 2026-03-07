<?php
/**
 * Template Name: Страница отдельной услуги
 * Template Post Type: page
 *
 * Individual service page: Замер, Монтаж, Реставрация, etc.
 *
 * @package StoneArt
 */
get_header();

$icon     = function_exists('get_field') ? get_field('sa_sd_icon')     : 'fa-solid fa-screwdriver-wrench';
$subtitle = function_exists('get_field') ? get_field('sa_sd_subtitle') : '';
$steps    = function_exists('get_field') ? get_field('sa_sd_steps')    : [];
$price    = function_exists('get_field') ? get_field('sa_sd_price')    : '';
$faq_cat  = function_exists('get_field') ? get_field('sa_sd_faq_cat')  : '';
?>

<div class="sa-page-header">
    <div class="sa-container">
        <?php sa_breadcrumbs(); ?>
        <h1 class="sa-page-header__title">
            <?php if ($icon) : ?>
                <i class="<?php echo esc_attr($icon); ?> sa-text-primary" style="margin-right:0.5rem;font-size:0.85em;"></i>
            <?php endif; ?>
            <?php the_title(); ?>
        </h1>
        <?php if ($subtitle) : ?>
            <p class="sa-page-header__subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>
    </div>
</div>

<main class="sa-main">

    <!-- Main content -->
    <section class="sa-section sa-section--white">
        <div class="sa-container">
            <div style="display:grid;grid-template-columns:2fr 1fr;gap:3rem;align-items:start;" class="sa-service-layout">
                <div class="sa-prose">
                    <?php the_content(); ?>
                </div>

                <!-- Sidebar: quick info -->
                <div>
                    <div class="sa-card" style="padding:1.75rem;background:var(--sa-gray-50);border:1px solid var(--sa-gray-200);border-radius:var(--sa-radius-lg);">
                        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;">Быстрый заказ</h3>
                        <?php if ($price) : ?>
                            <div style="font-size:1.5rem;font-weight:800;color:var(--sa-primary);margin-bottom:1rem;">
                                <?php echo esc_html($price); ?>
                            </div>
                        <?php endif; ?>
                        <ul style="list-style:none;padding:0;margin:0 0 1.5rem;font-size:0.9rem;display:flex;flex-direction:column;gap:0.5rem;">
                            <li><i class="fa-solid fa-check sa-text-primary mr-2"></i> Выезд в день обращения</li>
                            <li><i class="fa-solid fa-check sa-text-primary mr-2"></i> Договор с фиксированной ценой</li>
                            <li><i class="fa-solid fa-check sa-text-primary mr-2"></i> Гарантия 10 лет</li>
                            <li><i class="fa-solid fa-check sa-text-primary mr-2"></i> Москва и область</li>
                        </ul>
                        <a href="<?php echo esc_url(sa_phone_href()); ?>" class="sa-btn sa-btn--primary" style="width:100%;text-align:center;margin-bottom:0.75rem;">
                            <i class="fa-solid fa-phone"></i> <?php echo esc_html(sa_phone()); ?>
                        </a>
                        <a href="#cta-form-section" class="sa-btn sa-btn--outline" style="width:100%;text-align:center;">
                            Оставить заявку
                        </a>
                        <?php if (sa_whatsapp() !== '#') : ?>
                            <a href="<?php echo esc_url(sa_whatsapp()); ?>" class="sa-btn" style="width:100%;text-align:center;margin-top:0.75rem;background:#22c55e;color:#fff;">
                                <i class="fa-brands fa-whatsapp"></i> WhatsApp
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Steps / Process -->
    <?php if ($steps) : ?>
        <section class="sa-section sa-section--light">
            <div class="sa-container">
                <h2 class="sa-section__title">Как это работает</h2>
                <div class="sa-process" style="margin-top:2rem;">
                    <?php foreach ($steps as $i => $step) : ?>
                        <div class="sa-process__item">
                            <div class="sa-process__number"><?php echo ($i + 1); ?></div>
                            <div class="sa-process__content">
                                <h3 class="sa-process__title"><?php echo esc_html($step['sa_sd_step_title']); ?></h3>
                                <p class="sa-process__text"><?php echo esc_html($step['sa_sd_step_text']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php else : ?>
        <?php get_template_part('template-parts/process'); ?>
    <?php endif; ?>

    <!-- FAQ for this service -->
    <?php if ($faq_cat) : get_template_part('template-parts/faq-block', null, ['cat_name' => $faq_cat]); endif; ?>

    <!-- Reviews -->
    <?php get_template_part('template-parts/reviews'); ?>

    <!-- CTA form -->
    <?php get_template_part('template-parts/cta-form'); ?>

    <!-- Related services -->
    <section class="sa-section sa-section--light">
        <div class="sa-container">
            <h2 class="sa-section__title">Другие услуги</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-top:1.5rem;">
                <?php
                $other_services = [
                    'uslugi-zamer'          => ['icon' => 'fa-solid fa-ruler-combined', 'label' => 'Выезд замерщика'],
                    'uslugi-proektirovanie' => ['icon' => 'fa-solid fa-cube',           'label' => '3D-проектирование'],
                    'uslugi-dostavka'       => ['icon' => 'fa-solid fa-truck',          'label' => 'Доставка'],
                    'uslugi-montazh'        => ['icon' => 'fa-solid fa-screwdriver-wrench','label' => 'Монтаж'],
                    'uslugi-restavratsiya'  => ['icon' => 'fa-solid fa-wand-magic-sparkles','label' => 'Реставрация'],
                    'uslugi-ukhod'          => ['icon' => 'fa-solid fa-spray-can-sparkles','label' => 'Уход за камнем'],
                ];
                foreach ($other_services as $slug => $data) :
                    $pg = get_page_by_path($slug);
                    if (!$pg || $pg->ID === $post->ID) continue;
                    ?>
                    <a href="<?php echo esc_url(get_permalink($pg)); ?>"
                       style="display:flex;align-items:center;gap:0.75rem;padding:1rem;background:#fff;border:1px solid var(--sa-gray-200);border-radius:var(--sa-radius);text-decoration:none;transition:all 0.3s;">
                        <i class="<?php echo esc_attr($data['icon']); ?>" style="color:var(--sa-primary);font-size:1.25rem;flex-shrink:0;"></i>
                        <span style="font-weight:700;font-size:0.9rem;color:var(--sa-gray-800);"><?php echo esc_html($data['label']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
