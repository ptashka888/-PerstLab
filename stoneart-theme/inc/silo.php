<?php
/**
 * SILO Internal Linking System
 *
 * @package StoneArt
 */

defined('ABSPATH') || exit;

/**
 * Render SILO links block on front page
 */
function sa_render_silo_links() {
    $silo_pages = [
        'services'       => ['label' => 'Изделия из камня', 'icon' => 'fa-solid fa-gem'],
        'materials'      => ['label' => 'Каталог материалов', 'icon' => 'fa-solid fa-layer-group'],
        'portfolio-page' => ['label' => 'Портфолио работ', 'icon' => 'fa-solid fa-images'],
        'calculator'     => ['label' => 'Калькулятор стоимости', 'icon' => 'fa-solid fa-calculator'],
        'about'          => ['label' => 'О компании', 'icon' => 'fa-solid fa-building'],
        'faq-page'       => ['label' => 'Вопросы и ответы', 'icon' => 'fa-solid fa-circle-question'],
        'contacts'       => ['label' => 'Контакты', 'icon' => 'fa-solid fa-location-dot'],
        'blog'           => ['label' => 'Блог о камне', 'icon' => 'fa-solid fa-newspaper'],
    ];

    echo '<section class="sa-section sa-section--white sa-animate" style="border-top:1px solid var(--sa-gray-200);">';
    echo '<div class="sa-container">';
    echo '<h2 class="sa-section__title">Разделы сайта</h2>';
    echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-top:2rem;">';

    foreach ($silo_pages as $slug => $data) {
        $page = get_page_by_path($slug);
        if (!$page) continue;
        $url = get_permalink($page);

        echo '<a href="' . esc_url($url) . '" style="display:flex;align-items:center;gap:0.75rem;padding:1rem;background:var(--sa-gray-50);border-radius:var(--sa-radius);border:1px solid var(--sa-gray-200);transition:all 0.3s;text-decoration:none;">';
        echo '<i class="' . esc_attr($data['icon']) . '" style="font-size:1.25rem;color:var(--sa-primary);flex-shrink:0;"></i>';
        echo '<span style="font-weight:700;font-size:0.875rem;color:var(--sa-gray-800);">' . esc_html($data['label']) . '</span>';
        echo '</a>';
    }

    echo '</div>';
    echo '</div>';
    echo '</section>';
}

/**
 * Related posts (same category) for blog posts
 */
function sa_related_posts() {
    if (!is_singular('post')) return;

    $cats = get_the_category();
    if (!$cats) return;

    $related = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post__not_in'   => [get_the_ID()],
        'category__in'   => [$cats[0]->term_id],
        'orderby'        => 'rand',
    ]);

    if (!$related->have_posts()) return;

    echo '<div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--sa-gray-200);">';
    echo '<h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;" class="sa-font-serif">Читайте также</h3>';
    echo '<div class="sa-blog-grid">';

    while ($related->have_posts()) {
        $related->the_post();
        echo '<a href="' . esc_url(get_permalink()) . '" class="sa-blog-card" style="text-decoration:none;">';
        if (has_post_thumbnail()) {
            the_post_thumbnail('sa-card', ['class' => 'sa-blog-card__image', 'loading' => 'lazy']);
        }
        echo '<div class="sa-blog-card__body">';
        echo '<h4 class="sa-blog-card__title">' . esc_html(get_the_title()) . '</h4>';
        echo '<p class="sa-blog-card__excerpt">' . esc_html(get_the_excerpt()) . '</p>';
        echo '</div>';
        echo '</a>';
    }

    echo '</div>';
    echo '</div>';

    wp_reset_postdata();
}

/**
 * Add related services link to portfolio single
 */
function sa_portfolio_related_services() {
    if (!is_singular('sa_portfolio')) return;

    $terms = get_the_terms(get_the_ID(), 'sa_product_cat');
    if (!$terms) return;

    $services = new WP_Query([
        'post_type'      => 'sa_service',
        'posts_per_page' => 3,
        'tax_query'      => [
            ['taxonomy' => 'sa_product_cat', 'field' => 'term_id', 'terms' => $terms[0]->term_id],
        ],
    ]);

    if (!$services->have_posts()) return;

    echo '<div style="margin-top:3rem;">';
    echo '<h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;" class="sa-font-serif">Наши услуги</h3>';
    echo '<div class="sa-catalog__grid">';

    while ($services->have_posts()) {
        $services->the_post();
        echo '<div class="sa-card">';
        if (has_post_thumbnail()) {
            the_post_thumbnail('sa-card', ['class' => 'sa-card__image', 'loading' => 'lazy']);
        }
        echo '<h4 class="sa-card__title">' . esc_html(get_the_title()) . '</h4>';
        echo '<p class="sa-card__text">' . esc_html(get_the_excerpt()) . '</p>';
        echo '<a href="' . esc_url(get_permalink()) . '" class="sa-card__link">Подробнее →</a>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';

    wp_reset_postdata();
}

/**
 * Auto-add internal links to content (for SEO)
 */
function sa_auto_internal_links($content) {
    if (!is_singular('post')) return $content;

    $link_map = [
        'столешниц'    => 'services',
        'подоконник'   => 'services',
        'кварцевый агломерат' => 'materials',
        'натуральный камень'  => 'materials',
        'мрамор'       => 'materials',
        'гранит'       => 'materials',
        'акриловый камень'    => 'materials',
        'портфолио'    => 'portfolio-page',
        'калькулятор'  => 'calculator',
    ];

    $linked = [];
    foreach ($link_map as $keyword => $slug) {
        if (isset($linked[$slug])) continue;

        $page = get_page_by_path($slug);
        if (!$page) continue;

        $url = get_permalink($page);
        $pattern = '/(?<![<\/a-zA-Zа-яА-Я])(' . preg_quote($keyword, '/') . '(?:ы|ов|ам|ами|ах|а|у|е|ей|ой)?)/iu';

        $replacement = '<a href="' . esc_url($url) . '" title="' . esc_attr($page->post_title) . '">$1</a>';
        $content = preg_replace($pattern, $replacement, $content, 1);
        $linked[$slug] = true;
    }

    return $content;
}
add_filter('the_content', 'sa_auto_internal_links', 20);
