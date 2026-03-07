<?php
/**
 * Schema.org JSON-LD Generator
 *
 * Outputs structured data via @graph pattern in wp_head.
 * Skips output if Yoast SEO is active.
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper: get ACF field with function_exists guard.
 */
if ( ! function_exists( 'cf_get_field' ) ) {
    function cf_get_field( $field, $post_id = false ) {
        if ( function_exists( 'get_field' ) ) {
            return get_field( $field, $post_id );
        }
        return '';
    }
}

/**
 * Main schema output — hooked to wp_head.
 * Builds @graph array based on current page context.
 */
function cf_schema_output() {
    // Skip if Yoast SEO is active
    if ( defined( 'WPSEO_VERSION' ) ) {
        return;
    }

    $graph = array();

    // Organization on all pages
    $graph[] = cf_schema_organization();

    // Homepage
    if ( is_front_page() ) {
        $graph[] = cf_schema_website();
    }

    // Single car_model
    if ( is_singular( 'car_model' ) ) {
        $graph[] = cf_schema_product( get_the_ID() );
    }

    // Service page or country page
    if ( is_singular( 'cf_service' ) || is_page_template( 'page-templates/country.php' ) ) {
        $graph[] = cf_schema_service();
    }

    // Blog post
    if ( is_singular( 'post' ) ) {
        $graph[] = cf_schema_article( get_the_ID() );
    }

    // Team member
    if ( is_singular( 'cf_team' ) ) {
        $graph[] = cf_schema_person( get_the_ID() );
    }

    // Calculator page
    if ( is_page_template( 'page-templates/calculator.php' ) ) {
        $graph[] = cf_schema_web_application();
    }

    // Case study — Review
    if ( is_singular( 'case_study' ) ) {
        $graph[] = cf_schema_review( get_the_ID() );
    }

    // City page — LocalBusiness
    if ( is_page_template( 'page-templates/city.php' ) ) {
        $graph[] = cf_schema_local_business();
    }

    // Catalog / archive / tag pages — ItemList
    if ( is_post_type_archive( 'car_model' ) || is_tax( 'car_brand' ) || is_tax( 'car_type' ) || is_tax( 'price_range' ) || is_tax( 'car_country' ) || is_tax( 'engine_type' ) ) {
        $items = array();
        if ( have_posts() ) {
            $position = 1;
            while ( have_posts() ) {
                the_post();
                $items[] = array(
                    'name' => get_the_title(),
                    'url'  => get_permalink(),
                );
                $position++;
            }
            rewind_posts();
        }
        if ( ! empty( $items ) ) {
            $graph[] = cf_schema_item_list( $items );
        }
    }

    // Filter out empty entries
    $graph = array_filter( $graph );

    if ( empty( $graph ) ) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@graph'   => array_values( $graph ),
    );

    echo "\n" . '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'cf_schema_output', 1 );

/**
 * Organization schema — all pages.
 */
function cf_schema_organization() {
    $logo_url = '';
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
    }

    $phone = cf_get_field( 'cf_phone', 'option' );
    $email = cf_get_field( 'cf_email', 'option' );

    $org = array(
        '@type'  => 'Organization',
        '@id'    => home_url( '/#organization' ),
        'name'   => 'CarFinance MSK',
        'url'    => home_url( '/' ),
    );

    if ( $logo_url ) {
        $org['logo'] = array(
            '@type'      => 'ImageObject',
            '@id'        => home_url( '/#logo' ),
            'url'        => $logo_url,
            'contentUrl' => $logo_url,
            'caption'    => 'CarFinance MSK',
        );
        $org['image'] = array( '@id' => home_url( '/#logo' ) );
    }

    if ( $phone ) {
        $org['telephone'] = $phone;
    }

    if ( $email ) {
        $org['email'] = $email;
    }

    $org['contactPoint'] = array(
        '@type'             => 'ContactPoint',
        'contactType'       => 'customer service',
        'availableLanguage' => array( 'Russian' ),
    );

    if ( $phone ) {
        $org['contactPoint']['telephone'] = $phone;
    }

    $address = cf_get_field( 'cf_address', 'option' );
    if ( $address ) {
        $org['address'] = array(
            '@type'          => 'PostalAddress',
            'addressCountry' => 'RU',
            'addressLocality' => 'Москва',
            'streetAddress'  => $address,
        );
    }

    return $org;
}

/**
 * WebSite + SearchAction — homepage only.
 */
