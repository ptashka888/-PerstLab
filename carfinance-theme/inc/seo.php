<?php
/**
 * SEO Module
 *
 * Canonical URLs, meta robots, hreflang, robots.txt, Open Graph, SEO titles.
 * Skips output where Yoast SEO is active.
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Initialize all SEO hooks.
 * Called from functions.php via require_once.
 */
function cf_seo_init() {
    add_action( 'wp_head', 'cf_canonical', 1 );
    add_action( 'wp_head', 'cf_meta_robots', 1 );
    add_action( 'wp_head', 'cf_hreflang', 1 );
    add_action( 'wp_head', 'cf_og_meta', 2 );
    add_filter( 'robots_txt', 'cf_robots_txt', 10, 2 );
    add_filter( 'pre_get_document_title', 'cf_seo_title', 10, 1 );
}

/**
 * Query params that trigger noindex and should be stripped from canonical.
 */
function cf_seo_noindex_params() {
    return array( 'sort', 'filter', 'color' );
}

/**
 * Check if current request has noindex query params.
 */
function cf_has_noindex_params() {
    foreach ( cf_seo_noindex_params() as $param ) {
        if ( isset( $_GET[ $param ] ) ) {
            return true;
        }
    }
    return false;
}

/**
 * Canonical URL — hooked to wp_head.
 *
 * Strips query params (sort, filter, color).
 * On paginated pages, points to page 1.
 * Skips if Yoast active.
 */
function cf_canonical() {
    if ( defined( 'WPSEO_VERSION' ) ) {
        return;
    }

    // Don't output on 404 or search
    if ( is_404() || is_search() ) {
        return;
    }

    $canonical = '';

    if ( is_singular() ) {
        $canonical = get_permalink();
    } elseif ( is_tax() || is_category() || is_tag() ) {
        $term = get_queried_object();
        if ( $term ) {
            $canonical = get_term_link( $term );
        }
    } elseif ( is_post_type_archive() ) {
        $canonical = get_post_type_archive_link( get_queried_object()->name ?? get_query_var( 'post_type' ) );
    } elseif ( is_home() ) {
        $canonical = get_permalink( get_option( 'page_for_posts' ) );
    } elseif ( is_front_page() ) {
        $canonical = home_url( '/' );
    } else {
        $canonical = home_url( $_SERVER['REQUEST_URI'] ?? '/' );
    }

    if ( is_wp_error( $canonical ) || empty( $canonical ) ) {
        return;
    }

    // Strip query params
    $canonical = strtok( $canonical, '?' );

    // Remove pagination — point to page 1
    $canonical = preg_replace( '#/page/\d+/?$#', '/', $canonical );

    // Ensure trailing slash
    $canonical = trailingslashit( $canonical );

    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";
}

/**
 * Meta robots — hooked to wp_head.
 *
 * noindex,follow: pages with query params, paginated /page/2+
 * index,follow: /catalog/tags/*, all normal pages
 * Skips if Yoast active.
 */
function cf_meta_robots() {
    if ( defined( 'WPSEO_VERSION' ) ) {
        return;
    }

    // Default: index, follow
    $robots = 'index, follow';

    // noindex for pages with filter/sort/color query params
    if ( cf_has_noindex_params() ) {
        $robots = 'noindex, follow';
    }

    // noindex for paginated pages (/page/2+)
    if ( is_paged() ) {
        $robots = 'noindex, follow';
    }

    // Always index catalog tags — CRITICAL for SEO
    if ( is_tax( 'catalog_tag' ) ) {
        $robots = 'index, follow';
    }

    // noindex for search and 404
    if ( is_search() ) {
        $robots = 'noindex, follow';
    }
    if ( is_404() ) {
        $robots = 'noindex, nofollow';
    }

    echo '<meta name="robots" content="' . esc_attr( $robots ) . '" />' . "\n";
}

/**
 * Hreflang tags for multisite city subdomains.
 * Hooked to wp_head.
 */
