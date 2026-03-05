<?php
/**
 * Theme Helpers
 *
 * cf_block(), cf_format_price(), cf_excerpt(), cf_get_country_data(),
 * cf_get_faq_items(), multisite helpers.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

/**
 * Render a block from blocks/{name}/block.php with auto CSS enqueue.
 */
function cf_block(string $name, array $args = []): void {
    $file = get_template_directory() . '/blocks/' . $name . '/block.php';
    if (!file_exists($file)) {
        return;
    }

    // Auto-enqueue block CSS
    $css_file = get_template_directory() . '/blocks/' . $name . '/style.css';
    if (file_exists($css_file)) {
        wp_enqueue_style(
            'cf-block-' . $name,
            get_template_directory_uri() . '/blocks/' . $name . '/style.css',
            [],
            filemtime($css_file)
        );
    }

    extract($args, EXTR_SKIP);
    include $file;
}

/**
 * Get posts from the main site in a Multisite network.
 */
function cf_get_main_site_posts(string $post_type, array $args = []): array {
    if (!is_multisite()) {
        return get_posts(array_merge(['post_type' => $post_type, 'numberposts' => -1], $args));
    }

    $main_blog_id = get_main_site_id();
    switch_to_blog($main_blog_id);
    $posts = get_posts(array_merge(['post_type' => $post_type, 'numberposts' => -1], $args));
    restore_current_blog();
    return $posts;
}

/**
 * Get an ACF field from the main site.
 */
function cf_get_main_site_field(string $field, $post_id = false): mixed {
    if (!is_multisite() || !function_exists('get_field')) {
        return function_exists('get_field') ? get_field($field, $post_id) : get_post_meta($post_id ?: get_the_ID(), $field, true);
    }

    switch_to_blog(get_main_site_id());
    $value = get_field($field, $post_id);
    restore_current_blog();
    return $value;
}

/**
 * Format price in Russian locale with ruble sign.
 */
function cf_format_price(int $price): string {
    return number_format($price, 0, ',', ' ') . ' &#8381;';
}

/**
 * Truncate text to a given number of words.
 */
function cf_excerpt(string $text, int $words = 25): string {
    return wp_trim_words($text, $words, '...');
}

/**
 * Get country data by code.
 */
function cf_get_country_data(string $code = ''): array {
    $countries = [
        'korea' => [
            'name'      => 'Корея',
            'name_from' => 'из Кореи',
            'flag'      => "\u{1F1F0}\u{1F1F7}",
            'url'       => '/avto-iz-korei/',
            'color'     => '#003478',
            'hero_css'  => 'hero--korea',
            'slug'      => 'korea',
        ],
        'japan' => [
            'name'      => 'Япония',
            'name_from' => 'из Японии',
            'flag'      => "\u{1F1EF}\u{1F1F5}",
            'url'       => '/avto-iz-yaponii/',
            'color'     => '#bc002d',
            'hero_css'  => 'hero--japan',
            'slug'      => 'japan',
        ],
        'china' => [
            'name'      => 'Китай',
            'name_from' => 'из Китая',
            'flag'      => "\u{1F1E8}\u{1F1F3}",
            'url'       => '/avto-iz-kitaya/',
            'color'     => '#de2910',
            'hero_css'  => 'hero--china',
            'slug'      => 'china',
        ],
        'usa' => [
            'name'      => 'США',
            'name_from' => 'из США',
            'flag'      => "\u{1F1FA}\u{1F1F8}",
            'url'       => '/avto-iz-usa/',
            'color'     => '#3c3b6e',
            'hero_css'  => 'hero--usa',
            'slug'      => 'usa',
        ],
        'uae' => [
            'name'      => 'ОАЭ',
            'name_from' => 'из ОАЭ',
            'flag'      => "\u{1F1E6}\u{1F1EA}",
            'url'       => '/avto-iz-oae/',
            'color'     => '#00732f',
            'hero_css'  => 'hero--uae',
            'slug'      => 'uae',
        ],
    ];

    if ($code) {
        return $countries[$code] ?? [];
    }

    return $countries;
}

/**
 * Get FAQ items for the current page context.
 *
 * @param string $source Context: 'home', 'country', 'catalog', 'model', 'brand', 'tag', 'service', 'city', 'blog'
 */
