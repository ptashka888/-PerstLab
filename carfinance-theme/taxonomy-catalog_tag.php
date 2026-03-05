<?php
/**
 * Template: Catalog Tag Archive (SEO Tag Pages)
 * URL: /catalog/tags/{tag-slug}/
 * CRITICAL: These pages are index, follow for SEO
 */

defined('ABSPATH') || exit;

get_header();

$term    = get_queried_object();
$term_id = $term->term_id ?? 0;

// Tag page data
$intro = '';
$seo_title = '';
$seo_desc = '';
if (function_exists('get_field')) {
    $intro     = get_field('cf_tag_intro', $term) ?: '';
    $seo_title = get_field('cf_tag_seo_title', $term) ?: '';
    $seo_desc  = get_field('cf_tag_seo_desc', $term) ?: '';
}
if (!$intro) {
    $intro = term_description($term_id);
}

// Related tags
$related_tags = function_exists('cf_get_related_tags') ? cf_get_related_tags($term_id, 5) : [];
?>

<section class="cf-section">
    <div class="cf-container">
        <h1 class="cf-section__title"><?php echo esc_html($seo_title ?: 'Купить ' . $term->name . ' из-за рубежа'); ?></h1>

        <?php if ($intro): ?>
            <div class="cf-content cf-content--intro">
                <?php echo wp_kses_post($intro); ?>
            </div>
        <?php endif; ?>

        <!-- Related Tags Navigation -->
        <?php if ($related_tags): ?>
            <div class="cf-tag-nav">
                <span class="cf-tag-nav__label">Похожие теги:</span>
                <?php foreach ($related_tags as $tag): ?>
                    <a href="<?php echo esc_url(get_term_link($tag)); ?>" class="cf-tag-nav__item">
                        <?php echo esc_html($tag->name); ?>
                        <span class="cf-tag-nav__count">(<?php echo esc_html($tag->count); ?>)</span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Car Grid -->
<div class="cf-catalog cf-catalog--tag">
    <div class="cf-container">
        <div class="cf-catalog__toolbar">
            <div class="cf-catalog__count">
                Найдено: <span><?php echo esc_html($wp_query->found_posts); ?></span> авто
            </div>
            <div class="cf-catalog__sort">
                <label for="cf-sort">Сортировка:</label>
                <select id="cf-sort" class="cf-form__select">
                    <option value="popular">По популярности</option>
                    <option value="price_asc">Цена ↑</option>
                    <option value="price_desc">Цена ↓</option>
                    <option value="year_desc">Сначала новые</option>
                </select>
            </div>
        </div>

        <div class="cf-catalog__grid cf-grid cf-grid--3">
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <?php cf_block('car-card', ['post_id' => get_the_ID()]); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="cf-catalog__empty">
                    <p>Автомобили с тегом «<?php echo esc_html($term->name); ?>» пока не добавлены.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($wp_query->max_num_pages > 1): ?>
            <div class="cf-catalog__pagination">
                <?php
                echo paginate_links([
                    'total'     => $wp_query->max_num_pages,
                    'current'   => max(1, get_query_var('paged')),
                    'prev_text' => '← Назад',
                    'next_text' => 'Далее →',
                    'type'      => 'list',
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Tag FAQ
$tag_faq = function_exists('cf_get_tag_faq') ? cf_get_tag_faq($term_id) : [];
if ($tag_faq) {
    cf_block('faq', ['items' => $tag_faq]);
}

// Interlinking
cf_block('interlinking', ['position' => 'footer']);

// CTA
cf_block('cta-final', ['variant' => 'default']);

get_footer();