function cf_schema_website() {
    return array(
        '@type'         => 'WebSite',
        '@id'           => home_url( '/#website' ),
        'url'           => home_url( '/' ),
        'name'          => 'CarFinance MSK',
        'description'   => get_bloginfo( 'description' ),
        'publisher'     => array( '@id' => home_url( '/#organization' ) ),
        'inLanguage'    => 'ru-RU',
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'        => 'EntryPoint',
                'urlTemplate'  => home_url( '/?s={search_term_string}' ),
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );
}

/**
 * Product + Vehicle + Offer — car_model CPT.
 */
function cf_schema_product( $post_id ) {
    $title        = get_the_title( $post_id );
    $url          = get_permalink( $post_id );
    $description  = get_the_excerpt( $post_id );
    $thumbnail    = get_the_post_thumbnail_url( $post_id, 'full' );

    $engine_cc    = cf_get_field( 'cf_engine_cc', $post_id );
    $power_hp     = cf_get_field( 'cf_power_hp', $post_id );
    $year         = cf_get_field( 'cf_year', $post_id );
    $price_from   = cf_get_field( 'cf_price_from', $post_id );
    $price_to     = cf_get_field( 'cf_price_to', $post_id );
    $fuel_type    = cf_get_field( 'cf_fuel_type', $post_id );
    $transmission = cf_get_field( 'cf_transmission', $post_id );
    $drive        = cf_get_field( 'cf_drive', $post_id );

    // Map fuel types to Schema values
    $fuel_map = array(
        'бензин'       => 'https://schema.org/Gasoline',
        'дизель'       => 'https://schema.org/Diesel',
        'электро'      => 'https://schema.org/Electric',
        'гибрид'       => 'https://schema.org/HybridElectric',
        'газ'          => 'https://schema.org/NaturalGas',
    );

    $product = array(
        '@type'       => array( 'Product', 'Vehicle' ),
        '@id'         => $url . '#product',
        'name'        => $title,
        'url'         => $url,
        'description' => $description,
    );

    if ( $thumbnail ) {
        $product['image'] = $thumbnail;
    }

    if ( $year ) {
        $product['vehicleModelDate'] = (string) $year;
        $product['productionDate']   = (string) $year;
    }

    if ( $engine_cc ) {
        $product['vehicleEngine'] = array(
            '@type'              => 'EngineSpecification',
            'engineDisplacement' => array(
                '@type'    => 'QuantitativeValue',
                'value'    => $engine_cc,
                'unitCode' => 'CMQ',
            ),
        );
        if ( $power_hp ) {
            $product['vehicleEngine']['enginePower'] = array(
                '@type'    => 'QuantitativeValue',
                'value'    => $power_hp,
                'unitText' => 'л.с.',
            );
        }
    }

    if ( $fuel_type ) {
        $fuel_lower = mb_strtolower( $fuel_type );
        if ( isset( $fuel_map[ $fuel_lower ] ) ) {
            $product['fuelType'] = $fuel_map[ $fuel_lower ];
        } else {
            $product['fuelType'] = $fuel_type;
        }
    }

    if ( $transmission ) {
        $product['vehicleTransmission'] = $transmission;
    }

    if ( $drive ) {
        $product['driveWheelConfiguration'] = $drive;
    }

    // Brand from taxonomy
    $brands = get_the_terms( $post_id, 'car_brand' );
    if ( $brands && ! is_wp_error( $brands ) ) {
        $brand = reset( $brands );
        $product['brand'] = array(
            '@type' => 'Brand',
            'name'  => $brand->name,
        );
    }

    // Offer
    if ( $price_from ) {
        $offer = array(
            '@type'         => 'AggregateOffer',
            'priceCurrency' => 'RUB',
            'lowPrice'      => $price_from,
            'availability'  => 'https://schema.org/InStock',
            'url'           => $url,
        );
        if ( $price_to ) {
            $offer['highPrice'] = $price_to;
        }
        $product['offers'] = $offer;
    }

    return $product;
}

/**
 * Service schema — for cf_service CPT and country pages.
 */
function cf_schema_service() {
    $post_id     = get_the_ID();
    $title       = get_the_title();
    $url         = get_permalink();
    $description = get_the_excerpt();
    $thumbnail   = get_the_post_thumbnail_url( $post_id, 'full' );

    $service = array(
        '@type'       => 'Service',
        '@id'         => $url . '#service',
        'name'        => $title,
        'url'         => $url,
        'description' => $description ?: $title,
        'provider'    => array( '@id' => home_url( '/#organization' ) ),
        'areaServed'  => array(
            '@type' => 'Country',
            'name'  => 'Россия',
        ),
    );

    if ( $thumbnail ) {
        $service['image'] = $thumbnail;
    }

    $price = cf_get_field( 'cf_service_price', $post_id );
    if ( $price ) {
        $service['offers'] = array(
            '@type'         => 'Offer',
            'price'         => $price,
            'priceCurrency' => 'RUB',
        );
    }

    return $service;
}

