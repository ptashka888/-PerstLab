<?php
/**
 * 404 Page Template
 *
 * @package CarFinance
 */

get_header();
?>

<section class="cf-section" style="text-align:center;padding:80px 0;">
  <div class="cf-container">
    <h1 style="font-size:4rem;color:var(--cf-primary);">404</h1>
    <h2 class="cf-mt-2">Страница не найдена</h2>
    <p class="cf-mt-2" style="color:var(--cf-gray-500);max-width:500px;margin:16px auto 32px;">
      Страница была удалена или перемещена. Воспользуйтесь навигацией или вернитесь на главную.
    </p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="cf-btn cf-btn--primary">На главную</a>
    <a href="<?php echo esc_url(home_url('/catalog/')); ?>" class="cf-btn cf-btn--outline" style="margin-left:12px;">Каталог</a>
  </div>
</section>

<?php get_footer(); ?>
