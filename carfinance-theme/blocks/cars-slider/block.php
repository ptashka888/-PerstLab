<?php
/**
 * Block: Cars Slider
 * Car models slider/grid.
 *
 * @param array $args {
 *     @type string $variant  'featured'|'related'|'similar'|'country'
 *     @type string $country  Country code (for country variant)
 *     @type string $brand    Brand slug (for filtering)
 *     @type int    $limit    Number of cars to show (default 8)
 *     @type int    $post_id  Post ID (for related variant)
 * }
 */

defined('ABSPATH') || exit;

$variant = $args['variant'] ?? 'featured';
$country = $args['country'] ?? '';
$brand   = $args['brand'] ?? '';
$limit   = (int) ($args['limit'] ?? 8);
$post_id = (int) ($args['post_id'] ?? get_the_ID());

$cars = [];

if ($variant === 'related' && function_exists('cf_get_related_models')) {
    $cars = cf_get_related_models($post_id, $limit);
} else {
    $query_args = [
        'post_type'      => 'car_model',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
    ];

    if ($variant === 'featured') {
        $query_args['meta_key']   = 'cf_featured';
        $query_args['meta_value'] = '1';
    }

    if ($variant === 'country' && $country) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'cf_country',
                'field'    => 'slug',
                'terms'    => $country,
            ],
        ];
    }

    if ($brand) {
        $query_args['tax_query']   = $query_args['tax_query'] ?? [];
        $query_args['tax_query'][] = [
            'taxonomy' => 'cf_brand',
            'field'    => 'slug',
            'terms'    => $brand,
        ];
    }

    $cars = get_posts($query_args);
}

$section_titles = [
    'featured' => 'Популярные модели',
    'related'  => 'Похожие модели',
    'similar'  => 'Вас может заинтересовать',
    'country'  => 'Автомобили из этой страны',
];

$section_title = $args['title'] ?? ($section_titles[$variant] ?? 'Автомобили');
?>

<section class="cf-cars-slider cf-cars-slider--<?php echo esc_attr($variant); ?>">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title"><?php echo esc_html($section_title); ?></h2>
        </div>

        <div class="cf-cars-slider__grid">
            <?php if (!empty($cars)): ?>
                <?php foreach ($cars as $car):
                    $car_id    = $car->ID;
                    $thumbnail = get_the_post_thumbnail_url($car_id, 'medium_large') ?: '';
                    $title     = get_the_title($car_id);
                    $permalink = get_permalink($car_id);
                    $year      = cf_get_field('cf_year', $car_id) ?: '';
                    $price_from = cf_get_field('cf_price_from', $car_id) ?: '';
                    $engine    = cf_get_field('cf_engine', $car_id) ?: '';
                    $transmission = cf_get_field('cf_transmission', $car_id) ?: '';

                    $brand_terms = get_the_terms($car_id, 'cf_brand');
                    $brand_name  = ($brand_terms && !is_wp_error($brand_terms)) ? $brand_terms[0]->name : '';
                ?>
                    <a href="<?php echo esc_url($permalink); ?>" class="cf-cars-slider__card cf-card">
                        <div class="cf-card__image-wrap">
                            <?php if ($thumbnail): ?>
                                <img class="cf-card__image" src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" width="400" height="260">
                            <?php else: ?>
                                <div class="cf-card__image-placeholder"></div>
                            <?php endif; ?>
                        </div>
                        <div class="cf-card__body">
                            <h3 class="cf-card__title">
                                <?php if ($brand_name): ?>
                                    <span class="cf-card__brand"><?php echo esc_html($brand_name); ?></span>
                                <?php endif; ?>
                                <?php echo esc_html($title); ?>
                            </h3>
                            <?php if ($year): ?>
                                <span class="cf-card__year"><?php echo esc_html($year); ?> г.</span>
                            <?php endif; ?>
                            <div class="cf-card__specs">
                                <?php if ($engine): ?>
                                    <span class="cf-card__spec"><?php echo esc_html($engine); ?></span>
                                <?php endif; ?>
                                <?php if ($transmission): ?>
                                    <span class="cf-card__spec"><?php echo esc_html($transmission); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($price_from): ?>
                                <div class="cf-card__price">от <?php echo esc_html(number_format((int) $price_from, 0, '', ' ')); ?> ₽</div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="cf-cars-slider__empty">Автомобили не найдены. Обновите каталог.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
