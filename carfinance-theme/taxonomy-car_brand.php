<?php
/**
 * Template: Car Brand Taxonomy Archive
 * URL: /catalog/{brand-slug}/
 */

defined('ABSPATH') || exit;

get_header();

$term    = get_queried_object();
$term_id = $term->term_id ?? 0;

// Get country for this brand (from posts)
$country_data = [];
$sample_post = get_posts([
    'post_type'      => 'car_model',
    'tax_query'      => [['taxonomy' => 'car_brand', 'terms' => $term_id]],
    'posts_per_page' => 1,
    'fields'         => 'ids',
]);
if ($sample_post) {
    $countries = get_the_terms($sample_post[0], 'car_country');
    if ($countries) {
        $country_data = cf_get_country_data($countries[0]->slug);
    }
}
?>

<section class="cf-section">
    <div class="cf-container">
        <div class="cf-section__header">
            <h1 class="cf-section__title"><?php echo esc_html($term->name); ?> из-за рубежа</h1>
            <p class="cf-section__subtitle">Каталог моделей <?php echo esc_html($term->name); ?> с доставкой и растаможкой под ключ</p>
        </div>

        <?php if ($term->description): ?>
            <div class="cf-content cf-content--intro">
                <?php echo wp_kses_post(wpautop($term->description)); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Models Grid -->
<div class="cf-catalog cf-catalog--brand">
    <div class="cf-container">
        <div class="cf-catalog__toolbar">
            <div class="cf-catalog__count">
                Моделей: <span><?php echo esc_html($wp_query->found_posts); ?></span>
            </div>
            <div class="cf-catalog__sort">
                <label for="cf-sort">Сортировка:</label>
                <select id="cf-sort" class="cf-form__select">
                    <option value="popular">По популярности</option>
                    <option value="price_asc">Цена ↑</option>
                    <option value="price_desc">Цена ↓</option>
                </select>
            </div>
        </div>

        <div class="cf-catalog__grid cf-grid cf-grid--3">
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <?php cf_block('car-card', ['post_id' => get_the_ID()]); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="cf-catalog__empty">
                    <p>Модели <?php echo esc_html($term->name); ?> скоро появятся в каталоге.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($wp_query->max_num_pages > 1): ?>
            <div class="cf-catalog__pagination">
                <?php
                echo paginate_links([
                    'total'     => $wp_query->max_num_pages,
                    'current'   => max(1, get_query_var('paged')),
                    'prev_text' => '← Назад',
                    'next_text' => 'Далее →',
                    'type'      => 'list',
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Cases
cf_block('cases', ['variant' => 'grid', 'limit' => 3]);

// Calculator
cf_block('calculator', ['variant' => 'turnkey']);

// FAQ
cf_block('faq', ['source' => 'brand']);

// Interlinking
cf_block('interlinking', ['position' => 'footer']);

// CTA
cf_block('cta-final', ['variant' => 'default']);

get_footer();
