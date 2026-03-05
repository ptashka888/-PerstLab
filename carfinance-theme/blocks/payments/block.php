<?php
/**
 * Block: Payments
 * Payment methods and financing info.
 *
 * @param array $args (none expected)
 */

defined('ABSPATH') || exit;

$items = [
    [
        'icon'        => '💵',
        'title'       => 'Наличные',
        'description' => 'Оплата наличными в офисе или при получении автомобиля.',
    ],
    [
        'icon'        => '🏦',
        'title'       => 'Безналичный расчёт',
        'description' => 'Перевод на расчётный счёт компании. Все документы предоставляем.',
    ],
    [
        'icon'        => '💳',
        'title'       => 'Кредит',
        'description' => 'Автокредит от 4.9% годовых. Одобрение за 1 день, без первого взноса.',
    ],
    [
        'icon'        => '📆',
        'title'       => 'Рассрочка',
        'description' => 'Рассрочка 0% на срок до 12 месяцев без переплаты.',
    ],
];
?>

<section class="cf-payments">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">Способы оплаты</h2>
            <p class="cf-section-header__subtitle">Выберите удобный способ оплаты — мы подберём лучшие условия</p>
        </div>

        <div class="cf-payments__grid">
            <?php foreach ($items as $item): ?>
                <div class="cf-payments__item">
                    <div class="cf-payments__icon"><?php echo esc_html($item['icon']); ?></div>
                    <h3 class="cf-payments__title"><?php echo esc_html($item['title']); ?></h3>
                    <p class="cf-payments__description"><?php echo esc_html($item['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