function cf_get_faq_items(string $source = 'home'): array {
    $faqs = [];

    // Try ACF relationship field first
    if (function_exists('get_field')) {
        $faq_relation = null;
        switch ($source) {
            case 'country':
                $faq_relation = get_field('country_faq');
                break;
            case 'model':
                $faq_relation = get_field('model_faq');
                break;
            case 'tag':
                $term = get_queried_object();
                if ($term instanceof WP_Term) {
                    $faq_relation = get_field('tag_faq', $term);
                }
                break;
            case 'service':
                $faq_relation = get_field('service_faq');
                break;
            case 'blog':
                $faq_relation = get_field('blog_faq');
                break;
        }

        if (is_array($faq_relation)) {
            foreach ($faq_relation as $faq_post) {
                if ($faq_post instanceof WP_Post) {
                    $faqs[] = [
                        'question' => $faq_post->post_title,
                        'answer'   => wp_strip_all_tags($faq_post->post_content),
                    ];
                }
            }
            return $faqs;
        }
    }

    // Fallback: get from faq_item CPT by category
    $faq_args = [
        'post_type'      => 'faq_item',
        'posts_per_page' => 10,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ];

    // Filter by FAQ category if source is known
    $cat_map = [
        'home'    => 'general',
        'country' => 'import',
        'catalog' => 'catalog',
        'model'   => 'catalog',
        'service' => 'services',
        'city'    => 'general',
    ];

    if (isset($cat_map[$source])) {
        $faq_args['tax_query'] = [[
            'taxonomy' => 'cf_faq_cat',
            'field'    => 'slug',
            'terms'    => $cat_map[$source],
        ]];
    }

    $faq_posts = get_posts($faq_args);
    foreach ($faq_posts as $fp) {
        $faqs[] = [
            'question' => $fp->post_title,
            'answer'   => wp_strip_all_tags($fp->post_content),
        ];
    }

    return $faqs;
}

/**
 * Get related models in same price range (SILO cross-link, same brand only).
 */
function cf_get_related_models(int $post_id, int $limit = 4): array {
    $price = 0;
    if (function_exists('get_field')) {
        $price = (int) get_field('model_price_turnkey', $post_id);
    }
    if (!$price) {
        $price = (int) get_post_meta($post_id, 'model_price_turnkey', true);
    }
    if (!$price) {
        return [];
    }

    $brands = get_the_terms($post_id, 'car_brand');
    $args = [
        'post_type'      => 'car_model',
        'posts_per_page' => $limit,
        'post__not_in'   => [$post_id],
        'meta_query'     => [
            [
                'key'     => 'model_price_turnkey',
                'value'   => [(int) ($price * 0.7), (int) ($price * 1.3)],
                'type'    => 'NUMERIC',
                'compare' => 'BETWEEN',
            ],
        ],
    ];

    // SILO rule: only same brand!
    if ($brands && !is_wp_error($brands)) {
        $args['tax_query'] = [[
            'taxonomy' => 'car_brand',
            'field'    => 'term_id',
            'terms'    => $brands[0]->term_id,
        ]];
    }

    return get_posts($args);
}

/**
 * Get active auction lots for a model.
 */
function cf_get_model_lots(int $model_id, int $limit = 6): array {
    $brands = get_the_terms($model_id, 'car_brand');
    if (!$brands || is_wp_error($brands)) {
        return [];
    }

    return get_posts([
        'post_type'      => 'auction_lot',
        'posts_per_page' => $limit,
        'meta_query'     => [[
            'key'   => 'lot_status',
            'value' => 'active',
        ]],
        'tax_query' => [[
            'taxonomy' => 'car_brand',
            'field'    => 'slug',
            'terms'    => $brands[0]->slug,
        ]],
    ]);
}

/**
 * Get ACF field with fallback to post meta.
 */
function cf_get_field(string $field, $post_id = false): mixed {
    if (function_exists('get_field')) {
        return get_field($field, $post_id);
    }
    return get_post_meta($post_id ?: get_the_ID(), $field, true);
}

/**
 * Generate Schema.org FAQPage JSON-LD from FAQ items array.
 */
function cf_schema_faqpage(array $faq_items): string {
    if (empty($faq_items)) {
        return '';
    }

    $entities = [];
    foreach ($faq_items as $item) {
        $entities[] = [
            '@type'          => 'Question',
            'name'           => $item['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $item['answer'],
            ],
        ];
    }

    $schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    ];

    return wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
