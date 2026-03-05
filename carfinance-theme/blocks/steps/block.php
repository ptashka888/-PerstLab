<?php
defined('ABSPATH') || exit;

$variant = $args['variant'] ?? 'home';
$steps   = $args['steps'] ?? [];

if (empty($steps)) {
    if ($variant === 'country' && function_exists('cf_get_field')) {
        $steps = cf_get_field('cf_country_steps', get_the_ID());
    }

    if (empty($steps) || !is_array($steps)) {
        $steps = [
            ['title' => 'Заявка',       'text' => 'Оставьте заявку на сайте или позвоните нам. Мы свяжемся с вами в течение 15 минут.'],
            ['title' => 'Консультация',  'text' => 'Обсудим ваши пожелания, бюджет и подберём оптимальные варианты автомобилей.'],
            ['title' => 'Подбор авто',   'text' => 'Найдём автомобиль на аукционах и у проверенных дилеров по лучшей цене.'],
            ['title' => 'Проверка',      'text' => 'Полная проверка истории, технического состояния и юридической чистоты авто.'],
            ['title' => 'Покупка',       'text' => 'Выкупаем автомобиль после вашего подтверждения. Безопасная сделка с гарантией.'],
            ['title' => 'Доставка',      'text' => 'Организуем доставку морем или автовозом до порта или вашего города.'],
            ['title' => 'Растаможка',    'text' => 'Берём на себя все таможенные процедуры: пошлины, СБКТС, ЭПТС.'],
            ['title' => 'Передача',      'text' => 'Передаём автомобиль с полным пакетом документов. Вы — счастливый владелец!'],
        ];
    }
}
?>
<section class="cf-steps cf-steps--<?php echo esc_attr($variant); ?>">
    <div class="cf-steps__container">
        <h2 class="cf-steps__title">Как мы работаем</h2>
        <p class="cf-steps__subtitle">Прозрачный процесс от заявки до передачи автомобиля</p>

        <div class="cf-steps__timeline">
            <?php foreach ($steps as $index => $step) : ?>
                <div class="cf-steps__item">
                    <div class="cf-steps__number">
                        <span><?php echo $index + 1; ?></span>
                    </div>
                    <div class="cf-steps__content">
                        <h3 class="cf-steps__item-title"><?php echo esc_html($step['title']); ?></h3>
                        <?php if (!empty($step['text'])) : ?>
                            <p class="cf-steps__item-text"><?php echo esc_html($step['text']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
