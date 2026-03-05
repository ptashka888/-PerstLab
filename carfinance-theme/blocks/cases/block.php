<?php
defined('ABSPATH') || exit;

$variant = $args['variant'] ?? 'grid';
$limit   = (int) ($args['limit'] ?? 6);
$country = $args['country'] ?? '';

$query_args = [
    'post_type'      => 'case_study',
    'posts_per_page' => $limit,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if (!empty($country)) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => 'cf_country',
            'field'    => 'slug',
            'terms'    => $country,
        ],
    ];
}

$cases = new WP_Query($query_args);
?>
<section class="cf-cases cf-cases--<?php echo esc_attr($variant); ?>">
    <div class="cf-cases__container">
        <h2 class="cf-cases__title">Реальные кейсы клиентов</h2>
        <p class="cf-cases__subtitle">Посмотрите, как мы помогли нашим клиентам сэкономить на покупке автомобиля</p>

        <?php if ($cases->have_posts()) : ?>
            <div class="cf-cases__grid">
                <?php while ($cases->have_posts()) : $cases->the_post();
                    $post_id      = get_the_ID();
                    $before_img   = cf_get_field('cf_case_before_image', $post_id);
                    $after_img    = cf_get_field('cf_case_after_image', $post_id);
                    $model_name   = cf_get_field('cf_case_model', $post_id);
                    $budget       = cf_get_field('cf_case_budget', $post_id);
                    $found_price  = cf_get_field('cf_case_found_price', $post_id);
                    $savings      = cf_get_field('cf_case_savings', $post_id);
                    $duration     = cf_get_field('cf_case_duration', $post_id);
                    $client_quote = cf_get_field('cf_case_client_quote', $post_id);
                ?>
                    <article class="cf-cases__card cf-card">
                        <div class="cf-cases__images">
                            <?php if ($before_img) : ?>
                                <div class="cf-cases__image cf-cases__image--before">
                                    <img src="<?php echo esc_url($before_img); ?>" alt="До" loading="lazy" width="300" height="200">
                                    <span class="cf-cases__image-label">До</span>
                                </div>
                            <?php endif; ?>
                            <?php if ($after_img) : ?>
                                <div class="cf-cases__image cf-cases__image--after">
                                    <img src="<?php echo esc_url($after_img); ?>" alt="После" loading="lazy" width="300" height="200">
                                    <span class="cf-cases__image-label">После</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="cf-cases__body">
                            <h3 class="cf-cases__card-title">
                                <a href="<?php the_permalink(); ?>"><?php echo esc_html($model_name ?: get_the_title()); ?></a>
                            </h3>

                            <div class="cf-cases__financials">
                                <?php if ($budget) : ?>
                                    <div class="cf-cases__fin-item">
                                        <span class="cf-cases__fin-label">Бюджет</span>
                                        <span class="cf-cases__fin-value"><?php echo esc_html($budget); ?> ₽</span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($found_price) : ?>
                                    <div class="cf-cases__fin-item">
                                        <span class="cf-cases__fin-label">Итоговая цена</span>
                                        <span class="cf-cases__fin-value"><?php echo esc_html($found_price); ?> ₽</span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($savings) : ?>
                                    <div class="cf-cases__fin-item cf-cases__fin-item--savings">
                                        <span class="cf-cases__fin-label">Экономия</span>
                                        <span class="cf-cases__fin-value cf-cases__fin-value--savings"><?php echo esc_html($savings); ?> ₽</span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($duration) : ?>
                                    <div class="cf-cases__fin-item">
                                        <span class="cf-cases__fin-label">Срок</span>
                                        <span class="cf-cases__fin-value"><?php echo esc_html($duration); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($client_quote) : ?>
                                <blockquote class="cf-cases__quote">
                                    &laquo;<?php echo esc_html($client_quote); ?>&raquo;
                                </blockquote>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="cf-cases__empty">Кейсы пока не добавлены.</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</section>
