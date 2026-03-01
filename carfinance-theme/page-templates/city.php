<?php
/**
 * Template Name: Город
 * Template Post Type: page
 *
 * City landing page for subdomains / local SEO.
 * Schema.org/LocalBusiness.
 *
 * @package CarFinance
 */

get_header();

$city_name    = get_post_meta(get_the_ID(), 'cf_city_name', true) ?: 'Москва';
$city_phone   = get_post_meta(get_the_ID(), 'cf_city_phone', true) ?: '+7 (XXX) XXX-XX-XX';
$city_address = get_post_meta(get_the_ID(), 'cf_city_address', true) ?: '';
?>

<section class="cf-country-hero cf-country-hero--korea">
  <div class="cf-container">
    <h1>Авто из Кореи, Японии, Китая под ключ в <?php echo esc_html($city_name); ?></h1>
    <p style="font-size:1.125rem;opacity:0.9;margin-top:12px;">Импорт и подбор автомобилей с доставкой до <?php echo esc_html($city_name); ?></p>
    <div class="cf-mt-4">
      <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Оставить заявку</a>
    </div>
  </div>
</section>

<!-- Local office info -->
<?php if ($city_address) : ?>
<section class="cf-section">
  <div class="cf-container">
    <div class="cf-grid cf-grid--2">
      <div>
        <h2>Наш офис в <?php echo esc_html($city_name); ?></h2>
        <p class="cf-mt-2"><strong>Адрес:</strong> <?php echo esc_html($city_address); ?></p>
        <p><strong>Телефон:</strong> <a href="tel:<?php echo esc_attr($city_phone); ?>"><?php echo esc_html($city_phone); ?></a></p>
      </div>
      <div>
        <!-- Google Maps placeholder -->
        <div style="background:var(--cf-gray-100);border-radius:var(--cf-radius);height:300px;display:flex;align-items:center;justify-content:center;color:var(--cf-gray-500);">
          Google Maps Embed
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Local cases -->
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Кейсы клиентов из <?php echo esc_html($city_name); ?></h2>
    </div>
    <div class="cf-grid cf-grid--3">
      <?php
      $cases = get_posts([
          'post_type'      => 'case_study',
          'posts_per_page' => 3,
          'tax_query'      => [['taxonomy' => 'cf_city', 'field' => 'name', 'terms' => $city_name]],
      ]);
      if ($cases) :
          foreach ($cases as $case) :
              $savings = get_post_meta($case->ID, 'cf_case_savings', true);
      ?>
        <div class="cf-card">
          <?php if (has_post_thumbnail($case->ID)) : ?>
            <img class="cf-card__img" src="<?php echo get_the_post_thumbnail_url($case->ID, 'cf-card'); ?>"
                 alt="<?php echo esc_attr($case->post_title); ?>" loading="lazy" width="600" height="375">
          <?php endif; ?>
          <div class="cf-card__body">
            <h3 class="cf-card__title"><?php echo esc_html($case->post_title); ?></h3>
            <p class="cf-card__text"><?php echo cf_excerpt($case->post_content, 20); ?></p>
            <?php if ($savings) : ?>
              <div class="cf-card__price cf-mt-1">Экономия: <?php echo cf_format_price((int)$savings); ?></div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; else : ?>
        <p style="grid-column:1/-1;" class="cf-text-center">Кейсы из <?php echo esc_html($city_name); ?> скоро появятся.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Local reviews -->
<section class="cf-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Отзывы из <?php echo esc_html($city_name); ?></h2>
    </div>
    <div class="cf-grid cf-grid--3">
      <?php
      $reviews = get_posts([
          'post_type'      => 'cf_review',
          'posts_per_page' => 3,
          'tax_query'      => [['taxonomy' => 'cf_city', 'field' => 'name', 'terms' => $city_name]],
      ]);
      foreach ($reviews as $r) :
          $author = get_post_meta($r->ID, 'cf_review_author', true);
      ?>
        <div class="cf-card"><div class="cf-card__body">
          <p style="font-style:italic;">"<?php echo cf_excerpt($r->post_content, 25); ?>"</p>
          <p class="cf-mt-2" style="font-weight:700;"><?php echo esc_html($author ?: $r->post_title); ?></p>
        </div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cf-cta">
  <div class="cf-container">
    <h2>Подберём авто в <?php echo esc_html($city_name); ?></h2>
    <p>Бесплатная консультация и расчёт стоимости с доставкой</p>
    <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Оставить заявку</a>
  </div>
</section>

<?php get_footer(); ?>
