<?php
/**
 * Template Name: Service Landing
 * For individual service pages and services overview
 */

defined('ABSPATH') || exit;

get_header();

// Check if this is a service_page CPT single or a regular page
$is_service_cpt = get_post_type() === 'service_page';
?>

<?php if ($is_service_cpt): ?>
    <?php
    $post_id    = get_the_ID();
    $icon       = cf_get_field('cf_service_icon', $post_id);
    $short_desc = cf_get_field('cf_service_short_desc', $post_id);
    $benefits   = cf_get_field('cf_service_benefits', $post_id);
    $packages   = cf_get_field('cf_service_packages', $post_id);
    ?>

    <!-- Hero -->
    <?php cf_block('hero', ['variant' => 'service', 'title' => get_the_title()]); ?>

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
                                <span class="cf-card__icon"><?php echo esc_html($b['cf_benefit_icon'] ?? '✓'); ?></span>
                                <h3 class="cf-card__title"><?php echo esc_html($b['cf_benefit_title'] ?? ''); ?></h3>
                                <p class="cf-card__text"><?php echo esc_html($b['cf_benefit_text'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Packages -->
    <?php if ($packages): ?>
        <?php cf_block('service-packages', ['packages' => $packages]); ?>
    <?php endif; ?>

    <!-- Content -->
    <section class="cf-section">
        <div class="cf-container">
            <div class="cf-content"><?php the_content(); ?></div>
        </div>
    </section>

    <?php cf_block('steps', ['variant' => 'service']); ?>
    <?php cf_block('cases', ['variant' => 'grid', 'limit' => 3]); ?>
    <?php cf_block('reviews-video', ['limit' => 3]); ?>
    <?php cf_block('faq', ['source' => 'service']); ?>

<?php else: ?>
    <!-- Services Overview Page -->
    <?php cf_block('hero', ['variant' => 'service', 'title' => 'Наши услуги']); ?>
    <?php cf_block('services-cards', ['limit' => 12]); ?>
    <?php cf_block('service-packages'); ?>
    <?php cf_block('comparison-table', ['variant' => 'dealer-vs-us']); ?>
    <?php cf_block('cases', ['variant' => 'grid', 'limit' => 4]); ?>
    <?php cf_block('faq', ['source' => 'services']); ?>
<?php endif; ?>

<?php
cf_block('interlinking', ['position' => 'footer']);
cf_block('cta-final', ['variant' => 'default']);
get_footer();
