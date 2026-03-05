<?php
/**
 * Template: Single Case Study
 * Before/after, savings, client review
 */

defined('ABSPATH') || exit;

get_header();

$post_id      = get_the_ID();
$model        = cf_get_field('cf_case_model', $post_id);
$budget       = cf_get_field('cf_case_budget', $post_id);
$found_price  = cf_get_field('cf_case_found_price', $post_id);
$savings      = cf_get_field('cf_case_savings', $post_id);
$duration     = cf_get_field('cf_case_duration', $post_id);
$before_img   = cf_get_field('cf_case_before_image', $post_id);
$after_img    = cf_get_field('cf_case_after_image', $post_id);
$client_name  = cf_get_field('cf_case_client_name', $post_id);
$client_city  = cf_get_field('cf_case_client_city', $post_id);
$testimonial  = cf_get_field('cf_case_testimonial', $post_id);
$rating       = cf_get_field('cf_case_rating', $post_id) ?: 5;

$model_id    = is_object($model) ? $model->ID : ($model ?: 0);
$model_title = $model_id ? get_the_title($model_id) : '';
$countries   = $model_id ? get_the_terms($model_id, 'car_country') : [];
$country     = $countries && !is_wp_error($countries) ? $countries[0] : null;
?>

<article class="cf-case">
    <div class="cf-container">
        <header class="cf-case__header">
            <h1 class="cf-case__title"><?php the_title(); ?></h1>
            <?php if ($model_title): ?>
                <p class="cf-case__subtitle">
                    Автомобиль: <a href="<?php echo esc_url(get_permalink($model_id)); ?>"><?php echo esc_html($model_title); ?></a>
                    <?php if ($country):
                        $cd = cf_get_country_data($country->slug); ?>
                        · <?php echo esc_html($cd['flag'] ?? ''); ?> <?php echo esc_html($cd['name'] ?? $country->name); ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </header>

        <!-- Before / After -->
        <?php if ($before_img || $after_img): ?>
            <div class="cf-case__images cf-grid cf-grid--2">
                <?php if ($before_img): ?>
                    <div class="cf-case__image cf-case__image--before">
                        <span class="cf-case__image-label">До</span>
                        <img src="<?php echo esc_url(is_array($before_img) ? $before_img['url'] : $before_img); ?>"
                             alt="До — <?php the_title(); ?>" width="600" height="400" loading="lazy">
                    </div>
                <?php endif; ?>
                <?php if ($after_img): ?>
                    <div class="cf-case__image cf-case__image--after">
                        <span class="cf-case__image-label">После</span>
                        <img src="<?php echo esc_url(is_array($after_img) ? $after_img['url'] : $after_img); ?>"
                             alt="После — <?php the_title(); ?>" width="600" height="400" loading="lazy">
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Financial Breakdown -->
        <div class="cf-case__stats cf-grid cf-grid--4">
            <?php if ($budget): ?>
                <div class="cf-case__stat">
                    <span class="cf-case__stat-label">Бюджет</span>
                    <span class="cf-case__stat-value"><?php echo esc_html(cf_format_price($budget)); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($found_price): ?>
                <div class="cf-case__stat">
                    <span class="cf-case__stat-label">Итоговая цена</span>
                    <span class="cf-case__stat-value"><?php echo esc_html(cf_format_price($found_price)); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($savings): ?>
                <div class="cf-case__stat cf-case__stat--savings">
                    <span class="cf-case__stat-label">Экономия</span>
                    <span class="cf-case__stat-value"><?php echo esc_html(cf_format_price($savings)); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($duration): ?>
                <div class="cf-case__stat">
                    <span class="cf-case__stat-label">Срок доставки</span>
                    <span class="cf-case__stat-value"><?php echo esc_html($duration); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Content -->
        <div class="cf-case__content cf-content">
            <?php the_content(); ?>
        </div>

        <!-- Client Testimonial -->
        <?php if ($testimonial): ?>
            <blockquote class="cf-case__testimonial">
                <p><?php echo esc_html($testimonial); ?></p>
                <footer>
                    <strong><?php echo esc_html($client_name ?: 'Клиент'); ?></strong>
                    <?php if ($client_city): ?>
                        <span>, <?php echo esc_html($client_city); ?></span>
                    <?php endif; ?>
                    <div class="cf-case__rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="cf-star<?php echo $i <= $rating ? ' cf-star--filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                </footer>
            </blockquote>
        <?php endif; ?>
    </div>
</article>

<?php
// Related model
if ($model_id) {
    cf_block('related-models', ['post_id' => $model_id, 'limit' => 4]);
}

cf_block('cases', ['variant' => 'grid', 'limit' => 3]);
cf_block('interlinking', ['position' => 'footer']);
cf_block('cta-final', ['variant' => 'default']);
get_footer();
