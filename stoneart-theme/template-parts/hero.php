<?php
/**
 * Template Part: Hero Section
 *
 * @package StoneArt
 */

$hero_title    = sa_option('sa_hero_title', 'Изделия из натурального и искусственного камня');
$hero_accent   = sa_option('sa_hero_accent', 'на заказ в Москве');
$hero_bg       = sa_option('sa_hero_bg');
$hero_bg_url   = $hero_bg ? $hero_bg['url'] : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80';

$features = sa_option('sa_hero_features');
if (!$features) {
    $features = [
        ['sa_feature_text' => '<strong>Официальная гарантия 10 лет</strong> по договору на материал и монтаж'],
        ['sa_feature_text' => 'Сотни слэбов в наличии: от акрила Grandex до кварцита Patagonia'],
        ['sa_feature_text' => 'Срок изготовления столешниц и подоконников <strong>от 5 дней</strong>'],
    ];
}

$promo_text = sa_option('sa_hero_promo', 'Пройдите тест за 1 минуту и получите скидку до 30 000 ₽');
$cta_text   = sa_option('sa_hero_cta_text', 'Рассчитать стоимость');
$cta_url    = sa_option('sa_hero_cta_url');
if (!$cta_url) {
    $calc = get_page_by_path('calculator');
    $cta_url = $calc ? get_permalink($calc) : '#quiz-section';
}
$gift_text = sa_option('sa_hero_gift', 'Подарок за прохождение — набор по уходу за камнем.');
?>

<section id="hero" class="sa-hero" style="background-image: url('<?php echo esc_url($hero_bg_url); ?>');">
    <div class="sa-hero__overlay"></div>
    <div class="sa-container" style="position:relative;z-index:10;">
        <div class="sa-hero__content">
            <h1 class="sa-hero__title">
                <?php echo esc_html($hero_title); ?>
                <span class="sa-hero__title-accent"><?php echo esc_html($hero_accent); ?></span>
            </h1>

            <ul class="sa-hero__features">
                <?php foreach ($features as $feature) :
                    $text = is_array($feature) ? ($feature['sa_feature_text'] ?? '') : $feature;
                ?>
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <span><?php echo wp_kses_post($text); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="sa-hero__promo">
                <span class="sa-hero__promo-dot"></span>
                <p class="sa-hero__promo-text"><?php echo esc_html($promo_text); ?></p>
            </div>

            <div class="sa-hero__actions">
                <a href="<?php echo esc_url($cta_url); ?>" class="sa-btn sa-btn--primary sa-btn--lg sa-btn--pulse">
                    <?php echo esc_html($cta_text); ?>
                </a>
            </div>

            <?php if ($gift_text) : ?>
                <p class="sa-hero__gift">
                    <i class="fa-solid fa-gift"></i>
                    <?php echo esc_html($gift_text); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>
