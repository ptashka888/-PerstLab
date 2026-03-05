<?php
/**
 * Template: Car Type (Body Type) Taxonomy Archive
 * URL: /catalog/type/{type-slug}/
 */

defined('ABSPATH') || exit;

get_header();

$term = get_queried_object();
?>

<section class="cf-section">
    <div class="cf-container">
        <h1 class="cf-section__title"><?php echo esc_html($term->name); ?> из-за рубежа</h1>
        <p class="cf-section__subtitle">Каталог автомобилей типа «<?php echo esc_html($term->name); ?>» с доставкой под ключ</p>
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
                    <p>Автомобили данного типа скоро появятся в каталоге.</p>
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
