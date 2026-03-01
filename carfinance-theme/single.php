<?php
/**
 * Single Blog Post Template
 *
 * Blog articles with sidebar, Schema.org/Article,
 * related lot links, author card (E-E-A-T).
 *
 * @package CarFinance
 */

get_header();
?>

<section class="cf-section" style="padding-top:32px;">
  <div class="cf-container">
    <div class="cf-content-with-sidebar">

      <!-- Main content -->
      <article class="cf-article">
        <?php if (has_post_thumbnail()) : ?>
          <img src="<?php the_post_thumbnail_url('cf-hero'); ?>"
               alt="<?php the_title_attribute(); ?>"
               width="1200" height="600"
               style="border-radius:var(--cf-radius);width:100%;height:auto;margin-bottom:32px;">
        <?php endif; ?>

        <h1><?php the_title(); ?></h1>

        <div class="cf-card__meta cf-mt-1 cf-mb-3">
          <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
          <span>Автор: <?php the_author(); ?></span>
          <?php
          $cats = get_the_category();
          if ($cats) :
          ?>
            <span><?php echo esc_html($cats[0]->name); ?></span>
          <?php endif; ?>
        </div>

        <div class="cf-article__content" style="line-height:1.8;font-size:1.0625rem;">
          <?php the_content(); ?>
        </div>

        <!-- Author card (E-E-A-T) -->
        <div style="margin-top:48px;padding:32px;background:var(--cf-gray-100);border-radius:var(--cf-radius);display:flex;gap:24px;align-items:center;">
          <div>
            <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', ['class' => 'cf-team-member__photo', 'style' => 'margin:0;']); ?>
          </div>
          <div>
            <p style="font-weight:700;font-size:1.125rem;"><?php the_author(); ?></p>
            <p class="cf-card__text"><?php echo esc_html(get_the_author_meta('description')); ?></p>
          </div>
        </div>

        <!-- Related posts -->
        <div class="cf-mt-4">
          <h3 class="cf-mb-3">Читайте также</h3>
          <div class="cf-grid cf-grid--3">
            <?php
            $related = get_posts([
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post__not_in'   => [get_the_ID()],
                'category__in'   => wp_get_post_categories(get_the_ID()),
            ]);
            foreach ($related as $rp) :
            ?>
              <a href="<?php echo get_permalink($rp->ID); ?>" class="cf-card" style="text-decoration:none;">
                <?php if (has_post_thumbnail($rp->ID)) : ?>
                  <img class="cf-card__img" src="<?php echo get_the_post_thumbnail_url($rp->ID, 'cf-card'); ?>"
                       alt="<?php echo esc_attr($rp->post_title); ?>" loading="lazy" width="600" height="375">
                <?php endif; ?>
                <div class="cf-card__body">
                  <h4 class="cf-card__title"><?php echo esc_html($rp->post_title); ?></h4>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </article>

      <!-- Sidebar -->
      <aside class="cf-sidebar">
        <!-- Lead widget -->
        <div class="cf-sidebar__widget" style="background:var(--cf-primary);color:var(--cf-white);border-radius:var(--cf-radius);">
          <h4 style="color:var(--cf-white);border-color:rgba(255,255,255,0.2);">Бесплатная консультация</h4>
          <p style="font-size:0.875rem;opacity:0.9;margin-bottom:16px;">Оставьте заявку — мы свяжемся за 15 минут</p>
          <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--block" data-modal="lead">Оставить заявку</a>
        </div>

        <!-- Calculator widget -->
        <div class="cf-sidebar__widget">
          <h4>Калькулятор</h4>
          <p class="cf-card__text">Рассчитайте стоимость авто под ключ</p>
          <a href="<?php echo esc_url(home_url('/calculator/')); ?>" class="cf-btn cf-btn--outline cf-btn--sm cf-btn--block cf-mt-2">Открыть калькулятор</a>
        </div>

        <!-- Dynamic sidebar -->
        <?php if (is_active_sidebar('blog-sidebar')) : ?>
          <?php dynamic_sidebar('blog-sidebar'); ?>
        <?php endif; ?>
      </aside>

    </div>
  </div>
</section>

<?php get_footer(); ?>
