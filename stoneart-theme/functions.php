<?php
/**
 * StoneArt Theme Functions
 *
 * @package StoneArt
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('SA_VERSION', '1.0.0');
define('SA_DIR', get_template_directory());
define('SA_URI', get_template_directory_uri());

// ============================================================
// Theme Setup
// ============================================================
function sa_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('editor-styles');

    set_post_thumbnail_size(800, 600, true);
    add_image_size('sa-card', 400, 300, true);
    add_image_size('sa-hero', 1920, 1080, true);
    add_image_size('sa-portfolio', 600, 450, true);

    register_nav_menus([
        'primary'   => 'Основное меню',
        'mobile'    => 'Мобильное меню',
        'footer_1'  => 'Подвал — Изделия',
        'footer_2'  => 'Подвал — Услуги',
        'footer_3'  => 'Подвал — Материалы',
        'footer_4'  => 'Подвал — О компании',
    ]);
}
add_action('after_setup_theme', 'sa_theme_setup');

// ============================================================
// Enqueue Scripts & Styles
// ============================================================
function sa_enqueue_assets() {
    // Tailwind CDN
    wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css', [], '2.2.19');

    // Font Awesome
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');

    // Theme styles
    wp_enqueue_style('stoneart-style', get_stylesheet_uri(), ['tailwindcss', 'fontawesome'], SA_VERSION);
    wp_enqueue_style('stoneart-custom', SA_URI . '/assets/css/custom.css', ['stoneart-style'], SA_VERSION);

    // Main JS
    wp_enqueue_script('stoneart-main', SA_URI . '/assets/js/main.js', [], SA_VERSION, true);

    // Calculator JS (loaded on calculator page and front page)
    if (is_page_template('page-templates/calculator.php') || is_front_page() || is_page('calculator')) {
        wp_enqueue_script('stoneart-calc', SA_URI . '/assets/js/calculator.js', [], SA_VERSION, true);
    }

    // Localize for AJAX
    wp_localize_script('stoneart-main', 'saAjax', [
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sa_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'sa_enqueue_assets');

// ============================================================
// Performance: Remove WordPress bloat
// ============================================================
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

// ============================================================
// Custom Post Types
// ============================================================
function sa_register_post_types() {
    // Product (catalog items with specs, prices, gallery)
    register_post_type('sa_product', [
        'labels' => [
            'name'               => 'Каталог изделий',
            'singular_name'      => 'Изделие',
            'add_new'            => 'Добавить изделие',
            'add_new_item'       => 'Добавить новое изделие',
            'edit_item'          => 'Редактировать изделие',
            'all_items'          => 'Все изделия',
            'search_items'       => 'Найти изделие',
            'not_found'          => 'Изделия не найдены',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'catalog'],
        'menu_icon'    => 'dashicons-store',
        'menu_position'=> 5,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    // Portfolio
    register_post_type('sa_portfolio', [
        'labels' => [
            'name'               => 'Портфолио',
            'singular_name'      => 'Работа',
            'add_new'            => 'Добавить работу',
            'add_new_item'       => 'Добавить новую работу',
            'edit_item'          => 'Редактировать работу',
            'all_items'          => 'Все работы',
            'search_items'       => 'Найти работу',
            'not_found'          => 'Работы не найдены',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'portfolio'],
        'menu_icon'    => 'dashicons-images-alt2',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    // Reviews
    register_post_type('sa_review', [
        'labels' => [
            'name'               => 'Отзывы',
            'singular_name'      => 'Отзыв',
            'add_new'            => 'Добавить отзыв',
            'add_new_item'       => 'Добавить новый отзыв',
            'edit_item'          => 'Редактировать отзыв',
            'all_items'          => 'Все отзывы',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'reviews'],
        'menu_icon'    => 'dashicons-star-filled',
        'supports'     => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);

    // Team
    register_post_type('sa_team', [
        'labels' => [
            'name'               => 'Команда',
            'singular_name'      => 'Сотрудник',
            'add_new'            => 'Добавить сотрудника',
            'add_new_item'       => 'Добавить нового сотрудника',
            'edit_item'          => 'Редактировать сотрудника',
            'all_items'          => 'Вся команда',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'team'],
        'menu_icon'    => 'dashicons-groups',
        'supports'     => ['title', 'thumbnail'],
        'show_in_rest' => true,
    ]);

    // FAQ
    register_post_type('sa_faq', [
        'labels' => [
            'name'               => 'FAQ',
            'singular_name'      => 'Вопрос',
            'add_new'            => 'Добавить вопрос',
            'add_new_item'       => 'Добавить новый вопрос',
            'edit_item'          => 'Редактировать вопрос',
            'all_items'          => 'Все вопросы',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'faq'],
        'menu_icon'    => 'dashicons-editor-help',
        'supports'     => ['title', 'editor'],
        'show_in_rest' => true,
    ]);

    // Services
    register_post_type('sa_service', [
        'labels' => [
            'name'               => 'Услуги',
            'singular_name'      => 'Услуга',
            'add_new'            => 'Добавить услугу',
            'add_new_item'       => 'Добавить новую услугу',
            'edit_item'          => 'Редактировать услугу',
            'all_items'          => 'Все услуги',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'services'],
        'menu_icon'    => 'dashicons-hammer',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sa_register_post_types');

// ============================================================
// Custom Taxonomies
// ============================================================
function sa_register_taxonomies() {
    // Product Category (for portfolio, services)
    register_taxonomy('sa_product_cat', ['sa_portfolio', 'sa_service', 'sa_product'], [
        'labels' => [
            'name'          => 'Категории изделий',
            'singular_name' => 'Категория изделий',
            'add_new_item'  => 'Добавить категорию',
        ],
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'product-category'],
        'show_in_rest' => true,
    ]);

    // Material Type
    register_taxonomy('sa_material', ['sa_portfolio', 'sa_product'], [
        'labels' => [
            'name'          => 'Материалы',
            'singular_name' => 'Материал',
            'add_new_item'  => 'Добавить материал',
        ],
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'material'],
        'show_in_rest' => true,
    ]);

    // Product Type (hierarchical: Столешницы > Из мрамора / Кухонные)
    register_taxonomy('sa_product_type', ['sa_product'], [
        'labels' => [
            'name'          => 'Тип изделия',
            'singular_name' => 'Тип изделия',
            'add_new_item'  => 'Добавить тип',
        ],
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'type'],
        'show_in_rest' => true,
    ]);

    // Blog cluster (for SILO blog architecture)
    register_taxonomy('sa_blog_cluster', ['post'], [
        'labels' => [
            'name'          => 'Кластер блога',
            'singular_name' => 'Кластер',
            'add_new_item'  => 'Добавить кластер',
        ],
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'blog-cluster'],
        'show_in_rest' => true,
    ]);

    // FAQ Category
    register_taxonomy('sa_faq_cat', ['sa_faq'], [
        'labels' => [
            'name'          => 'Категории FAQ',
            'singular_name' => 'Категория FAQ',
        ],
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'faq-category'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sa_register_taxonomies');

// ============================================================
// ACF PRO: Save/Load JSON
// ============================================================
function sa_acf_json_save_point($path) {
    return SA_DIR . '/acf-json';
}
add_filter('acf/settings/save_json', 'sa_acf_json_save_point');

function sa_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = SA_DIR . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'sa_acf_json_load_point');

// ============================================================
// ACF Options Pages
// ============================================================
function sa_acf_options_pages() {
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => 'Настройки StoneArt',
        'menu_title' => 'StoneArt',
        'menu_slug'  => 'stoneart-settings',
        'capability' => 'edit_posts',
        'icon_url'   => 'dashicons-admin-generic',
        'redirect'   => false,
    ]);

    acf_add_options_sub_page([
        'page_title'  => 'Контакты и реквизиты',
        'menu_title'  => 'Контакты',
        'parent_slug' => 'stoneart-settings',
    ]);

    acf_add_options_sub_page([
        'page_title'  => 'Соцсети и мессенджеры',
        'menu_title'  => 'Соцсети',
        'parent_slug' => 'stoneart-settings',
    ]);

    acf_add_options_sub_page([
        'page_title'  => 'SEO настройки',
        'menu_title'  => 'SEO',
        'parent_slug' => 'stoneart-settings',
    ]);
}
add_action('acf/init', 'sa_acf_options_pages');

// ============================================================
// ACF Field Groups (programmatic registration)
// ============================================================
require_once SA_DIR . '/inc/acf-fields.php';

// ============================================================
// Schema.org & SEO
// ============================================================
require_once SA_DIR . '/inc/schema.php';

// ============================================================
// Demo Content
// ============================================================
require_once SA_DIR . '/inc/demo-content.php';

// ============================================================
// SILO Internal Linking
// ============================================================
require_once SA_DIR . '/inc/silo.php';

// ============================================================
// Helpers
// ============================================================

/**
 * Get theme option (ACF)
 */
