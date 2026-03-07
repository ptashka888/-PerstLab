<?php
/**
 * Header Template
 *
 * @package StoneArt
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-50 text-gray-800 antialiased overflow-x-hidden'); ?>>
<?php wp_body_open(); ?>

<div id="toast-container" class="sa-toast-container"></div>

<header class="sa-header" id="header">
    <!-- Top Bar -->
    <div class="sa-header__topbar">
        <div class="sa-container sa-header__topbar-inner">
            <div class="sa-header__topbar-left">
                <span><i class="fa-solid fa-location-dot sa-text-primary mr-2"></i><?php echo esc_html(sa_address()); ?></span>
                <span><i class="fa-regular fa-clock sa-text-primary mr-2"></i><?php echo esc_html(sa_hours()); ?></span>
                <span class="sa-badge">B2B и Частные клиенты | Работаем по РФ</span>
            </div>
            <div class="sa-header__topbar-right">
                <a href="mailto:<?php echo esc_attr(sa_email()); ?>">
                    <i class="fa-regular fa-envelope sa-text-primary mr-2"></i><?php echo esc_html(sa_email()); ?>
                </a>
                <div style="display:flex;gap:0.75rem;border-left:1px solid #374151;padding-left:1rem;">
                    <?php if (sa_whatsapp() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_whatsapp()); ?>" style="color:#22c55e;" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                    <?php endif; ?>
                    <?php if (sa_telegram() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_telegram()); ?>" style="color:#3b82f6;" aria-label="Telegram"><i class="fa-brands fa-telegram"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="sa-container sa-header__main" id="main-header-bar">
        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="sa-logo">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <i class="fa-solid fa-gem sa-logo__icon"></i>
                <div class="sa-logo__text">Stone<span>Art</span></div>
            <?php endif; ?>
        </a>

        <!-- Navigation / Mega Menu -->
        <nav class="sa-nav" aria-label="Основная навигация">
            <?php if (has_nav_menu('primary')) : ?>
                <?php wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'walker'         => new SA_Nav_Walker(),
                    'depth'          => 2,
                ]); ?>
            <?php else : ?>
                <?php
                // Mega menu fallback — hardcoded structure
                $mega = [
                    'Изделия' => [
                        'url'  => get_page_by_path('services') ? get_permalink(get_page_by_path('services')) : '#',
                        'cols' => [
                            [
                                ['slug' => 'stoleshnitsy',      'label' => 'Столешницы',          'icon' => 'fa-solid fa-kitchen-set'],
                                ['slug' => 'lestnitsy',         'label' => 'Лестницы и ступени',  'icon' => 'fa-solid fa-stairs'],
                                ['slug' => 'kaminy',            'label' => 'Камины и порталы',    'icon' => 'fa-solid fa-fire'],
                            ],
                            [
                                ['slug' => 'poly-i-oblitsovka', 'label' => 'Полы и облицовка',   'icon' => 'fa-solid fa-grip'],
                                ['slug' => 'rakoviny',          'label' => 'Раковины и мойки',   'icon' => 'fa-solid fa-sink'],
                                ['slug' => 'vanny',             'label' => 'Ванны из камня',      'icon' => 'fa-solid fa-bath'],
                            ],
                            [
                                ['slug' => 'fasady',            'label' => 'Фасады и экстерьер', 'icon' => 'fa-solid fa-building'],
                                ['slug' => 'malye-formy',       'label' => 'Малые формы',         'icon' => 'fa-solid fa-mountain'],
                                ['slug' => 'pamyatniki',        'label' => 'Памятники',           'icon' => 'fa-solid fa-monument'],
                            ],
                        ],
                    ],
                    'Материалы' => [
                        'url'  => get_page_by_path('materials') ? get_permalink(get_page_by_path('materials')) : '#',
                        'cols' => [
                            [
                                ['slug' => 'materialy-mramor',   'label' => 'Мрамор',    'icon' => 'fa-solid fa-gem'],
                                ['slug' => 'materialy-granit',   'label' => 'Гранит',    'icon' => 'fa-solid fa-gem'],
                                ['slug' => 'materialy-oniks',    'label' => 'Оникс',     'icon' => 'fa-solid fa-gem'],
                                ['slug' => 'materialy-travertin','label' => 'Травертин', 'icon' => 'fa-solid fa-gem'],
                                ['slug' => 'materialy-kvartsit', 'label' => 'Кварцит',   'icon' => 'fa-solid fa-gem'],
                            ],
                        ],
                    ],
                    'Услуги' => [
                        'url'  => get_page_by_path('services') ? get_permalink(get_page_by_path('services')) : '#',
                        'cols' => [
                            [
                                ['slug' => 'uslugi-zamer',          'label' => 'Выезд замерщика',    'icon' => 'fa-solid fa-ruler-combined'],
                                ['slug' => 'uslugi-proektirovanie', 'label' => 'Проектирование 3D',  'icon' => 'fa-solid fa-cube'],
                                ['slug' => 'uslugi-dostavka',       'label' => 'Доставка',           'icon' => 'fa-solid fa-truck'],
                                ['slug' => 'uslugi-montazh',        'label' => 'Монтаж и установка', 'icon' => 'fa-solid fa-screwdriver-wrench'],
                                ['slug' => 'uslugi-restavratsiya',  'label' => 'Реставрация',        'icon' => 'fa-solid fa-wand-magic-sparkles'],
                                ['slug' => 'uslugi-ukhod',          'label' => 'Уход за камнем',     'icon' => 'fa-solid fa-spray-can-sparkles'],
                            ],
                        ],
                    ],
                ];

                foreach ($mega as $label => $data) :
                    $has_cols = !empty($data['cols']);
                    ?>
                    <?php if ($has_cols) : ?>
                        <div class="sa-nav__mega-wrap">
                            <a href="<?php echo esc_url($data['url']); ?>" class="sa-nav__link">
                                <?php echo esc_html($label); ?> <i class="fa-solid fa-chevron-down" style="font-size:0.6rem;margin-left:0.25rem;"></i>
                            </a>
                            <div class="sa-nav__mega">
                                <div class="sa-nav__mega-inner">
                                    <?php foreach ($data['cols'] as $col) : ?>
                                        <div class="sa-nav__mega-col">
                                            <?php foreach ($col as $link) :
                                                $page = get_page_by_path($link['slug']);
                                                if (!$page) continue;
                                                $url = get_permalink($page);
                                                ?>
                                                <a href="<?php echo esc_url($url); ?>" class="sa-nav__mega-item">
                                                    <i class="<?php echo esc_attr($link['icon']); ?> sa-nav__mega-icon"></i>
                                                    <span><?php echo esc_html($link['label']); ?></span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url($data['url']); ?>" class="sa-nav__link"><?php echo esc_html($label); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php
                $simple = ['portfolio-page' => 'Портфолио', 'blog' => 'Блог', 'contacts' => 'Контакты'];
                foreach ($simple as $slug => $lbl) {
                    $page = get_page_by_path($slug);
                    echo '<a href="' . esc_url($page ? get_permalink($page) : '#') . '" class="sa-nav__link">' . esc_html($lbl) . '</a>';
                }
                ?>
            <?php endif; ?>
        </nav>

        <!-- Actions -->
        <div class="sa-header__actions">
            <div class="sa-header__phone">
                <a href="<?php echo esc_attr(sa_phone_href()); ?>" class="sa-header__phone-number">
                    <?php echo esc_html(sa_phone()); ?>
                </a>
                <a href="#quiz-section" class="sa-header__phone-callback">Заказать звонок</a>
            </div>

            <a href="<?php
                $calc = get_page_by_path('calculator');
                echo $calc ? esc_url(get_permalink($calc)) : '#quiz-section';
            ?>" class="sa-btn sa-btn--primary" style="display:none;">
                <i class="fa-solid fa-calculator"></i> Рассчитать
            </a>
            <style>@media(min-width:768px){.sa-header__actions .sa-btn{display:inline-flex!important;}}</style>

            <button class="sa-mobile-btn" id="mobile-menu-btn" aria-label="Открыть меню">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="sa-mobile-overlay" id="mobile-menu-overlay">
        <div class="sa-mobile-drawer" id="mobile-menu-drawer">
            <div class="sa-mobile-drawer__header">
                <div style="font-size:1.25rem;font-weight:800;color:var(--sa-gray-900);text-transform:uppercase;font-family:var(--sa-font-serif);">
                    <i class="fa-solid fa-gem sa-text-primary" style="margin-right:0.5rem;"></i>Stone<span class="sa-text-primary">Art</span>
                </div>
                <button class="sa-mobile-drawer__close" id="mobile-menu-close" aria-label="Закрыть меню">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="sa-mobile-drawer__nav">
                <?php
                if (has_nav_menu('mobile')) {
                    wp_nav_menu([
                        'theme_location' => 'mobile',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'link_before'    => '',
                        'link_after'     => '',
                        'depth'          => 1,
                    ]);
                } else {
                    $mobile_pages = [
                        'about'          => 'О производстве',
                        'services'       => 'Изделия',
                        'materials'      => 'Каталог материалов',
                        'portfolio-page' => 'Наши работы',
                        'blog'           => 'Блог',
                        'contacts'       => 'Контакты',
                    ];
                    foreach ($mobile_pages as $slug => $label) {
                        $page = get_page_by_path($slug);
                        $url = $page ? get_permalink($page) : '#';
                        echo '<a href="' . esc_url($url) . '" class="sa-mobile-drawer__link">' . esc_html($label) . '</a>';
                    }
                }
                ?>
            </div>
            <div class="sa-mobile-drawer__footer">
                <a href="<?php echo esc_attr(sa_phone_href()); ?>" class="sa-mobile-drawer__footer-phone">
                    <i class="fa-solid fa-phone sa-text-primary" style="margin-right:0.5rem;"></i><?php echo esc_html(sa_phone()); ?>
                </a>
                <a href="<?php
                    $calc = get_page_by_path('calculator');
                    echo $calc ? esc_url(get_permalink($calc)) : '#quiz-section';
                ?>" class="sa-btn sa-btn--primary" style="width:100%;text-align:center;">Рассчитать стоимость</a>
            </div>
        </div>
    </div>
</header>
