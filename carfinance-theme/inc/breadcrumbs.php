<?php
/**
 * Context-aware breadcrumb generator with BreadcrumbList Schema.org JSON-LD.
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Output breadcrumb HTML and BreadcrumbList Schema.org JSON-LD.
 */
function cf_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }

    $items = array();

    // First item is always Home.
    $items[] = array(
        'name' => 'Главная',
        'url'  => home_url( '/' ),
    );

    // Country pages (regular pages with country template).
    $country_slugs = array(
        'avto-iz-korei'  => 'Авто из Кореи',
        'avto-iz-yaponii' => 'Авто из Японии',
        'avto-iz-kitaya'  => 'Авто из Китая',
        'avto-iz-ssha'    => 'Авто из США',
        'avto-iz-oae'     => 'Авто из ОАЭ',
    );

    if ( is_404() ) {
        $items[] = array(
            'name' => 'Страница не найдена',
            'url'  => '',
        );

    } elseif ( is_search() ) {
        $items[] = array(
            'name' => 'Поиск',
            'url'  => '',
        );

    } elseif ( is_post_type_archive( 'car_model' ) ) {
        $items[] = array(
            'name' => 'Каталог',
            'url'  => '',
        );

    } elseif ( is_tax( 'car_brand' ) ) {
        $term     = get_queried_object();
        $active_c = sanitize_text_field( $_GET['country'] ?? '' );
        $items[]  = [ 'name' => 'Каталог', 'url' => get_post_type_archive_link( 'car_model' ) ];
        if ( $active_c ) {
            $c_page_slugs = [ 'korea' => 'avto-iz-korei', 'japan' => 'avto-iz-yaponii', 'china' => 'avto-iz-kitaya', 'usa' => 'avto-iz-usa', 'uae' => 'avto-iz-oae' ];
            $c_from_names = [ 'korea' => 'Из Кореи', 'japan' => 'Из Японии', 'china' => 'Из Китая', 'usa' => 'Из США', 'uae' => 'Из ОАЭ' ];
            $c_gen_names  = [ 'korea' => 'Кореи', 'japan' => 'Японии', 'china' => 'Китая', 'usa' => 'США', 'uae' => 'ОАЭ' ];
            $cp = get_page_by_path( $c_page_slugs[ $active_c ] ?? '' );
            if ( $cp ) {
                $items[] = [ 'name' => $c_from_names[ $active_c ] ?? $active_c, 'url' => get_permalink( $cp ) ];
            }
            $items[] = [ 'name' => $term->name . ' из ' . ( $c_gen_names[ $active_c ] ?? $active_c ), 'url' => '' ];
        } else {
            $items[] = [ 'name' => $term->name, 'url' => '' ];
        }

    } elseif ( is_tax( 'car_country' ) ) {
        $term = get_queried_object();
        $items[] = array(
            'name' => 'Авто из ' . $term->name,
            'url'  => '',
        );

    } elseif ( is_tax( 'car_type' ) ) {
        $term = get_queried_object();
        $items[] = array(
            'name' => 'Каталог',
            'url'  => get_post_type_archive_link( 'car_model' ),
        );
        $items[] = array(
            'name' => 'Кузов: ' . $term->name,
            'url'  => '',
        );

    } elseif ( is_tax( 'price_range' ) ) {
        $term = get_queried_object();
        $items[] = array(
            'name' => 'Каталог',
            'url'  => get_post_type_archive_link( 'car_model' ),
        );
        $items[] = array(
            'name' => 'Бюджет: ' . $term->name,
            'url'  => '',
        );

    } elseif ( is_tax( 'engine_type' ) ) {
        $term = get_queried_object();
        $items[] = array(
            'name' => 'Каталог',
            'url'  => get_post_type_archive_link( 'car_model' ),
        );
        $items[] = array(
            'name' => 'Двигатель: ' . $term->name,
            'url'  => '',
        );

    } elseif ( is_tax( 'catalog_tag' ) ) {
        $term = get_queried_object();
        $items[] = array(
            'name' => 'Каталог',
            'url'  => get_post_type_archive_link( 'car_model' ),
        );
        $items[] = array(
            'name' => 'Теги',
            'url'  => '',
        );
        $items[] = array(
            'name' => $term->name,
            'url'  => '',
        );

    } elseif ( is_singular( 'car_model' ) ) {
        $post_id = get_the_ID();
        $items[] = array(
            'name' => 'Каталог',
            'url'  => get_post_type_archive_link( 'car_model' ),
        );
        $brands = get_the_terms( $post_id, 'car_brand' );
        if ( $brands && ! is_wp_error( $brands ) ) {
            $brand = $brands[0];
            $items[] = array(
                'name' => $brand->name,
                'url'  => get_term_link( $brand ),
            );
        }
        // Year / modification as last crumb context
        $year = get_post_meta( $post_id, 'cf_year', true );
        $crumb_title = get_the_title();
        if ( $year ) {
            $crumb_title .= ' ' . $year . ' г.';
        }
        $items[] = array(
            'name' => $crumb_title,
            'url'  => '',
        );

    } elseif ( is_singular( 'case_study' ) ) {
        $items[] = array(
            'name' => 'Кейсы',
            'url'  => get_post_type_archive_link( 'case_study' ),
        );
        $items[] = array(
            'name' => get_the_title(),
            'url'  => '',
        );

    } elseif ( is_singular( 'service_page' ) ) {
        $items[] = array(
            'name' => 'Услуги',
            'url'  => get_post_type_archive_link( 'service_page' ),
        );
        $items[] = array(
            'name' => get_the_title(),
            'url'  => '',
        );

    } elseif ( is_single() ) {
        // Blog post.
        $post_id = get_the_ID();
        $items[] = array(
            'name' => 'Блог',
            'url'  => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ),
        );
        $topics = get_the_terms( $post_id, 'blog_topic' );
        if ( $topics && ! is_wp_error( $topics ) ) {
            $topic = $topics[0];
            $items[] = array(
                'name' => $topic->name,
                'url'  => get_term_link( $topic ),
            );
        }
        $items[] = array(
            'name' => get_the_title(),
            'url'  => '',
        );

    } elseif ( is_page() ) {
        // Check for country pages.
        $current_slug    = get_post_field( 'post_name', get_the_ID() );
        $page_template   = get_page_template_slug( get_the_ID() );
        $is_country_page = false;

        if ( isset( $country_slugs[ $current_slug ] ) ) {
            $is_country_page = true;
            $items[] = array(
                'name' => $country_slugs[ $current_slug ],
                'url'  => '',
            );
        } elseif ( 'page-country.php' === $page_template ) {
            $is_country_page = true;
            $items[] = array(
                'name' => get_the_title(),
                'url'  => '',
            );
        }

        if ( ! $is_country_page ) {
            $page_template = get_page_template_slug( get_the_ID() );

            // Brand+Country hub page
            if ( 'page-brand-country.php' === $page_template ) {
                $ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
                foreach ( $ancestors as $anc_id ) {
                    $anc_slug = get_post_field( 'post_name', $anc_id );
                    if ( isset( $country_slugs[ $anc_slug ] ) ) {
                        $items[] = [ 'name' => $country_slugs[ $anc_slug ], 'url' => get_permalink( $anc_id ) ];
                    } else {
                        $items[] = [ 'name' => get_the_title( $anc_id ), 'url' => get_permalink( $anc_id ) ];
                    }
                }
                $brand_slug_field = cf_get_field( 'cf_brand_slug', get_the_ID() ) ?: get_post_field( 'post_name', get_the_ID() );
                $brand_term       = get_term_by( 'slug', $brand_slug_field, 'car_brand' );
                if ( $brand_term ) {
                    $items[] = [ 'name' => $brand_term->name, 'url' => '' ];
                } else {
                    $items[] = [ 'name' => get_the_title(), 'url' => '' ];
                }

            } elseif ( 'page-author.php' === $page_template ) {
                $ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
                foreach ( $ancestors as $anc_id ) {
                    $items[] = [ 'name' => get_the_title( $anc_id ), 'url' => get_permalink( $anc_id ) ];
                }
                $items[] = [ 'name' => get_the_title(), 'url' => '' ];

            } elseif ( is_page( 'calculator' ) || is_page( 'kalkulyator' ) ) {
                $items[] = [ 'name' => 'Калькулятор', 'url' => '' ];

            } else {
                // Build hierarchy for nested pages.
                $ancestors = get_post_ancestors( get_the_ID() );
                if ( $ancestors ) {
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor_id ) {
                        $items[] = [
                            'name' => get_the_title( $ancestor_id ),
                            'url'  => get_permalink( $ancestor_id ),
                        ];
                    }
                }
                $items[] = [ 'name' => get_the_title(), 'url' => '' ];
            }
        }
    }

    // Bail if only Home (shouldn't happen since we check is_front_page above).
    if ( count( $items ) < 2 ) {
        return;
    }

    // Build Schema.org BreadcrumbList JSON-LD.
    $schema_items = array();
    foreach ( $items as $index => $item ) {
        $position      = $index + 1;
        $schema_item   = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $item['name'],
        );
        if ( ! empty( $item['url'] ) ) {
            $schema_item['item'] = $item['url'];
        }
        $schema_items[] = $schema_item;
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $schema_items,
    );

    // Render HTML.
    echo '<nav class="cf-breadcrumbs" aria-label="Хлебные крошки">';
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
    echo '<ol>';

    $last_index = count( $items ) - 1;
    foreach ( $items as $index => $item ) {
        if ( $index === $last_index ) {
            echo '<li aria-current="page"><span>' . esc_html( $item['name'] ) . '</span></li>';
        } else {
            echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a></li>';
        }
    }

    echo '</ol>';
    echo '</nav>';
}