/**
 * FAQPage schema from array of Q&A items.
 */
if ( ! function_exists( 'cf_schema_faqpage' ) ) {
    function cf_schema_faqpage( $faq_items ) {
        if ( empty( $faq_items ) || ! is_array( $faq_items ) ) {
            return null;
        }

        $entities = array();
        foreach ( $faq_items as $item ) {
            if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
                continue;
            }
            $entities[] = array(
                '@type'          => 'Question',
                'name'           => $item['question'],
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => $item['answer'],
                ),
            );
        }

        if ( empty( $entities ) ) {
            return null;
        }

        return array(
            '@type'      => 'FAQPage',
            '@id'        => get_permalink() . '#faqpage',
            'mainEntity' => $entities,
        );
    }
}

/**
 * Article schema — blog posts.
 */
function cf_schema_article( $post_id ) {
    $post      = get_post( $post_id );
    $title     = get_the_title( $post_id );
    $url       = get_permalink( $post_id );
    $excerpt   = get_the_excerpt( $post_id );
    $thumbnail = get_the_post_thumbnail_url( $post_id, 'full' );
    $author    = get_userdata( $post->post_author );

    $article = array(
        '@type'            => 'Article',
        '@id'              => $url . '#article',
        'headline'         => mb_substr( $title, 0, 110 ),
        'url'              => $url,
        'description'      => $excerpt,
        'datePublished'    => get_the_date( 'c', $post_id ),
        'dateModified'     => get_the_modified_date( 'c', $post_id ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id'   => $url,
        ),
        'publisher'        => array( '@id' => home_url( '/#organization' ) ),
        'inLanguage'       => 'ru-RU',
    );

    if ( $thumbnail ) {
        $article['image'] = $thumbnail;
    }

    if ( $author ) {
        $article['author'] = array(
            '@type' => 'Person',
            'name'  => $author->display_name,
            'url'   => get_author_posts_url( $author->ID ),
        );
    }

    // Word count
    $content    = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    if ( $word_count > 0 ) {
        $article['wordCount'] = $word_count;
    }

    return $article;
}

/**
 * LocalBusiness schema — city pages (multisite subdomains).
 */
function cf_schema_local_business() {
    $post_id = get_the_ID();
    $title   = get_the_title();
    $url     = get_permalink();

    $address    = cf_get_field( 'cf_city_address', $post_id );
    $city_name  = cf_get_field( 'cf_city_name', $post_id ) ?: $title;
    $latitude   = cf_get_field( 'cf_city_latitude', $post_id );
    $longitude  = cf_get_field( 'cf_city_longitude', $post_id );
    $phone      = cf_get_field( 'cf_city_phone', $post_id ) ?: cf_get_field( 'cf_phone', 'option' );
    $thumbnail  = get_the_post_thumbnail_url( $post_id, 'full' );

    $business = array(
        '@type'           => 'LocalBusiness',
        '@id'             => $url . '#localbusiness',
        'name'            => 'CarFinance MSK — ' . $city_name,
        'url'             => $url,
        'telephone'       => $phone,
        'priceRange'      => '₽₽₽',
        'parentOrganization' => array( '@id' => home_url( '/#organization' ) ),
    );

    if ( $thumbnail ) {
        $business['image'] = $thumbnail;
    }

    if ( $address ) {
        $business['address'] = array(
            '@type'            => 'PostalAddress',
            'addressCountry'   => 'RU',
            'addressLocality'  => $city_name,
            'streetAddress'    => $address,
        );
    }

    if ( $latitude && $longitude ) {
        $business['geo'] = array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => $latitude,
            'longitude' => $longitude,
        );
    }

    // Opening hours
    $business['openingHoursSpecification'] = array(
        '@type'     => 'OpeningHoursSpecification',
        'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
        'opens'     => '09:00',
        'closes'    => '19:00',
    );

    return $business;
}

/**
 * Person schema — cf_team CPT.
 */
