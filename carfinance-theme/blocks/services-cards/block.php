<?php
defined('ABSPATH') || exit;

$limit = (int) ($args['limit'] ?? 6);

$defaults = [
    [
        'title' => 'Импорт под ключ',
        'text'  => 'Полный цикл: от подбора автомобиля до передачи вам с документами. Без скрытых платежей.',
        'icon'  => 'key',
        'url'   => '/services/import/',
    ],
    [
        'title' => 'Автоподбор',
        'text'  => 'Подберём автомобиль по вашим критериям из лучших предложений на рынке.',
        'icon'  => 'search',
        'url'   => '/services/autopodbor/',
    ],
    [
        'title' => 'Растаможка',
        'text'  => 'Оформим все таможенные документы и рассчитаем точную стоимость пошлин.',
        'icon'  => 'document',
        'url'   => '/services/rastamozhka/',
    ],
    [
        'title' => 'Доставка',
        'text'  => 'Организуем доставку из любой страны: морем, автовозом или ж/д транспортом.',
        'icon'  => 'truck',
        'url'   => '/services/dostavka/',
    ],
    [
        'title' => 'СБКТС / ЭПТС',
        'text'  => 'Оформление сертификатов безопасности и электронных паспортов транспортных средств.',
        'icon'  => 'shield',
        'url'   => '/services/sbkts-epts/',
    ],
    [
        'title' => 'Страхование',
        'text'  => 'ОСАГО и КАСКО от ведущих страховых компаний по выгодным тарифам.',
        'icon'  => 'umbrella',
        'url'   => '/services/strahovanie/',
    ],
];

$services_query = new WP_Query([
    'post_type'      => 'cf_service',
    'posts_per_page' => $limit,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

$services = [];
if ($services_query->have_posts()) {
    while ($services_query->have_posts()) {
        $services_query->the_post();
        $pid = get_the_ID();
        $services[] = [
            'title' => get_the_title(),
            'text'  => cf_get_field('cf_service_short_desc', $pid) ?: get_the_excerpt(),
            'icon'  => cf_get_field('cf_service_icon', $pid) ?: 'key',
            'url'   => get_permalink(),
        ];
    }
    wp_reset_postdata();
}

if (empty($services)) {
    $services = array_slice($defaults, 0, $limit);
}
?>
<section class="cf-services-cards">
    <div class="cf-services-cards__container">
        <h2 class="cf-services-cards__title">Наши услуги</h2>
        <p class="cf-services-cards__subtitle">Комплексные решения для покупки и оформления авто из-за рубежа</p>

        <div class="cf-services-cards__grid">
            <?php foreach ($services as $service) : ?>
                <div class="cf-services-cards__card">
                    <div class="cf-services-cards__icon" data-icon="<?php echo esc_attr($service['icon']); ?>">
                        <span class="cf-services-cards__icon-inner"></span>
                    </div>
                    <h3 class="cf-services-cards__card-title"><?php echo esc_html($service['title']); ?></h3>
                    <p class="cf-services-cards__card-text"><?php echo esc_html($service['text']); ?></p>
                    <?php if (!empty($service['url'])) : ?>
                        <a class="cf-services-cards__link" href="<?php echo esc_url($service['url']); ?>">
                            Подробнее &rarr;
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
