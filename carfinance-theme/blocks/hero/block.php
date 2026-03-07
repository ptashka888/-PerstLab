<?php
/**
 * Block: Hero
 * Variants: home, country, service, city, calculator
 *
 * @param array $args {
 *     @type string $variant   'home'|'country'|'service'|'city'|'calculator'
 *     @type string $title     Override title
 *     @type string $subtitle  Override subtitle
 *     @type string $bg_image  Background image URL
 *     @type string $country   Country code for country variant
 * }
 */

defined('ABSPATH') || exit;

$variant  = $args['variant'] ?? 'home';
$title    = $args['title'] ?? '';
$subtitle = $args['subtitle'] ?? '';
$bg_image = $args['bg_image'] ?? '';
$country  = $args['country'] ?? '';

// Defaults per variant
if ($variant === 'home') {
    $title    = $title ?: cf_get_field('cf_hero_title', get_the_ID()) ?: 'Импорт автомобилей из-за рубежа под ключ';
    $subtitle = $subtitle ?: cf_get_field('cf_hero_subtitle', get_the_ID()) ?: 'Подберём, проверим, доставим и растаможим автомобиль вашей мечты из Кореи, Японии, Китая, США и ОАЭ';
    $bg_image = $bg_image ?: (cf_get_field('cf_hero_image', get_the_ID()) ?: '');
    if (is_array($bg_image)) $bg_image = $bg_image['url'] ?? '';
}

if ($variant === 'country' && $country) {
    $data     = cf_get_country_data($country);
    $title    = $title ?: cf_get_field('cf_country_hero_title', get_the_ID()) ?: 'Автомобили из ' . ($data['name_from'] ?? $data['name']);
    $subtitle = $subtitle ?: cf_get_field('cf_country_hero_subtitle', get_the_ID()) ?: '';
    $bg_image = $bg_image ?: (cf_get_field('cf_country_hero_image', get_the_ID()) ?: '');
    if (is_array($bg_image)) $bg_image = $bg_image['url'] ?? '';
}

$bg_style      = $bg_image ? 'background-image: url(' . esc_url($bg_image) . ');' : '';
$phone         = cf_get_field('cf_phone', 'option') ?: '+7 (800) 123-45-67';
$phone_display = cf_get_field('cf_phone_display', 'option') ?: $phone;

// Country quick-select pills for home hero
$country_pills = [
    'korea' => ['flag' => '🇰🇷', 'label' => 'Корея', 'url' => home_url('/avto-iz-korei/')],
    'japan' => ['flag' => '🇯🇵', 'label' => 'Япония', 'url' => home_url('/avto-iz-yaponii/')],
    'china' => ['flag' => '🇨🇳', 'label' => 'Китай', 'url' => home_url('/avto-iz-kitaya/')],
    'usa'   => ['flag' => '🇺🇸', 'label' => 'США', 'url' => home_url('/avto-iz-ssha/')],
    'uae'   => ['flag' => '🇦🇪', 'label' => 'ОАЭ', 'url' => home_url('/avto-iz-oae/')],
];
?>

<section class="cf-hero cf-hero--<?php echo esc_attr($variant); ?>"<?php if ($bg_style): ?> style="<?php echo esc_attr($bg_style); ?>"<?php endif; ?>>

    <?php if ($variant === 'home'): ?>
    <!-- Live stats ticker -->
    <div class="cf-hero__ticker">
        <div class="cf-container">
            <span class="cf-hero__ticker-item">🔥 Сегодня подобрано: <strong class="cf-counter" data-target="7">7</strong> авто</span>
            <span class="cf-hero__ticker-sep">·</span>
            <span class="cf-hero__ticker-item">📦 В пути сейчас: <strong>23</strong> автомобиля</span>
            <span class="cf-hero__ticker-sep">·</span>
            <span class="cf-hero__ticker-item">⭐ Средний балл: <strong>4.9/5</strong></span>
        </div>
    </div>
    <?php endif; ?>

    <div class="cf-container">
        <div class="cf-hero__content">
            <h1 class="cf-hero__title"><?php echo esc_html($title); ?></h1>

            <?php if ($subtitle): ?>
                <p class="cf-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>

            <?php if ($variant === 'home'): ?>
            <!-- Country quick-select pills -->
            <div class="cf-hero__country-pills">
                <?php foreach ($country_pills as $key => $pill): ?>
                    <a href="<?php echo esc_url($pill['url']); ?>" class="cf-hero__country-pill">
                        <?php echo $pill['flag']; ?> <?php echo esc_html($pill['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="cf-hero__actions">
                <a href="#cf-modal" class="cf-btn cf-btn--primary cf-btn--lg cf-hero__cta" data-modal="lead">
                    <?php echo esc_html(cf_get_field('cf_cta_text', 'option') ?: 'Получить консультацию'); ?>
                </a>
                <a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>" class="cf-btn cf-btn--secondary cf-btn--lg cf-hero__phone">
                    <?php echo esc_html($phone_display); ?>
                </a>
                <?php if ($variant === 'home'): ?>
                <a href="https://t.me/carfinancemsk" class="cf-btn cf-btn--tg cf-btn--lg" target="_blank" rel="noopener">
                    💬 Telegram
                </a>
                <?php endif; ?>
            </div>

            <?php if ($variant === 'home'): ?>
                <div class="cf-hero__badges">
                    <span class="cf-hero__badge">✓ Без посредников</span>
                    <span class="cf-hero__badge">✓ Полное сопровождение</span>
                    <span class="cf-hero__badge">✓ Гарантия на авто</span>
                    <span class="cf-hero__badge">✓ 3100+ доставленных авто</span>
                    <span class="cf-hero__badge">✓ 8 лет на рынке</span>
                </div>
                <!-- Urgency social proof -->
                <p class="cf-hero__social-proof">
                    🕐 Среднее время ответа: <strong>2 минуты</strong> · Работаем с 9:00 до 21:00 по МСК
                </p>
            <?php endif; ?>

            <?php if ($variant === 'country' && $country && isset($data)): ?>
                <div class="cf-hero__country-flag">
                    <span class="cf-hero__flag"><?php echo esc_html($data['flag'] ?? ''); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
