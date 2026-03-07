<?php
/**
 * AJAX Catalog Filter Backend
 *
 * Handles AJAX filtering, cascading dropdowns, and pagination
 * for the car_model catalog archive.
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register AJAX handlers for catalog filtering.
 */
function cf_catalog_filter_init() {
    add_action( 'wp_ajax_cf_catalog_filter', 'cf_ajax_catalog_filter' );
    add_action( 'wp_ajax_nopriv_cf_catalog_filter', 'cf_ajax_catalog_filter' );

    add_action( 'wp_ajax_cf_get_brands_by_country', 'cf_ajax_get_brands_by_country' );
    add_action( 'wp_ajax_nopriv_cf_get_brands_by_country', 'cf_ajax_get_brands_by_country' );

    add_action( 'wp_ajax_cf_get_models_by_brand', 'cf_ajax_get_models_by_brand' );
    add_action( 'wp_ajax_nopriv_cf_get_models_by_brand', 'cf_ajax_get_models_by_brand' );
}
add_action( 'init', 'cf_catalog_filter_init' );

/**
 * Main catalog filter AJAX handler.
 *
 * Accepts POST params for filtering car_model posts by taxonomy and meta,
 * returns rendered HTML cards, total found count, and max pages.
 */
function cf_ajax_catalog_filter() {
    check_ajax_referer( 'cf_catalog_nonce', 'nonce' );

    // Read and sanitize inputs.
    $country         = sanitize_text_field( wp_unslash( $_POST['country'] ?? '' ) );
    $brand           = sanitize_text_field( wp_unslash( $_POST['brand'] ?? '' ) );
    $body_type       = sanitize_text_field( wp_unslash( $_POST['body_type'] ?? '' ) );
    $year_from       = absint( $_POST['year_from'] ?? 0 );
    $year_to         = absint( $_POST['year_to'] ?? 0 );
    $price_from      = absint( $_POST['price_from'] ?? 0 );
    $price_to        = absint( $_POST['price_to'] ?? 0 );
    $fuel            = sanitize_text_field( wp_unslash( $_POST['fuel'] ?? '' ) );
    $transmission    = sanitize_text_field( wp_unslash( $_POST['transmission'] ?? '' ) );
    $drive           = sanitize_text_field( wp_unslash( $_POST['drive'] ?? '' ) );
    $mileage_from    = absint( $_POST['mileage_from'] ?? 0 );
    $mileage_to      = absint( $_POST['mileage_to'] ?? 0 );
    $engine_from     = (float) ( $_POST['engine_from'] ?? 0 );
    $engine_to       = (float) ( $_POST['engine_to'] ?? 0 );
    $power_from      = absint( $_POST['power_from'] ?? 0 );
    $power_to        = absint( $_POST['power_to'] ?? 0 );
    $steering        = sanitize_text_field( wp_unslash( $_POST['steering'] ?? '' ) );
    $accident_free   = absint( $_POST['accident_free'] ?? 0 );
    $condition       = sanitize_text_field( wp_unslash( $_POST['condition'] ?? '' ) ); // new/used/all
    $seats           = absint( $_POST['seats'] ?? 0 );
    $color           = sanitize_text_field( wp_unslash( $_POST['color'] ?? '' ) );
    $sort            = sanitize_text_field( wp_unslash( $_POST['sort'] ?? '' ) );
    $page            = absint( $_POST['page'] ?? 1 );
    $per_page        = absint( $_POST['per_page'] ?? 12 );

    if ( $page < 1 ) {
        $page = 1;
    }
    if ( $per_page < 1 || $per_page > 48 ) {
        $per_page = 12;
    }

    // Build base query args.
    $args = [
        'post_type'      => 'car_model',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    // --- Taxonomy queries ---
    $tax_query = [];

    if ( $country ) {
        $tax_query[] = [
            'taxonomy' => 'car_country',
            'field'    => 'slug',
            'terms'    => array_map( 'sanitize_text_field', (array) $country ),
            'operator' => is_array( $country ) ? 'IN' : '=',
        ];
    }

    if ( $brand ) {
        $tax_query[] = [
            'taxonomy' => 'car_brand',
            'field'    => 'slug',
            'terms'    => $brand,
        ];
    }

    if ( $body_type ) {
        $tax_query[] = [
            'taxonomy' => 'car_type',
            'field'    => 'slug',
            'terms'    => array_map( 'sanitize_text_field', (array) $body_type ),
            'operator' => is_array( $body_type ) ? 'IN' : '=',
        ];
    }

    if ( $fuel ) {
        $tax_query[] = [
            'taxonomy' => 'engine_type',
            'field'    => 'slug',
            'terms'    => array_map( 'sanitize_text_field', (array) $fuel ),
            'operator' => is_array( $fuel ) ? 'IN' : '=',
        ];
    }

    if ( $transmission ) {
        $tax_query[] = [
            'taxonomy' => 'transmission_type',
            'field'    => 'slug',
            'terms'    => array_map( 'sanitize_text_field', (array) $transmission ),
            'operator' => is_array( $transmission ) ? 'IN' : '=',
        ];
    }

    if ( $drive ) {
        $tax_query[] = [
            'taxonomy' => 'drive_type',
            'field'    => 'slug',
            'terms'    => array_map( 'sanitize_text_field', (array) $drive ),
            'operator' => is_array( $drive ) ? 'IN' : '=',
        ];
    }

    if ( $color ) {
        $tax_query[] = [
            'taxonomy' => 'car_color',
            'field'    => 'slug',
            'terms'    => $color,
        ];
    }

    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }

    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    // --- Meta queries ---
    $meta_query = [];

    if ( $year_from ) {
        $meta_query[] = [
            'key'     => 'cf_year',
            'value'   => $year_from,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $year_to ) {
        $meta_query[] = [
            'key'     => 'cf_year',
            'value'   => $year_to,
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $price_from ) {
        $meta_query[] = [
            'key'     => 'cf_price_from',
            'value'   => $price_from,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $price_to ) {
        $meta_query[] = [
            'key'     => 'cf_price_from',
            'value'   => $price_to,
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $mileage_from ) {
        $meta_query[] = [
            'key'     => 'cf_mileage',
            'value'   => $mileage_from,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $mileage_to ) {
        $meta_query[] = [
            'key'     => 'cf_mileage',
            'value'   => $mileage_to,
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $engine_from > 0 ) {
        $meta_query[] = [
            'key'     => 'cf_engine_cc',
            'value'   => (int) round( $engine_from * 1000 ),
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $engine_to > 0 ) {
        $meta_query[] = [
            'key'     => 'cf_engine_cc',
            'value'   => (int) round( $engine_to * 1000 ),
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $power_from ) {
        $meta_query[] = [
            'key'     => 'cf_power_hp',
            'value'   => $power_from,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $power_to ) {
        $meta_query[] = [
            'key'     => 'cf_power_hp',
            'value'   => $power_to,
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( $steering ) {
        $meta_query[] = [
            'key'     => 'cf_steering',
            'value'   => $steering,
            'compare' => '=',
        ];
    }

    if ( $accident_free ) {
        $meta_query[] = [
            'key'     => 'cf_accident_free',
            'value'   => '1',
            'compare' => '=',
        ];
    }

    if ( $condition && $condition !== 'all' ) {
        $meta_query[] = [
            'key'     => 'cf_condition',
            'value'   => $condition,
            'compare' => '=',
        ];
    }

    if ( $seats ) {
        $meta_query[] = [
            'key'     => 'cf_seats',
            'value'   => $seats,
            'compare' => '=',
            'type'    => 'NUMERIC',
        ];
    }

    if ( count( $meta_query ) > 1 ) {
        $meta_query['relation'] = 'AND';
    }

    if ( ! empty( $meta_query ) ) {
        $args['meta_query'] = $meta_query;
    }

    // --- Sorting ---
    switch ( $sort ) {
        case 'price_asc':
            $args['meta_key'] = 'cf_price_from';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;

        case 'price_desc':
            $args['meta_key'] = 'cf_price_from';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;

        case 'year_desc':
            $args['meta_key'] = 'cf_year';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;

        case 'mileage_asc':
            $args['meta_key'] = 'cf_mileage';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;

        case 'popular':
            $args['meta_key'] = 'cf_views_count';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;

        default:
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
    }

    // --- Run query ---
    $query = new WP_Query( $args );

    $html = '';

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            if ( function_exists( 'cf_block' ) ) {
                $html .= cf_block( 'car-card', [ 'post_id' => get_the_ID() ] );
            } else {
                // Fallback card rendering.
                $post_id    = get_the_ID();
                $title      = get_the_title();
                $permalink  = get_permalink();
                $thumbnail  = get_the_post_thumbnail( $post_id, 'medium', [ 'loading' => 'lazy' ] );
                $price      = get_post_meta( $post_id, 'cf_price_from', true );
                $year       = get_post_meta( $post_id, 'cf_year', true );

                $html .= '<div class="cf-card cf-card--car">';
                $html .= '<a href="' . esc_url( $permalink ) . '" class="cf-card__link">';
                if ( $thumbnail ) {
                    $html .= '<div class="cf-card__image">' . $thumbnail . '</div>';
                }
                $html .= '<div class="cf-card__body">';
                $html .= '<h3 class="cf-card__title">' . esc_html( $title ) . '</h3>';
                if ( $year ) {
                    $html .= '<span class="cf-card__year">' . esc_html( $year ) . ' г.</span>';
                }
                if ( $price ) {
                    $html .= '<span class="cf-card__price">от ' . number_format( (float) $price, 0, '', ' ' ) . ' ₽</span>';
                }
                $html .= '</div>';
                $html .= '</a>';
                $html .= '</div>';
            }
        }
        wp_reset_postdata();
    } else {
        $html = '<div class="cf-catalog__empty">';
        $html .= '<p>Автомобили не найдены</p>';
        $html .= '<p>Попробуйте изменить параметры фильтра или <a href="' . esc_url( get_post_type_archive_link( 'car_model' ) ) . '">сбросить все фильтры</a>.</p>';
        $html .= '</div>';
    }

    wp_send_json_success( [
        'html'      => $html,
        'found'     => $query->found_posts,
        'max_pages' => $query->max_num_pages,
    ] );
}

/**
 * Cascading dropdown: get brands that have posts in a given country.
 *
 * Returns JSON array of [{id, name, slug, count}].
 */
function cf_ajax_get_brands_by_country() {
    check_ajax_referer( 'cf_catalog_nonce', 'nonce' );

    $country_slug = sanitize_text_field( wp_unslash( $_POST['country'] ?? '' ) );

    if ( empty( $country_slug ) ) {
        wp_send_json_success( [] );
    }

    // Get all car_model posts in this country.
    $post_ids = get_posts( [
        'post_type'      => 'car_model',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => [
            [
                'taxonomy' => 'car_country',
                'field'    => 'slug',
                'terms'    => $country_slug,
            ],
        ],
    ] );

    if ( empty( $post_ids ) ) {
        wp_send_json_success( [] );
    }

    // Get brand terms from those posts.
    $brands = wp_get_object_terms( $post_ids, 'car_brand', [
        'orderby' => 'name',
        'order'   => 'ASC',
    ] );

    if ( is_wp_error( $brands ) ) {
        wp_send_json_success( [] );
    }

    // Deduplicate and format.
    $seen   = [];
    $result = [];

    foreach ( $brands as $brand ) {
        if ( isset( $seen[ $brand->term_id ] ) ) {
            continue;
        }
        $seen[ $brand->term_id ] = true;

        $result[] = [
            'id'    => $brand->term_id,
            'name'  => $brand->name,
            'slug'  => $brand->slug,
            'count' => $brand->count,
        ];
    }

    wp_send_json_success( $result );
}

/**
 * Cascading dropdown: get car_model posts for a given brand.
 *
 * Returns JSON array of [{id, title, slug}].
 */
function cf_ajax_get_models_by_brand() {
    check_ajax_referer( 'cf_catalog_nonce', 'nonce' );

    $brand_slug = sanitize_text_field( wp_unslash( $_POST['brand'] ?? '' ) );

    if ( empty( $brand_slug ) ) {
        wp_send_json_success( [] );
    }

    $posts = get_posts( [
        'post_type'      => 'car_model',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'tax_query'      => [
            [
                'taxonomy' => 'car_brand',
                'field'    => 'slug',
                'terms'    => $brand_slug,
            ],
        ],
    ] );

    $result = [];

    foreach ( $posts as $post ) {
        $result[] = [
            'id'    => $post->ID,
            'title' => $post->post_title,
            'slug'  => $post->post_name,
        ];
    }

    wp_send_json_success( $result );
}
