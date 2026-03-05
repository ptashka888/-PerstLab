<?php
/**
 * Fallback template.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="cf-section">
  <div class="cf-container">
    <?php if (have_posts()) : ?>
      <div class="cf-grid cf-grid--3">
        <?php while (have_posts()) : the_post(); ?>
          <article class="cf-card">
            <?php if (has_post_thumbnail()) : ?>
              <img class="cf-card__img"
                   src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'cf-card')); ?>"
                   alt="<?php the_title_attribute(); ?>"
                   loading="lazy" width="600" height="375">
            <?php endif; ?>
            <div class="cf-card__body">
              <h2 class="cf-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <p class="cf-card__text"><?php echo esc_html(cf_excerpt(get_the_excerpt(), 20)); ?></p>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
      <?php the_posts_pagination(['mid_size' => 2]); ?>
    <?php else : ?>
      <p class="cf-text-center"><?php esc_html_e('Ничего не найдено.', 'carfinance'); ?></p>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>
