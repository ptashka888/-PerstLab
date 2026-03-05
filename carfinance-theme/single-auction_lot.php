<?php
/**
 * Template: Single Auction Lot
 */

defined('ABSPATH') || exit;

get_header();

$post_id    = get_the_ID();
$auction    = cf_get_field('cf_lot_auction', $post_id);
$lot_number = cf_get_field('cf_lot_number', $post_id);
$grade      = cf_get_field('cf_lot_grade', $post_id);
$mileage    = cf_get_field('cf_lot_mileage', $post_id);
$price_start = cf_get_field('cf_lot_price_start', $post_id);
$price_final = cf_get_field('cf_lot_price_final', $post_id);
$currency   = cf_get_field('cf_lot_currency', $post_id) ?: 'USD';
$status     = cf_get_field('cf_lot_status', $post_id) ?: 'active';
$gallery    = cf_get_field('cf_lot_gallery', $post_id);
$lot_date   = cf_get_field('cf_lot_date', $post_id);
$car_model  = cf_get_field('cf_lot_car_model', $post_id);

$status_labels = ['active' => 'Активный', 'sold' => 'Продан', 'cancelled' => 'Отменён'];
$status_class  = ['active' => 'success', 'sold' => 'danger', 'cancelled' => 'warning'];
$model_id = is_object($car_model) ? $car_model->ID : ($car_model ?: 0);
?>

<article class="cf-lot">
    <div class="cf-container">
        <header class="cf-lot__header">
            <div class="cf-lot__badges">
                <span class="cf-badge cf-badge--<?php echo esc_attr($status_class[$status] ?? 'default'); ?>">
                    <?php echo esc_html($status_labels[$status] ?? $status); ?>
                </span>
                <?php if ($auction): ?>
                    <span class="cf-badge"><?php echo esc_html(strtoupper($auction)); ?></span>
                <?php endif; ?>
            </div>
            <h1 class="cf-lot__title"><?php the_title(); ?></h1>
            <?php if ($lot_number): ?>
                <span class="cf-lot__number">Лот #<?php echo esc_html($lot_number); ?></span>
            <?php endif; ?>
        </header>

        <div class="cf-lot__layout cf-grid cf-grid--2">
            <!-- Gallery -->
            <div class="cf-lot__gallery">
                <?php if ($gallery): ?>
                    <div class="cf-lot__main-image">
                        <img src="<?php echo esc_url($gallery[0]['sizes']['large'] ?? $gallery[0]['url']); ?>"
                             alt="<?php the_title(); ?>" width="800" height="600" loading="eager">
                    </div>
                    <?php if (count($gallery) > 1): ?>
                        <div class="cf-lot__thumbs">
                            <?php foreach ($gallery as $img): ?>
                                <button class="cf-lot__thumb" data-src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>">
                                    <img src="<?php echo esc_url($img['sizes']['thumbnail'] ?? $img['url']); ?>"
                                         alt="" width="120" height="90" loading="lazy">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php elseif (has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div class="cf-lot__info">
                <table class="cf-table cf-table--specs">
                    <?php if ($grade): ?>
                        <tr><td>Оценка</td><td><strong><?php echo esc_html($grade); ?></strong></td></tr>
                    <?php endif; ?>
                    <?php if ($mileage): ?>
                        <tr><td>Пробег</td><td><?php echo esc_html(number_format($mileage, 0, '', ' ')); ?> км</td></tr>
                    <?php endif; ?>
                    <?php if ($lot_date): ?>
                        <tr><td>Дата аукциона</td><td><?php echo esc_html($lot_date); ?></td></tr>
                    <?php endif; ?>
                    <?php if ($price_start): ?>
                        <tr><td>Стартовая цена</td><td><?php echo esc_html(number_format($price_start, 0, '', ' ')); ?> <?php echo esc_html($currency); ?></td></tr>
                    <?php endif; ?>
                    <?php if ($price_final): ?>
                        <tr><td>Финальная цена</td><td><strong><?php echo esc_html(number_format($price_final, 0, '', ' ')); ?> <?php echo esc_html($currency); ?></strong></td></tr>
                    <?php endif; ?>
                </table>

                <?php if ($model_id): ?>
                    <a href="<?php echo esc_url(get_permalink($model_id)); ?>" class="cf-btn cf-btn--secondary cf-btn--full">
                        Смотреть модель <?php echo esc_html(get_the_title($model_id)); ?>
                    </a>
                <?php endif; ?>

                <?php if ($status === 'active'): ?>
                    <a href="#cf-modal" class="cf-btn cf-btn--primary cf-btn--lg cf-btn--full" data-modal="lead">
                        Участвовать в торгах
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="cf-lot__content cf-content">
            <?php the_content(); ?>
        </div>
    </div>
</article>

<?php
if ($model_id) {
    cf_block('related-models', ['post_id' => $model_id, 'limit' => 4]);
}
cf_block('cta-final', ['variant' => 'default']);
get_footer();
