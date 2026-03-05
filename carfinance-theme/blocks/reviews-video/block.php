<?php
/**
 * Block: Reviews Video
 * Video reviews from clients.
 *
 * @param array $args {
 *     @type string $variant  'default'|'country'
 *     @type string $country  Country code (for country variant)
 *     @type int    $limit    Number of reviews (default 6)
 * }
 */

defined('ABSPATH') || exit;

$variant = $args['variant'] ?? 'default';
$country = $args['country'] ?? '';
$limit   = (int) ($args['limit'] ?? 6);

$query_args = [
    'post_type'      => 'cf_review',
    'posts_per_page' => $limit,
    'post_status'    => 'publish',
];

if ($variant === 'country' && $country) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => 'cf_country',
            'field'    => 'slug',
            'terms'    => $country,
        ],
    ];
}

$reviews = get_posts($query_args);

// Fallback placeholder data if no reviews found
if (empty($reviews)) {
    $placeholders = [
        ['name' => 'Алексей М.', 'car' => 'KIA K5 2024',   'rating' => 5],
        ['name' => 'Дмитрий К.', 'car' => 'Toyota Camry',   'rating' => 5],
        ['name' => 'Ирина С.',   'car' => 'Hyundai Tucson',  'rating' => 5],
        ['name' => 'Павел В.',   'car' => 'Mazda CX-5',      'rating' => 4],
        ['name' => 'Елена Н.',   'car' => 'Chery Tiggo 7',   'rating' => 5],
        ['name' => 'Сергей Л.',  'car' => 'Genesis GV70',    'rating' => 5],
    ];
    $placeholders = array_slice($placeholders, 0, $limit);
}
?>

<section class="cf-reviews cf-reviews--<?php echo esc_attr($variant); ?>">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">Видеоотзывы клиентов</h2>
            <p class="cf-section-header__subtitle">Реальные истории людей, которые уже получили свой автомобиль</p>
        </div>

        <div class="cf-reviews__grid">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review):
                    $review_id  = $review->ID;
                    $name       = get_the_title($review_id);
                    $car_model  = cf_get_field('cf_review_car', $review_id) ?: '';
                    $rating     = (int) (cf_get_field('cf_review_rating', $review_id) ?: 5);
                    $video_url  = cf_get_field('cf_review_video', $review_id) ?: '';
                    $thumbnail  = get_the_post_thumbnail_url($review_id, 'medium_large') ?: '';
                ?>
                    <div class="cf-reviews__card">
                        <div class="cf-reviews__video-wrap">
                            <?php if ($thumbnail): ?>
                                <img class="cf-reviews__thumbnail" src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" width="400" height="225">
                            <?php else: ?>
                                <div class="cf-reviews__thumbnail-placeholder"></div>
                            <?php endif; ?>
                            <?php if ($video_url): ?>
                                <a href="<?php echo esc_url($video_url); ?>" class="cf-reviews__play" target="_blank" rel="noopener" aria-label="Смотреть видеоотзыв">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="24" fill="rgba(0,0,0,0.6)"/><polygon points="19,14 36,24 19,34" fill="#fff"/></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="cf-reviews__info">
                            <div class="cf-reviews__name"><?php echo esc_html($name); ?></div>
                            <?php if ($car_model): ?>
                                <div class="cf-reviews__car"><?php echo esc_html($car_model); ?></div>
                            <?php endif; ?>
                            <div class="cf-reviews__rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="cf-reviews__star <?php echo $i <= $rating ? 'cf-reviews__star--active' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($placeholders as $ph): ?>
                    <div class="cf-reviews__card">
                        <div class="cf-reviews__video-wrap">
                            <div class="cf-reviews__thumbnail-placeholder"></div>
                            <div class="cf-reviews__play">
                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="24" fill="rgba(0,0,0,0.6)"/><polygon points="19,14 36,24 19,34" fill="#fff"/></svg>
                            </div>
                        </div>
                        <div class="cf-reviews__info">
                            <div class="cf-reviews__name"><?php echo esc_html($ph['name']); ?></div>
                            <div class="cf-reviews__car"><?php echo esc_html($ph['car']); ?></div>
                            <div class="cf-reviews__rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="cf-reviews__star <?php echo $i <= $ph['rating'] ? 'cf-reviews__star--active' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
