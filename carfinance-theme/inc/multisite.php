<?php
/**
 * WordPress Multisite support for city subdomains.
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if WordPress Multisite is active.
 *
 * @return bool
 */
function cf_is_multisite_active() {
    return is_multisite();
}

/**
 * Check if the current site is a city subsite (not the main site).
 *
 * @return bool
 */
function cf_is_city_site() {
    if ( ! is_multisite() ) {
        return false;
    }
    return get_current_blog_id() > 1;
}

/**
 * Get city data for the current subdomain site.
 *
 * Uses ACF options page if available, falls back to site meta, then to the
 * hardcoded cities list keyed by subdomain.
 *
 * @return array City data: name, name_prepositional, phone, address, lat, lng, region.
 */
function cf_get_city_data() {
    $cities = cf_get_cities_list();

    // Determine current subdomain.
    $current_host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
    $subdomain    = '';

    if ( preg_match( '/^([a-z0-9-]+)\.carfinance-msk\.ru$/i', $current_host, $matches ) ) {
        $subdomain = strtolower( $matches[1] );
    }

    // Try ACF options first.
    if ( function_exists( 'get_field' ) ) {
        $acf_city = get_field( 'cf_city_data', 'option' );
        if ( is_array( $acf_city ) && ! empty( $acf_city['name'] ) ) {
            return wp_parse_args( $acf_city, cf_get_default_city_data() );
        }
    }

    // Try site meta.
    $blog_id   = get_current_blog_id();
    $site_meta = get_site_meta( $blog_id, 'cf_city_data', true );
    if ( is_array( $site_meta ) && ! empty( $site_meta['name'] ) ) {
        return wp_parse_args( $site_meta, cf_get_default_city_data() );
    }

    // Fall back to hardcoded list.
    if ( isset( $cities[ $subdomain ] ) ) {
        return $cities[ $subdomain ];
    }

    return cf_get_default_city_data();
}

/**
 * Get the main site blog ID.
 *
 * @return int
 */
function cf_get_main_site_id() {
    if ( function_exists( 'get_main_site_id' ) ) {
        return get_main_site_id();
    }
    return 1;
}

/**
 * Query posts from the main site catalog.
 *
 * Switches to the main blog, runs WP_Query, resolves thumbnails and permalinks
 * before restoring the current blog.
 *
 * @param array $args WP_Query arguments.
 * @return array Array of post objects with added thumbnail_url and permalink properties.
 */
function cf_cross_site_query( $args ) {
    if ( ! is_multisite() ) {
        $query = new WP_Query( $args );
        $posts = array();
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $post                = get_post();
                $post->thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'medium' ) ?: '';
                $post->permalink     = get_permalink( $post->ID );
                $posts[]             = $post;
            }
            wp_reset_postdata();
        }
        return $posts;
    }

    $main_site_id = cf_get_main_site_id();
    $current_blog = get_current_blog_id();
    $posts        = array();

    if ( $current_blog !== $main_site_id ) {
        switch_to_blog( $main_site_id );
    }

    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post                = get_post();
            $post->thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'medium' ) ?: '';
            $post->permalink     = get_permalink( $post->ID );
            $posts[]             = $post;
        }
        wp_reset_postdata();
    }

    if ( $current_blog !== $main_site_id ) {
        restore_current_blog();
    }

    return $posts;
}

/**
 * Get all city subsites with their data.
 *
 * @return array Array of city site data, keyed by blog_id.
 */
function cf_get_all_city_sites() {
    if ( ! is_multisite() ) {
        return array();
    }

    $sites      = get_sites( array( 'public' => 1 ) );
    $main_id    = cf_get_main_site_id();
    $city_sites = array();
    $cities     = cf_get_cities_list();

    foreach ( $sites as $site ) {
        // Skip main site.
        if ( (int) $site->blog_id === $main_id ) {
            continue;
        }

        $subdomain = '';
        if ( preg_match( '/^([a-z0-9-]+)\.carfinance-msk\.ru$/i', $site->domain, $matches ) ) {
            $subdomain = strtolower( $matches[1] );
        }

        // Try site meta first.
        $site_data = get_site_meta( $site->blog_id, 'cf_city_data', true );
        if ( ! is_array( $site_data ) || empty( $site_data['name'] ) ) {
            // Fall back to hardcoded list.
            $site_data = isset( $cities[ $subdomain ] ) ? $cities[ $subdomain ] : cf_get_default_city_data();
        }

        $site_data['blog_id']   = (int) $site->blog_id;
        $site_data['domain']    = $site->domain;
        $site_data['subdomain'] = $subdomain;
        $site_data['url']       = 'https://' . $site->domain . $site->path;

        $city_sites[ (int) $site->blog_id ] = $site_data;
    }

    return $city_sites;
}

/**
 * Multisite setup hooked to init.
 *
 * On city sites: registers CPTs and taxonomies (they must be registered on each site),
 * and applies city-specific customizations.
 */
