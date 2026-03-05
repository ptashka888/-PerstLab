<?php
/**
 * Block: Trust
 * Trust indicators section.
 *
 * @param array $args (none expected)
 */

defined('ABSPATH') || exit;

$items = [
    [
        'icon'        => '🏢',
        'title'       => 'Официальный дилер',
        'description' => 'Работаем по договору с полным комплектом документов.',
    ],
    [
        'icon'        => '🛡️',
        'title'       => 'Застрахованные сделки',
        'description' => 'Каждая сделка застрахована — ваши деньги под защитой.',
    ],
    [
        'icon'        => '👥',
        'title'       => '2500+ довольных клиентов',
        'description' => 'Тысячи клиентов уже получили свой автомобиль мечты.',
    ],
    [
        'icon'        => '🔧',
        'title'       => 'Собственная СТО',
        'description' => 'Предпродажная подготовка и обслуживание на своей станции.',
    ],
    [
        'icon'        => '📄',
        'title'       => 'Юридическая чистота',
        'description' => 'Полная проверка по всем базам: залоги, аресты, ДТП.',
    ],
    [
        'icon'        => '↩️',
        'title'       => 'Гарантия возврата',
        'description' => 'Вернём деньги, если автомобиль не соответствует описанию.',
    ],
];
?>

<section class="cf-trust">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">Почему нам доверяют</h2>
            <p class="cf-section-header__subtitle">Гарантии, которые дают уверенность в каждой сделке</p>
        </div>

        <div class="cf-trust__grid">
            <?php foreach ($items as $item): ?>
                <div class="cf-trust__item">
                    <div class="cf-trust__icon"><?php echo esc_html($item['icon']); ?></div>
                    <h3 class="cf-trust__title"><?php echo esc_html($item['title']); ?></h3>
                    <p class="cf-trust__description"><?php echo esc_html($item['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
