<?php
/**
 * Template Name: FAQ
 * Template Post Type: page
 *
 * FAQ page with categories, search, Schema.org/FAQPage.
 *
 * @package CarFinance
 */

get_header();
?>

<section class="cf-section" style="padding-top:32px;">
  <div class="cf-container">
    <div class="cf-section__header">
      <h1>Часто задаваемые вопросы</h1>
      <p>Ответы на 20 главных вопросов об импорте и подборе автомобилей</p>
    </div>

    <!-- FAQ Categories -->
    <?php
    $faq_cats = get_terms(['taxonomy' => 'cf_faq_cat', 'hide_empty' => true]);

    if ($faq_cats && !is_wp_error($faq_cats)) :
        foreach ($faq_cats as $cat) :
            $faqs = get_posts([
                'post_type'      => 'cf_faq',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'tax_query'      => [['taxonomy' => 'cf_faq_cat', 'field' => 'term_id', 'terms' => $cat->term_id]],
            ]);

            if (!$faqs) continue;
    ?>
      <h2 class="cf-mt-4 cf-mb-3"><?php echo esc_html($cat->name); ?></h2>
      <div class="cf-faq__list">
        <?php foreach ($faqs as $faq) : ?>
          <div class="cf-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
            <button class="cf-faq__question" itemprop="name" aria-expanded="false">
              <?php echo esc_html($faq->post_title); ?>
            </button>
            <div class="cf-faq__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
              <div itemprop="text"><?php echo wp_kses_post($faq->post_content); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php
        endforeach;
    else :
        // Fallback: get all FAQs without categories
        $all_faqs = get_posts(['post_type' => 'cf_faq', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC']);
        if ($all_faqs) :
    ?>
      <div class="cf-faq__list">
        <?php foreach ($all_faqs as $faq) : ?>
          <div class="cf-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
            <button class="cf-faq__question" itemprop="name" aria-expanded="false">
              <?php echo esc_html($faq->post_title); ?>
            </button>
            <div class="cf-faq__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
              <div itemprop="text"><?php echo wp_kses_post($faq->post_content); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p>Вопросы скоро появятся.</p>
    <?php endif; endif; ?>

    <!-- Didn't find answer -->
    <div class="cf-mt-4 cf-text-center" style="padding:40px;background:var(--cf-gray-100);border-radius:var(--cf-radius);">
      <h3>Не нашли ответ?</h3>
      <p class="cf-mt-1">Задайте вопрос нашему специалисту — ответим в течение 15 минут</p>
      <a href="#cf-lead-modal" class="cf-btn cf-btn--primary cf-mt-2" data-modal="lead">Задать вопрос</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
