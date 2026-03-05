<?php
/**
 * Single template for service_page CPT
 *
 * URL pattern: /services/{slug}/
 * e.g. /services/kredit-lizing/
 *      /services/import-pod-klyuch/
 *      /services/trade-in/
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
    the_post();

    $post_id    = get_the_ID();
    $icon       = cf_get_field('cf_service_icon',       $post_id);
    $short_desc = cf_get_field('cf_service_short_desc',  $post_id);
    $benefits   = cf_get_field('cf_service_benefits',    $post_id);
    $packages   = cf_get_field('cf_service_packages',    $post_id);
    ?>

    <!-- Hero -->
    <?php cf_block('hero', [
        'variant' => 'service',
        'title'   => get_the_title(),
        'icon'    => $icon ?: '',
    ]); ?>

    <!-- Short description -->
    <?php if ($short_desc): ?>
        <section class="cf-section">
            <div class="cf-container">
                <div class="cf-content cf-content--intro">
                    <p class="cf-lead"><?php echo esc_html($short_desc); ?></p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Benefits -->
    <?php if ($benefits): ?>
        <section class="cf-section cf-section--alt">
            <div class="cf-container">
                <div class="cf-section__header">
                    <h2 class="cf-section__title">Преимущества</h2>
                </div>
                <div class="cf-grid cf-grid--3">
                    <?php foreach ($benefits as $b): ?>
                        <div class="cf-card">
                            <div class="cf-card__body">
                                <?php if (!empty($b['cf_benefit_icon'])): ?>
                                    <span class="cf-card__icon"><?php echo esc_html($b['cf_benefit_icon']); ?></span>
                                <?php endif; ?>
                                <h3 class="cf-card__title"><?php echo esc_html($b['cf_benefit_title'] ?? ''); ?></h3>
                                <p class="cf-card__text"><?php echo esc_html($b['cf_benefit_text'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Pricing packages -->
    <?php if ($packages): ?>
        <?php cf_block('service-packages', ['packages' => $packages]); ?>
    <?php endif; ?>

    <!-- Main content (WP editor) -->
    <?php if (get_the_content()): ?>
        <section class="cf-section">
            <div class="cf-container">
                <div class="cf-content"><?php the_content(); ?></div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Process steps -->
    <?php cf_block('steps', ['variant' => 'service']); ?>

    <!-- Cases -->
    <?php cf_block('cases', ['variant' => 'grid', 'limit' => 3]); ?>

    <!-- Video reviews -->
    <?php cf_block('reviews-video', ['limit' => 3]); ?>

    <!-- FAQ -->
    <?php cf_block('faq', ['source' => 'service', 'post_id' => $post_id]); ?>

<?php endwhile; ?>

<?php
cf_block('interlinking', ['position' => 'footer']);
cf_block('cta-final',    ['variant'  => 'default']);
get_footer();
