<?php
/**
 * Theme Header
 *
 * Top bar with contacts, sticky header with mega-menu, CTA button, mobile burger.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

$countries = cf_get_country_data();
$phone     = function_exists('get_field') ? get_field('site_phone_moscow', 'option') : '';
$phone     = $phone ?: '+7 (XXX) XXX-XX-XX';
$whatsapp  = function_exists('get_field') ? get_field('site_whatsapp', 'option') : '';
$telegram  = function_exists('get_field') ? get_field('site_telegram', 'option') : 'carfinance_msk';
$email     = function_exists('get_field') ? get_field('site_email', 'option') : 'info@carfinance-msk.ru';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#1a5276">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top bar (non-sticky) -->
<div class="cf-topbar">
  <div class="cf-container">
    <div class="cf-topbar__inner">
      <div class="cf-topbar__cities">
        <span>Москва</span>
        <span>Владивосток</span>
        <span>Краснодар</span>
        <span>Сочи</span>
        <span>Уссурийск</span>
      </div>
      <div class="cf-topbar__contacts">
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
</div>

<!-- Sticky header -->
<header class="cf-header" role="banner">
  <div class="cf-container">
    <div class="cf-header__inner">

      <!-- Logo -->
      <a href="<?php echo esc_url(home_url('/')); ?>" class="cf-header__logo" aria-label="На главную">
        <?php if (has_custom_logo()) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          Car<span>Finance</span>
        <?php endif; ?>
      </a>

      <!-- Navigation (all links visible to GoogleBot) -->
      <nav class="cf-nav" id="cf-main-nav" role="navigation" aria-label="Основная навигация">

        <!-- Countries dropdown (Level 1 SILO) -->
        <div class="cf-nav__item">
          <a href="#" aria-haspopup="true">Направления &#9662;</a>
          <div class="cf-nav__dropdown">
            <?php foreach ($countries as $code => $c) : ?>
              <a href="<?php echo esc_url(home_url($c['url'])); ?>" class="cf-nav__dropdown-item">
                <span class="flag"><?php echo $c['flag']; ?></span>
                <?php echo esc_html('Авто ' . $c['name_from']); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <a href="<?php echo esc_url(home_url('/catalog/')); ?>" class="cf-nav__item">Каталог</a>

        <!-- Services dropdown -->
        <div class="cf-nav__item">
          <a href="<?php echo esc_url(home_url('/services/')); ?>" aria-haspopup="true">Услуги &#9662;</a>
          <div class="cf-nav__dropdown">
            <a href="<?php echo esc_url(home_url('/services/avtopodborshchik/')); ?>" class="cf-nav__dropdown-item">Автоподбор</a>
            <a href="<?php echo esc_url(home_url('/services/import-pod-klyuch/')); ?>" class="cf-nav__dropdown-item">Импорт под ключ</a>
            <a href="<?php echo esc_url(home_url('/services/kredit-lizing/')); ?>" class="cf-nav__dropdown-item">Кредит / Лизинг</a>
            <a href="<?php echo esc_url(home_url('/services/logistika/')); ?>" class="cf-nav__dropdown-item">Логистика</a>
            <a href="<?php echo esc_url(home_url('/services/trade-in/')); ?>" class="cf-nav__dropdown-item">Trade-in</a>
          </div>
        </div>

        <a href="<?php echo esc_url(home_url('/calculator/')); ?>" class="cf-nav__item">Калькулятор</a>
        <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="cf-nav__item">Блог</a>
        <a href="<?php echo esc_url(home_url('/o-kompanii/')); ?>" class="cf-nav__item">О нас</a>

      </nav>

      <!-- Phone + CTA -->
      <a href="<?php echo esc_url('tel:' . preg_replace('/\s/', '', $phone)); ?>" class="cf-header__phone">
        <?php echo esc_html($phone); ?>
      </a>
      <a href="#cf-lead-modal" class="cf-header__cta" data-modal="lead">Оставить заявку</a>

      <!-- Mobile burger -->
      <button class="cf-burger" id="cf-burger" aria-label="Открыть меню" aria-expanded="false">
        <span class="cf-burger__line"></span>
        <span class="cf-burger__line"></span>
        <span class="cf-burger__line"></span>
      </button>

    </div>
  </div>
</header>

<!-- Breadcrumbs -->
<?php cf_breadcrumbs(); ?>

<main id="cf-main" role="main">