function cf_hreflang() {
    if ( ! is_multisite() ) {
        return;
    }

    $current_blog_id = get_current_blog_id();
    $current_path    = $_SERVER['REQUEST_URI'] ?? '/';

    // Strip query string for clean path
    $current_path = strtok( $current_path, '?' );

    $sites = get_sites( array(
        'public'   => 1,
        'archived' => 0,
        'deleted'  => 0,
        'number'   => 50,
    ) );

    if ( empty( $sites ) || count( $sites ) < 2 ) {
        return;
    }

    foreach ( $sites as $site ) {
        switch_to_blog( $site->blog_id );

        $site_url = home_url( $current_path );

        // Default lang for all Russian city subdomains
        $hreflang = 'ru';

        // Mark main site as x-default
        if ( is_main_site( $site->blog_id ) ) {
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $site_url ) . '" />' . "\n";
        }

        echo '<link rel="alternate" hreflang="' . esc_attr( $hreflang ) . '" href="' . esc_url( $site_url ) . '" />' . "\n";

        restore_current_blog();
    }
}

/**
 * Robots.txt additions — filter on robots_txt.
 */
function cf_robots_txt( $output, $public ) {
    // If site is not public, don't modify
    if ( '0' === (string) $public ) {
        return $output;
    }

    $additions  = "\n# CarFinance SEO rules\n";
    $additions .= "Disallow: /*?sort=\n";
    $additions .= "Disallow: /*?filter=\n";
    $additions .= "Disallow: /*?color=\n";
    $additions .= "Allow: /catalog/tags/\n";
    $additions .= "\n";
    $additions .= "Sitemap: " . home_url( '/sitemap.xml' ) . "\n";

    return $output . $additions;
}

/**
 * Open Graph meta tags — hooked to wp_head.
 * Skips if Yoast active.
 */
function cf_og_meta() {
    if ( defined( 'WPSEO_VERSION' ) ) {
        return;
    }

    $og_title       = '';
    $og_description = '';
    $og_image       = '';
    $og_type        = 'website';
    $og_url         = '';

    if ( is_singular() ) {
        $post_id        = get_the_ID();
        $og_title       = get_the_title( $post_id );
        $og_description = get_the_excerpt( $post_id );
        $og_image       = get_the_post_thumbnail_url( $post_id, 'full' );
        $og_url         = get_permalink( $post_id );

        if ( is_singular( 'post' ) ) {
            $og_type = 'article';
        } elseif ( is_singular( 'car_model' ) ) {
            $og_type = 'product';
        }
    } elseif ( is_tax() || is_category() || is_tag() ) {
        $term = get_queried_object();
        if ( $term ) {
            $og_title       = $term->name;
            $og_description = $term->description ?: $term->name . ' — CarFinance MSK';
            $og_url         = get_term_link( $term );
        }
    } elseif ( is_post_type_archive() ) {
        $post_type_obj  = get_queried_object();
        $og_title       = $post_type_obj->label ?? get_bloginfo( 'name' );
        $og_description = $post_type_obj->description ?? '';
        $og_url         = get_post_type_archive_link( $post_type_obj->name ?? '' );
    } elseif ( is_front_page() ) {
        $og_title       = get_bloginfo( 'name' );
        $og_description = get_bloginfo( 'description' );
        $og_url         = home_url( '/' );
    } else {
        $og_title = get_bloginfo( 'name' );
        $og_url   = home_url( $_SERVER['REQUEST_URI'] ?? '/' );
    }

    // Fallback image from site logo
    if ( empty( $og_image ) ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $og_image = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        }
    }

    // Sanitize URL for og:url
    if ( is_wp_error( $og_url ) ) {
        $og_url = home_url( '/' );
    }
    $og_url = strtok( $og_url, '?' );

    if ( $og_title ) {
        echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
    }
    if ( $og_description ) {
        echo '<meta property="og:description" content="' . esc_attr( wp_trim_words( $og_description, 30, '...' ) ) . '" />' . "\n";
    }
    if ( $og_image ) {
        echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
    }
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $og_url ) . '" />' . "\n";
    echo '<meta property="og:locale" content="ru_RU" />' . "\n";
    echo '<meta property="og:site_name" content="CarFinance MSK" />' . "\n";
}

/**
 * SEO title filter — hooked to pre_get_document_title.
 *
 * Custom titles for taxonomy archives.
 * Skips if Yoast active.
 */
function cf_seo_title( $title ) {
    if ( defined( 'WPSEO_VERSION' ) ) {
        return $title;
    }

    // catalog_tag taxonomy
    if ( is_tax( 'catalog_tag' ) ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            return 'Купить ' . $term->name . ' из-за рубежа — CarFinance MSK';
        }
    }

    // cf_brand taxonomy
    if ( is_tax( 'cf_brand' ) ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            return $term->name . ' из-за рубежа — каталог CarFinance MSK';
        }
    }

    return $title;
}
