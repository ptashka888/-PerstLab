<?php
/**
 * Block: Service Packages
 * Pricing packages (3 tiers).
 *
 * @param array $args {
 *     @type array $packages     Array of package data
 *     @type bool  $show_popular Highlight popular package (default true)
 * }
 */

defined('ABSPATH') || exit;

$show_popular = $args['show_popular'] ?? true;
$packages     = $args['packages'] ?? [];

if (empty($packages)) {
    $packages = [
        [
            'name'     => 'Эконом',
            'price'    => 'от 50 000 ₽',
            'popular'  => false,
            'features' => [
                'Подбор авто',
                'Проверка по базам',
                'Консультация',
            ],
        ],
        [
            'name'     => 'Стандарт',
            'price'    => 'от 150 000 ₽',
            'popular'  => true,
            'features' => [
                'Всё из пакета Эконом',
                'Торги на аукционе',
                'Доставка до порта',
                'Растаможка под ключ',
            ],
        ],
        [
            'name'     => 'Премиум',
            'price'    => 'от 250 000 ₽',
            'popular'  => false,
            'features' => [
                'Всё из пакета Стандарт',
                'Персональный менеджер',
                'Гарантия 2 года',
                'КАСКО в подарок',
            ],
        ],
    ];
}
?>

<section class="cf-packages">
    <div class="cf-container">
        <div class="cf-section-header">
            <h2 class="cf-section-header__title">Пакеты услуг</h2>
            <p class="cf-section-header__subtitle">Выберите подходящий пакет — или закажите индивидуальный расчёт</p>
        </div>

        <div class="cf-packages__grid">
            <?php foreach ($packages as $pkg):
                $is_popular = $show_popular && !empty($pkg['popular']);
                $card_class = 'cf-packages__card';
                if ($is_popular) {
                    $card_class .= ' cf-packages__card--popular';
                }
            ?>
                <div class="<?php echo esc_attr($card_class); ?>">
                    <?php if ($is_popular): ?>
                        <div class="cf-packages__badge">Популярный</div>
                    <?php endif; ?>

                    <h3 class="cf-packages__name"><?php echo esc_html($pkg['name']); ?></h3>
                    <div class="cf-packages__price"><?php echo esc_html($pkg['price']); ?></div>

                    <ul class="cf-packages__features">
                        <?php foreach ($pkg['features'] as $feature): ?>
                            <li class="cf-packages__feature">
                                <span class="cf-packages__check">✓</span>
                                <?php echo esc_html($feature); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="#cf-modal" class="cf-btn <?php echo $is_popular ? 'cf-btn--primary' : 'cf-btn--secondary'; ?> cf-packages__cta" data-modal="lead" data-package="<?php echo esc_attr($pkg['name']); ?>">
                        Оставить заявку
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
