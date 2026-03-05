<?php
defined('ABSPATH') || exit;

$variant   = $args['variant'] ?? 'default';
$city_name = $args['city_name'] ?? '';

$title    = $args['title'] ?? '';
$subtitle = $args['subtitle'] ?? '';

if (empty($title)) {
    switch ($variant) {
        case 'urgency':
            $title = 'Готовы заказать автомобиль?';
            break;
        case 'city':
            $title = 'Закажите авто в ' . esc_html($city_name);
            break;
        default:
            $title = 'Готовы заказать автомобиль?';
            break;
    }
}

if (empty($subtitle)) {
    switch ($variant) {
        case 'urgency':
            $subtitle = 'Курс валют растёт — зафиксируйте цену сегодня!';
            break;
        case 'city':
            $subtitle = 'Доставим автомобиль прямо в ' . esc_html($city_name) . '. Полное сопровождение сделки.';
            break;
        default:
            $subtitle = 'Оставьте заявку и получите бесплатную консультацию по подбору автомобиля.';
            break;
    }
}

$phone        = function_exists('cf_get_field') ? cf_get_field('cf_phone', 'option') : '';
$phone_clean  = $phone ? preg_replace('/[^+\d]/', '', $phone) : '+74951234567';
$phone_display = $phone ?: '+7 (495) 123-45-67';
?>
<section class="cf-cta cf-cta--<?php echo esc_attr($variant); ?>">
    <div class="cf-cta__container">
        <h2 class="cf-cta__title"><?php echo esc_html($title); ?></h2>
        <p class="cf-cta__subtitle"><?php echo esc_html($subtitle); ?></p>

        <div class="cf-cta__actions">
            <button class="cf-cta__button cf-btn cf-btn--primary cf-btn--lg" data-modal="cf-lead-modal" type="button">
                Оставить заявку
            </button>
            <a class="cf-cta__phone" href="tel:<?php echo esc_attr($phone_clean); ?>">
                <?php echo esc_html($phone_display); ?>
            </a>
        </div>
    </div>
</section>