function sa_option($key, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($key, 'option');
        return $value ?: $default;
    }
    return $default;
}

/**
 * Get phone number
 */
function sa_phone() {
    return sa_option('sa_phone', '+7 (495) 000-00-00');
}

/**
 * Get phone href
 */
function sa_phone_href() {
    return 'tel:' . preg_replace('/[^\d+]/', '', sa_phone());
}

/**
 * Get email
 */
function sa_email() {
    return sa_option('sa_email', 'info@stoneart.ru');
}

/**
 * Get address
 */
function sa_address() {
    return sa_option('sa_address', 'г. Москва, ул. Каменная, д. 1 (Шоурум)');
}

/**
 * Get working hours
 */
function sa_hours() {
    return sa_option('sa_hours', 'Пн-Вс: 09:00 — 20:00');
}

/**
 * Get WhatsApp link
 */
function sa_whatsapp() {
    return sa_option('sa_whatsapp', '#');
}

/**
 * Get Telegram link
 */
function sa_telegram() {
    return sa_option('sa_telegram', '#');
}

/**
 * Get company name
 */
function sa_company_name() {
    return sa_option('sa_company_name', 'StoneArt');
}

/**
 * Breadcrumbs
 */
function sa_breadcrumbs() {
    if (is_front_page()) return;

    $items = [];
    $items[] = ['url' => home_url('/'), 'name' => 'Главная'];

    if (is_singular('sa_portfolio')) {
        $items[] = ['url' => get_post_type_archive_link('sa_portfolio'), 'name' => 'Портфолио'];
        $items[] = ['name' => get_the_title()];
    } elseif (is_post_type_archive('sa_portfolio')) {
        $items[] = ['name' => 'Портфолио'];
    } elseif (is_singular('sa_service')) {
        $items[] = ['url' => get_post_type_archive_link('sa_service'), 'name' => 'Услуги'];
        $items[] = ['name' => get_the_title()];
    } elseif (is_singular()) {
        if (is_single()) {
            $cats = get_the_category();
            if ($cats) {
                $items[] = ['url' => get_category_link($cats[0]->term_id), 'name' => $cats[0]->name];
            }
        }
        $items[] = ['name' => get_the_title()];
    } elseif (is_page()) {
        $items[] = ['name' => get_the_title()];
    } elseif (is_category()) {
        $items[] = ['name' => single_cat_title('', false)];
    } elseif (is_search()) {
        $items[] = ['name' => 'Результаты поиска'];
    } elseif (is_404()) {
        $items[] = ['name' => 'Страница не найдена'];
    }

    echo '<nav class="sa-page-header__breadcrumbs" aria-label="Хлебные крошки">';
    $last = count($items) - 1;
    foreach ($items as $i => $item) {
        if ($i === $last || !isset($item['url'])) {
            echo '<span>' . esc_html($item['name']) . '</span>';
        } else {
            echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['name']) . '</a>';
            echo ' <span>/</span> ';
        }
    }
    echo '</nav>';
}

