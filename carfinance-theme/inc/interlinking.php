<?php
/**
 * Automatic SILO interlinking module.
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get interlinking data based on the current page type.
 *
 * @return array Array of link groups. Each group: ['title' => string, 'links' => [['url' => string, 'anchor' => string], ...]].
 */
function cf_get_interlinking_data() {
    $groups = array();

    if ( is_singular( 'car_model' ) ) {
        $groups = cf_interlinking_car_model();
    } elseif ( is_tax( 'car_brand' ) ) {
        $groups = cf_interlinking_brand();
    } elseif ( is_tax( 'catalog_tag' ) ) {
        $groups = cf_interlinking_tag();
    } elseif ( is_post_type_archive( 'car_model' ) ) {
        $groups = cf_interlinking_catalog_archive();
    } elseif ( is_singular( 'case_study' ) ) {
        $groups = cf_interlinking_case_study();
    } elseif ( is_singular( 'service_page' ) ) {
        $groups = cf_interlinking_service();
    } elseif ( is_single() ) {
        $groups = cf_interlinking_blog_post();
    } elseif ( is_page() ) {
        $page_template = get_page_template_slug( get_the_ID() );
        if ( 'page-country.php' === $page_template ) {
            $groups = cf_interlinking_country();
        }
    }

    return $groups;
}

/**
 * Interlinking for country pages.
 */
function cf_interlinking_country() {
    $groups  = array();
    $post_id = get_the_ID();

    // Detect country by page slug or meta.
    $slug         = get_post_field( 'post_name', $post_id );
    $country_term = cf_get_country_term_by_page_slug( $slug );

    // Brands from this country.
    if ( $country_term ) {
        $brand_links = array();
        $models      = get_posts( array(
            'post_type'      => 'car_model',
            'posts_per_page' => 100,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'cf_country',
                    'field'    => 'term_id',
                    'terms'    => $country_term->term_id,
                ),
            ),
        ) );

        $brand_ids = array();
        foreach ( $models as $model_id ) {
            $brands = get_the_terms( $model_id, 'car_brand' );
            if ( $brands && ! is_wp_error( $brands ) ) {
                foreach ( $brands as $brand ) {
                    $brand_ids[ $brand->term_id ] = $brand;
                }
            }
        }

        $count = 0;
        foreach ( $brand_ids as $brand ) {
            if ( $count >= 8 ) break;
            $brand_links[] = array(
                'url'    => get_term_link( $brand ),
                'anchor' => $brand->name,
            );
            $count++;
        }

        if ( $brand_links ) {
            $groups[] = array(
                'title' => 'Бренды',
                'links' => $brand_links,
            );
        }

        // Related catalog tags.
        $tag_links = array();
        $tags      = get_terms( array(
            'taxonomy' => 'catalog_tag',
            'number'   => 6,
        ) );
        if ( $tags && ! is_wp_error( $tags ) ) {
            foreach ( $tags as $tag ) {
                $tag_links[] = array(
                    'url'    => get_term_link( $tag ),
                    'anchor' => $tag->name,
                );
            }
        }
        if ( $tag_links ) {
            $groups[] = array(
                'title' => 'Популярные категории',
                'links' => $tag_links,
            );
        }
    }

    // Blog articles about this country.
    $blog_links = array();
    $blog_posts = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        's'              => get_the_title( $post_id ),
    ) );
    if ( $blog_posts->have_posts() ) {
        while ( $blog_posts->have_posts() ) {
            $blog_posts->the_post();
            $blog_links[] = array(
                'url'    => get_permalink(),
                'anchor' => get_the_title(),
            );
        }
        wp_reset_postdata();
    }
    if ( $blog_links ) {
        $groups[] = array(
            'title' => 'Статьи по теме',
            'links' => $blog_links,
        );
    }

    return $groups;
}

/**
 * Interlinking for brand taxonomy pages.
 */
