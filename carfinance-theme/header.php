
<?php
/**
 * Theme Header
 *
 * Top bar with contacts, main nav with SILO structure,
 * country dropdown, CTA button, mobile burger menu.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#1a56db">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ===== HEADER ===== -->
<header class="cf-header" role="banner">

  <!-- Top bar -->
  <div class="cf-header__top">
    <div class="cf-container">
      <div class="cf-header__top-left">
        <span>Москва, Владивосток, Краснодар, Сочи, Уссурийск</span>
      </div>
      <div class="cf-header__top-right" style="display:flex;gap:16px;align-items:center;">
        <a href="mailto:info@carfinance-msk.ru">info@carfinance-msk.ru</a>
        <a href="https://t.me/carfinance_msk" target="_blank" rel="noopener">Telegram</a>
        <a href="https://wa.me/7XXXXXXXXXX" target="_blank" rel="noopener">WhatsApp</a>
      </div>
    </div>
  </div>

  <!-- Main navigation -->
  <div class="cf-header__main">
    <div class="cf-container">

      <!-- Logo -->
      <a href="<?php echo esc_url(home_url('/')); ?>" class="cf-logo" aria-label="На главную">
        <?php if (has_custom_logo()) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          Car<span>Finance</span>
        <?php endif; ?>
      </a>

      <!-- Navigation -->
      <nav class="cf-nav" id="cf-main-nav" role="navigation" aria-label="Основная навигация">

        <!-- Countries dropdown (Level 1 SILO) -->
        <div class="cf-nav__dropdown">
          <a href="#" class="cf-nav__link" aria-haspopup="true">Направления &#9662;</a>
          <div class="cf-nav__dropdown-menu">
            <?php
            $countries = [
                'korea' => ['flag' => "\xF0\x9F\x87\xB0\xF0\x9F\x87\xB7", 'name' => 'Корея'],
                'japan' => ['flag' => "\xF0\x9F\x87\xAF\xF0\x9F\x87\xB5", 'name' => 'Япония'],
                'china' => ['flag' => "\xF0\x9F\x87\xA8\xF0\x9F\x87\xB3", 'name' => 'Китай'],
                'usa'   => ['flag' => "\xF0\x9F\x87\xBA\xF0\x9F\x87\xB8", 'name' => 'США'],
                'uae'   => ['flag' => "\xF0\x9F\x87\xA6\xF0\x9F\x87\xAA", 'name' => 'ОАЭ'],
            ];
            foreach ($countries as $slug => $c) :
            ?>
              <a href="<?php echo esc_url(home_url('/' . $slug . '/')); ?>">
                <span class="flag"><?php echo $c['flag']; ?></span>
                <?php echo esc_html('Авто из ' . $c['name']); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <a href="<?php echo esc_url(home_url('/catalog/')); ?>" class="cf-nav__link">Каталог</a>

        <!-- Services dropdown -->
        <div class="cf-nav__dropdown">
          <a href="<?php echo esc_url(home_url('/services/')); ?>" class="cf-nav__link" aria-haspopup="true">Услуги &#9662;</a>
          <div class="cf-nav__dropdown-menu">
            <a href="<?php echo esc_url(home_url('/avtopodborshchik/')); ?>">Автоподбор</a>
            <a href="<?php echo esc_url(home_url('/kupit-avto-s-probegom/')); ?>">Авто с пробегом</a>
            <a href="<?php echo esc_url(home_url('/proverka-avto-gibdd/')); ?>">Проверка авто</a>
          </div>
        </div>

        <a href="<?php echo esc_url(home_url('/calculator/')); ?>" class="cf-nav__link">Калькулятор</a>
        <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="cf-nav__link">Блог</a>
        <a href="<?php echo esc_url(home_url('/o-kompanii/')); ?>" class="cf-nav__link">О нас</a>
      </nav>

      <!-- Right side: phone + CTA -->
      <div class="cf-header__cta">
        <a href="tel:+7XXXXXXXXXX" class="cf-header__phone">+7 (XXX) XXX-XX-XX</a>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--primary cf-btn--sm" data-modal="lead">Оставить заявку</a>
      </div>

      <!-- Mobile burger -->
      <button class="cf-burger" id="cf-burger" aria-label="Открыть меню" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>

    </div>
  </div>

</header>

<!-- Breadcrumbs -->
<?php cf_breadcrumbs(); ?>

<main id="cf-main" role="main">
