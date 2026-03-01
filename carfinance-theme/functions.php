<?php
/**
 * CarFinance MSK Theme Functions
 *
 * Custom post types, taxonomies, menus, Schema.org,
 * SILO navigation, calculators, and theme setup.
 *
 * @package CarFinance
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('CF_VERSION', '1.0.0');
define('CF_DIR', get_template_directory());
define('CF_URI', get_template_directory_uri());

/* ==========================================================================
   1. Theme Setup
   ========================================================================== */

add_action('after_setup_theme', function () {
    load_theme_textdomain('carfinance', CF_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('editor-styles');

    // Custom image sizes
    add_image_size('cf-card', 600, 375, true);       // 16:10
    add_image_size('cf-lot', 480, 360, true);         // 4:3
    add_image_size('cf-hero', 1200, 600, true);       // Hero banner
    add_image_size('cf-team', 240, 240, true);        // Team avatars
    add_image_size('cf-gallery', 800, 600, true);     // Case gallery

    // Navigation menus
    register_nav_menus([
        'primary'   => 'Основное меню',
        'countries' => 'Меню стран',
        'footer_1'  => 'Подвал — Направления',
        'footer_2'  => 'Подвал — Услуги',
        'footer_3'  => 'Подвал — Информация',
    ]);
});

/* ==========================================================================
   2. Enqueue Scripts & Styles
   ========================================================================== */

add_action('wp_enqueue_scripts', function () {
    // Fonts — preconnect
    wp_enqueue_style('cf-fonts-preconnect', 'https://fonts.googleapis.com', [], null);
    wp_enqueue_style('cf-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap', [], null);

    // Theme styles
    wp_enqueue_style('cf-style', get_stylesheet_uri(), [], CF_VERSION);

    // Calculator JS — deferred
    wp_enqueue_script('cf-calculator', CF_URI . '/assets/js/calculator.js', [], CF_VERSION, true);
    wp_localize_script('cf-calculator', 'cfCalcData', [
        'ajaxUrl'  => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('cf_calc_nonce'),
        'currency' => 'RUB',
    ]);

    // Main JS — deferred
    wp_enqueue_script('cf-main', CF_URI . '/assets/js/main.js', [], CF_VERSION, true);
});

/* ==========================================================================
   3. Custom Post Types
   ========================================================================== */

add_action('init', 'cf_register_post_types');

function cf_register_post_types(): void {
    // Car Model
    register_post_type('car_model', [
        'labels' => [
            'name'          => 'Модели авто',
            'singular_name' => 'Модель авто',
            'add_new'       => 'Добавить модель',
            'add_new_item'  => 'Новая модель авто',
            'edit_item'     => 'Редактировать модель',
            'all_items'     => 'Все модели',
        ],
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'catalog', 'with_front' => false],
        'menu_icon'     => 'dashicons-car',
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'],
        'show_in_rest'  => true,
    ]);

    // Auction Lot
    register_post_type('auction_lot', [
        'labels' => [
            'name'          => 'Аукционные лоты',
            'singular_name' => 'Аукционный лот',
            'add_new'       => 'Добавить лот',
            'add_new_item'  => 'Новый лот',
            'edit_item'     => 'Редактировать лот',
            'all_items'     => 'Все лоты',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'auctions', 'with_front' => false],
        'menu_icon'    => 'dashicons-hammer',
        'supports'     => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);

    // Case Study
    register_post_type('case_study', [
        'labels' => [
            'name'          => 'Кейсы',
            'singular_name' => 'Кейс',
            'add_new'       => 'Добавить кейс',
            'add_new_item'  => 'Новый кейс',
            'edit_item'     => 'Редактировать кейс',
            'all_items'     => 'Все кейсы',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'kejsy', 'with_front' => false],
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest' => true,
    ]);

    // Service
    register_post_type('cf_service', [
        'labels' => [
            'name'          => 'Услуги',
            'singular_name' => 'Услуга',
            'add_new'       => 'Добавить услугу',
            'all_items'     => 'Все услуги',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'services', 'with_front' => false],
        'menu_icon'    => 'dashicons-admin-tools',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        'show_in_rest' => true,
    ]);

    // FAQ
    register_post_type('cf_faq', [
        'labels' => [
            'name'          => 'FAQ',
            'singular_name' => 'Вопрос-ответ',
            'add_new'       => 'Добавить вопрос',
            'all_items'     => 'Все вопросы',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => false,
        'menu_icon'    => 'dashicons-editor-help',
        'supports'     => ['title', 'editor', 'custom-fields', 'page-attributes'],
        'show_in_rest' => true,
    ]);

    // Team Member
    register_post_type('cf_team', [
        'labels' => [
            'name'          => 'Команда',
            'singular_name' => 'Сотрудник',
            'add_new'       => 'Добавить сотрудника',
            'all_items'     => 'Вся команда',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => false,
        'menu_icon'    => 'dashicons-groups',
        'supports'     => ['title', 'thumbnail', 'editor', 'custom-fields', 'page-attributes'],
        'show_in_rest' => true,
    ]);

    // Review / Testimonial
    register_post_type('cf_review', [
        'labels' => [
            'name'          => 'Отзывы',
            'singular_name' => 'Отзыв',
            'add_new'       => 'Добавить отзыв',
            'all_items'     => 'Все отзывы',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => false,
        'menu_icon'    => 'dashicons-star-filled',
        'supports'     => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);
}

/* ==========================================================================
   4. Custom Taxonomies
   ========================================================================== */

add_action('init', 'cf_register_taxonomies');

function cf_register_taxonomies(): void {
    // Country
    register_taxonomy('cf_country', ['car_model', 'auction_lot', 'case_study', 'cf_review'], [
        'labels' => [
            'name'          => 'Страна',
            'singular_name' => 'Страна',
            'add_new_item'  => 'Добавить страну',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'country', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // Car Brand
    register_taxonomy('cf_brand', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Марка',
            'singular_name' => 'Марка',
            'add_new_item'  => 'Добавить марку',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'catalog', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // Body Type
    register_taxonomy('cf_body_type', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Тип кузова',
            'singular_name' => 'Тип кузова',
            'add_new_item'  => 'Добавить тип кузова',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'catalog', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // Price Range
    register_taxonomy('cf_price_range', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Ценовой диапазон',
            'singular_name' => 'Ценовой диапазон',
            'add_new_item'  => 'Добавить диапазон',
        ],
        'public'       => true,
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'catalog/price', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // FAQ Category
    register_taxonomy('cf_faq_cat', ['cf_faq'], [
        'labels' => [
            'name'          => 'Раздел FAQ',
            'singular_name' => 'Раздел FAQ',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => false,
        'show_in_rest' => true,
    ]);

    // Blog Category (SILO clusters)
    register_taxonomy('cf_blog_cluster', ['post'], [
        'labels' => [
            'name'          => 'Кластер блога',
            'singular_name' => 'Кластер',
            'add_new_item'  => 'Добавить кластер',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'blog', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // City
    register_taxonomy('cf_city', ['case_study', 'cf_review'], [
        'labels' => [
            'name'          => 'Город',
            'singular_name' => 'Город',
        ],
        'public'       => true,
        'hierarchical' => false,
        'rewrite'      => false,
        'show_in_rest' => true,
    ]);
}

/* ==========================================================================
   5. Custom Meta Boxes (Car Model / Lot fields)
   ========================================================================== */

add_action('add_meta_boxes', 'cf_add_meta_boxes');

function cf_add_meta_boxes(): void {
    // Car Model meta
    add_meta_box('cf_model_details', 'Характеристики модели', 'cf_model_meta_html', 'car_model', 'normal', 'high');
    // Auction Lot meta
    add_meta_box('cf_lot_details', 'Данные лота', 'cf_lot_meta_html', 'auction_lot', 'normal', 'high');
    // Case Study meta
    add_meta_box('cf_case_details', 'Данные кейса', 'cf_case_meta_html', 'case_study', 'normal', 'high');
    // Team member meta
    add_meta_box('cf_team_details', 'Данные сотрудника', 'cf_team_meta_html', 'cf_team', 'normal', 'high');
    // Review meta
    add_meta_box('cf_review_details', 'Данные отзыва', 'cf_review_meta_html', 'cf_review', 'normal', 'high');
}

function cf_model_meta_html($post): void {
    wp_nonce_field('cf_model_meta', 'cf_model_nonce');
    $fields = [
        'cf_year_from'     => ['label' => 'Год выпуска (от)',   'type' => 'number'],
        'cf_year_to'       => ['label' => 'Год выпуска (до)',   'type' => 'number'],
        'cf_engine'        => ['label' => 'Двигатель',          'type' => 'text'],
        'cf_power_hp'      => ['label' => 'Мощность (л.с.)',    'type' => 'number'],
        'cf_transmission'  => ['label' => 'КПП',                'type' => 'text'],
        'cf_drive'         => ['label' => 'Привод',             'type' => 'text'],
        'cf_price_from'    => ['label' => 'Цена от (руб)',      'type' => 'number'],
        'cf_price_to'      => ['label' => 'Цена до (руб)',      'type' => 'number'],
        'cf_generation'    => ['label' => 'Поколение',          'type' => 'text'],
        'cf_reliability'   => ['label' => 'Оценка надёжности (1-10)', 'type' => 'number'],
    ];
    cf_render_meta_fields($post, $fields);
}

function cf_lot_meta_html($post): void {
    wp_nonce_field('cf_lot_meta', 'cf_lot_nonce');
    $fields = [
        'cf_lot_number'    => ['label' => 'Номер лота',       'type' => 'text'],
        'cf_lot_auction'   => ['label' => 'Аукцион',          'type' => 'text'],
        'cf_lot_year'      => ['label' => 'Год выпуска',      'type' => 'number'],
        'cf_lot_mileage'   => ['label' => 'Пробег (км)',      'type' => 'number'],
        'cf_lot_engine_cc' => ['label' => 'Объём двигателя (cc)', 'type' => 'number'],
        'cf_lot_price_jpy' => ['label' => 'Цена (JPY/KRW/CNY)', 'type' => 'number'],
        'cf_lot_price_rub' => ['label' => 'Цена под ключ (руб)', 'type' => 'number'],
        'cf_lot_grade'     => ['label' => 'Оценка',           'type' => 'text'],
        'cf_lot_color'     => ['label' => 'Цвет',             'type' => 'text'],
        'cf_lot_status'    => ['label' => 'Статус (active/sold)', 'type' => 'text'],
    ];
    cf_render_meta_fields($post, $fields);
}

function cf_case_meta_html($post): void {
    wp_nonce_field('cf_case_meta', 'cf_case_nonce');
    $fields = [
        'cf_case_model'    => ['label' => 'Модель авто',       'type' => 'text'],
        'cf_case_budget'   => ['label' => 'Бюджет клиента',    'type' => 'text'],
        'cf_case_found'    => ['label' => 'Что нашли / проблемы', 'type' => 'text'],
        'cf_case_savings'  => ['label' => 'Экономия (руб)',    'type' => 'number'],
        'cf_case_duration' => ['label' => 'Срок подбора',      'type' => 'text'],
    ];
    cf_render_meta_fields($post, $fields);
}

function cf_team_meta_html($post): void {
    wp_nonce_field('cf_team_meta', 'cf_team_nonce');
    $fields = [
        'cf_team_role'       => ['label' => 'Должность',      'type' => 'text'],
        'cf_team_experience' => ['label' => 'Опыт (лет)',     'type' => 'number'],
        'cf_team_telegram'   => ['label' => 'Telegram',       'type' => 'text'],
        'cf_team_instagram'  => ['label' => 'Instagram',      'type' => 'text'],
    ];
    cf_render_meta_fields($post, $fields);
}

function cf_review_meta_html($post): void {
    wp_nonce_field('cf_review_meta', 'cf_review_nonce');
    $fields = [
        'cf_review_author' => ['label' => 'Имя клиента',     'type' => 'text'],
        'cf_review_model'  => ['label' => 'Модель авто',      'type' => 'text'],
        'cf_review_rating' => ['label' => 'Рейтинг (1-5)',    'type' => 'number'],
        'cf_review_video'  => ['label' => 'YouTube URL',      'type' => 'url'],
    ];
    cf_render_meta_fields($post, $fields);
}

/**
 * Render meta box fields.
 */
function cf_render_meta_fields($post, array $fields): void {
    echo '<table class="form-table"><tbody>';
    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        $type  = $field['type'];
        printf(
            '<tr><th><label for="%1$s">%2$s</label></th><td><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" class="regular-text" /></td></tr>',
            esc_attr($key),
            esc_html($field['label']),
            esc_attr($type),
            esc_attr($value)
        );
    }
    echo '</tbody></table>';
}

/**
 * Save meta box fields.
 */
add_action('save_post', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $nonce_map = [
        'cf_model_nonce'  => 'cf_model_meta',
        'cf_lot_nonce'    => 'cf_lot_meta',
        'cf_case_nonce'   => 'cf_case_meta',
        'cf_team_nonce'   => 'cf_team_meta',
        'cf_review_nonce' => 'cf_review_meta',
    ];

    foreach ($nonce_map as $nonce_field => $nonce_action) {
        if (isset($_POST[$nonce_field]) && wp_verify_nonce($_POST[$nonce_field], $nonce_action)) {
            break;
        }
    }

    $meta_keys = [
        // Model
        'cf_year_from', 'cf_year_to', 'cf_engine', 'cf_power_hp', 'cf_transmission',
        'cf_drive', 'cf_price_from', 'cf_price_to', 'cf_generation', 'cf_reliability',
        // Lot
        'cf_lot_number', 'cf_lot_auction', 'cf_lot_year', 'cf_lot_mileage',
        'cf_lot_engine_cc', 'cf_lot_price_jpy', 'cf_lot_price_rub', 'cf_lot_grade',
        'cf_lot_color', 'cf_lot_status',
        // Case
        'cf_case_model', 'cf_case_budget', 'cf_case_found', 'cf_case_savings', 'cf_case_duration',
        // Team
        'cf_team_role', 'cf_team_experience', 'cf_team_telegram', 'cf_team_instagram',
        // Review
        'cf_review_author', 'cf_review_model', 'cf_review_rating', 'cf_review_video',
    ];

    foreach ($meta_keys as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
});

/* ==========================================================================
   6. Widgets
   ========================================================================== */

add_action('widgets_init', function () {
    register_sidebar([
        'name'          => 'Сайдбар блога',
        'id'            => 'blog-sidebar',
        'before_widget' => '<div class="cf-sidebar__widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ]);

    register_sidebar([
        'name'          => 'Сайдбар каталога',
        'id'            => 'catalog-sidebar',
        'before_widget' => '<div class="cf-sidebar__widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ]);
});

/* ==========================================================================
   7. Breadcrumbs
   ========================================================================== */

function cf_breadcrumbs(): void {
    if (is_front_page()) return;

    $items = [['url' => home_url('/'), 'name' => 'Главная']];

    if (is_singular('car_model')) {
        $items[] = ['url' => get_post_type_archive_link('car_model'), 'name' => 'Каталог'];
        $brands = get_the_terms(get_the_ID(), 'cf_brand');
        if ($brands && !is_wp_error($brands)) {
            $brand = $brands[0];
            $items[] = ['url' => get_term_link($brand), 'name' => $brand->name];
        }
        $items[] = ['name' => get_the_title()];
    } elseif (is_singular('auction_lot')) {
        $items[] = ['url' => get_post_type_archive_link('auction_lot'), 'name' => 'Аукционы'];
        $items[] = ['name' => get_the_title()];
    } elseif (is_singular('case_study')) {
        $items[] = ['url' => get_post_type_archive_link('case_study'), 'name' => 'Кейсы'];
        $items[] = ['name' => get_the_title()];
    } elseif (is_singular('post')) {
        $items[] = ['url' => get_permalink(get_option('page_for_posts')), 'name' => 'Блог'];
        $items[] = ['name' => get_the_title()];
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        foreach (array_reverse($ancestors) as $ancestor_id) {
            $items[] = ['url' => get_permalink($ancestor_id), 'name' => get_the_title($ancestor_id)];
        }
        $items[] = ['name' => get_the_title()];
    } elseif (is_post_type_archive()) {
        $items[] = ['name' => post_type_archive_title('', false)];
    } elseif (is_tax() || is_category() || is_tag()) {
        $items[] = ['name' => single_term_title('', false)];
    } elseif (is_search()) {
        $items[] = ['name' => 'Результаты поиска'];
    } elseif (is_404()) {
        $items[] = ['name' => 'Страница не найдена'];
    }

    // Schema.org BreadcrumbList
    echo '<nav class="cf-breadcrumbs" aria-label="Навигация"><div class="cf-container">';
    echo '<ol class="cf-breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">';

    foreach ($items as $i => $item) {
        $pos = $i + 1;
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        if (isset($item['url'])) {
            printf('<a itemprop="item" href="%s"><span itemprop="name">%s</span></a>',
                esc_url($item['url']),
                esc_html($item['name'])
            );
        } else {
            printf('<span itemprop="name">%s</span>', esc_html($item['name']));
        }
        printf('<meta itemprop="position" content="%d" />', $pos);
        echo '</li>';
    }

    echo '</ol></div></nav>';
}

/* ==========================================================================
   8. Schema.org JSON-LD Output
   ========================================================================== */

add_action('wp_head', 'cf_schema_output');

function cf_schema_output(): void {
    $schemas = [];

    // Organization — always
    $schemas[] = [
        '@type'       => 'Organization',
        'name'        => 'CarFinance MSK',
        'url'         => home_url('/'),
        'logo'        => CF_URI . '/assets/img/logo.png',
        'description' => 'Импорт и подбор автомобилей из Кореи, Японии, Китая, США, ОАЭ',
        'address'     => [
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Москва',
            'addressCountry'  => 'RU',
        ],
        'contactPoint' => [
            '@type'       => 'ContactPoint',
            'telephone'   => '+7-XXX-XXX-XX-XX',
            'contactType' => 'customer service',
            'areaServed'  => 'RU',
        ],
        'sameAs' => [
            'https://t.me/carfinance_msk',
            'https://www.instagram.com/carfinance_msk/',
        ],
    ];

    // WebSite — homepage
    if (is_front_page()) {
        $schemas[] = [
            '@type'           => 'WebSite',
            'url'             => home_url('/'),
            'name'            => 'CarFinance MSK',
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    // Product — single car_model
    if (is_singular('car_model')) {
        $post_id   = get_the_ID();
        $price_from = get_post_meta($post_id, 'cf_price_from', true);

        $product = [
            '@type'       => 'Product',
            'name'        => get_the_title(),
            'description' => get_the_excerpt(),
            'image'       => get_the_post_thumbnail_url($post_id, 'cf-hero'),
        ];

        $brands = get_the_terms($post_id, 'cf_brand');
        if ($brands && !is_wp_error($brands)) {
            $product['brand'] = ['@type' => 'Brand', 'name' => $brands[0]->name];
        }

        if ($price_from) {
            $product['offers'] = [
                '@type'         => 'Offer',
                'price'         => $price_from,
                'priceCurrency' => 'RUB',
                'availability'  => 'https://schema.org/InStock',
            ];
        }

        $schemas[] = $product;
    }

    // Service — single cf_service or country pages
    if (is_singular('cf_service') || is_page_template('page-templates/country.php')) {
        $schemas[] = [
            '@type'       => 'Service',
            'name'        => get_the_title(),
            'description' => get_the_excerpt(),
            'provider'    => ['@type' => 'Organization', 'name' => 'CarFinance MSK'],
            'areaServed'  => 'RU',
        ];
    }

    // FAQPage — pages with FAQ block
    $faqs = cf_get_page_faqs();
    if (!empty($faqs)) {
        $faq_entities = [];
        foreach ($faqs as $faq) {
            $faq_entities[] = [
                '@type'          => 'Question',
                'name'           => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $faq['answer'],
                ],
            ];
        }
        $schemas[] = [
            '@type'      => 'FAQPage',
            'mainEntity' => $faq_entities,
        ];
    }

    // Article — single blog post
    if (is_singular('post')) {
        $schemas[] = [
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'image'         => get_the_post_thumbnail_url(get_the_ID(), 'cf-hero'),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'author'        => [
                '@type' => 'Person',
                'name'  => get_the_author(),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name'  => 'CarFinance MSK',
                'logo'  => ['@type' => 'ImageObject', 'url' => CF_URI . '/assets/img/logo.png'],
            ],
        ];
    }

    // LocalBusiness — city page
    if (is_page_template('page-templates/city.php')) {
        $city_name = get_post_meta(get_the_ID(), 'cf_city_name', true);
        $schemas[] = [
            '@type'         => 'LocalBusiness',
            'name'          => 'CarFinance MSK — ' . $city_name,
            'url'           => get_permalink(),
            'address'       => [
                '@type'           => 'PostalAddress',
                'addressLocality' => $city_name,
                'addressCountry'  => 'RU',
            ],
        ];
    }

    // Person — team/author page
    if (is_singular('cf_team')) {
        $post_id = get_the_ID();
        $schemas[] = [
            '@type'    => 'Person',
            'name'     => get_the_title(),
            'jobTitle' => get_post_meta($post_id, 'cf_team_role', true),
            'image'    => get_the_post_thumbnail_url($post_id, 'cf-team'),
            'sameAs'   => array_filter([
                get_post_meta($post_id, 'cf_team_telegram', true),
                get_post_meta($post_id, 'cf_team_instagram', true),
            ]),
        ];
    }

    if (!empty($schemas)) {
        $output = [
            '@context' => 'https://schema.org',
        ];

        if (count($schemas) === 1) {
            $output = array_merge($output, $schemas[0]);
        } else {
            $output['@graph'] = $schemas;
        }

        printf(
            '<script type="application/ld+json">%s</script>' . "\n",
            wp_json_encode($output, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }
}

/**
 * Get FAQ items for current page (from cf_faq post type or custom field).
 */
function cf_get_page_faqs(): array {
    $faqs = [];

    // Check custom field first
    $page_faqs = get_post_meta(get_the_ID(), 'cf_faqs', true);
    if (is_array($page_faqs)) {
        return $page_faqs;
    }

    // Get from cf_faq post type if on FAQ page
    if (is_page_template('page-templates/faq.php')) {
        $faq_posts = get_posts([
            'post_type'      => 'cf_faq',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);
        foreach ($faq_posts as $faq_post) {
            $faqs[] = [
                'question' => $faq_post->post_title,
                'answer'   => wp_strip_all_tags($faq_post->post_content),
            ];
        }
    }

    return $faqs;
}

/* ==========================================================================
   9. Calculator AJAX Handler
   ========================================================================== */

add_action('wp_ajax_cf_calculate', 'cf_calculate_handler');
add_action('wp_ajax_nopriv_cf_calculate', 'cf_calculate_handler');

function cf_calculate_handler(): void {
    check_ajax_referer('cf_calc_nonce', 'nonce');

    $country    = sanitize_text_field($_POST['country'] ?? 'korea');
    $price_fob  = floatval($_POST['price_fob'] ?? 0);
    $engine_cc  = intval($_POST['engine_cc'] ?? 2000);
    $year       = intval($_POST['year'] ?? 2020);
    $fuel_type  = sanitize_text_field($_POST['fuel_type'] ?? 'gasoline');

    $age = date('Y') - $year;

    // Shipping cost by country
    $shipping = [
        'korea' => 120000,
        'japan' => 100000,
        'china' => 150000,
        'usa'   => 350000,
        'uae'   => 250000,
    ];

    $freight = $shipping[$country] ?? 150000;

    // Customs duty calculation (simplified rates)
    $customs_duty = 0;
    if ($age <= 3) {
        // New car rates based on price
        if ($price_fob <= 730000) {
            $customs_duty = max($price_fob * 0.54, $engine_cc * 2.5);
        } elseif ($price_fob <= 1500000) {
            $customs_duty = max($price_fob * 0.48, $engine_cc * 3.5);
        } else {
            $customs_duty = max($price_fob * 0.48, $engine_cc * 5.5);
        }
    } elseif ($age <= 5) {
        // 3-5 years
        if ($engine_cc <= 1000) {
            $customs_duty = $engine_cc * 1.5;
        } elseif ($engine_cc <= 1500) {
            $customs_duty = $engine_cc * 1.7;
        } elseif ($engine_cc <= 1800) {
            $customs_duty = $engine_cc * 2.5;
        } elseif ($engine_cc <= 2300) {
            $customs_duty = $engine_cc * 2.7;
        } elseif ($engine_cc <= 3000) {
            $customs_duty = $engine_cc * 3.0;
        } else {
            $customs_duty = $engine_cc * 3.6;
        }
    } else {
        // Over 5 years
        if ($engine_cc <= 1000) {
            $customs_duty = $engine_cc * 3.0;
        } elseif ($engine_cc <= 1500) {
            $customs_duty = $engine_cc * 3.2;
        } elseif ($engine_cc <= 1800) {
            $customs_duty = $engine_cc * 3.5;
        } elseif ($engine_cc <= 2300) {
            $customs_duty = $engine_cc * 4.8;
        } elseif ($engine_cc <= 3000) {
            $customs_duty = $engine_cc * 5.0;
        } else {
            $customs_duty = $engine_cc * 5.7;
        }
    }

    // VAT (NDS) is included in customs calculation for physical persons
    // Utilization fee (util'sbor)
    $util_base = ($age <= 3) ? 20000 : 20000;
    $util_coeff = 1;
    if ($engine_cc <= 1000)      $util_coeff = ($age <= 3) ? 0.17 : 0.26;
    elseif ($engine_cc <= 2000)  $util_coeff = ($age <= 3) ? 4.2 : 15.69;
    elseif ($engine_cc <= 3000)  $util_coeff = ($age <= 3) ? 6.3 : 24.01;
    elseif ($engine_cc <= 3500)  $util_coeff = ($age <= 3) ? 5.73 : 28.5;
    else                         $util_coeff = ($age <= 3) ? 9.08 : 35.01;

    $util_fee = $util_base * $util_coeff;

    // SBKTS + EPTS
    $sbkts = 25000;
    $epts  = 600;

    // Broker / Customs processing
    $broker = 30000;

    // Company commission
    $commission = max($price_fob * 0.05, 50000);

    $total = $price_fob + $freight + $customs_duty + $util_fee + $sbkts + $epts + $broker + $commission;

    wp_send_json_success([
        'price_fob'    => round($price_fob),
        'freight'      => round($freight),
        'customs_duty' => round($customs_duty),
        'util_fee'     => round($util_fee),
        'sbkts'        => round($sbkts),
        'epts'         => round($epts),
        'broker'       => round($broker),
        'commission'   => round($commission),
        'total'        => round($total),
    ]);
}

/* ==========================================================================
   10. SILO Internal Linking Helpers
   ========================================================================== */

/**
 * Get related models in same budget (SILO cross-link).
 */
function cf_get_related_models(int $post_id, int $limit = 4): array {
    $price_from = (int) get_post_meta($post_id, 'cf_price_from', true);
    $price_to   = (int) get_post_meta($post_id, 'cf_price_to', true);

    if (!$price_from && !$price_to) return [];

    $avg = ($price_from + $price_to) / 2;
    $range_low  = $avg * 0.7;
    $range_high = $avg * 1.3;

    return get_posts([
        'post_type'      => 'car_model',
        'posts_per_page' => $limit,
        'post__not_in'   => [$post_id],
        'meta_query'     => [
            [
                'key'     => 'cf_price_from',
                'value'   => [$range_low, $range_high],
                'type'    => 'NUMERIC',
                'compare' => 'BETWEEN',
            ],
        ],
    ]);
}

/**
 * Get active auction lots for a model.
 */
function cf_get_model_lots(int $model_id, int $limit = 6): array {
    $brand = '';
    $brands = get_the_terms($model_id, 'cf_brand');
    if ($brands && !is_wp_error($brands)) {
        $brand = $brands[0]->slug;
    }

    if (!$brand) return [];

    return get_posts([
        'post_type'      => 'auction_lot',
        'posts_per_page' => $limit,
        'meta_query'     => [
            [
                'key'   => 'cf_lot_status',
                'value' => 'active',
            ],
        ],
        'tax_query' => [
            [
                'taxonomy' => 'cf_brand',
                'field'    => 'slug',
                'terms'    => $brand,
            ],
        ],
    ]);
}

/**
 * Country data helper.
 */
function cf_get_country_data(string $slug): array {
    $countries = [
        'korea' => [
            'name'     => 'Корея',
            'flag'     => "\u{1F1F0}\u{1F1F7}",
            'url'      => '/korea/',
            'color'    => '#003478',
            'hero_css' => 'cf-country-hero--korea',
        ],
        'japan' => [
            'name'     => 'Япония',
            'flag'     => "\u{1F1EF}\u{1F1F5}",
            'url'      => '/japan/',
            'color'    => '#bc002d',
            'hero_css' => 'cf-country-hero--japan',
        ],
        'china' => [
            'name'     => 'Китай',
            'flag'     => "\u{1F1E8}\u{1F1F3}",
            'url'      => '/china/',
            'color'    => '#de2910',
            'hero_css' => 'cf-country-hero--china',
        ],
        'usa' => [
            'name'     => 'США',
            'flag'     => "\u{1F1FA}\u{1F1F8}",
            'url'      => '/usa/',
            'color'    => '#3c3b6e',
            'hero_css' => 'cf-country-hero--usa',
        ],
        'uae' => [
            'name'     => 'ОАЭ',
            'flag'     => "\u{1F1E6}\u{1F1EA}",
            'url'      => '/uae/',
            'color'    => '#00732f',
            'hero_css' => 'cf-country-hero--uae',
        ],
    ];

    return $countries[$slug] ?? [];
}

/* ==========================================================================
   11. Performance & Security
   ========================================================================== */

// Remove unnecessary WordPress head junk
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

// Disable emojis for performance
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
});

// Add preconnect hints
add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
}, 1);

/* ==========================================================================
   12. Custom Rewrite Rules for SILO URLs
   ========================================================================== */

add_action('init', 'cf_custom_rewrite_rules');

function cf_custom_rewrite_rules(): void {
    // Country pages: /korea/, /japan/, /china/, /usa/, /uae/
    $countries = ['korea', 'japan', 'china', 'usa', 'uae'];
    foreach ($countries as $country) {
        add_rewrite_rule(
            '^' . $country . '/?$',
            'index.php?pagename=' . $country,
            'top'
        );
    }

    // Calculator
    add_rewrite_rule('^calculator/?$', 'index.php?pagename=calculator', 'top');

    // SILO pillar pages
    $pillars = [
        'kupit-avto-s-probegom',
        'avtopodborshchik',
        'avto-iz-yaponii',
        'avto-iz-korei',
        'avto-iz-kitaya',
        'avto-iz-oae',
        'elektromobili',
        'mototsikly',
    ];

    foreach ($pillars as $pillar) {
        add_rewrite_rule(
            '^' . $pillar . '/?$',
            'index.php?pagename=' . $pillar,
            'top'
        );
        // Sub-pages: /avtopodborshchik/kak-rabotaet/
        add_rewrite_rule(
            '^' . $pillar . '/([^/]+)/?$',
            'index.php?pagename=$matches[1]',
            'top'
        );
    }
}

/* ==========================================================================
   13. Template Part Helpers
   ========================================================================== */

/**
 * Render a section from template-parts/sections/.
 */
function cf_section(string $name, array $args = []): void {
    get_template_part('template-parts/sections/' . $name, null, $args);
}

/**
 * Format price in Russian locale.
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

/* ==========================================================================
   14. Admin Customization
   ========================================================================== */

// Add columns to car_model admin list
add_filter('manage_car_model_posts_columns', function ($columns) {
    $new = [];
    foreach ($columns as $key => $value) {
        $new[$key] = $value;
        if ($key === 'title') {
            $new['cf_brand_col']  = 'Марка';
            $new['cf_country_col'] = 'Страна';
            $new['cf_price_col']   = 'Цена';
        }
    }
    return $new;
});

add_action('manage_car_model_posts_custom_column', function ($column, $post_id) {
    if ($column === 'cf_brand_col') {
        $terms = get_the_terms($post_id, 'cf_brand');
        echo $terms ? esc_html($terms[0]->name) : '—';
    }
    if ($column === 'cf_country_col') {
        $terms = get_the_terms($post_id, 'cf_country');
        echo $terms ? esc_html($terms[0]->name) : '—';
    }
    if ($column === 'cf_price_col') {
        $price = get_post_meta($post_id, 'cf_price_from', true);
        echo $price ? cf_format_price((int) $price) : '—';
    }
}, 10, 2);

/* ==========================================================================
   15. Theme Activation: Create Default Pages
   ========================================================================== */

add_action('after_switch_theme', 'cf_create_default_pages');

function cf_create_default_pages(): void {
    $pages = [
        'korea'      => ['title' => 'Авто из Кореи',  'template' => 'page-templates/country.php'],
        'japan'      => ['title' => 'Авто из Японии',  'template' => 'page-templates/country.php'],
        'china'      => ['title' => 'Авто из Китая',   'template' => 'page-templates/country.php'],
        'usa'        => ['title' => 'Авто из США',     'template' => 'page-templates/country.php'],
        'uae'        => ['title' => 'Авто из ОАЭ',    'template' => 'page-templates/country.php'],
        'calculator' => ['title' => 'Калькулятор',      'template' => 'page-templates/calculator.php'],
        'services'   => ['title' => 'Услуги',           'template' => 'page-templates/services.php'],
        'o-kompanii' => ['title' => 'О компании',        'template' => 'page-templates/about.php'],
        'faq'        => ['title' => 'FAQ',               'template' => 'page-templates/faq.php'],
        'blog'       => ['title' => 'Блог',              'template' => ''],
    ];

    foreach ($pages as $slug => $data) {
        $existing = get_page_by_path($slug);
        if ($existing) continue;

        $page_id = wp_insert_post([
            'post_title'   => $data['title'],
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ]);

        if ($data['template'] && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', $data['template']);
        }

        if ($slug === 'blog' && !is_wp_error($page_id)) {
            update_option('page_for_posts', $page_id);
        }
    }

    // Flush rewrite rules
    flush_rewrite_rules();
}
