<?php
/**
 * Custom Taxonomies Registration
 *
 * 8 taxonomies: car_country, car_brand, car_type, price_range,
 * catalog_tag, service_cat, blog_topic, city_term
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

add_action('init', 'cf_register_taxonomies');

function cf_register_taxonomies(): void {
    // Country of origin
    register_taxonomy('car_country', ['car_model', 'auction_lot', 'case_study'], [
        'labels' => [
            'name'          => 'Страна',
            'singular_name' => 'Страна',
            'add_new_item'  => 'Добавить страну',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'catalog/country', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);

    // Car Brand — hierarchical, URL: /catalog/toyota/
    register_taxonomy('car_brand', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Марка',
            'singular_name' => 'Марка',
            'add_new_item'  => 'Добавить марку',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'catalog', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);

    // Body type
    register_taxonomy('car_type', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Тип кузова',
            'singular_name' => 'Тип кузова',
            'add_new_item'  => 'Добавить тип кузова',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'catalog/type', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);

    // Price range
    register_taxonomy('price_range', ['car_model', 'auction_lot'], [
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

    // Catalog Tag — SEO tag pages: /catalog/tags/krossovery-iz-korei/
    register_taxonomy('catalog_tag', ['car_model'], [
        'labels' => [
            'name'          => 'SEO-теги каталога',
            'singular_name' => 'SEO-тег',
            'add_new_item'  => 'Добавить SEO-тег',
            'search_items'  => 'Искать теги',
            'popular_items' => 'Популярные теги',
        ],
        'public'       => true,
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'catalog/tags', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);

    // Service category
    register_taxonomy('service_cat', ['service_page'], [
        'labels' => [
            'name'          => 'Категория услуги',
            'singular_name' => 'Категория',
        ],
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => true,
        'rewrite'      => false,
        'show_in_rest' => true,
    ]);

    // Blog topic (Hub & Spoke clusters)
    register_taxonomy('blog_topic', ['post'], [
        'labels' => [
            'name'          => 'Тема блога',
            'singular_name' => 'Тема',
            'add_new_item'  => 'Добавить тему',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'blog/topic', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);

    // City term
    register_taxonomy('city_term', ['case_study', 'auction_lot'], [
        'labels' => [
            'name'          => 'Город',
            'singular_name' => 'Город',
        ],
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => false,
        'rewrite'      => false,
        'show_in_rest' => true,
    ]);

    // FAQ category (kept from v1)
    register_taxonomy('cf_faq_cat', ['faq_item'], [
        'labels' => [
            'name'          => 'Раздел FAQ',
            'singular_name' => 'Раздел FAQ',
        ],
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => true,
        'rewrite'      => false,
        'show_in_rest' => true,
    ]);

    // Generation — /catalog/toyota/camry/xv70/
    register_taxonomy('generation', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Поколение',
            'singular_name' => 'Поколение',
            'add_new_item'  => 'Добавить поколение',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'catalog/generation', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);

    // Engine type (Бензин, Дизель, Гибрид, Электро, ГБО) — taxonomy for SEO pages
    register_taxonomy('engine_type', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Тип двигателя',
            'singular_name' => 'Тип двигателя',
            'add_new_item'  => 'Добавить тип двигателя',
        ],
        'public'       => true,
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'catalog/dvigatel', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);

    // Transmission type (АКПП, МКПП, Робот, Вариатор) — taxonomy for SEO pages
    register_taxonomy('transmission_type', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Коробка передач',
            'singular_name' => 'Коробка передач',
            'add_new_item'  => 'Добавить КПП',
        ],
        'public'       => true,
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'catalog/kpp', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => false,
    ]);

    // Drive type (Передний, Задний, Полный) — taxonomy for SEO pages
    register_taxonomy('drive_type', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Тип привода',
            'singular_name' => 'Тип привода',
            'add_new_item'  => 'Добавить привод',
        ],
        'public'       => true,
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'catalog/privod', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => false,
    ]);

    // Car color taxonomy — for SEO pages and filter
    register_taxonomy('car_color', ['car_model', 'auction_lot'], [
        'labels' => [
            'name'          => 'Цвет',
            'singular_name' => 'Цвет',
            'add_new_item'  => 'Добавить цвет',
        ],
        'public'       => true,
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'catalog/color', 'with_front' => false],
        'show_in_rest' => true,
        'show_admin_column' => false,
    ]);
}