function cf_interlinking_brand() {
    $groups = array();
    $term   = get_queried_object();

    // Models of this brand.
    $model_links = array();
    $models      = new WP_Query( array(
        'post_type'      => 'car_model',
        'posts_per_page' => 8,
        'tax_query'      => array(
            array(
                'taxonomy' => 'car_brand',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ),
        ),
    ) );
    if ( $models->have_posts() ) {
        while ( $models->have_posts() ) {
            $models->the_post();
            $model_links[] = array(
                'url'    => get_permalink(),
                'anchor' => cf_diversify_anchor( '', get_the_ID() ),
            );
        }
        wp_reset_postdata();
    }
    if ( $model_links ) {
        $groups[] = array(
            'title' => 'Модели ' . $term->name,
            'links' => $model_links,
        );
    }

    // Parent country.
    $country_links = cf_get_country_links_for_brand( $term->term_id );
    if ( $country_links ) {
        $groups[] = array(
            'title' => 'Страна производства',
            'links' => $country_links,
        );
    }

    // Related tags.
    $tag_links = array();
    $tags      = get_terms( array(
        'taxonomy' => 'catalog_tag',
        'number'   => 5,
    ) );
    if ( $tags && ! is_wp_error( $tags ) ) {
        foreach ( $tags as $tag ) {
            $tag_links[] = array(
                'url'    => get_term_link( $tag ),
                'anchor' => $tag->name,
            );
        }
    }
    if ( $tag_links ) {
        $groups[] = array(
            'title' => 'Смотрите также',
            'links' => $tag_links,
        );
    }

    return $groups;
}

/**
 * Interlinking for car model single pages.
 * SILO rule: only link to models of the SAME brand, never cross-brand.
 */
function cf_interlinking_car_model() {
    $groups  = array();
    $post_id = get_the_ID();

    // Brand page link.
    $brands = get_the_terms( $post_id, 'car_brand' );
    if ( $brands && ! is_wp_error( $brands ) ) {
        $brand         = $brands[0];
        $brand_links   = array();
        $brand_links[] = array(
            'url'    => get_term_link( $brand ),
            'anchor' => 'Все модели ' . $brand->name,
        );
        $groups[] = array(
            'title' => 'Бренд',
            'links' => $brand_links,
        );

        // Similar models — same brand ONLY (SILO rule).
        $similar_links = array();
        $similar       = new WP_Query( array(
            'post_type'      => 'car_model',
            'posts_per_page' => 6,
            'post__not_in'   => array( $post_id ),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'car_brand',
                    'field'    => 'term_id',
                    'terms'    => $brand->term_id,
                ),
            ),
        ) );
        if ( $similar->have_posts() ) {
            while ( $similar->have_posts() ) {
                $similar->the_post();
                $similar_links[] = array(
                    'url'    => get_permalink(),
                    'anchor' => cf_diversify_anchor( '', get_the_ID() ),
                );
            }
            wp_reset_postdata();
        }
        if ( $similar_links ) {
            $groups[] = array(
                'title' => 'Похожие модели ' . $brand->name,
                'links' => $similar_links,
            );
        }
    }

    // Country page link.
    $country_links = cf_get_country_links_for_model( $post_id );
    if ( $country_links ) {
        $groups[] = array(
            'title' => 'Страна',
            'links' => $country_links,
        );
    }

    return $groups;
}

/**
 * Interlinking for catalog tag pages.
 */
function cf_interlinking_tag() {
    $groups = array();
    $term   = get_queried_object();

    // Related tags (up to 5).
    $tag_links = array();
    $tags      = get_terms( array(
        'taxonomy' => 'catalog_tag',
        'number'   => 6,
        'exclude'  => array( $term->term_id ),
    ) );
    if ( $tags && ! is_wp_error( $tags ) ) {
        $count = 0;
        foreach ( $tags as $tag ) {
            if ( $count >= 5 ) break;
            $tag_links[] = array(
                'url'    => get_term_link( $tag ),
                'anchor' => $tag->name,
            );
            $count++;
        }
    }
    if ( $tag_links ) {
        $groups[] = array(
            'title' => 'Смотрите также',
            'links' => $tag_links,
        );
    }

    // Related brands.
    $brand_links = array();
    $models      = get_posts( array(
        'post_type'      => 'car_model',
        'posts_per_page' => 50,
        'fields'         => 'ids',
        'tax_query'      => array(
            array(
                'taxonomy' => 'catalog_tag',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ),
        ),
    ) );
    $brand_ids = array();
    foreach ( $models as $model_id ) {
        $brands = get_the_terms( $model_id, 'car_brand' );
        if ( $brands && ! is_wp_error( $brands ) ) {
            foreach ( $brands as $brand ) {
                $brand_ids[ $brand->term_id ] = $brand;
            }
        }
    }
    $count = 0;
    foreach ( $brand_ids as $brand ) {
        if ( $count >= 6 ) break;
        $brand_links[] = array(
            'url'    => get_term_link( $brand ),
            'anchor' => $brand->name,
        );
        $count++;
    }
    if ( $brand_links ) {
        $groups[] = array(
            'title' => 'Бренды',
            'links' => $brand_links,
        );
    }

    return $groups;
}

