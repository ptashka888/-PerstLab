<?php
/**
 * Template: Car Country Taxonomy Archive
 * URL: /catalog/country/{country-slug}/
 */

defined('ABSPATH') || exit;

get_header();

$term         = get_queried_object();
$country_data = cf_get_country_data($term->slug ?? '');
?>

<section class="cf-section">
    <div class="cf-container">
        <h1 class="cf-section__title">
            <?php echo esc_html($country_data['flag'] ?? ''); ?>
            Автомобили из <?php echo esc_html($country_data['name_from'] ?? $term->name); ?>
        </h1>
    </div>
</section>

<div class="cf-catalog">
    <div class="cf-container">
        <div class="cf-catalog__grid cf-grid cf-grid--3">
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <?php cf_block('car-card', ['post_id' => get_the_ID()]); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="cf-catalog__empty">
                    <p>Автомобили из этой страны скоро появятся в каталоге.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($wp_query->max_num_pages > 1): ?>
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
</div>

<?php
cf_block('interlinking', ['position' => 'footer']);
cf_block('cta-final', ['variant' => 'default']);
get_footer();
