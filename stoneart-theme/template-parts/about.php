<?php
/**
 * Template Part: About / Production
 *
 * @package StoneArt
 */

$title    = sa_option('sa_about_title', 'Искусство в каждом камне');
$text1    = sa_option('sa_about_text_1', '<span class="sa-about__highlight">StoneArt — это прямой производитель.</span> Мы создаем изделия на высокоточных станках с ЧПУ, обеспечивая идеальную геометрию и бесшовную стыковку деталей.');
$text2    = sa_option('sa_about_text_2', 'Мы привозим слэбы напрямую с карьеров Европы, Азии и Бразилии, а также являемся официальными партнерами брендов Avant Quartz, Caesarstone и Grandex. Это позволяет держать цены на 15-20% ниже рынка.');
$image    = sa_option('sa_about_image');
$img_url  = $image ? $image['url'] : 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=800';

$stats = sa_option('sa_about_stats');
if (!$stats) {
    $stats = [
        ['sa_stat_number' => '15 лет', 'sa_stat_label' => 'Опыта на рынке'],
        ['sa_stat_number' => '2000+', 'sa_stat_label' => 'Успешных проектов'],
    ];
}
?>

<section id="about" class="sa-section sa-section--white sa-animate">
    <div class="sa-container">
        <div class="sa-about">
            <div class="sa-about__image-wrap">
                <div class="sa-about__image-bg"></div>
                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>" class="sa-about__image" loading="lazy">
            </div>
            <div class="sa-about__content">
                <h2 class="sa-about__title sa-font-serif"><?php echo esc_html($title); ?></h2>
                <p class="sa-about__text"><?php echo wp_kses_post($text1); ?></p>
                <p class="sa-about__text"><?php echo wp_kses_post($text2); ?></p>
                <div class="sa-about__stats">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="sa-about__stat">
                            <div class="sa-about__stat-number"><?php echo esc_html($stat['sa_stat_number'] ?? ''); ?></div>
                            <div class="sa-about__stat-label"><?php echo esc_html($stat['sa_stat_label'] ?? ''); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
