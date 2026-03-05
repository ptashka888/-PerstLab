<?php
/**
 * CarFinance MSK Theme Functions
 *
 * Slim bootstrap: constants, theme supports, enqueue, includes.
 * All logic is delegated to inc/*.php modules.
 *
 * @package CarFinance
 * @version 2.0.0
 */

defined('ABSPATH') || exit;

define('CF_VERSION', '2.0.0');
define('CF_DIR', get_template_directory());
define('CF_URI', get_template_directory_uri());

/* ==========================================================================
   1. Include Modules
   ========================================================================== */

require_once CF_DIR . '/inc/helpers.php';
require_once CF_DIR . '/inc/cpt.php';
require_once CF_DIR . '/inc/taxonomies.php';
require_once CF_DIR . '/inc/acf-fields.php';
require_once CF_DIR . '/inc/schema.php';
require_once CF_DIR . '/inc/seo.php';
require_once CF_DIR . '/inc/breadcrumbs.php';
require_once CF_DIR . '/inc/interlinking.php';
require_once CF_DIR . '/inc/catalog-filter.php';
require_once CF_DIR . '/inc/catalog-tags.php';
require_once CF_DIR . '/inc/calculator-ajax.php';
require_once CF_DIR . '/inc/multisite.php';

/* ==========================================================================
   2. Theme Setup
   ========================================================================== */

add_action('after_setup_theme', function (): void {
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

    add_image_size('cf-card', 600, 375, true);
    add_image_size('cf-lot', 480, 360, true);
    add_image_size('cf-hero', 1200, 600, true);
    add_image_size('cf-team', 240, 240, true);
    add_image_size('cf-gallery', 800, 600, true);

    register_nav_menus([
        'primary'   => 'Основное меню',
        'countries' => 'Меню стран',
        'footer_1'  => 'Подвал — Направления',
        'footer_2'  => 'Подвал — Услуги',
        'footer_3'  => 'Подвал — Информация',
    ]);
});

/* ==========================================================================
   3. Enqueue Scripts & Styles
   ========================================================================== */

add_action('wp_enqueue_scripts', function (): void {
    $css_dir = CF_DIR . '/assets/css';
    $css_uri = CF_URI . '/assets/css';

    // Google Fonts (no preconnect via wp_enqueue_style — preconnect is handled in wp_head below)
    wp_enqueue_style('cf-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap', [], null);

    // Base CSS: variables → reset → layout (strict dependency chain)
    $base_files = [
        'variables' => [],
        'reset'     => ['cf-variables'],
        'layout'    => ['cf-variables', 'cf-reset'],
    ];
    foreach ($base_files as $file => $deps) {
        $path = $css_dir . '/' . $file . '.css';
        if (file_exists($path)) {
            wp_enqueue_style('cf-' . $file, $css_uri . '/' . $file . '.css', $deps, filemtime($path));
        }
    }

    // Component CSS (each depends on variables)
    $component_files = ['buttons', 'cards', 'forms', 'header', 'footer', 'modal', 'nav'];
    foreach ($component_files as $file) {
        $path = $css_dir . '/components/' . $file . '.css';
        if (file_exists($path)) {
            wp_enqueue_style('cf-comp-' . $file, $css_uri . '/components/' . $file . '.css', ['cf-variables'], filemtime($path));
        }
    }

    // Catalog CSS (only on catalog/taxonomy pages)
    if (is_post_type_archive('car_model') || is_tax('car_brand') || is_tax('catalog_tag') || is_tax('car_type') || is_tax('car_country')) {
        $catalog_path = $css_dir . '/components/catalog.css';
        if (file_exists($catalog_path)) {
            wp_enqueue_style('cf-comp-catalog', $css_uri . '/components/catalog.css', ['cf-variables', 'cf-comp-forms'], filemtime($catalog_path));
        }
    }

    // Responsive CSS (depends on layout and all components)
    $responsive_path = $css_dir . '/responsive.css';
    if (file_exists($responsive_path)) {
        wp_enqueue_style('cf-responsive', $css_uri . '/responsive.css', ['cf-layout'], filemtime($responsive_path));
    }

    // Main theme stylesheet (theme declaration only — no actual styles)
    wp_enqueue_style('cf-style', get_stylesheet_uri(), ['cf-responsive'], CF_VERSION);

    // Calculator JS
    wp_enqueue_script('cf-calculator', CF_URI . '/assets/js/calculator.js', [], CF_VERSION, true);
    wp_localize_script('cf-calculator', 'cfCalcData', [
        'ajaxUrl'  => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('cf_calc_nonce'),
        'currency' => 'RUB',
    ]);

    // Catalog filter JS (only on catalog/tag pages)
    if (is_post_type_archive('car_model') || is_tax('car_brand') || is_tax('catalog_tag') || is_tax('car_type') || is_tax('car_country')) {
        wp_enqueue_script('cf-catalog-filter', CF_URI . '/assets/js/catalog-filter.js', [], CF_VERSION, true);
        wp_localize_script('cf-catalog-filter', 'cfCatalog', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('cf_catalog_nonce'),
        ]);
    }

    // Main JS
    wp_enqueue_script('cf-main', CF_URI . '/assets/js/main.js', [], CF_VERSION, true);
});