function cf_schema_person( $post_id ) {
    $title     = get_the_title( $post_id );
    $url       = get_permalink( $post_id );
    $thumbnail = get_the_post_thumbnail_url( $post_id, 'full' );

    $role      = cf_get_field( 'cf_team_role', $post_id );
    $bio       = get_the_excerpt( $post_id );
    $email     = cf_get_field( 'cf_team_email', $post_id );
    $phone     = cf_get_field( 'cf_team_phone', $post_id );

    $person = array(
        '@type'       => 'Person',
        '@id'         => $url . '#person',
        'name'        => $title,
        'url'         => $url,
        'description' => $bio,
        'worksFor'    => array( '@id' => home_url( '/#organization' ) ),
    );

    if ( $thumbnail ) {
        $person['image'] = $thumbnail;
    }

    if ( $role ) {
        $person['jobTitle'] = $role;
    }

    if ( $email ) {
        $person['email'] = $email;
    }

    if ( $phone ) {
        $person['telephone'] = $phone;
    }

    // Social links
    $social_links = array();
    $vk       = cf_get_field( 'cf_team_vk', $post_id );
    $telegram = cf_get_field( 'cf_team_telegram', $post_id );

    if ( $vk ) {
        $social_links[] = $vk;
    }
    if ( $telegram ) {
        $social_links[] = $telegram;
    }
    if ( ! empty( $social_links ) ) {
        $person['sameAs'] = $social_links;
    }

    return $person;
}

/**
 * BreadcrumbList from array of ['name' => ..., 'url' => ...].
 */
function cf_schema_breadcrumb( $items ) {
    if ( empty( $items ) || ! is_array( $items ) ) {
        return null;
    }

    $list_items = array();
    foreach ( $items as $index => $item ) {
        $list_items[] = array(
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'name'     => $item['name'],
            'item'     => $item['url'],
        );
    }

    return array(
        '@type'           => 'BreadcrumbList',
        '@id'             => get_permalink() . '#breadcrumb',
        'itemListElement' => $list_items,
    );
}

/**
 * ItemList for catalog, tag pages, brand pages.
 */
function cf_schema_item_list( $items ) {
    if ( empty( $items ) || ! is_array( $items ) ) {
        return null;
    }

    $list_items = array();
    foreach ( $items as $index => $item ) {
        $list_items[] = array(
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'name'     => $item['name'],
            'url'      => $item['url'],
        );
    }

    return array(
        '@type'           => 'ItemList',
        '@id'             => get_pagenum_link( 1 ) . '#itemlist',
        'numberOfItems'   => count( $list_items ),
        'itemListElement' => $list_items,
    );
}

/**
 * WebApplication for calculator page.
 */
function cf_schema_web_application() {
    $url = get_permalink();

    return array(
        '@type'              => 'WebApplication',
        '@id'                => $url . '#webapp',
        'name'               => 'Калькулятор стоимости авто из-за рубежа',
        'url'                => $url,
        'applicationCategory' => 'FinanceApplication',
        'operatingSystem'    => 'All',
        'browserRequirements' => 'Requires JavaScript',
        'description'        => 'Калькулятор таможенных пошлин, стоимости владения и конструктора для импорта автомобилей',
        'offers'             => array(
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'RUB',
        ),
        'provider'           => array( '@id' => home_url( '/#organization' ) ),
    );
}

/**
 * Review schema for case_study CPT.
 */
function cf_schema_review( $post_id ) {
    $title     = get_the_title( $post_id );
    $url       = get_permalink( $post_id );
    $excerpt   = get_the_excerpt( $post_id );
    $thumbnail = get_the_post_thumbnail_url( $post_id, 'full' );

    $client_name = cf_get_field( 'cf_case_client_name', $post_id );
    $rating      = cf_get_field( 'cf_case_rating', $post_id );
    $car_name    = cf_get_field( 'cf_case_car_name', $post_id );

    $review = array(
        '@type'         => 'Review',
        '@id'           => $url . '#review',
        'name'          => $title,
        'url'           => $url,
        'description'   => $excerpt,
        'datePublished' => get_the_date( 'c', $post_id ),
    );

    if ( $thumbnail ) {
        $review['image'] = $thumbnail;
    }

    if ( $client_name ) {
        $review['author'] = array(
            '@type' => 'Person',
            'name'  => $client_name,
        );
    }

    if ( $rating ) {
        $review['reviewRating'] = array(
            '@type'       => 'Rating',
            'ratingValue' => $rating,
            'bestRating'  => '5',
            'worstRating' => '1',
        );
    }

    if ( $car_name ) {
        $review['itemReviewed'] = array(
            '@type' => 'Product',
            'name'  => $car_name,
        );
    } else {
        $review['itemReviewed'] = array( '@id' => home_url( '/#organization' ) );
    }

    return $review;
}
