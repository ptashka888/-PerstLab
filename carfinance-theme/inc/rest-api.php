<?php
/**
 * REST API Endpoint: /wp-json/carfinance/v1/cars
 *
 * Provides a public REST endpoint for catalog filtering,
 * cascading dropdowns, and filter aggregation.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

add_action('rest_api_init', 'cf_register_rest_routes');

/**
 * Register all CarFinance REST routes.
 */
function cf_register_rest_routes(): void {
    $ns = 'carfinance/v1';

    // Main cars endpoint with filtering
    register_rest_route($ns, '/cars', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'cf_rest_get_cars',
        'permission_callback' => '__return_true',
        'args'                => cf_rest_cars_args(),
    ]);

    // Filter aggregates (for dynamic range/count updates)
    register_rest_route($ns, '/cars/aggregates', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'cf_rest_get_aggregates',
        'permission_callback' => '__return_true',
    ]);

    // Cascading: brands by country
    register_rest_route($ns, '/brands', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'cf_rest_get_brands',
        'permission_callback' => '__return_true',
        'args'                => [
            'country' => ['sanitize_callback' => 'sanitize_text_field'],
        ],
    ]);

    // Cascading: models by brand
    register_rest_route($ns, '/models', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'cf_rest_get_models',
        'permission_callback' => '__return_true',
        'args'                => [
            'brand' => ['sanitize_callback' => 'sanitize_text_field'],
        ],
    ]);
}

/**
 * Arg schema for /cars endpoint.
 */
function cf_rest_cars_args(): array {
    return [
        'page'          => ['default' => 1,  'sanitize_callback' => 'absint'],
        'per_page'      => ['default' => 12, 'sanitize_callback' => 'absint'],
        'country'       => ['sanitize_callback' => 'sanitize_text_field'],
        'brand'         => ['sanitize_callback' => 'sanitize_text_field'],
        'model'         => ['sanitize_callback' => 'sanitize_text_field'],
        'body_type'     => ['sanitize_callback' => 'sanitize_text_field'],
        'fuel'          => ['sanitize_callback' => 'sanitize_text_field'],
        'transmission'  => ['sanitize_callback' => 'sanitize_text_field'],
        'drive'         => ['sanitize_callback' => 'sanitize_text_field'],
        'color'         => ['sanitize_callback' => 'sanitize_text_field'],
        'year_from'     => ['sanitize_callback' => 'absint'],
        'year_to'       => ['sanitize_callback' => 'absint'],
        'price_from'    => ['sanitize_callback' => 'absint'],
        'price_to'      => ['sanitize_callback' => 'absint'],
        'mileage_from'  => ['sanitize_callback' => 'absint'],
        'mileage_to'    => ['sanitize_callback' => 'absint'],
        'power_from'    => ['sanitize_callback' => 'absint'],
        'power_to'      => ['sanitize_callback' => 'absint'],
        'steering'      => ['sanitize_callback' => 'sanitize_text_field'],
        'accident_free' => ['sanitize_callback' => 'absint'],
        'condition'     => ['sanitize_callback' => 'sanitize_text_field'],
        'seats'         => ['sanitize_callback' => 'absint'],
        'sort'          => ['sanitize_callback' => 'sanitize_text_field'],
    ];
}

/**
 * GET /wp-json/carfinance/v1/cars
 *
 * Returns paginated list of car_model posts matching filters.
 */
