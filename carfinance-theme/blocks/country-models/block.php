<?php
defined('ABSPATH') || exit;

$country = $args['country'] ?? '';
$limit   = (int) ($args['limit'] ?? 8);

if (! $country) {
    return;
}

$country_term = get_term_by('slug', $country, 'car_country');

if (! $country_term || is_wp_error($country_term)) {
    return;
}

$country_data = function_exists('cf_get_country_data') ? cf_get_country_data($country) : [];
$country_name = $country_data['name'] ?? $country_term->name;

$query_args = [
    'post_type'      => 'car_model',
    'posts_per_page' => $limit,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'tax_query'      => [
        [
            'taxonomy' => 'car_country',
            'field'    => 'slug',
            'terms'    => $country,
        ],
    ],
];

$models_query = new WP_Query($query_args);

if (! $models_query->have_posts()) {
    return;
}
?>
<section class="cf-country-models">
    <h2 class="cf-country-models__title">Популярные модели из <?php echo esc_html($country_name); ?></h2>

    <div class="cf-country-models__grid">
        <?php while ($models_query->have_posts()) : $models_query->the_post();
            $model_id  = get_the_ID();
            $price     = cf_get_field('cf_price_from', $model_id);
            $brand     = get_the_terms($model_id, 'car_brand');
            $brand_name = ($brand && ! is_wp_error($brand)) ? $brand[0]->name : '';
        ?>
            <a href="<?php the_permalink(); ?>" class="cf-country-models__card">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="cf-country-models__image">
                        <?php the_post_thumbnail('medium', ['loading' => 'lazy']); ?>
                    </div>
                <?php endif; ?>

                <div class="cf-country-models__body">
                    <?php if ($brand_name) : ?>
                        <span class="cf-country-models__brand"><?php echo esc_html($brand_name); ?></span>
                    <?php endif; ?>
                    <h3 class="cf-country-models__name"><?php the_title(); ?></h3>
                    <?php if ($price) : ?>
                        <span class="cf-country-models__price">от <?php echo esc_html(number_format((float) $price, 0, '', ' ')); ?> ₽</span>
                    <?php endif; ?>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</section>
<?php wp_reset_postdata(); ?>
