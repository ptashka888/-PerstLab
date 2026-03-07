<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="kv-header" id="kv-header">
    <div class="kv-container">
        <div class="kv-header-inner">

            <!-- Логотип -->
            <a href="<?php echo home_url('/'); ?>" class="kv-logo" aria-label="<?php bloginfo('name'); ?>">
                <div class="kv-logo__icon">⚒️</div>
                <div>
                    <span class="kv-logo__text"><?php bloginfo('name'); ?></span>
                    <span class="kv-logo__sub">Художественная ковка</span>
                </div>
            </a>

            <!-- Навигация -->
            <nav class="kv-nav" role="navigation" aria-label="Основное меню">

                <!-- Каталог -->
                <div class="kv-nav__item">
                    <a href="<?php echo get_post_type_archive_link('kv_product'); ?>" class="kv-nav__link">
                        Каталог <span style="font-size:.65rem">▾</span>
                    </a>
                    <div class="kv-dropdown">
                        <?php
                        $cats = kv_get_category_data();
                        foreach ($cats as $slug => $cat) :
                            $term = get_term_by('slug', $slug, 'kv_category');
                            $url  = $term ? get_term_link($term) : get_page_link(get_page_by_path($slug));
                        ?>
                        <a href="<?= esc_url($url ?: home_url('/' . $slug . '/')) ?>">
                            <?= $cat['icon'] ?> <?= esc_html($cat['name']) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <a href="<?php echo home_url('/portfolio/'); ?>" class="kv-nav__link">Портфолио</a>
                <a href="<?php echo home_url('/calculator/'); ?>" class="kv-nav__link">Калькулятор</a>
                <a href="<?php echo home_url('/about/'); ?>" class="kv-nav__link">О нас</a>
                <a href="<?php echo home_url('/blog/'); ?>" class="kv-nav__link">Блог</a>
                <a href="<?php echo home_url('/contacts/'); ?>" class="kv-nav__link">Контакты</a>
            </nav>

            <!-- Действия справа -->
            <div class="kv-header-actions">
                <?php $phone = get_theme_mod('kv_phone', '+7 (800) 555-00-00'); ?>
                <a href="tel:<?= preg_replace('/\D/', '', $phone) ?>" class="kv-header-phone" aria-label="Позвонить">
                    <?= esc_html($phone) ?>
                </a>
                <a href="#kv-modal" class="kv-btn kv-btn--primary kv-btn--sm kv-modal-open">
                    Заказать
                </a>
            </div>

            <!-- Бургер -->
            <button class="kv-burger" id="kv-burger" aria-label="Открыть меню" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>

<!-- Мобильное меню -->
<div class="kv-mobile-menu" id="kv-mobile-menu" role="dialog" aria-label="Мобильное меню">
    <a href="<?php echo get_post_type_archive_link('kv_product'); ?>">📦 Каталог</a>
    <?php foreach (kv_get_category_data() as $slug => $cat) : ?>
    <a href="<?= esc_url(home_url('/' . $slug . '/')) ?>" style="padding-left:32px;font-size:.875rem">
        <?= $cat['icon'] ?> <?= esc_html($cat['name']) ?>
    </a>
    <?php endforeach; ?>
    <a href="<?php echo home_url('/portfolio/'); ?>">🖼 Портфолио</a>
    <a href="<?php echo home_url('/calculator/'); ?>">🔢 Калькулятор</a>
    <a href="<?php echo home_url('/about/'); ?>">🏭 О нас</a>
    <a href="<?php echo home_url('/blog/'); ?>">📝 Блог</a>
    <a href="<?php echo home_url('/contacts/'); ?>">📍 Контакты</a>
    <div style="padding:20px 0">
        <?php $phone = get_theme_mod('kv_phone', '+7 (800) 555-00-00'); ?>
        <a href="tel:<?= preg_replace('/\D/', '', $phone) ?>" class="kv-btn kv-btn--primary" style="width:100%;justify-content:center;margin-bottom:10px">
            📞 <?= esc_html($phone) ?>
        </a>
        <a href="#kv-modal" class="kv-btn kv-btn--secondary kv-modal-open" style="width:100%;justify-content:center">
            Заказать расчёт
        </a>
    </div>
</div>
