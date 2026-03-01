<?php
/**
 * Main Index Template (fallback)
 *
 * @package CarFinance
 */

get_header();
?>

<div class="cf-section">
  <div class="cf-container">

    <?php if (have_posts()) : ?>

      <div class="cf-grid cf-grid--3">
        <?php while (have_posts()) : the_post(); ?>
          <article class="cf-card">
            <?php if (has_post_thumbnail()) : ?>
              <img class="cf-card__img" src="<?php the_post_thumbnail_url('cf-card'); ?>"
                   alt="<?php the_title_attribute(); ?>" loading="lazy"
                   width="600" height="375">
            <?php endif; ?>
            <div class="cf-card__body">
              <h2 class="cf-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <p class="cf-card__text"><?php echo cf_excerpt(get_the_excerpt(), 20); ?></p>
              <div class="cf-card__meta">
                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
              </div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <div class="cf-mt-4 cf-text-center">
        <?php
        the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => '&larr; Назад',
            'next_text' => 'Вперёд &rarr;',
        ]);
        ?>
      </div>

    <?php else : ?>
      <p>Ничего не найдено.</p>
    <?php endif; ?>

  </div>
</div>

<?php get_footer(); ?>
