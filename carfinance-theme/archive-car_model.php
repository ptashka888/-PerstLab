<?php
/**
 * Car Model Archive — Catalog Page
 *
 * /catalog/ — faceted navigation with brands, body types, price ranges.
 * Static pages indexed, sort/filter params noindexed.
 *
 * @package CarFinance
 */

get_header();

// Determine if we're on a taxonomy archive
$current_brand    = get_queried_object();
$is_brand         = is_tax('cf_brand');
$is_body_type     = is_tax('cf_body_type');
$is_price_range   = is_tax('cf_price_range');
$archive_title    = 'Каталог автомобилей';

if ($is_brand) {
    $archive_title = 'Автомобили ' . $current_brand->name;
} elseif ($is_body_type) {
    $archive_title = $current_brand->name;
} elseif ($is_price_range) {
    $archive_title = $current_brand->name;
}
?>

<section class="cf-section" style="padding-top:32px;">
  <div class="cf-container">
    <h1><?php echo esc_html($archive_title); ?></h1>

    <!-- Filters -->
    <div class="cf-catalog__filters cf-mt-3">
      <!-- Country filter -->
      <select class="cf-catalog__filter" onchange="if(this.value)location.href=this.value">
        <option value="">Все страны</option>
        <?php
        $countries = get_terms(['taxonomy' => 'cf_country', 'hide_empty' => true]);
        foreach ($countries as $c) :
        ?>
          <option value="<?php echo esc_url(get_term_link($c)); ?>"
            <?php selected(is_tax('cf_country', $c->term_id)); ?>>
            <?php echo esc_html($c->name); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Brand filter -->
      <select class="cf-catalog__filter" onchange="if(this.value)location.href=this.value">
        <option value="">Все марки</option>
        <?php
        $brands = get_terms(['taxonomy' => 'cf_brand', 'hide_empty' => true]);
        foreach ($brands as $b) :
        ?>
          <option value="<?php echo esc_url(get_term_link($b)); ?>"
            <?php selected($is_brand && $current_brand->term_id === $b->term_id); ?>>
            <?php echo esc_html($b->name); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Body type filter -->
      <select class="cf-catalog__filter" onchange="if(this.value)location.href=this.value">
        <option value="">Все типы кузова</option>
        <?php
        $body_types = get_terms(['taxonomy' => 'cf_body_type', 'hide_empty' => true]);
        foreach ($body_types as $bt) :
        ?>
          <option value="<?php echo esc_url(get_term_link($bt)); ?>"
            <?php selected($is_body_type && $current_brand->term_id === $bt->term_id); ?>>
            <?php echo esc_html($bt->name); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- Price range filter -->
      <select class="cf-catalog__filter" onchange="if(this.value)location.href=this.value">
        <option value="">Любая цена</option>
        <?php
        $price_ranges = get_terms(['taxonomy' => 'cf_price_range', 'hide_empty' => true]);
        foreach ($price_ranges as $pr) :
        ?>
          <option value="<?php echo esc_url(get_term_link($pr)); ?>"
            <?php selected($is_price_range && $current_brand->term_id === $pr->term_id); ?>>
            <?php echo esc_html($pr->name); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <a href="<?php echo esc_url(get_post_type_archive_link('car_model')); ?>" class="cf-btn cf-btn--sm cf-btn--outline">Сбросить</a>
    </div>

    <!-- Results grid -->
    <div class="cf-grid cf-grid--4">
      <?php if (have_posts()) : while (have_posts()) : the_post();
          $price = get_post_meta(get_the_ID(), 'cf_price_from', true);
          $year  = get_post_meta(get_the_ID(), 'cf_year_from', true);
          $eng   = get_post_meta(get_the_ID(), 'cf_engine', true);
          $model_brand = '';
          $b = get_the_terms(get_the_ID(), 'cf_brand');
          if ($b && !is_wp_error($b)) $model_brand = $b[0]->name;
      ?>
        <a href="<?php the_permalink(); ?>" class="cf-card" style="text-decoration:none;">
          <?php if (has_post_thumbnail()) : ?>
            <img class="cf-card__img" src="<?php the_post_thumbnail_url('cf-card'); ?>"
                 alt="<?php the_title_attribute(); ?>" loading="lazy" width="600" height="375">
          <?php endif; ?>
          <div class="cf-card__body">
            <h3 class="cf-card__title"><?php the_title(); ?></h3>
            <p class="cf-card__text">
              <?php echo esc_html(implode(' / ', array_filter([$model_brand, $year ? 'с ' . $year : '', $eng]))); ?>
            </p>
            <?php if ($price) : ?>
              <div class="cf-card__price cf-mt-1"><?php echo cf_format_price((int) $price); ?></div>
            <?php endif; ?>
          </div>
        </a>
      <?php endwhile; else : ?>
        <p style="grid-column:1/-1;">Модели пока не добавлены. <a href="#cf-lead-modal" data-modal="lead">Оставьте заявку</a> — мы подберём авто под ваш запрос.</p>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="cf-mt-4 cf-text-center">
      <?php
      the_posts_pagination([
          'mid_size'  => 2,
          'prev_text' => '&larr; Назад',
          'next_text' => 'Вперёд &rarr;',
      ]);
      ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