/**
 * Interlinking for the catalog archive page.
 */
function cf_interlinking_catalog_archive() {
    $groups = array();

    // Country pages.
    $country_pages = array(
        'avto-iz-korei'   => 'Авто из Кореи',
        'avto-iz-yaponii' => 'Авто из Японии',
        'avto-iz-kitaya'  => 'Авто из Китая',
        'avto-iz-ssha'    => 'Авто из США',
        'avto-iz-oae'     => 'Авто из ОАЭ',
    );
    $country_links = array();
    foreach ( $country_pages as $slug => $name ) {
        $page = get_page_by_path( $slug );
        if ( $page ) {
            $country_links[] = array(
                'url'    => get_permalink( $page ),
                'anchor' => $name,
            );
        }
    }
    if ( $country_links ) {
        $groups[] = array(
            'title' => 'Импорт по странам',
            'links' => $country_links,
        );
    }

    // Top brands.
    $brand_links = array();
    $brands      = get_terms( array(
        'taxonomy'   => 'car_brand',
        'number'     => 8,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'hide_empty' => true,
    ) );
    if ( $brands && ! is_wp_error( $brands ) ) {
        foreach ( $brands as $brand ) {
            $brand_links[] = array(
                'url'    => get_term_link( $brand ),
                'anchor' => $brand->name,
            );
        }
    }
    if ( $brand_links ) {
        $groups[] = array(
            'title' => 'Популярные бренды',
            'links' => $brand_links,
        );
    }

    // Popular tags.
    $tag_links = array();
    $tags      = get_terms( array(
        'taxonomy'   => 'catalog_tag',
        'number'     => 6,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'hide_empty' => true,
    ) );
    if ( $tags && ! is_wp_error( $tags ) ) {
        foreach ( $tags as $tag ) {
            $tag_links[] = array(
                'url'    => get_term_link( $tag ),
                'anchor' => $tag->name,
            );
        }
    }
    if ( $tag_links ) {
        $groups[] = array(
            'title' => 'Популярные категории',
            'links' => $tag_links,
        );
    }

    return $groups;
}

/**
 * Interlinking for blog posts.
 */
function cf_interlinking_blog_post() {
    $groups  = array();
    $post_id = get_the_ID();

    // Hub page (blog_topic term archive).
    $topics = get_the_terms( $post_id, 'blog_topic' );
    if ( $topics && ! is_wp_error( $topics ) ) {
        $topic       = $topics[0];
        $hub_links   = array();
        $hub_links[] = array(
            'url'    => get_term_link( $topic ),
            'anchor' => $topic->name,
        );
        $groups[] = array(
            'title' => 'Раздел блога',
            'links' => $hub_links,
        );
    }

    // Related models.
    $model_links = array();
    $tags        = get_the_terms( $post_id, 'post_tag' );
    if ( $tags && ! is_wp_error( $tags ) ) {
        $tag_names = wp_list_pluck( $tags, 'name' );
        $models    = new WP_Query( array(
            'post_type'      => 'car_model',
            'posts_per_page' => 5,
            's'              => implode( ' ', $tag_names ),
        ) );
        if ( $models->have_posts() ) {
            while ( $models->have_posts() ) {
                $models->the_post();
                $model_links[] = array(
                    'url'    => get_permalink(),
                    'anchor' => cf_diversify_anchor( '', get_the_ID() ),
                );
            }
            wp_reset_postdata();
        }
    }
    if ( $model_links ) {
        $groups[] = array(
            'title' => 'Модели в каталоге',
            'links' => $model_links,
        );
    }

    // Related tags.
    $tag_links    = array();
    $catalog_tags = get_terms( array(
        'taxonomy' => 'catalog_tag',
        'number'   => 5,
    ) );
    if ( $catalog_tags && ! is_wp_error( $catalog_tags ) ) {
        foreach ( $catalog_tags as $tag ) {
            $tag_links[] = array(
                'url'    => get_term_link( $tag ),
                'anchor' => $tag->name,
            );
        }
    }
    if ( $tag_links ) {
        $groups[] = array(
            'title' => 'Категории каталога',
            'links' => $tag_links,
        );
    }

    return $groups;
}

