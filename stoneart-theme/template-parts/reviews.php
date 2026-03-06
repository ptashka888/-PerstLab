<?php
/**
 * Template Part: Reviews
 *
 * @package StoneArt
 */

$title = sa_option('sa_reviews_title', 'Отзывы клиентов');

// Try CPT first
$reviews_query = new WP_Query([
    'post_type'      => 'sa_review',
    'posts_per_page' => 6,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$has_reviews = $reviews_query->have_posts();

$default_reviews = [
    [
        'text'     => '«Заказывали столешницу из кварца Avant на кухню. Сделали всё быстро, замеры точные, стыков не видно вообще. Очень довольна качеством материала и работой монтажников!»',
        'author'   => 'Елена В.',
        'location' => 'г. Москва, ЖК "Символ"',
        'rating'   => 5,
    ],
    [
        'text'     => '«Делали облицовку камина натуральным мрамором Calacatta. Мастера — настоящие профи. Подсказали, как лучше сделать раскладку жил бабочкой (bookmatch). Выглядит роскошно.»',
        'author'   => 'Игорь Николаевич',
        'location' => 'КП "Жуковка"',
        'rating'   => 5,
    ],
    [
        'text'     => '«Меняли пластиковые подоконники на гранитные Absolute Black. Совсем другой вид квартиры! Монтажники работали чисто, убрали за собой строительный мусор.»',
        'author'   => 'Анна С.',
        'location' => 'г. Химки',
        'rating'   => 5,
    ],
];
?>

<section class="sa-section sa-section--gray sa-animate">
    <div class="sa-container">
        <h2 class="sa-section__title"><?php echo esc_html($title); ?></h2>
        <div style="margin-top:4rem;">
            <div class="sa-reviews">
                <?php if ($has_reviews) :
                    while ($reviews_query->have_posts()) : $reviews_query->the_post();
                        $rating   = function_exists('get_field') ? (int) get_field('sa_review_rating') : 5;
                        $author   = function_exists('get_field') ? get_field('sa_review_author') : get_the_title();
                        $location = function_exists('get_field') ? get_field('sa_review_location') : '';
                ?>
                    <div class="sa-review" itemscope itemtype="https://schema.org/Review">
                        <div class="sa-review__stars">
                            <?php for ($s = 0; $s < $rating; $s++) : ?>
                                <i class="fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="sa-review__text" itemprop="reviewBody"><?php echo wp_kses_post(get_the_content()); ?></p>
                        <div class="sa-review__author" itemprop="author"><?php echo esc_html($author); ?></div>
                        <div class="sa-review__location"><?php echo esc_html($location); ?></div>
                        <meta itemprop="datePublished" content="<?php echo get_the_date('c'); ?>">
                        <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                            <meta itemprop="ratingValue" content="<?php echo $rating; ?>">
                            <meta itemprop="bestRating" content="5">
                        </div>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    foreach ($default_reviews as $review) : ?>
                        <div class="sa-review" itemscope itemtype="https://schema.org/Review">
                            <div class="sa-review__stars">
                                <?php for ($s = 0; $s < $review['rating']; $s++) : ?>
                                    <i class="fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="sa-review__text" itemprop="reviewBody"><?php echo esc_html($review['text']); ?></p>
                            <div class="sa-review__author" itemprop="author"><?php echo esc_html($review['author']); ?></div>
                            <div class="sa-review__location"><?php echo esc_html($review['location']); ?></div>
                            <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                <meta itemprop="ratingValue" content="<?php echo $review['rating']; ?>">
                                <meta itemprop="bestRating" content="5">
                            </div>
                        </div>
                    <?php endforeach;
                endif; ?>
            </div>
        </div>
    </div>
</section>