/* ==========================================================================
   4. Widgets
   ========================================================================== */

add_action('widgets_init', function (): void {
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
   5. Performance & Security
   ========================================================================== */

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

add_action('init', function (): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
});

add_action('wp_head', function (): void {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
}, 1);

/* ==========================================================================
   6. Custom Rewrite Rules
   ========================================================================== */

add_action('init', 'cf_custom_rewrite_rules');

function cf_custom_rewrite_rules(): void {
    // Country pages: flat URLs
    $country_slugs = [
        'avto-iz-korei',
        'avto-iz-yaponii',
        'avto-iz-kitaya',
        'avto-iz-usa',
        'avto-iz-oae',
    ];
    foreach ($country_slugs as $slug) {
        add_rewrite_rule(
            '^' . $slug . '/?$',
            'index.php?pagename=' . $slug,
            'top'
        );
    }

    // Calculator
    add_rewrite_rule('^calculator/?$', 'index.php?pagename=calculator', 'top');
}

/* ==========================================================================
   7. Admin Customization
   ========================================================================== */

add_filter('manage_car_model_posts_columns', function (array $columns): array {
    $new = [];
    foreach ($columns as $key => $value) {
        $new[$key] = $value;
        if ($key === 'title') {
            $new['cf_brand_col']   = 'Марка';
            $new['cf_country_col'] = 'Страна';
            $new['cf_price_col']   = 'Цена';
        }
    }
    return $new;
});

add_action('manage_car_model_posts_custom_column', function (string $column, int $post_id): void {
    match ($column) {
        'cf_brand_col' => (function () use ($post_id) {
            $terms = get_the_terms($post_id, 'car_brand');
            echo $terms ? esc_html($terms[0]->name) : '—';
        })(),
        'cf_country_col' => (function () use ($post_id) {
            $terms = get_the_terms($post_id, 'car_country');
            echo $terms ? esc_html($terms[0]->name) : '—';
        })(),
        'cf_price_col' => (function () use ($post_id) {
            $price = function_exists('get_field')
                ? get_field('model_price_turnkey', $post_id)
                : get_post_meta($post_id, 'model_price_turnkey', true);
            echo $price ? cf_format_price((int) $price) : '—';
        })(),
        default => null,
    };
}, 10, 2);

/* ==========================================================================
   8. Theme Activation: Create Default Pages
   ========================================================================== */

add_action('after_switch_theme', 'cf_create_default_pages');

function cf_create_default_pages(): void {
    $pages = [
        'avto-iz-korei'   => ['title' => 'Автомобили из Кореи под заказ',  'template' => 'page-country.php'],
        'avto-iz-yaponii' => ['title' => 'Авто из Японии с аукционов',     'template' => 'page-country.php'],
        'avto-iz-kitaya'  => ['title' => 'Китайские авто: EV, гибриды',    'template' => 'page-country.php'],
        'avto-iz-usa'     => ['title' => 'Авто из США: Copart, IAAI',      'template' => 'page-country.php'],
        'avto-iz-oae'     => ['title' => 'Авто из ОАЭ: параллельный импорт', 'template' => 'page-country.php'],
        'calculator'      => ['title' => 'Калькулятор растаможки авто 2026', 'template' => 'page-calculator.php'],
        'services'        => ['title' => 'Услуги',  'template' => 'page-service.php'],
        'o-kompanii'      => ['title' => 'О компании', 'template' => ''],
        'faq'             => ['title' => 'FAQ',     'template' => ''],
        'blog'            => ['title' => 'Блог',    'template' => ''],
    ];

    foreach ($pages as $slug => $data) {
        if (get_page_by_path($slug)) {
            continue;
        }

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

    flush_rewrite_rules();
}