/**
 * Interlinking for case study pages.
 */
function cf_interlinking_case_study() {
    $groups  = array();
    $post_id = get_the_ID();

    // Related model.
    $model_id = get_post_meta( $post_id, 'cf_related_model', true );
    if ( $model_id && get_post( $model_id ) ) {
        $groups[] = array(
            'title' => 'Модель автомобиля',
            'links' => array(
                array(
                    'url'    => get_permalink( $model_id ),
                    'anchor' => cf_diversify_anchor( '', $model_id ),
                ),
            ),
        );
    }

    // Country page.
    $countries = get_the_terms( $post_id, 'cf_country' );
    if ( $countries && ! is_wp_error( $countries ) ) {
        $country       = $countries[0];
        $country_page  = cf_get_country_page_url( $country->slug );
        if ( $country_page ) {
            $groups[] = array(
                'title' => 'Импорт из страны',
                'links' => array(
                    array(
                        'url'    => $country_page,
                        'anchor' => $country->name,
                    ),
                ),
            );
        }
    }

    // Service page.
    $services = new WP_Query( array(
        'post_type'      => 'service_page',
        'posts_per_page' => 3,
    ) );
    if ( $services->have_posts() ) {
        $service_links = array();
        while ( $services->have_posts() ) {
            $services->the_post();
            $service_links[] = array(
                'url'    => get_permalink(),
                'anchor' => get_the_title(),
            );
        }
        wp_reset_postdata();
        $groups[] = array(
            'title' => 'Наши услуги',
            'links' => $service_links,
        );
    }

    return $groups;
}

/**
 * Interlinking for service pages.
 */
function cf_interlinking_service() {
    $groups = array();

    // Calculator link.
    $calc_page = get_page_by_path( 'kalkulyator' );
    if ( ! $calc_page ) {
        $calc_page = get_page_by_path( 'calculator' );
    }
    if ( $calc_page ) {
        $groups[] = array(
            'title' => 'Инструменты',
            'links' => array(
                array(
                    'url'    => get_permalink( $calc_page ),
                    'anchor' => 'Калькулятор растаможки',
                ),
            ),
        );
    }

    // Related cases.
    $case_links = array();
    $cases      = new WP_Query( array(
        'post_type'      => 'case_study',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );
    if ( $cases->have_posts() ) {
        while ( $cases->have_posts() ) {
            $cases->the_post();
            $case_links[] = array(
                'url'    => get_permalink(),
                'anchor' => get_the_title(),
            );
        }
        wp_reset_postdata();
    }
    if ( $case_links ) {
        $groups[] = array(
            'title' => 'Кейсы клиентов',
            'links' => $case_links,
        );
    }

    // Related blog posts.
    $blog_links = array();
    $blog_posts = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );
    if ( $blog_posts->have_posts() ) {
        while ( $blog_posts->have_posts() ) {
            $blog_posts->the_post();
            $blog_links[] = array(
                'url'    => get_permalink(),
                'anchor' => get_the_title(),
            );
        }
        wp_reset_postdata();
    }
    if ( $blog_links ) {
        $groups[] = array(
            'title' => 'Статьи по теме',
            'links' => $blog_links,
        );
    }

    return $groups;
}

/**
 * Render the interlinking block.
 *
 * @param string $position 'footer' for full block, 'sidebar' for compact list.
 */
