<?php
/**
 * Theme Header
 *
 * Top bar with contacts, mega-menu with all countries visible to GoogleBot,
 * CTA button, mobile burger menu.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

$countries = cf_get_country_data();
$phone = function_exists('get_field') ? get_field('site_phone_moscow', 'option') : '';
$phone = $phone ?: '+7 (XXX) XXX-XX-XX';
$whatsapp = function_exists('get_field') ? get_field('site_whatsapp', 'option') : '';
$telegram = function_exists('get_field') ? get_field('site_telegram', 'option') : 'carfinance_msk';
$email = function_exists('get_field') ? get_field('site_email', 'option') : 'info@carfinance-msk.ru';
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

<header class="cf-header" role="banner">

  <!-- Top bar -->
  <div class="cf-header__top">
    <div class="cf-container">
      <div class="cf-header__top-left">
        <span>Москва, Владивосток, Краснодар, Сочи, Уссурийск</span>
      </div>
      <div class="cf-header__top-right">
        <a href="<?php echo esc_url('mailto:' . $email); ?>"><?php echo esc_html($email); ?></a>
        <?php if ($telegram) : ?>
          <a href="<?php echo esc_url('https://t.me/' . $telegram); ?>" target="_blank" rel="noopener">Telegram</a>
        <?php endif; ?>
        <?php if ($whatsapp) : ?>
          <a href="<?php echo esc_url('https://wa.me/' . preg_replace('/\D/', '', $whatsapp)); ?>" target="_blank" rel="noopener">WhatsApp</a>
        <?php endif; ?>
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

      <!-- Navigation (all links visible to GoogleBot) -->
      <nav class="cf-nav" id="cf-main-nav" role="navigation" aria-label="Основная навигация">

        <!-- Countries dropdown (Level 1 SILO — inter-cocoon links) -->
        <div class="cf-nav__dropdown">
          <a href="#" class="cf-nav__link" aria-haspopup="true">Направления &#9662;</a>
          <div class="cf-nav__dropdown-menu">
            <?php foreach ($countries as $code => $c) : ?>
              <a href="<?php echo esc_url(home_url($c['url'])); ?>">
                <span class="flag"><?php echo $c['flag']; ?></span>
                <?php echo esc_html('Авто ' . $c['name_from']); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <a href="<?php echo esc_url(home_url('/catalog/')); ?>" class="cf-nav__link">Каталог</a>

        <!-- Services dropdown -->
        <div class="cf-nav__dropdown">
          <a href="<?php echo esc_url(home_url('/services/')); ?>" class="cf-nav__link" aria-haspopup="true">Услуги &#9662;</a>
          <div class="cf-nav__dropdown-menu">
            <a href="<?php echo esc_url(home_url('/services/avtopodborshchik/')); ?>">Автоподбор</a>
            <a href="<?php echo esc_url(home_url('/services/import-pod-klyuch/')); ?>">Импорт под ключ</a>
            <a href="<?php echo esc_url(home_url('/services/kredit-lizing/')); ?>">Кредит / Лизинг</a>
            <a href="<?php echo esc_url(home_url('/services/logistika/')); ?>">Логистика</a>
            <a href="<?php echo esc_url(home_url('/services/trade-in/')); ?>">Trade-in</a>
          </div>
        </div>

        <a href="<?php echo esc_url(home_url('/calculator/')); ?>" class="cf-nav__link">Калькулятор</a>
        <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="cf-nav__link">Блог</a>
        <a href="<?php echo esc_url(home_url('/o-kompanii/')); ?>" class="cf-nav__link">О нас</a>
      </nav>

      <!-- Right side: phone + CTA -->
      <div class="cf-header__cta">
        <a href="<?php echo esc_url('tel:' . preg_replace('/\s/', '', $phone)); ?>" class="cf-header__phone"><?php echo esc_html($phone); ?></a>
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
