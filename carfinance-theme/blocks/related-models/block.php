<?php
defined('ABSPATH') || exit;

$post_id = (int) ($args['post_id'] ?? get_the_ID());
$limit   = (int) ($args['limit'] ?? 4);

if (! function_exists('cf_get_related_models')) {
    return;
}

$related = cf_get_related_models($post_id, $limit);

if (empty($related)) {
    return;
}
?>
<section class="cf-related">
    <h2 class="cf-related__title">Похожие модели</h2>

    <div class="cf-related__grid">
        <?php foreach ($related as $model) :
            $model_id  = $model->ID ?? $model;
            $thumb     = get_the_post_thumbnail_url($model_id, 'medium');
            $name      = get_the_title($model_id);
            $link      = get_permalink($model_id);
            $price     = cf_get_field('cf_price_from', $model_id);
        ?>
            <a href="<?php echo esc_url($link); ?>" class="cf-related__card">
                <?php if ($thumb) : ?>
                    <div class="cf-related__image">
                        <img src="<?php echo esc_url($thumb); ?>"
                             alt="<?php echo esc_attr($name); ?>"
                             loading="lazy"
                             width="400" height="300">
                    </div>
                <?php endif; ?>

                <div class="cf-related__body">
                    <h3 class="cf-related__name"><?php echo esc_html($name); ?></h3>
                    <?php if ($price) : ?>
                        <span class="cf-related__price">от <?php echo esc_html(number_format((float) $price, 0, '', ' ')); ?> ₽</span>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
