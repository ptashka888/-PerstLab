<?php
/**
 * SEO Tag Pages Support
 *
 * Customizes catalog_tag taxonomy archive queries,
 * provides intro text, related tags, FAQ, and SEO meta.
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Initialize tag page hooks.
 */
function cf_catalog_tags_init() {
    add_action( 'pre_get_posts', 'cf_tag_page_query' );
}
add_action( 'init', 'cf_catalog_tags_init' );

/**
 * Customize query for catalog_tag taxonomy archives.
 *
 * Sets posts_per_page to 12 and orders by menu_order then date.
 *
 * @param WP_Query $query The main query object.
 */
function cf_tag_page_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( ! is_tax( 'catalog_tag' ) ) {
        return;
    }

    $query->set( 'posts_per_page', 12 );
    $query->set( 'orderby', 'menu_order date' );
    $query->set( 'order', 'DESC' );
}

/**
 * Get intro text for a tag page.
 *
 * Tries ACF field 'cf_tag_intro' first, falls back to term description.
 *
 * @param int $term_id Term ID.
 * @return string Intro text (may contain HTML).
 */
function cf_get_tag_intro( $term_id ) {
    $term_id = absint( $term_id );

    // Try ACF field first.
    if ( function_exists( 'get_field' ) ) {
        $intro = get_field( 'cf_tag_intro', 'catalog_tag_' . $term_id );
        if ( ! empty( $intro ) ) {
            return wp_kses_post( $intro );
        }
    }

    // Fall back to term description.
    $term = get_term( $term_id, 'catalog_tag' );

    if ( ! is_wp_error( $term ) && ! empty( $term->description ) ) {
        return wp_kses_post( $term->description );
    }

    return '';
}

/**
 * Find related catalog_tag terms.
 *
 * Strategy: get posts with the current tag, then collect other tags
 * from those posts, sorted by frequency (most common first).
 *
 * @param int $term_id Current term ID.
 * @param int $limit   Maximum number of related tags to return.
 * @return array Array of WP_Term objects.
 */
function cf_get_related_tags( $term_id, $limit = 5 ) {
    $term_id = absint( $term_id );
    $limit   = absint( $limit );

    // Get posts tagged with the current term.
    $post_ids = get_posts( [
        'post_type'      => 'car_model',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'fields'         => 'ids',
        'tax_query'      => [
            [
                'taxonomy' => 'catalog_tag',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ],
        ],
    ] );

    if ( empty( $post_ids ) ) {
        return [];
    }

    // Collect all catalog_tag terms from these posts.
    $all_terms = wp_get_object_terms( $post_ids, 'catalog_tag' );

    if ( is_wp_error( $all_terms ) || empty( $all_terms ) ) {
        return [];
    }

    // Count frequency, excluding the current term.
    $frequency = [];
    $term_map  = [];

    foreach ( $all_terms as $term ) {
        if ( (int) $term->term_id === $term_id ) {
            continue;
        }

        $tid = $term->term_id;

        if ( ! isset( $frequency[ $tid ] ) ) {
            $frequency[ $tid ] = 0;
            $term_map[ $tid ]  = $term;
        }

        $frequency[ $tid ]++;
    }

    // Sort by frequency descending.
    arsort( $frequency );

    // Return top $limit terms.
    $related = [];
    $count   = 0;

    foreach ( $frequency as $tid => $freq ) {
        if ( $count >= $limit ) {
            break;
        }
        $related[] = $term_map[ $tid ];
        $count++;
    }

    return $related;
}

/**
 * Get FAQ items for a tag page.
 *
 * Tries ACF repeater field 'cf_tag_faq' on the term first,
 * then falls back to cf_faq CPT posts matching the term name.
 *
 * @param int $term_id Term ID.
 * @return array Array of ['question' => '...', 'answer' => '...'].
 */
function cf_get_tag_faq( $term_id ) {
    $term_id = absint( $term_id );
    $faqs    = [];

    // Try ACF repeater field on the term.
    if ( function_exists( 'get_field' ) ) {
        $acf_faq = get_field( 'cf_tag_faq', 'catalog_tag_' . $term_id );

        if ( ! empty( $acf_faq ) && is_array( $acf_faq ) ) {
            foreach ( $acf_faq as $item ) {
                if ( ! empty( $item['question'] ) && ! empty( $item['answer'] ) ) {
                    $faqs[] = [
                        'question' => wp_kses_post( $item['question'] ),
                        'answer'   => wp_kses_post( $item['answer'] ),
                    ];
                }
            }
            return $faqs;
        }
    }

    // Fallback: get cf_faq posts tagged with the same term name.
    $term = get_term( $term_id, 'catalog_tag' );

    if ( is_wp_error( $term ) ) {
        return [];
    }

    $faq_posts = get_posts( [
        'post_type'      => 'cf_faq',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        's'              => $term->name,
    ] );

    if ( empty( $faq_posts ) ) {
        // Also try cf_faq_cat taxonomy with term name as slug.
        $faq_posts = get_posts( [
            'post_type'      => 'cf_faq',
            'post_status'    => 'publish',
            'posts_per_page' => 10,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'cf_faq_cat',
                    'field'    => 'slug',
                    'terms'    => $term->slug,
                ],
            ],
        ] );
    }

    foreach ( $faq_posts as $faq_post ) {
        $faqs[] = [
            'question' => get_the_title( $faq_post->ID ),
            'answer'   => apply_filters( 'the_content', $faq_post->post_content ),
        ];
    }

    return $faqs;
}

/**
 * Generate SEO meta for a tag page.
 *
 * @param WP_Term $term The term object.
 * @return array ['title' => '...', 'description' => '...']
 */
function cf_tag_page_meta( $term ) {
    $name = $term->name;

    $title = sprintf(
        'Купить %s из-за рубежа — каталог CarFinance MSK',
        esc_html( $name )
    );

    $description = sprintf(
        'Каталог автомобилей %s с доставкой из Кореи, Японии, Китая. Цены, характеристики, калькулятор растаможки.',
        esc_html( $name )
    );

    return [
        'title'       => $title,
        'description' => $description,
    ];
}
