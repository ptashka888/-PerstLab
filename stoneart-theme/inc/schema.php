<?php
/**
 * Schema.org JSON-LD Markup
 *
 * @package StoneArt
 */

defined('ABSPATH') || exit;

function sa_schema_output() {
    $schemas = [];

    // Organization (all pages)
    $schemas[] = [
        '@type'       => 'Organization',
        'name'        => sa_company_name(),
        'url'         => home_url('/'),
        'telephone'   => sa_phone(),
        'email'       => sa_email(),
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => sa_address(),
            'addressLocality' => 'Москва',
            'addressCountry'  => 'RU',
        ],
        'sameAs' => array_filter([
            sa_option('sa_whatsapp'),
            sa_option('sa_telegram'),
            sa_option('sa_vk'),
            sa_option('sa_instagram'),
            sa_option('sa_youtube'),
        ]),
    ];

    // WebSite + SearchAction (homepage)
    if (is_front_page()) {
        $schemas[] = [
            '@type'           => 'WebSite',
            'name'            => sa_company_name(),
            'url'             => home_url('/'),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];

        // LocalBusiness
        $schemas[] = [
            '@type'       => 'LocalBusiness',
            'name'        => sa_company_name(),
            '@id'         => home_url('/#business'),
            'url'         => home_url('/'),
            'telephone'   => sa_phone(),
            'email'       => sa_email(),
            'image'       => sa_option('sa_hero_bg') ? sa_option('sa_hero_bg')['url'] : '',
            'priceRange'  => '₽₽₽',
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => sa_address(),
                'addressLocality' => 'Москва',
                'addressCountry'  => 'RU',
            ],
            'openingHoursSpecification' => [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'opens'     => '09:00',
                'closes'    => '20:00',
            ],
            'aggregateRating' => [
                '@type'       => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '127',
                'bestRating'  => '5',
            ],
        ];
    }

    // Category landing pages (CollectionPage + ItemList)
    if (is_page_template('page-templates/category-landing.php')) {
        $page_title = get_the_title();
        $products   = new WP_Query([
            'post_type'      => 'sa_product',
            'posts_per_page' => 10,
            'tax_query'      => [
                [
                    'taxonomy' => 'sa_product_cat',
                    'field'    => 'name',
                    'terms'    => $page_title,
                ],
            ],
        ]);
        $list_items = [];
        $pos = 1;
        while ($products->have_posts()) {
            $products->the_post();
            $list_items[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => get_the_title(),
                'url'      => get_permalink(),
                'image'    => get_the_post_thumbnail_url(get_the_ID(), 'large') ?: '',
            ];
        }
        wp_reset_postdata();

        $schemas[] = [
            '@type'            => 'CollectionPage',
            'name'             => $page_title,
            'description'      => get_the_excerpt() ?: wp_trim_words(get_the_content(), 40),
            'url'              => get_permalink(),
            'provider'         => ['@type' => 'Organization', 'name' => sa_company_name()],
        ];
        if ($list_items) {
            $schemas[] = [
                '@type'           => 'ItemList',
                'itemListElement' => $list_items,
            ];
        }
    }

    // Material single pages
    if (is_page_template('page-templates/material-single.php')) {
        $schemas[] = [
            '@type'       => 'Product',
            'name'        => get_the_title(),
            'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 40),
            'image'       => get_the_post_thumbnail_url(get_the_ID(), 'large') ?: '',
            'brand'       => ['@type' => 'Brand', 'name' => sa_company_name()],
            'offers'      => [
                '@type'         => 'Offer',
                'availability'  => 'https://schema.org/InStock',
                'priceCurrency' => 'RUB',
                'seller'        => ['@type' => 'Organization', 'name' => sa_company_name()],
            ],
        ];
    }

    // Individual product pages (sa_product)
    if (is_singular('sa_product')) {
        $price_from = function_exists('get_field') ? get_field('sa_product_price_from') : '';
        $price_to   = function_exists('get_field') ? get_field('sa_product_price_to')   : '';
        $offer = [
            '@type'         => 'Offer',
            'availability'  => 'https://schema.org/InStock',
            'priceCurrency' => 'RUB',
            'seller'        => ['@type' => 'Organization', 'name' => sa_company_name()],
        ];
        if ($price_from) {
            $offer['priceSpecification'] = [
                '@type'         => 'UnitPriceSpecification',
                'minPrice'      => $price_from,
                'priceCurrency' => 'RUB',
                'unitText'      => 'м²',
            ];
        }
        $schemas[] = [
            '@type'       => 'Product',
            'name'        => get_the_title(),
            'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 40),
            'image'       => get_the_post_thumbnail_url(get_the_ID(), 'large') ?: '',
            'brand'       => ['@type' => 'Brand', 'name' => sa_company_name()],
            'offers'      => $offer,
        ];
    }

    // Service detail pages
    if (is_page_template('page-templates/service-detail.php')) {
        $schemas[] = [
            '@type'       => 'Service',
            'name'        => get_the_title(),
            'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 40),
            'provider'    => ['@type' => 'Organization', 'name' => sa_company_name()],
            'areaServed'  => 'Москва и Московская область',
            'url'         => get_permalink(),
        ];
    }

    // Geo-landing pages (LocalBusiness)
    if (is_page_template('page-templates/geo-landing.php')) {
        $city = function_exists('get_field') ? get_field('sa_geo_city') : '';
        $schemas[] = [
            '@type'       => 'LocalBusiness',
            'name'        => sa_company_name() . ($city ? ' — ' . $city : ''),
            'url'         => get_permalink(),
            'telephone'   => sa_phone(),
            'areaServed'  => $city ?: 'Москва',
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => sa_address(),
                'addressLocality' => $city ?: 'Москва',
                'addressCountry'  => 'RU',
            ],
        ];
    }

    // Service pages
    if (is_singular('sa_service') || is_page_template('page-templates/services.php')) {
        $schemas[] = [
            '@type'       => 'Service',
            'name'        => is_singular() ? get_the_title() : 'Изделия из камня на заказ',
            'provider'    => [
                '@type' => 'Organization',
                'name'  => sa_company_name(),
            ],
            'areaServed'  => 'Москва и Московская область',
            'description' => is_singular() ? get_the_excerpt() : 'Производство столешниц, подоконников, ступеней из натурального и искусственного камня.',
        ];
    }

    // Portfolio items
    if (is_singular('sa_portfolio')) {
        $schemas[] = [
            '@type'       => 'Product',
            'name'        => get_the_title(),
            'description' => get_the_excerpt(),
            'image'       => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            'brand'       => [
                '@type' => 'Brand',
                'name'  => sa_company_name(),
            ],
            'offers' => [
                '@type'         => 'Offer',
                'availability'  => 'https://schema.org/InStock',
                'priceCurrency' => 'RUB',
            ],
        ];
    }

    // Blog posts (Article)
    if (is_singular('post')) {
        $schemas[] = [
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'author'        => [
                '@type' => 'Person',
                'name'  => get_the_author(),
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => sa_company_name(),
            ],
            'image'         => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            'mainEntityOfPage' => get_permalink(),
        ];
    }

    // FAQ page
    if (is_page_template('page-templates/faq.php')) {
        $faq_items = [];
        $faqs = new WP_Query([
            'post_type'      => 'sa_faq',
            'posts_per_page' => -1,
        ]);
        while ($faqs->have_posts()) {
            $faqs->the_post();
            $faq_items[] = [
                '@type'          => 'Question',
                'name'           => get_the_title(),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => wp_strip_all_tags(get_the_content()),
                ],
            ];
        }
        wp_reset_postdata();

        if ($faq_items) {
            $schemas[] = [
                '@type'      => 'FAQPage',
                'mainEntity' => $faq_items,
            ];
        }
    }

    // BreadcrumbList (inner pages)
    if (!is_front_page()) {
        $breadcrumb_items = [];
        $breadcrumb_items[] = [
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => 'Главная',
            'item'     => home_url('/'),
        ];

        $pos = 2;
        if (is_singular('sa_portfolio')) {
            $breadcrumb_items[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => 'Портфолио',
                'item'     => get_post_type_archive_link('sa_portfolio'),
            ];
        }

        $breadcrumb_items[] = [
            '@type'    => 'ListItem',
            'position' => $pos,
            'name'     => is_singular() || is_page() ? get_the_title() : (is_archive() ? post_type_archive_title('', false) : wp_title('', false)),
        ];

        $schemas[] = [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $breadcrumb_items,
        ];
    }

    // Output
    if ($schemas) {
        $output = [
            '@context' => 'https://schema.org',
            '@graph'   => $schemas,
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
add_action('wp_head', 'sa_schema_output', 5);

/**
 * Custom Nav Walker for header
 */
class SA_Nav_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = implode(' ', $item->classes ?? []);
        $has_children = in_array('menu-item-has-children', $item->classes ?? []);

        if ($depth === 0 && $has_children) {
            $output .= '<div class="sa-nav__dropdown">';
            $output .= '<a href="' . esc_url($item->url) . '" class="sa-nav__link">';
            $output .= esc_html($item->title);
            $output .= ' <i class="fa-solid fa-chevron-down" style="font-size:0.625rem;margin-left:0.25rem;"></i>';
            $output .= '</a>';
            $output .= '<div class="sa-nav__dropdown-menu">';
        } elseif ($depth === 0) {
            $output .= '<a href="' . esc_url($item->url) . '" class="sa-nav__link">';
            $output .= esc_html($item->title);
            $output .= '</a>';
        } else {
            $output .= '<a href="' . esc_url($item->url) . '" class="sa-nav__dropdown-item">';
            $output .= esc_html($item->title);
            $output .= '</a>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $has_children = in_array('menu-item-has-children', $item->classes ?? []);
        if ($depth === 0 && $has_children) {
            $output .= '</div></div>';
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = null) {
        // Dropdown is already opened in start_el
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        // Dropdown is closed in end_el
    }
}