function cf_rest_get_cars(WP_REST_Request $request): WP_REST_Response {
    $page     = max(1, $request->get_param('page'));
    $per_page = min(48, max(1, $request->get_param('per_page')));

    $args = [
        'post_type'      => 'car_model',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    // --- Taxonomy filters ---
    $tax_query = [];

    $country = $request->get_param('country');
    if ($country) {
        $tax_query[] = ['taxonomy' => 'car_country', 'field' => 'slug', 'terms' => explode(',', $country), 'operator' => 'IN'];
    }

    $brand = $request->get_param('brand');
    if ($brand) {
        $tax_query[] = ['taxonomy' => 'car_brand', 'field' => 'slug', 'terms' => $brand];
    }

    $body_type = $request->get_param('body_type');
    if ($body_type) {
        $tax_query[] = ['taxonomy' => 'car_type', 'field' => 'slug', 'terms' => explode(',', $body_type), 'operator' => 'IN'];
    }

    $fuel = $request->get_param('fuel');
    if ($fuel) {
        $tax_query[] = ['taxonomy' => 'engine_type', 'field' => 'slug', 'terms' => explode(',', $fuel), 'operator' => 'IN'];
    }

    $transmission = $request->get_param('transmission');
    if ($transmission) {
        $tax_query[] = ['taxonomy' => 'transmission_type', 'field' => 'slug', 'terms' => explode(',', $transmission), 'operator' => 'IN'];
    }

    $drive = $request->get_param('drive');
    if ($drive) {
        $tax_query[] = ['taxonomy' => 'drive_type', 'field' => 'slug', 'terms' => explode(',', $drive), 'operator' => 'IN'];
    }

    $color = $request->get_param('color');
    if ($color) {
        $tax_query[] = ['taxonomy' => 'car_color', 'field' => 'slug', 'terms' => $color];
    }

    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    // --- Meta filters ---
    $meta_query = [];

    $year_from = $request->get_param('year_from');
    if ($year_from) {
        $meta_query[] = ['key' => 'cf_year', 'value' => $year_from, 'compare' => '>=', 'type' => 'NUMERIC'];
    }

    $year_to = $request->get_param('year_to');
    if ($year_to) {
        $meta_query[] = ['key' => 'cf_year', 'value' => $year_to, 'compare' => '<=', 'type' => 'NUMERIC'];
    }

    $price_from = $request->get_param('price_from');
    if ($price_from) {
        $meta_query[] = ['key' => 'cf_price_from', 'value' => $price_from, 'compare' => '>=', 'type' => 'NUMERIC'];
    }

    $price_to = $request->get_param('price_to');
    if ($price_to) {
        $meta_query[] = ['key' => 'cf_price_from', 'value' => $price_to, 'compare' => '<=', 'type' => 'NUMERIC'];
    }

    $mileage_from = $request->get_param('mileage_from');
    if ($mileage_from) {
        $meta_query[] = ['key' => 'cf_mileage', 'value' => $mileage_from, 'compare' => '>=', 'type' => 'NUMERIC'];
    }

    $mileage_to = $request->get_param('mileage_to');
    if ($mileage_to) {
        $meta_query[] = ['key' => 'cf_mileage', 'value' => $mileage_to, 'compare' => '<=', 'type' => 'NUMERIC'];
    }

    $power_from = $request->get_param('power_from');
    if ($power_from) {
        $meta_query[] = ['key' => 'cf_power_hp', 'value' => $power_from, 'compare' => '>=', 'type' => 'NUMERIC'];
    }

    $power_to = $request->get_param('power_to');
    if ($power_to) {
        $meta_query[] = ['key' => 'cf_power_hp', 'value' => $power_to, 'compare' => '<=', 'type' => 'NUMERIC'];
    }

    $steering = $request->get_param('steering');
    if ($steering) {
        $meta_query[] = ['key' => 'cf_steering', 'value' => $steering, 'compare' => '='];
    }

    $accident_free = $request->get_param('accident_free');
    if ($accident_free) {
        $meta_query[] = ['key' => 'cf_accident_free', 'value' => '1', 'compare' => '='];
    }

    $condition = $request->get_param('condition');
    if ($condition && $condition !== 'all') {
        $meta_query[] = ['key' => 'cf_condition', 'value' => $condition, 'compare' => '='];
    }

    $seats = $request->get_param('seats');
    if ($seats) {
        $meta_query[] = ['key' => 'cf_seats', 'value' => $seats, 'compare' => '=', 'type' => 'NUMERIC'];
    }

    if (count($meta_query) > 1) {
        $meta_query['relation'] = 'AND';
    }
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    // --- Sort ---
    switch ($request->get_param('sort')) {
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
        default:
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
    }

    $query = new WP_Query($args);

    $cars = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id    = get_the_ID();
            $brand_terms = get_the_terms($post_id, 'car_brand');
            $thumb_url   = get_the_post_thumbnail_url($post_id, 'cf-card');

            $cars[] = [
                'id'           => $post_id,
                'title'        => get_the_title(),
                'url'          => get_permalink(),
                'thumbnail'    => $thumb_url ?: null,
                'brand'        => $brand_terms && !is_wp_error($brand_terms) ? $brand_terms[0]->name : null,
                'year'         => (int)(get_post_meta($post_id, 'cf_year', true) ?: 0) ?: null,
                'mileage'      => (int)(get_post_meta($post_id, 'cf_mileage', true) ?: 0) ?: null,
                'engine_cc'    => (int)(get_post_meta($post_id, 'cf_engine_cc', true) ?: 0) ?: null,
                'price_from'   => (int)(get_post_meta($post_id, 'cf_price_from', true) ?: 0) ?: null,
                'auction_score'=> get_post_meta($post_id, 'cf_auction_score', true) ?: null,
                'accident_free'=> (bool)get_post_meta($post_id, 'cf_accident_free', true),
            ];
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response([
        'cars'      => $cars,
        'found'     => $query->found_posts,
        'pages'     => $query->max_num_pages,
        'page'      => $page,
        'per_page'  => $per_page,
    ], 200);
}

/**
 * GET /wp-json/carfinance/v1/cars/aggregates
 * Returns min/max values for range filters.
 */
function cf_rest_get_aggregates(WP_REST_Request $request): WP_REST_Response {
    $cache_key = 'cf_filter_aggregates';
    $cached    = get_transient($cache_key);
    if ($cached !== false) {
        return new WP_REST_Response($cached, 200);
    }

    global $wpdb;

    $price_range = $wpdb->get_row(
        "SELECT MIN(CAST(meta_value AS UNSIGNED)) as min_val, MAX(CAST(meta_value AS UNSIGNED)) as max_val
         FROM {$wpdb->postmeta}
         WHERE meta_key = 'cf_price_from' AND meta_value > 0"
    );

    $year_range = $wpdb->get_row(
        "SELECT MIN(CAST(meta_value AS UNSIGNED)) as min_val, MAX(CAST(meta_value AS UNSIGNED)) as max_val
         FROM {$wpdb->postmeta}
         WHERE meta_key = 'cf_year' AND meta_value > 0"
    );

    $mileage_range = $wpdb->get_row(
        "SELECT MIN(CAST(meta_value AS UNSIGNED)) as min_val, MAX(CAST(meta_value AS UNSIGNED)) as max_val
         FROM {$wpdb->postmeta}
         WHERE meta_key = 'cf_mileage' AND meta_value > 0"
    );

    $data = [
        'price'   => ['min' => (int)($price_range->min_val ?? 0),   'max' => (int)($price_range->max_val ?? 10000000)],
        'year'    => ['min' => (int)($year_range->min_val ?? 2000),  'max' => (int)($year_range->max_val ?? (int)date('Y'))],
        'mileage' => ['min' => (int)($mileage_range->min_val ?? 0),  'max' => (int)($mileage_range->max_val ?? 300000)],
    ];

    set_transient($cache_key, $data, HOUR_IN_SECONDS * 6);

    return new WP_REST_Response($data, 200);
}

/**
 * GET /wp-json/carfinance/v1/brands?country={slug}
 */
function cf_rest_get_brands(WP_REST_Request $request): WP_REST_Response {
    $country_slug = $request->get_param('country');

    $args = [
        'taxonomy'   => 'car_brand',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
        'number'     => 200,
    ];

    if ($country_slug) {
        // Get post IDs in this country
        $post_ids = get_posts([
            'post_type'      => 'car_model',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => [['taxonomy' => 'car_country', 'field' => 'slug', 'terms' => $country_slug]],
        ]);

        if (empty($post_ids)) {
            return new WP_REST_Response([], 200);
        }

        $terms = wp_get_object_terms($post_ids, 'car_brand', ['orderby' => 'name', 'order' => 'ASC']);
    } else {
        $terms = get_terms($args);
    }

    if (is_wp_error($terms)) {
        return new WP_REST_Response([], 200);
    }

    $seen   = [];
    $result = [];
    foreach ($terms as $term) {
        if (isset($seen[$term->term_id])) continue;
        $seen[$term->term_id] = true;
        $result[] = ['id' => $term->term_id, 'name' => $term->name, 'slug' => $term->slug, 'count' => $term->count];
    }

    return new WP_REST_Response($result, 200);
}

/**
 * GET /wp-json/carfinance/v1/models?brand={slug}
 */
function cf_rest_get_models(WP_REST_Request $request): WP_REST_Response {
    $brand_slug = $request->get_param('brand');

    if (!$brand_slug) {
        return new WP_REST_Response([], 200);
    }

    $posts = get_posts([
        'post_type'      => 'car_model',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'fields'         => 'ids',
        'tax_query'      => [['taxonomy' => 'car_brand', 'field' => 'slug', 'terms' => $brand_slug]],
    ]);

    $result = [];
    foreach ($posts as $post_id) {
        $result[] = ['id' => $post_id, 'title' => get_the_title($post_id), 'slug' => get_post_field('post_name', $post_id)];
    }

    return new WP_REST_Response($result, 200);
}
