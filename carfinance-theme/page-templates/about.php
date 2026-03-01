<?php
/**
 * Template Name: О компании
 * Template Post Type: page
 *
 * Founder card, company stats, certificates,
 * reviews, video, contacts.
 * Schema.org/Person for founder.
 *
 * @package CarFinance
 */

get_header();
?>

<section class="cf-section" style="padding-top:32px;">
  <div class="cf-container">
    <h1>О компании CarFinance MSK</h1>

    <!-- Founder section -->
    <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:48px;margin-top:40px;align-items:center;">
      <div>
        <img src="<?php echo esc_url(CF_URI . '/assets/img/founder-placeholder.jpg'); ?>"
             alt="Основатель CarFinance" loading="lazy"
             style="border-radius:var(--cf-radius);width:100%;height:auto;">
      </div>
      <div>
        <h2>Иван Лещенко</h2>
        <p style="color:var(--cf-gray-500);font-size:1.125rem;">Основатель и руководитель</p>
        <div class="cf-mt-3" style="line-height:1.8;">
          <?php the_content(); ?>
        </div>
        <div class="cf-mt-3" style="display:flex;gap:12px;">
          <a href="https://t.me/carfinance_msk" class="cf-btn cf-btn--outline cf-btn--sm" target="_blank" rel="noopener">Telegram</a>
          <a href="https://www.instagram.com/carfinance_msk/" class="cf-btn cf-btn--outline cf-btn--sm" target="_blank" rel="noopener">Instagram</a>
          <a href="https://www.youtube.com/@carfinance_msk" class="cf-btn cf-btn--outline cf-btn--sm" target="_blank" rel="noopener">YouTube</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Company stats -->
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div class="cf-counters">
      <div class="cf-counter">
        <div class="cf-counter__number">1 200+</div>
        <div class="cf-counter__label">Автомобилей доставлено</div>
      </div>
      <div class="cf-counter">
        <div class="cf-counter__number">7 лет</div>
        <div class="cf-counter__label">На рынке</div>
      </div>
      <div class="cf-counter">
        <div class="cf-counter__number">5</div>
        <div class="cf-counter__label">Офисов по России</div>
      </div>
      <div class="cf-counter">
        <div class="cf-counter__number">98%</div>
        <div class="cf-counter__label">Довольных клиентов</div>
      </div>
    </div>
  </div>
</section>

<!-- Team -->
<section class="cf-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Наша команда</h2>
    </div>
    <div class="cf-team__grid">
      <?php
      $team = get_posts(['post_type' => 'cf_team', 'posts_per_page' => 8, 'orderby' => 'menu_order', 'order' => 'ASC']);
      if ($team) :
          foreach ($team as $member) :
              $role = get_post_meta($member->ID, 'cf_team_role', true);
      ?>
        <div class="cf-team-member">
          <?php if (has_post_thumbnail($member->ID)) : ?>
            <img class="cf-team-member__photo" src="<?php echo get_the_post_thumbnail_url($member->ID, 'cf-team'); ?>"
                 alt="<?php echo esc_attr($member->post_title); ?>" loading="lazy" width="120" height="120">
          <?php endif; ?>
          <div class="cf-team-member__name"><?php echo esc_html($member->post_title); ?></div>
          <?php if ($role) : ?><div class="cf-team-member__role"><?php echo esc_html($role); ?></div><?php endif; ?>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<!-- Reviews -->
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Отзывы клиентов</h2>
    </div>
    <div class="cf-grid cf-grid--3">
      <?php
      $reviews = get_posts(['post_type' => 'cf_review', 'posts_per_page' => 6]);
      foreach ($reviews as $review) :
          $author = get_post_meta($review->ID, 'cf_review_author', true);
          $model  = get_post_meta($review->ID, 'cf_review_model', true);
          $rating = get_post_meta($review->ID, 'cf_review_rating', true);
      ?>
        <div class="cf-card">
          <div class="cf-card__body">
            <?php if ($rating) : ?>
              <div style="color:var(--cf-warning);font-size:1.25rem;margin-bottom:8px;">
                <?php echo str_repeat('&#9733;', (int)$rating) . str_repeat('&#9734;', 5 - (int)$rating); ?>
              </div>
            <?php endif; ?>
            <p style="font-style:italic;margin-bottom:12px;">"<?php echo cf_excerpt($review->post_content, 30); ?>"</p>
            <p style="font-weight:700;"><?php echo esc_html($author ?: $review->post_title); ?></p>
            <?php if ($model) : ?><p class="cf-card__text"><?php echo esc_html($model); ?></p><?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Contacts -->
<section class="cf-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Контакты</h2>
    </div>
    <div class="cf-grid cf-grid--3">
      <div class="cf-card"><div class="cf-card__body">
        <h4 class="cf-card__title">Москва</h4>
        <p class="cf-card__text">Адрес: уточняйте<br>Тел: +7 (XXX) XXX-XX-XX</p>
      </div></div>
      <div class="cf-card"><div class="cf-card__body">
        <h4 class="cf-card__title">Владивосток</h4>
        <p class="cf-card__text">Адрес: уточняйте<br>Тел: +7 (XXX) XXX-XX-XX</p>
      </div></div>
      <div class="cf-card"><div class="cf-card__body">
        <h4 class="cf-card__title">Краснодар</h4>
        <p class="cf-card__text">Адрес: уточняйте<br>Тел: +7 (XXX) XXX-XX-XX</p>
      </div></div>
    </div>
  </div>
</section>

<section class="cf-cta">
  <div class="cf-container">
    <h2>Свяжитесь с нами</h2>
    <p>Бесплатная консультация и расчёт стоимости</p>
    <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Оставить заявку</a>
  </div>
</section>

<?php get_footer(); ?>