function cf_render_interlinking( $position = 'footer' ) {
    $groups = cf_get_interlinking_data();

    if ( empty( $groups ) ) {
        return;
    }

    echo '<div class="cf-interlinking cf-interlinking--' . esc_attr( $position ) . '">';

    foreach ( $groups as $group ) {
        if ( empty( $group['links'] ) ) {
            continue;
        }

        echo '<div class="cf-interlinking__group">';
        echo '<h3 class="cf-interlinking__title">' . esc_html( $group['title'] ) . '</h3>';
        echo '<ul class="cf-interlinking__list">';

        foreach ( $group['links'] as $link ) {
            echo '<li class="cf-interlinking__item">';
            echo '<a href="' . esc_url( $link['url'] ) . '" class="cf-interlinking__link">';
            echo esc_html( $link['anchor'] );
            echo '</a>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';
    }

    echo '</div>';
}

/**
 * Anchor text rotation for diversified internal links.
 *
 * @param string $template Anchor template with placeholders. Empty string for auto-detect.
 * @param int    $post_id  Post ID to generate anchor for.
 * @return string Generated anchor text.
 */
function cf_diversify_anchor( $template, $post_id ) {
    $model_name   = get_the_title( $post_id );
    $country_name = '';

    $countries = get_the_terms( $post_id, 'cf_country' );
    if ( $countries && ! is_wp_error( $countries ) ) {
        $country_name = $countries[0]->name;
    }

    $variations = array(
        'Купить {model} из {country}',
        '{model} — цена и характеристики',
        'Заказать {model} под ключ',
        '{model} из {country} с доставкой',
    );

    $count = count( $variations );
    $index = $post_id % $count;

    if ( ! empty( $template ) ) {
        $anchor = $template;
    } else {
        $anchor = $variations[ $index ];
    }

    $anchor = str_replace( '{model}', $model_name, $anchor );
    $anchor = str_replace( '{country}', $country_name, $anchor );

    // Clean up if country is empty.
    $anchor = str_replace( ' из ', ' из ', $anchor ); // Keep consistent spacing.
    $anchor = preg_replace( '/\s+из\s*$/', '', $anchor );
    $anchor = preg_replace( '/\s+с доставкой$/', ' с доставкой', $anchor );

    return trim( $anchor );
}

/**
 * Helper: get country term by country page slug.
 *
 * @param string $slug Page slug.
 * @return WP_Term|null
 */
function cf_get_country_term_by_page_slug( $slug ) {
    $map = array(
        'avto-iz-korei'   => 'korea',
        'avto-iz-yaponii' => 'japan',
        'avto-iz-kitaya'  => 'china',
        'avto-iz-ssha'    => 'usa',
        'avto-iz-oae'     => 'uae',
    );

    if ( ! isset( $map[ $slug ] ) ) {
        return null;
    }

    $term = get_term_by( 'slug', $map[ $slug ], 'cf_country' );
    return $term ?: null;
}

/**
 * Helper: get country links for a brand (via its models).
 *
 * @param int $brand_term_id Brand term ID.
 * @return array Links array.
 */
function cf_get_country_links_for_brand( $brand_term_id ) {
    $links  = array();
    $models = get_posts( array(
        'post_type'      => 'car_model',
        'posts_per_page' => 50,
        'fields'         => 'ids',
        'tax_query'      => array(
            array(
                'taxonomy' => 'car_brand',
                'field'    => 'term_id',
                'terms'    => $brand_term_id,
            ),
        ),
    ) );

    $country_ids = array();
    foreach ( $models as $model_id ) {
        $countries = get_the_terms( $model_id, 'cf_country' );
        if ( $countries && ! is_wp_error( $countries ) ) {
            foreach ( $countries as $country ) {
                $country_ids[ $country->term_id ] = $country;
            }
        }
    }

    foreach ( $country_ids as $country ) {
        $page_url = cf_get_country_page_url( $country->slug );
        if ( $page_url ) {
            $links[] = array(
                'url'    => $page_url,
                'anchor' => $country->name,
            );
        }
    }

    return $links;
}

/**
 * Helper: get country links for a car model.
 *
 * @param int $post_id Car model post ID.
 * @return array Links array.
 */
function cf_get_country_links_for_model( $post_id ) {
    $links     = array();
    $countries = get_the_terms( $post_id, 'cf_country' );

    if ( $countries && ! is_wp_error( $countries ) ) {
        foreach ( $countries as $country ) {
            $page_url = cf_get_country_page_url( $country->slug );
            if ( $page_url ) {
                $links[] = array(
                    'url'    => $page_url,
                    'anchor' => $country->name,
                );
            }
        }
    }

    return $links;
}

/**
 * Helper: get country page URL by country term slug.
 *
 * @param string $country_slug Country taxonomy term slug.
 * @return string|null Page URL or null.
 */
function cf_get_country_page_url( $country_slug ) {
    $map = array(
        'korea' => 'avto-iz-korei',
        'japan' => 'avto-iz-yaponii',
        'china' => 'avto-iz-kitaya',
        'usa'   => 'avto-iz-ssha',
        'uae'   => 'avto-iz-oae',
    );

    if ( ! isset( $map[ $country_slug ] ) ) {
        return null;
    }

    $page = get_page_by_path( $map[ $country_slug ] );
    return $page ? get_permalink( $page ) : null;
}
