<?php
/**
 * Block: Self vs Us
 * Comparison: self-import vs. using CarFinance.
 *
 * @param array $args (none expected — hardcoded content)
 */

defined('ABSPATH') || exit;

$negative_items = [
    'Риск купить битый авто',
    'Сложная растаможка',
    'Нет гарантии на авто',
    'Срок от 3 месяцев',
    'Скрытые расходы +30%',
];

$positive_items = [
    '48-точечная проверка',
    'Полная растаможка под ключ',
    'Гарантия 1 год',
    'Доставка от 14 дней',
    'Фиксированная цена',
];
?>

<section class="cf-comparison">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">Почему с нами выгоднее</h2>
            <p class="cf-section-header__subtitle">Сравните самостоятельный импорт и работу с CarFinance</p>
        </div>

        <div class="cf-comparison__grid">
            <div class="cf-comparison__col cf-comparison__col--negative">
                <h3 class="cf-comparison__col-title">Самостоятельно</h3>
                <ul class="cf-comparison__list">
                    <?php foreach ($negative_items as $item): ?>
                        <li class="cf-comparison__item">
                            <span class="cf-comparison__marker">✗</span>
                            <?php echo esc_html($item); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="cf-comparison__col cf-comparison__col--positive">
                <h3 class="cf-comparison__col-title">С CarFinance</h3>
                <ul class="cf-comparison__list">
                    <?php foreach ($positive_items as $item): ?>
                        <li class="cf-comparison__item">
                            <span class="cf-comparison__marker">✓</span>
                            <?php echo esc_html($item); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
