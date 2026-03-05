<?php
defined('ABSPATH') || exit;

$post_id = (int) ($args['post_id'] ?? 0);

if (! $post_id || get_post_type($post_id) !== 'car_model') {
    return;
}

$title        = get_the_title($post_id);
$permalink    = get_permalink($post_id);
$thumb_url    = get_the_post_thumbnail_url($post_id, 'medium_large');
$brand_terms  = get_the_terms($post_id, 'car_brand');
$brand_name   = ($brand_terms && ! is_wp_error($brand_terms)) ? $brand_terms[0]->name : '';

$year         = cf_get_field('cf_year', $post_id);
$price_from   = cf_get_field('cf_price_from', $post_id);
$price_to     = cf_get_field('cf_price_to', $post_id);
$engine_cc    = cf_get_field('cf_engine_cc', $post_id);
$power_hp     = cf_get_field('cf_power_hp', $post_id);
$fuel_type    = cf_get_field('cf_fuel_type', $post_id);
$transmission = cf_get_field('cf_transmission', $post_id);
?>
<article class="cf-car-card">
    <div class="cf-car-card__media">
        <?php if ($thumb_url) : ?>
            <img src="<?php echo esc_url($thumb_url); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 class="cf-car-card__image"
                 loading="lazy"
                 width="480" height="360">
        <?php else : ?>
            <div class="cf-car-card__placeholder"></div>
        <?php endif; ?>

        <?php if ($brand_name) : ?>
            <span class="cf-car-card__badge"><?php echo esc_html($brand_name); ?></span>
        <?php endif; ?>
    </div>

    <div class="cf-car-card__body">
        <h3 class="cf-car-card__title">
            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
        </h3>

        <div class="cf-car-card__specs">
            <?php if ($year) : ?>
                <span class="cf-car-card__spec">
                    <svg class="cf-car-card__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?php echo esc_html($year); ?>
                </span>
            <?php endif; ?>

            <?php if ($engine_cc) : ?>
                <span class="cf-car-card__spec">
                    <svg class="cf-car-card__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v4m0 14v4M1 12h4m14 0h4"/></svg>
                    <?php echo esc_html(number_format((float) $engine_cc / 1000, 1, '.', '')); ?> л
                </span>
            <?php endif; ?>

            <?php if ($transmission) : ?>
                <span class="cf-car-card__spec">
                    <svg class="cf-car-card__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v8m-6-4v12m12-12v12"/><circle cx="6" cy="18" r="2"/><circle cx="12" cy="10" r="2"/><circle cx="18" cy="18" r="2"/></svg>
                    <?php echo esc_html($transmission); ?>
                </span>
            <?php endif; ?>

            <?php if ($fuel_type) : ?>
                <span class="cf-car-card__spec">
                    <svg class="cf-car-card__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22V6a2 2 0 012-2h6a2 2 0 012 2v16"/><path d="M13 10h4a2 2 0 012 2v2a2 2 0 002 2"/><path d="M21 10V6l-2-2"/></svg>
                    <?php echo esc_html($fuel_type); ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="cf-car-card__footer">
            <div class="cf-car-card__price">
                <?php if ($price_from) : ?>
                    <span class="cf-car-card__price-value">от <?php echo esc_html(number_format((float) $price_from, 0, '', ' ')); ?> ₽</span>
                <?php endif; ?>
                <?php if ($price_to) : ?>
                    <span class="cf-car-card__price-to">до <?php echo esc_html(number_format((float) $price_to, 0, '', ' ')); ?> ₽</span>
                <?php endif; ?>
            </div>

            <a href="<?php echo esc_url($permalink); ?>" class="cf-car-card__link">Подробнее</a>
        </div>
    </div>
</article>
