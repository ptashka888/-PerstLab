<?php
/**
 * Block: Dealer Traps
 * "Ловушки дилеров" — warning cards about common dealer scams.
 *
 * @param array $args {
 *     @type array $items [ ['icon'=>..., 'title'=>..., 'text'=>...], ... ]
 * }
 */

defined('ABSPATH') || exit;

$items = $args['items'] ?? [];

if (empty($items)) {
    $items = [
        [
            'icon'  => '🔧',
            'title' => 'Скрученный пробег',
            'text'  => 'Дилеры занижают пробег на 30-50%, чтобы продать авто дороже.',
        ],
        [
            'icon'  => '🎨',
            'title' => 'Скрытые ДТП',
            'text'  => 'Профессиональная покраска скрывает серьёзные повреждения кузова.',
        ],
        [
            'icon'  => '💰',
            'title' => 'Навязанные допы',
            'text'  => 'Наценка до 500 000 ₽ за ненужное оборудование и услуги.',
        ],
        [
            'icon'  => '📋',
            'title' => 'Юридические проблемы',
            'text'  => 'Залоги, аресты, ограничения регистрации — всё скрывают до сделки.',
        ],
        [
            'icon'  => '🔄',
            'title' => 'Подмена комплектации',
            'text'  => 'Выдают базовую версию за топовую, завышая стоимость.',
        ],
        [
            'icon'  => '⏰',
            'title' => 'Серый импорт',
            'text'  => 'Без гарантии производителя и с проблемами при обслуживании.',
        ],
    ];
}
?>

<section class="cf-dealer-traps">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">Ловушки дилеров</h2>
            <p class="cf-section-header__subtitle">На что обращать внимание при покупке автомобиля</p>
        </div>

        <div class="cf-dealer-traps__grid">
            <?php foreach ($items as $item): ?>
                <div class="cf-dealer-traps__card">
                    <div class="cf-dealer-traps__icon"><?php echo esc_html($item['icon']); ?></div>
                    <h3 class="cf-dealer-traps__title"><?php echo esc_html($item['title']); ?></h3>
                    <p class="cf-dealer-traps__text"><?php echo esc_html($item['text']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