// ============================================================
// AJAX Form Handler
// ============================================================
function sa_handle_form_submit() {
    check_ajax_referer('sa_nonce', 'nonce');

    $type  = sanitize_text_field($_POST['form_type'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $email = sanitize_text_field($_POST['email'] ?? '');
    $name  = sanitize_text_field($_POST['name'] ?? '');

    $to      = sa_email();
    $subject = '';
    $body    = '';

    switch ($type) {
        case 'quiz':
            $product = sanitize_text_field($_POST['product'] ?? '');
            $form    = sanitize_text_field($_POST['form_shape'] ?? '');
            $sink    = sanitize_text_field($_POST['sink'] ?? '');
            $method  = sanitize_text_field($_POST['contact_method'] ?? '');

            $subject = 'Заявка из квиза — ' . sa_company_name();
            $body    = "Телефон: {$phone}\nСпособ связи: {$method}\nИзделие: {$product}\nФорма: {$form}\nМойка: {$sink}";
            break;

        case 'callback':
            $subject = 'Обратный звонок — ' . sa_company_name();
            $body    = "Имя: {$name}\nТелефон: {$phone}";
            break;

        case 'pdf':
            $subject = 'Запрос PDF-каталога — ' . sa_company_name();
            $body    = "E-mail: {$email}";
            break;

        case 'contact':
            $message = sanitize_textarea_field($_POST['message'] ?? '');
            $subject = 'Сообщение с сайта — ' . sa_company_name();
            $body    = "Имя: {$name}\nТелефон: {$phone}\nE-mail: {$email}\nСообщение:\n{$message}";
            break;

        default:
            wp_send_json_error(['message' => 'Неизвестный тип формы']);
            return;
    }

    $sent = wp_mail($to, $subject, $body);

    if ($sent) {
        wp_send_json_success(['message' => 'Заявка отправлена!']);
    } else {
        wp_send_json_success(['message' => 'Спасибо! Мы свяжемся с вами в ближайшее время.']);
    }
}
add_action('wp_ajax_sa_form_submit', 'sa_handle_form_submit');
add_action('wp_ajax_nopriv_sa_form_submit', 'sa_handle_form_submit');

// ============================================================
// AJAX: Stone Price Calculator
// ============================================================
function sa_ajax_calculator() {
    check_ajax_referer('sa_nonce', 'nonce');

    $type      = sanitize_text_field($_POST['product_type'] ?? '');
    $material  = sanitize_text_field($_POST['material'] ?? '');
    $length    = floatval($_POST['length'] ?? 0);
    $width     = floatval($_POST['width'] ?? 0);
    $thickness = intval($_POST['thickness'] ?? 20);
    $edge      = sanitize_text_field($_POST['edge'] ?? 'straight');

    // Base prices per m² by material
    $base_prices = [
        'mramor'    => 18000,
        'granit'    => 14000,
        'oniks'     => 45000,
        'travertin' => 16000,
        'kvartsit'  => 22000,
        'kvarts'    => 12000,
        'akril'     => 9000,
    ];

    // Multipliers by product type
    $type_multipliers = [
        'stoleshnitsa' => 1.0,
        'lestnitsa'    => 1.4,
        'kamin'        => 2.0,
        'pol'          => 0.85,
        'rakoviny'     => 1.6,
        'vanna'        => 3.0,
        'fasad'        => 1.1,
        'podokonnik'   => 0.9,
    ];

    // Edge multipliers
    $edge_multipliers = [
        'straight' => 1.0,
        'bevel'    => 1.05,
        'round'    => 1.08,
        'profiled' => 1.15,
        'carving'  => 1.25,
    ];

    // Thickness multiplier
    $thickness_mult = $thickness >= 40 ? 1.2 : ($thickness >= 30 ? 1.1 : 1.0);

    $base    = $base_prices[$material] ?? 14000;
    $type_m  = $type_multipliers[$type] ?? 1.0;
    $edge_m  = $edge_multipliers[$edge] ?? 1.0;
    $area    = $length * $width / 1000000; // mm² to m²
    $area    = max($area, 0.1);

    $price_min = round($base * $type_m * $edge_m * $thickness_mult * $area * 0.9, -3);
    $price_max = round($base * $type_m * $edge_m * $thickness_mult * $area * 1.1, -3);

    wp_send_json_success([
        'price_min' => number_format($price_min, 0, '.', ' '),
        'price_max' => number_format($price_max, 0, '.', ' '),
        'area'      => round($area, 2),
    ]);
}
add_action('wp_ajax_sa_calculator', 'sa_ajax_calculator');
add_action('wp_ajax_nopriv_sa_calculator', 'sa_ajax_calculator');

// ============================================================
// Widgets
// ============================================================
function sa_widgets_init() {
    register_sidebar([
        'name'          => 'Сайдбар блога',
        'id'            => 'blog-sidebar',
        'before_widget' => '<div class="sa-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="sa-widget__title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'sa_widgets_init');

// ============================================================
// Create Default Pages on Theme Activation
// ============================================================
function sa_create_default_pages() {
    $pages = [
        // Core
        'front-page'  => ['title' => 'Главная',                          'template' => ''],
        'blog'        => ['title' => 'Блог',                             'template' => ''],
        'privacy'     => ['title' => 'Политика конфиденциальности',       'template' => ''],
        'contacts'    => ['title' => 'Контакты',                         'template' => 'page-templates/contacts.php'],
        'calculator'  => ['title' => 'Калькулятор стоимости',            'template' => 'page-templates/calculator.php'],
        'faq-page'    => ['title' => 'Часто задаваемые вопросы',         'template' => 'page-templates/faq.php'],
        'portfolio-page' => ['title' => 'Портфолио',                     'template' => 'page-templates/portfolio.php'],
        // Product category landing pages
        'stoleshnitsy'      => ['title' => 'Столешницы из камня',        'template' => 'page-templates/category-landing.php'],
        'lestnitsy'         => ['title' => 'Лестницы из камня',          'template' => 'page-templates/category-landing.php'],
        'kaminy'            => ['title' => 'Камины из камня',            'template' => 'page-templates/category-landing.php'],
        'poly-i-oblitsovka' => ['title' => 'Полы и облицовка камнем',    'template' => 'page-templates/category-landing.php'],
        'rakoviny'          => ['title' => 'Раковины и мойки из камня',  'template' => 'page-templates/category-landing.php'],
        'vanny'             => ['title' => 'Ванны из камня',             'template' => 'page-templates/category-landing.php'],
        'fasady'            => ['title' => 'Фасады и экстерьер из камня','template' => 'page-templates/category-landing.php'],
        'malye-formy'       => ['title' => 'Малые архитектурные формы',  'template' => 'page-templates/category-landing.php'],
        'pamyatniki'        => ['title' => 'Памятники и мемориалы',      'template' => 'page-templates/category-landing.php'],
        // Materials
        'materials'         => ['title' => 'Каталог материалов',         'template' => 'page-templates/materials.php'],
        'materialy-mramor'  => ['title' => 'Мрамор — каталог и цены',   'template' => 'page-templates/material-single.php'],
        'materialy-granit'  => ['title' => 'Гранит — каталог и цены',   'template' => 'page-templates/material-single.php'],
        'materialy-oniks'   => ['title' => 'Оникс — каталог',           'template' => 'page-templates/material-single.php'],
        'materialy-travertin'=> ['title' => 'Травертин — каталог',      'template' => 'page-templates/material-single.php'],
        'materialy-kvartsit' => ['title' => 'Кварцит — каталог',        'template' => 'page-templates/material-single.php'],
        // Services
        'services'          => ['title' => 'Услуги',                    'template' => 'page-templates/services.php'],
        'uslugi-zamer'      => ['title' => 'Выезд замерщика',           'template' => 'page-templates/service-detail.php'],
        'uslugi-proektirovanie' => ['title' => 'Проектирование и 3D',   'template' => 'page-templates/service-detail.php'],
        'uslugi-dostavka'   => ['title' => 'Доставка',                  'template' => 'page-templates/service-detail.php'],
        'uslugi-montazh'    => ['title' => 'Монтаж и установка',        'template' => 'page-templates/service-detail.php'],
        'uslugi-restavratsiya'=> ['title' => 'Реставрация камня',       'template' => 'page-templates/service-detail.php'],
        'uslugi-ukhod'      => ['title' => 'Уход за камнем',            'template' => 'page-templates/service-detail.php'],
        // Company
        'about'             => ['title' => 'О компании',                'template' => 'page-templates/about.php'],
        'o-kompanii-showroom'=> ['title' => 'Шоурум',                   'template' => ''],
        'o-kompanii-sertifikaty'=> ['title' => 'Сертификаты',           'template' => ''],
        'o-kompanii-otzyvy' => ['title' => 'Отзывы клиентов',          'template' => ''],
        'o-kompanii-vakansii'=> ['title' => 'Вакансии',                 'template' => ''],
        // Utility
        'garantii'          => ['title' => 'Гарантии',                  'template' => 'page-templates/garantii.php'],
        'oplata'            => ['title' => 'Способы оплаты',            'template' => 'page-templates/oplata.php'],
    ];

    foreach ($pages as $slug => $data) {
        $exists = get_page_by_path($slug);
        if (!$exists) {
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
        }
    }

    // Set front page
    $front = get_page_by_path('front-page');
    if ($front) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $front->ID);
    }

    // Set blog page
    $blog = get_page_by_path('blog');
    if ($blog) {
        update_option('page_for_posts', $blog->ID);
    }

    // Create default menus
    sa_create_default_menus();

    // Import demo content
    sa_import_demo_content();

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'sa_create_default_pages');

function sa_create_default_menus() {
    $menu_name = 'Основное меню';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        // Top-level + subcategory items for mega menu
        $items = [
            ['title' => 'Изделия',    'slug' => 'services',     'parent' => 0],
            ['title' => 'Столешницы', 'slug' => 'stoleshnitsy', 'parent_title' => 'Изделия'],
            ['title' => 'Лестницы',   'slug' => 'lestnitsy',    'parent_title' => 'Изделия'],
            ['title' => 'Камины',     'slug' => 'kaminy',       'parent_title' => 'Изделия'],
            ['title' => 'Полы и облицовка', 'slug' => 'poly-i-oblitsovka', 'parent_title' => 'Изделия'],
            ['title' => 'Раковины и мойки', 'slug' => 'rakoviny', 'parent_title' => 'Изделия'],
            ['title' => 'Ванны из камня',   'slug' => 'vanny',   'parent_title' => 'Изделия'],
            ['title' => 'Фасады',     'slug' => 'fasady',       'parent_title' => 'Изделия'],
            ['title' => 'Памятники',  'slug' => 'pamyatniki',   'parent_title' => 'Изделия'],
            ['title' => 'Материалы',  'slug' => 'materials',    'parent' => 0],
            ['title' => 'Мрамор',     'slug' => 'materialy-mramor',  'parent_title' => 'Материалы'],
            ['title' => 'Гранит',     'slug' => 'materialy-granit',  'parent_title' => 'Материалы'],
            ['title' => 'Оникс',      'slug' => 'materialy-oniks',   'parent_title' => 'Материалы'],
            ['title' => 'Травертин',  'slug' => 'materialy-travertin','parent_title' => 'Материалы'],
            ['title' => 'Кварцит',    'slug' => 'materialy-kvartsit', 'parent_title' => 'Материалы'],
            ['title' => 'Услуги',     'slug' => 'services',     'parent' => 0],
            ['title' => 'Замер',      'slug' => 'uslugi-zamer', 'parent_title' => 'Услуги'],
            ['title' => 'Монтаж',     'slug' => 'uslugi-montazh','parent_title' => 'Услуги'],
            ['title' => 'Реставрация','slug' => 'uslugi-restavratsiya','parent_title' => 'Услуги'],
            ['title' => 'Портфолио',  'slug' => 'portfolio-page','parent' => 0],
            ['title' => 'Блог',       'slug' => 'blog',          'parent' => 0],
            ['title' => 'Контакты',   'slug' => 'contacts',      'parent' => 0],
        ];

        $order = 1;
        $item_ids = [];
        foreach ($items as $item) {
            $page = get_page_by_path($item['slug']);
            if (!$page) continue;

            $parent_id = 0;
            if (!empty($item['parent_title']) && isset($item_ids[$item['parent_title']])) {
                $parent_id = $item_ids[$item['parent_title']];
            }

            $id = wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title'     => $item['title'],
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => $order++,
                'menu-item-parent-id' => $parent_id,
            ]);
            $item_ids[$item['title']] = $id;
        }

        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['primary'] = $menu_id;
        $locations['mobile']  = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}

// ============================================================
// Excerpt Length
// ============================================================
function sa_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'sa_excerpt_length');

function sa_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'sa_excerpt_more');
