<?php
/**
 * Default Page Template
 *
 * Used for SILO pillar pages and generic pages
 * that don't have a specific template.
 *
 * @package CarFinance
 */

get_header();
?>

<section class="cf-section" style="padding-top:32px;">
  <div class="cf-container">
    <h1><?php the_title(); ?></h1>

    <div class="cf-mt-3" style="max-width:800px;line-height:1.8;">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cf-cta">
  <div class="cf-container">
    <h2>Остались вопросы?</h2>
    <p>Оставьте заявку — мы свяжемся с вами в течение 15 минут</p>
    <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Оставить заявку</a>
  </div>
</section>

<?php get_footer(); ?>
