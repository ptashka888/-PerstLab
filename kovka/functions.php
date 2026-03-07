<?php
/**
 * Kovka Theme — functions.php
 * CPTs, taxonomies, ACF integration, Schema.org, AJAX, SILO helpers
 */

defined('ABSPATH') || exit;

/* ============================================================
   THEME SETUP
   ============================================================ */
function kv_setup() {
    load_theme_textdomain('kovka', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('woocommerce');
    add_image_size('kv-card',     600, 450, true);
    add_image_size('kv-wide',    1200, 675, true);
    add_image_size('kv-square',   600, 600, true);
    add_image_size('kv-portrait', 480, 640, true);

    register_nav_menus([
        'primary'  => 'Основное меню',
        'footer'   => 'Нижнее меню',
        'catalog'  => 'Каталог (категории)',
    ]);
}
add_action('after_setup_theme', 'kv_setup');

/* ============================================================
   ENQUEUE ASSETS
   ============================================================ */
function kv_enqueue() {
    $v = wp_get_theme()->get('Version');

    // Google Fonts
    wp_enqueue_style(
        'kv-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Roboto:wght@400;500&display=swap',
        [], null
    );

    wp_enqueue_style('kv-theme', get_stylesheet_uri(), ['kv-fonts'], $v);

    wp_enqueue_script('kv-main', get_template_directory_uri() . '/assets/js/main.js', [], $v, true);
    wp_enqueue_script('kv-calc', get_template_directory_uri() . '/assets/js/calculator.js', ['kv-main'], $v, true);

    wp_localize_script('kv-main', 'kvAjax', [
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('kv_nonce'),
    ]);

    // Дизейблим лишнее из ядра WP
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    add_filter('wp_resource_hints', '__return_empty_array', 99);
}
add_action('wp_enqueue_scripts', 'kv_enqueue');

/* ============================================================
   CUSTOM POST TYPES
   ============================================================ */
function kv_register_cpts() {

    // --- Изделия каталога ---
    register_post_type('kv_product', [
        'labels' => [
            'name'               => 'Изделия',
            'singular_name'      => 'Изделие',
            'add_new'            => 'Добавить изделие',
            'add_new_item'       => 'Новое изделие',
            'edit_item'          => 'Редактировать изделие',
            'all_items'          => 'Все изделия',
            'search_items'       => 'Найти изделие',
            'not_found'          => 'Изделий не найдено',
        ],
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => ['slug' => 'catalog', 'with_front' => false],
        'menu_icon'           => 'dashicons-hammer',
        'menu_position'       => 5,
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'show_in_rest'        => true,
        'template'            => [],
    ]);

    // --- Работы / портфолио ---
    register_post_type('kv_work', [
        'labels' => [
            'name'          => 'Работы',
            'singular_name' => 'Работа',
            'add_new'       => 'Добавить работу',
            'all_items'     => 'Все работы',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'portfolio', 'with_front' => false],
        'menu_icon'    => 'dashicons-format-gallery',
        'menu_position'=> 6,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    // --- Отзывы ---
    register_post_type('kv_review', [
        'labels' => [
            'name'          => 'Отзывы',
            'singular_name' => 'Отзыв',
            'add_new'       => 'Добавить отзыв',
            'all_items'     => 'Все отзывы',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'otzyvy'],
        'menu_icon'    => 'dashicons-format-quote',
        'menu_position'=> 7,
        'supports'     => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);

    // --- Кейсы (до/после) ---
    register_post_type('kv_case', [
        'labels' => [
            'name'          => 'Кейсы',
            'singular_name' => 'Кейс',
            'add_new'       => 'Добавить кейс',
            'all_items'     => 'Все кейсы',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'kejsy'],
        'menu_icon'    => 'dashicons-star-filled',
        'menu_position'=> 8,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    // --- FAQ ---
    register_post_type('kv_faq', [
        'labels' => [
            'name'          => 'FAQ',
            'singular_name' => 'Вопрос',
            'add_new'       => 'Добавить вопрос',
            'all_items'     => 'Все вопросы',
        ],
        'public'        => false,
        'show_ui'       => true,
        'menu_icon'     => 'dashicons-editor-help',
        'menu_position' => 9,
        'supports'      => ['title', 'editor'],
        'show_in_rest'  => true,
    ]);

    // --- Команда ---
    register_post_type('kv_team', [
        'labels' => [
            'name'          => 'Команда',
            'singular_name' => 'Сотрудник',
            'add_new'       => 'Добавить сотрудника',
            'all_items'     => 'Команда',
        ],
        'public'        => false,
        'show_ui'       => true,
        'menu_icon'     => 'dashicons-admin-users',
        'menu_position' => 10,
        'supports'      => ['title', 'editor', 'thumbnail'],
        'show_in_rest'  => true,
    ]);
}
add_action('init', 'kv_register_cpts');

/* ============================================================
   TAXONOMIES
   ============================================================ */
function kv_register_taxonomies() {

    // Категория изделия (ворота, заборы, перила…)
    register_taxonomy('kv_category', ['kv_product', 'kv_work', 'kv_case'], [
        'labels' => [
            'name'          => 'Категории',
            'singular_name' => 'Категория',
            'add_new_item'  => 'Добавить категорию',
            'all_items'     => 'Все категории',
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'category', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // Материал
    register_taxonomy('kv_material', ['kv_product', 'kv_work'], [
        'labels' => [
            'name'          => 'Материалы',
            'singular_name' => 'Материал',
            'add_new_item'  => 'Добавить материал',
        ],
        'hierarchical' => false,
        'public'       => true,
        'rewrite'      => ['slug' => 'material'],
        'show_in_rest' => true,
    ]);

    // Ценовой диапазон
    register_taxonomy('kv_price_range', ['kv_product'], [
        'labels' => [
            'name'          => 'Ценовой диапазон',
            'singular_name' => 'Диапазон цены',
        ],
        'hierarchical' => false,
        'public'       => true,
        'rewrite'      => ['slug' => 'cena'],
        'show_in_rest' => true,
    ]);

    // Применение (для чего)
    register_taxonomy('kv_use', ['kv_product', 'kv_work'], [
        'labels' => [
            'name'          => 'Применение',
            'singular_name' => 'Область применения',
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'primenenie'],
        'show_in_rest' => true,
    ]);

    // Категория FAQ
    register_taxonomy('kv_faq_cat', ['kv_faq'], [
        'labels' => [
            'name'          => 'Категории FAQ',
            'singular_name' => 'Категория FAQ',
        ],
        'hierarchical' => true,
        'public'       => false,
        'show_ui'      => true,
        'show_in_rest' => true,
    ]);

    // Блог: тематические кластеры
    register_taxonomy('kv_cluster', ['post'], [
        'labels' => [
            'name'          => 'Кластеры',
            'singular_name' => 'Кластер',
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'tema'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'kv_register_taxonomies');

/* ============================================================
   ACF PRO FIELD GROUPS (через PHP API)
   ============================================================ */
function kv_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    // === Поля: Изделие каталога ===
    acf_add_local_field_group([
        'key'      => 'group_kv_product',
        'title'    => 'Характеристики изделия',
        'fields'   => [
            ['key' => 'field_kv_price_from',     'label' => 'Цена от (руб)',       'name' => 'kv_price_from',     'type' => 'number',   'prepend' => '₽'],
            ['key' => 'field_kv_price_to',       'label' => 'Цена до (руб)',       'name' => 'kv_price_to',       'type' => 'number',   'prepend' => '₽'],
            ['key' => 'field_kv_metal',          'label' => 'Металл / сплав',      'name' => 'kv_metal',          'type' => 'text'],
            ['key' => 'field_kv_coating',        'label' => 'Покрытие',            'name' => 'kv_coating',        'type' => 'select',
                'choices' => ['powder' => 'Порошковая окраска', 'hot_zinc' => 'Горячее цинкование', 'patina' => 'Патина', 'rust' => 'Ржавый эффект', 'chrome' => 'Хром', 'none' => 'Без покрытия'],
            ],
            ['key' => 'field_kv_lead_time',      'label' => 'Срок изготовления',   'name' => 'kv_lead_time',      'type' => 'text',     'placeholder' => 'например, 7–14 дней'],
            ['key' => 'field_kv_warranty',       'label' => 'Гарантия',            'name' => 'kv_warranty',       'type' => 'text',     'placeholder' => 'например, 25 лет'],
            ['key' => 'field_kv_weight',         'label' => 'Масса (кг)',          'name' => 'kv_weight',         'type' => 'number'],
            ['key' => 'field_kv_section',        'label' => 'Сечение прутка (мм)', 'name' => 'kv_section',        'type' => 'text'],
            ['key' => 'field_kv_installable',    'label' => 'Монтаж включён',      'name' => 'kv_installable',    'type' => 'true_false', 'ui' => 1],
            ['key' => 'field_kv_gallery',        'label' => 'Галерея',             'name' => 'kv_gallery',        'type' => 'gallery',  'mime_types' => 'jpg,webp'],
            ['key' => 'field_kv_3d_model',       'label' => 'Ссылка на 3D-модель', 'name' => 'kv_3d_model',       'type' => 'url'],
            ['key' => 'field_kv_popular',        'label' => 'Хит продаж',         'name' => 'kv_popular',        'type' => 'true_false', 'ui' => 1],
            ['key' => 'field_kv_product_faq',    'label' => 'FAQ по изделию',      'name' => 'kv_product_faq',    'type' => 'repeater',
                'sub_fields' => [
                    ['key' => 'field_kv_faq_q', 'label' => 'Вопрос', 'name' => 'question', 'type' => 'text'],
                    ['key' => 'field_kv_faq_a', 'label' => 'Ответ',  'name' => 'answer',  'type' => 'textarea'],
                ],
            ],
        ],
        'location' => [[ ['param' => 'post_type', 'operator' => '==', 'value' => 'kv_product'] ]],
        'style'    => 'seamless',
    ]);

    // === Поля: Работа / портфолио ===
    acf_add_local_field_group([
        'key'   => 'group_kv_work',
        'title' => 'Данные работы',
        'fields' => [
            ['key' => 'field_kv_work_client',    'label' => 'Тип клиента',     'name' => 'kv_work_client',    'type' => 'select',
                'choices' => ['private' => 'Частный дом', 'cottage' => 'КП / дача', 'commercial' => 'Коммерция', 'restarant' => 'Ресторан', 'state' => 'Госзаказ'],
            ],
            ['key' => 'field_kv_work_city',      'label' => 'Город',           'name' => 'kv_work_city',      'type' => 'text'],
            ['key' => 'field_kv_work_year',      'label' => 'Год',             'name' => 'kv_work_year',      'type' => 'number', 'min' => 2000, 'max' => 2099],
            ['key' => 'field_kv_work_budget',    'label' => 'Бюджет (руб)',    'name' => 'kv_work_budget',    'type' => 'number'],
            ['key' => 'field_kv_work_duration',  'label' => 'Срок (дней)',     'name' => 'kv_work_duration',  'type' => 'number'],
            ['key' => 'field_kv_work_gallery',   'label' => 'Галерея',         'name' => 'kv_work_gallery',   'type' => 'gallery'],
            ['key' => 'field_kv_work_video',     'label' => 'Ссылка на видео', 'name' => 'kv_work_video',     'type' => 'url'],
            ['key' => 'field_kv_work_featured',  'label' => 'На главной',      'name' => 'kv_work_featured',  'type' => 'true_false', 'ui' => 1],
        ],
        'location' => [[ ['param' => 'post_type', 'operator' => '==', 'value' => 'kv_work'] ]],
    ]);

    // === Поля: Отзыв ===
    acf_add_local_field_group([
        'key'   => 'group_kv_review',
        'title' => 'Данные отзыва',
        'fields' => [
            ['key' => 'field_kv_r_rating',   'label' => 'Рейтинг (1–5)', 'name' => 'kv_rating',    'type' => 'number', 'min' => 1, 'max' => 5],
            ['key' => 'field_kv_r_city',     'label' => 'Город клиента', 'name' => 'kv_city',      'type' => 'text'],
            ['key' => 'field_kv_r_product',  'label' => 'Изделие',       'name' => 'kv_product',   'type' => 'post_object', 'post_type' => ['kv_product']],
            ['key' => 'field_kv_r_source',   'label' => 'Источник',      'name' => 'kv_source',    'type' => 'select',
                'choices' => ['google' => 'Google', 'yandex' => 'Яндекс', 'avito' => 'Avito', 'vk' => 'ВКонтакте', 'direct' => 'Прямой'],
            ],
            ['key' => 'field_kv_r_photo',    'label' => 'Фото клиента',  'name' => 'kv_r_photo',   'type' => 'image', 'return_format' => 'url'],
            ['key' => 'field_kv_r_video',    'label' => 'Ссылка на видеоотзыв', 'name' => 'kv_r_video', 'type' => 'url'],
            ['key' => 'field_kv_r_featured', 'label' => 'На главной',    'name' => 'kv_r_featured', 'type' => 'true_false', 'ui' => 1],
        ],
        'location' => [[ ['param' => 'post_type', 'operator' => '==', 'value', 'value' => 'kv_review'] ]],
    ]);

    // === Поля: Сотрудник команды ===
    acf_add_local_field_group([
        'key'   => 'group_kv_team',
        'title' => 'Данные сотрудника',
        'fields' => [
            ['key' => 'field_kv_t_role',       'label' => 'Должность',       'name' => 'kv_role',       'type' => 'text'],
            ['key' => 'field_kv_t_experience', 'label' => 'Стаж (лет)',      'name' => 'kv_experience', 'type' => 'number'],
            ['key' => 'field_kv_t_spec',       'label' => 'Специализация',   'name' => 'kv_spec',       'type' => 'text'],
            ['key' => 'field_kv_t_cert',       'label' => 'Сертификат/чин',  'name' => 'kv_cert',       'type' => 'text'],
            ['key' => 'field_kv_t_photo',      'label' => 'Фото',            'name' => 'kv_t_photo',    'type' => 'image', 'return_format' => 'url'],
            ['key' => 'field_kv_t_vk',         'label' => 'ВКонтакте',       'name' => 'kv_vk',         'type' => 'url'],
            ['key' => 'field_kv_t_tg',         'label' => 'Telegram',        'name' => 'kv_tg',         'type' => 'url'],
        ],
        'location' => [[ ['param' => 'post_type', 'operator' => '==', 'value' => 'kv_team'] ]],
    ]);

    // === Поля: Страница (SEO + hero) ===
    acf_add_local_field_group([
        'key'   => 'group_kv_page',
        'title' => 'Настройки страницы',
        'fields' => [
            ['key' => 'field_kv_p_hero_img',    'label' => 'Фон hero-блока', 'name' => 'kv_hero_img',    'type' => 'image', 'return_format' => 'url'],
            ['key' => 'field_kv_p_hero_sub',    'label' => 'Подзаголовок hero', 'name' => 'kv_hero_sub', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_kv_p_cta_text',    'label' => 'Текст CTA кнопки', 'name' => 'kv_cta_text', 'type' => 'text'],
            ['key' => 'field_kv_p_schema_type', 'label' => 'Schema.org тип', 'name' => 'kv_schema_type',
                'type' => 'select', 'choices' => ['Service' => 'Service', 'Product' => 'Product', 'Article' => 'Article', 'WebPage' => 'WebPage'],
            ],
        ],
        'location' => [[ ['param' => 'post_type', 'operator' => '==', 'value' => 'page'] ]],
    ]);
}
add_action('acf/init', 'kv_acf_fields');

/* ============================================================
   SCHEMA.ORG JSON-LD
   ============================================================ */
function kv_schema_output() {
    $schemas = [];
    $site_url = home_url();
    $site_name = get_bloginfo('name');

    // Organization — всегда
    $schemas[] = [
        '@context'  => 'https://schema.org',
        '@type'     => 'Organization',
        'name'      => $site_name,
        'url'       => $site_url,
        'logo'      => get_template_directory_uri() . '/assets/img/logo.png',
        'telephone' => get_theme_mod('kv_phone', '+7 (800) 000-00-00'),
        'email'     => get_theme_mod('kv_email', 'info@kovka.ru'),
        'address'   => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => get_theme_mod('kv_address', 'ул. Кузнечная, 1'),
            'addressLocality' => get_theme_mod('kv_city', 'Москва'),
            'postalCode'      => get_theme_mod('kv_zip', '115114'),
            'addressCountry'  => 'RU',
        ],
        'sameAs' => array_filter([
            get_theme_mod('kv_vk'),
            get_theme_mod('kv_tg'),
            get_theme_mod('kv_youtube'),
            get_theme_mod('kv_avito'),
        ]),
    ];

    // BreadcrumbList
    if (!is_front_page() && !is_home()) {
        $items = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => $site_url]];
        $pos = 2;
        if (is_singular('kv_product')) {
            $terms = get_the_terms(get_the_ID(), 'kv_category');
            if ($terms) {
                $items[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $terms[0]->name, 'item' => get_term_link($terms[0])];
            }
        }
        $items[] = ['@type' => 'ListItem', 'position' => $pos, 'name' => get_the_title()];
        $schemas[] = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items];
    }

    // Product Schema
    if (is_singular('kv_product')) {
        $price_from = function_exists('get_field') ? get_field('kv_price_from') : '';
        $schemas[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => get_the_title(),
            'description' => get_the_excerpt(),
            'image'       => get_the_post_thumbnail_url(null, 'kv-wide'),
            'brand'       => ['@type' => 'Brand', 'name' => $site_name],
            'offers'      => [
                '@type'         => 'Offer',
                'priceCurrency' => 'RUB',
                'price'         => $price_from ?: '0',
                'availability'  => 'https://schema.org/InStock',
                'seller'        => ['@type' => 'Organization', 'name' => $site_name],
            ],
        ];
    }

    // FAQPage
    if (is_page()) {
        $faqs = get_posts(['post_type' => 'kv_faq', 'posts_per_page' => 20, 'post_status' => 'publish']);
        if ($faqs) {
            $entities = [];
            foreach ($faqs as $faq) {
                $entities[] = [
                    '@type'          => 'Question',
                    'name'           => $faq->post_title,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => wp_strip_all_tags($faq->post_content)],
                ];
            }
            if ($entities) {
                $schemas[] = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $entities];
            }
        }
    }

    foreach ($schemas as $schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
add_action('wp_head', 'kv_schema_output');

/* ============================================================
   CUSTOMIZER
   ============================================================ */
function kv_customizer($wp_customize) {
    $wp_customize->add_section('kv_contacts', ['title' => 'Контакты кузницы', 'priority' => 30]);

    $fields = [
        'kv_phone'   => 'Телефон',
        'kv_phone2'  => 'Телефон 2',
        'kv_email'   => 'Email',
        'kv_address' => 'Адрес',
        'kv_city'    => 'Город',
        'kv_zip'     => 'Индекс',
        'kv_map_url' => 'Ссылка на карту (iframe src)',
        'kv_worktime'=> 'Время работы',
        'kv_vk'      => 'ВКонтакте URL',
        'kv_tg'      => 'Telegram URL',
        'kv_youtube' => 'YouTube URL',
        'kv_avito'   => 'Avito URL',
        'kv_whatsapp'=> 'WhatsApp номер',
    ];

    foreach ($fields as $id => $label) {
        $wp_customize->add_setting($id, ['default' => '', 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control($id, ['label' => $label, 'section' => 'kv_contacts', 'type' => 'text']);
    }
}
add_action('customize_register', 'kv_customizer');

/* ============================================================
   AJAX: Форма заявки
   ============================================================ */
function kv_ajax_lead() {
    check_ajax_referer('kv_nonce', 'nonce');

    $name    = sanitize_text_field($_POST['name'] ?? '');
    $phone   = sanitize_text_field($_POST['phone'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');
    $source  = sanitize_text_field($_POST['source'] ?? 'Сайт');

    if (!$phone) {
        wp_send_json_error(['msg' => 'Укажите телефон']);
    }

    // Создаём запись в WP
    $post_id = wp_insert_post([
        'post_type'   => 'kv_lead',
        'post_title'  => sprintf('[%s] %s — %s', date('d.m.Y H:i'), $name, $phone),
        'post_status' => 'private',
        'meta_input'  => [
            'kv_lead_phone'   => $phone,
            'kv_lead_name'    => $name,
            'kv_lead_message' => $message,
            'kv_lead_source'  => $source,
        ],
    ]);

    // Отправляем email
    $to = get_option('admin_email');
    $subject = 'Новая заявка с сайта';
    $body = "Имя: $name\nТелефон: $phone\nСообщение: $message\nИсточник: $source";
    wp_mail($to, $subject, $body);

    wp_send_json_success(['msg' => 'Заявка принята! Перезвоним в течение 15 минут.']);
}
add_action('wp_ajax_kv_lead', 'kv_ajax_lead');
add_action('wp_ajax_nopriv_kv_lead', 'kv_ajax_lead');

/* ============================================================
   AJAX: Быстрый расчёт цены
   ============================================================ */
function kv_ajax_calc() {
    check_ajax_referer('kv_nonce', 'nonce');

    $category = sanitize_text_field($_POST['category'] ?? '');
    $length   = floatval($_POST['length'] ?? 1);
    $height   = floatval($_POST['height'] ?? 1);
    $coating  = sanitize_text_field($_POST['coating'] ?? 'powder');
    $install  = (bool)($_POST['install'] ?? false);

    // Базовые цены за пог.м (руб) — условные
    $prices = [
        'gates'     => 18000,
        'fence'     => 4500,
        'stairs'    => 22000,
        'railing'   => 6500,
        'furniture' => 35000,
        'decor'     => 12000,
    ];

    $base = $prices[$category] ?? 5000;
    $area = $length * $height;
    $total = $base * $area;

    // Коэф. покрытия
    $coatings = ['powder' => 1.0, 'hot_zinc' => 1.35, 'patina' => 1.2, 'chrome' => 1.8, 'none' => 0.85];
    $total *= ($coatings[$coating] ?? 1.0);

    // Монтаж +25%
    if ($install) $total *= 1.25;

    wp_send_json_success([
        'min'   => round($total * 0.9 / 1000) * 1000,
        'max'   => round($total * 1.15 / 1000) * 1000,
        'total' => round($total / 1000) * 1000,
    ]);
}
add_action('wp_ajax_kv_calc', 'kv_ajax_calc');
add_action('wp_ajax_nopriv_kv_calc', 'kv_ajax_calc');

/* ============================================================
   SILO HELPERS
   ============================================================ */

/**
 * Данные категорий (slug => данные)
 */
function kv_get_category_data() {
    return [
        'vorota'    => ['name' => 'Ворота и калитки',       'icon' => '🚪', 'slug' => 'vorota',    'color' => '#E65100', 'desc' => 'Распашные, откатные, автоматические'],
        'zabory'    => ['name' => 'Заборы и ограждения',    'icon' => '🏠', 'slug' => 'zabory',    'color' => '#A67C00', 'desc' => 'Секционные, кованые, комбинированные'],
        'lestnitsy' => ['name' => 'Лестницы и перила',      'icon' => '🪜', 'slug' => 'lestnitsy', 'color' => '#6D4C41', 'desc' => 'Маршевые, винтовые, ограждения'],
        'mebel'     => ['name' => 'Мебель',                 'icon' => '🪑', 'slug' => 'mebel',     'color' => '#1B5E20', 'desc' => 'Столы, стулья, кровати, вешалки'],
        'dekor'     => ['name' => 'Декор и интерьер',       'icon' => '🕯️', 'slug' => 'dekor',     'color' => '#4A148C', 'desc' => 'Подсвечники, панно, вазы, светильники'],
        'art'       => ['name' => 'Художественная ковка',   'icon' => '⚒️', 'slug' => 'art',       'color' => '#B71C1C', 'desc' => 'Скульптуры, арт-объекты, подарки'],
    ];
}

/**
 * Хлебные крошки без плагина
 */
function kv_breadcrumbs() {
    $sep = '<span class="kv-breadcrumbs__sep">&rsaquo;</span>';
    echo '<nav class="kv-breadcrumbs" aria-label="Хлебные крошки">';
    echo '<a href="' . home_url() . '">Главная</a>' . $sep;

    if (is_singular('kv_product')) {
        echo '<a href="' . get_post_type_archive_link('kv_product') . '">Каталог</a>' . $sep;
        $terms = get_the_terms(null, 'kv_category');
        if ($terms) echo '<a href="' . get_term_link($terms[0]) . '">' . esc_html($terms[0]->name) . '</a>' . $sep;
        echo '<span>' . get_the_title() . '</span>';
    } elseif (is_tax()) {
        $term = get_queried_object();
        if ($term->parent) echo '<a href="' . get_term_link($term->parent, $term->taxonomy) . '">' . esc_html(get_term($term->parent)->name) . '</a>' . $sep;
        echo '<span>' . esc_html($term->name) . '</span>';
    } elseif (is_page()) {
        echo '<span>' . get_the_title() . '</span>';
    } elseif (is_single()) {
        $cats = get_the_category();
        if ($cats) echo '<a href="' . get_category_link($cats[0]) . '">' . esc_html($cats[0]->name) . '</a>' . $sep;
        echo '<span>' . get_the_title() . '</span>';
    } elseif (is_archive()) {
        echo '<span>' . get_the_archive_title() . '</span>';
    } elseif (is_search()) {
        echo '<span>Поиск: ' . esc_html(get_search_query()) . '</span>';
    }

    echo '</nav>';
}

/* ============================================================
   REGISTER LEAD CPT (для хранения заявок)
   ============================================================ */
function kv_register_lead_cpt() {
    register_post_type('kv_lead', [
        'labels'       => ['name' => 'Заявки', 'singular_name' => 'Заявка'],
        'public'       => false,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-email-alt',
        'menu_position'=> 2,
        'supports'     => ['title'],
        'capabilities' => ['create_posts' => 'do_not_allow'],
        'map_meta_cap' => true,
    ]);
}
add_action('init', 'kv_register_lead_cpt');

/* ============================================================
   АВТОСОЗДАНИЕ СТРАНИЦ ПРИ АКТИВАЦИИ
   ============================================================ */
function kv_create_default_pages() {
    $pages = [
        ['title' => 'Ворота и калитки',     'slug' => 'vorota',     'template' => 'page-templates/category.php'],
        ['title' => 'Заборы и ограждения',  'slug' => 'zabory',     'template' => 'page-templates/category.php'],
        ['title' => 'Лестницы и перила',    'slug' => 'lestnitsy',  'template' => 'page-templates/category.php'],
        ['title' => 'Мебель',               'slug' => 'mebel',      'template' => 'page-templates/category.php'],
        ['title' => 'Декор и интерьер',     'slug' => 'dekor',      'template' => 'page-templates/category.php'],
        ['title' => 'Художественная ковка', 'slug' => 'art',        'template' => 'page-templates/category.php'],
        ['title' => 'Калькулятор',          'slug' => 'calculator', 'template' => 'page-templates/calculator.php'],
        ['title' => 'Портфолио',            'slug' => 'portfolio',  'template' => 'page-templates/portfolio.php'],
        ['title' => 'О нас',                'slug' => 'about',      'template' => 'page-templates/about.php'],
        ['title' => 'Контакты',             'slug' => 'contacts',   'template' => 'page-templates/contacts.php'],
        ['title' => 'Блог',                 'slug' => 'blog',       'template' => ''],
    ];

    foreach ($pages as $p) {
        $exists = get_page_by_path($p['slug']);
        if (!$exists) {
            $id = wp_insert_post([
                'post_type'   => 'page',
                'post_title'  => $p['title'],
                'post_name'   => $p['slug'],
                'post_status' => 'publish',
            ]);
            if ($p['template']) update_post_meta($id, '_wp_page_template', $p['template']);
        }
    }
}
register_activation_hook(__FILE__, 'kv_create_default_pages');

/* ============================================================
   ПОЛЕЗНЫЕ ХЕЛПЕРЫ ШАБЛОНОВ
   ============================================================ */

function kv_field($key, $post_id = null) {
    if (function_exists('get_field')) return get_field($key, $post_id);
    return get_post_meta($post_id ?? get_the_ID(), $key, true);
}

function kv_price_format($price) {
    if (!$price) return '';
    return 'от ' . number_format((int)$price, 0, '.', '&nbsp;') . '&nbsp;₽';
}

function kv_stars($rating = 5) {
    $out = '';
    for ($i = 1; $i <= 5; $i++) {
        $out .= $i <= $rating ? '★' : '☆';
    }
    return '<span class="kv-stars" aria-label="Рейтинг: ' . $rating . ' из 5">' . $out . '</span>';
}

function kv_lead_form($source = 'inline', $btn = 'Получить расчёт') {
    ob_start(); ?>
    <form class="kv-lead-form" data-source="<?= esc_attr($source) ?>">
        <?php wp_nonce_field('kv_nonce', 'kv_lead_nonce'); ?>
        <div class="kv-form-group">
            <input type="text" name="name" class="kv-input" placeholder="Ваше имя">
        </div>
        <div class="kv-form-group">
            <input type="tel" name="phone" class="kv-input" placeholder="+7 (___) ___-__-__" required>
        </div>
        <div class="kv-form-group">
            <textarea name="message" class="kv-textarea" placeholder="Опишите изделие: размеры, стиль, назначение…" rows="3"></textarea>
        </div>
        <button type="submit" class="kv-btn kv-btn--primary" style="width:100%"><?= esc_html($btn) ?></button>
        <p style="font-size:.72rem;color:var(--kv-text-muted);margin-top:10px;text-align:center">
            Нажимая кнопку, вы соглашаетесь с <a href="/privacy">политикой конфиденциальности</a>
        </p>
        <div class="kv-form-result" style="display:none;margin-top:12px;padding:12px;border-radius:6px;text-align:center"></div>
    </form>
    <?php return ob_get_clean();
}

/* ============================================================
   TITLE TAG
   ============================================================ */
add_filter('document_title_separator', fn() => '|');
add_filter('document_title_parts', function($parts) {
    $parts['tagline'] = '';
    return $parts;
});

/* ============================================================
   EXCERPT
   ============================================================ */
add_filter('excerpt_length', fn() => 25);
add_filter('excerpt_more', fn() => '…');

/* ============================================================
   THUMBNAIL COLUMN IN ADMIN
   ============================================================ */
add_filter('manage_kv_product_posts_columns', function($cols) {
    return array_merge(['thumbnail' => 'Фото'], $cols);
});
add_action('manage_kv_product_posts_custom_column', function($col, $id) {
    if ($col === 'thumbnail') echo get_the_post_thumbnail($id, [60, 60]);
}, 10, 2);