function cf_multisite_setup() {
    if ( ! is_multisite() ) {
        return;
    }

    if ( cf_is_city_site() ) {
        // CPTs and taxonomies are registered in functions.php which runs on every site.
        // Here we add city-specific customizations.

        $city_data = cf_get_city_data();

        // Filter the site title to include city name.
        add_filter( 'option_blogname', function( $name ) use ( $city_data ) {
            if ( ! empty( $city_data['name'] ) ) {
                return 'CarFinance ' . $city_data['name'] . ' — импорт авто';
            }
            return $name;
        } );

        // Filter blogdescription for city context.
        add_filter( 'option_blogdescription', function( $desc ) use ( $city_data ) {
            if ( ! empty( $city_data['name_prepositional'] ) ) {
                return 'Импорт и подбор автомобилей в ' . $city_data['name_prepositional'];
            }
            return $desc;
        } );
    }
}
add_action( 'init', 'cf_multisite_setup' );

/**
 * Show notice in network admin if theme is not network-enabled.
 */
function cf_network_admin_notice() {
    if ( ! is_multisite() || ! is_network_admin() ) {
        return;
    }

    $theme         = wp_get_theme( 'carfinance-theme' );
    $allowed       = $theme->is_allowed( 'network' );

    if ( ! $allowed ) {
        echo '<div class="notice notice-warning"><p>';
        echo '<strong>CarFinance Theme:</strong> Тема не активирована для сети. ';
        echo 'Перейдите в <a href="' . esc_url( network_admin_url( 'themes.php' ) ) . '">Управление темами</a> ';
        echo 'и включите тему для всей сети, чтобы городские поддомены работали корректно.';
        echo '</p></div>';
    }
}
add_action( 'network_admin_notices', 'cf_network_admin_notice' );

/**
 * Get the full list of city data keyed by subdomain.
 *
 * @return array
 */
function cf_get_cities_list() {
    return array(
        'krasnodar' => array(
            'name'               => 'Краснодар',
            'name_prepositional' => 'Краснодаре',
            'phone'              => '',
            'address'            => '',
            'lat'                => 45.0353,
            'lng'                => 38.9753,
            'region'             => 'Краснодарский край',
        ),
        'rostov' => array(
            'name'               => 'Ростов-на-Дону',
            'name_prepositional' => 'Ростове-на-Дону',
            'phone'              => '',
            'address'            => '',
            'lat'                => 47.2357,
            'lng'                => 39.7015,
            'region'             => 'Ростовская область',
        ),
        'spb' => array(
            'name'               => 'Санкт-Петербург',
            'name_prepositional' => 'Санкт-Петербурге',
            'phone'              => '',
            'address'            => '',
            'lat'                => 59.9343,
            'lng'                => 30.3351,
            'region'             => 'Санкт-Петербург',
        ),
        'ekb' => array(
            'name'               => 'Екатеринбург',
            'name_prepositional' => 'Екатеринбурге',
            'phone'              => '',
            'address'            => '',
            'lat'                => 56.8389,
            'lng'                => 60.6057,
            'region'             => 'Свердловская область',
        ),
        'kazan' => array(
            'name'               => 'Казань',
            'name_prepositional' => 'Казани',
            'phone'              => '',
            'address'            => '',
            'lat'                => 55.7964,
            'lng'                => 49.1089,
            'region'             => 'Республика Татарстан',
        ),
        'novosibirsk' => array(
            'name'               => 'Новосибирск',
            'name_prepositional' => 'Новосибирске',
            'phone'              => '',
            'address'            => '',
            'lat'                => 55.0084,
            'lng'                => 82.9357,
            'region'             => 'Новосибирская область',
        ),
        'vladivostok' => array(
            'name'               => 'Владивосток',
            'name_prepositional' => 'Владивостоке',
            'phone'              => '',
            'address'            => '',
            'lat'                => 43.1156,
            'lng'                => 131.8855,
            'region'             => 'Приморский край',
        ),
        'sochi' => array(
            'name'               => 'Сочи',
            'name_prepositional' => 'Сочи',
            'phone'              => '',
            'address'            => '',
            'lat'                => 43.5855,
            'lng'                => 39.7231,
            'region'             => 'Краснодарский край',
        ),
        'samara' => array(
            'name'               => 'Самара',
            'name_prepositional' => 'Самаре',
            'phone'              => '',
            'address'            => '',
            'lat'                => 53.1959,
            'lng'                => 50.1002,
            'region'             => 'Самарская область',
        ),
    );
}

/**
 * Get default empty city data structure.
 *
 * @return array
 */
function cf_get_default_city_data() {
    return array(
        'name'               => '',
        'name_prepositional' => '',
        'phone'              => '',
        'address'            => '',
        'lat'                => 0,
        'lng'                => 0,
        'region'             => '',
    );
}
