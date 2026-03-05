<?php
/**
 * Custom Post Types Registration
 *
 * 7 CPTs: car_model, auction_lot, case_study, service_page,
 * faq_item, team_member, city_page
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

add_action('init', 'cf_register_post_types');

function cf_register_post_types(): void {
    // Car Model — catalog pages
    register_post_type('car_model', [
        'labels' => [
            'name'          => 'Модели',
            'singular_name' => 'Модель',
            'add_new'       => 'Добавить модель',
            'add_new_item'  => 'Новая модель авто',
            'edit_item'     => 'Редактировать модель',
            'all_items'     => 'Все модели',
        ],
        'public'       => true,
        'has_archive'  => 'catalog',
        'rewrite'      => ['slug' => 'catalog/%car_brand%', 'with_front' => false],
        'menu_icon'    => 'dashicons-car',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'],
        'show_in_rest' => true,
    ]);

    // Auction Lot
    register_post_type('auction_lot', [
        'labels' => [
            'name'          => 'Лоты',
            'singular_name' => 'Лот',
            'add_new'       => 'Добавить лот',
            'add_new_item'  => 'Новый лот',
            'edit_item'     => 'Редактировать лот',
            'all_items'     => 'Все лоты',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'car', 'with_front' => false],
        'menu_icon'    => 'dashicons-hammer',
        'supports'     => ['title', 'thumbnail', 'custom-fields'],
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
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'cases', 'with_front' => false],
        'menu_icon'    => 'dashicons-awards',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest' => true,
    ]);

    // Service Page
    register_post_type('service_page', [
        'labels' => [
            'name'          => 'Услуги',
            'singular_name' => 'Услуга',
            'add_new'       => 'Добавить услугу',
            'all_items'     => 'Все услуги',
        ],
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'services', 'with_front' => false],
        'menu_icon'    => 'dashicons-admin-tools',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        'show_in_rest' => true,
    ]);

    // FAQ Item (no public URL)
    register_post_type('faq_item', [
        'labels' => [
            'name'          => 'FAQ',
            'singular_name' => 'Вопрос-ответ',
            'add_new'       => 'Добавить вопрос',
            'all_items'     => 'Все вопросы',
        ],
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => false,
        'rewrite'            => false,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => ['title', 'editor', 'custom-fields', 'page-attributes'],
        'show_in_rest'       => true,
        'exclude_from_search'=> true,
    ]);

    // Team Member (no public URL)
    register_post_type('team_member', [
        'labels' => [
            'name'          => 'Команда',
            'singular_name' => 'Сотрудник',
            'add_new'       => 'Добавить сотрудника',
            'all_items'     => 'Вся команда',
        ],
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => false,
        'rewrite'            => false,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => ['title', 'thumbnail', 'editor', 'custom-fields', 'page-attributes'],
        'show_in_rest'       => true,
        'exclude_from_search'=> true,
    ]);

    // City Page (multisite content, no public URL on main site)
    register_post_type('city_page', [
        'labels' => [
            'name'          => 'Города',
            'singular_name' => 'Город',
            'add_new'       => 'Добавить город',
            'all_items'     => 'Все города',
        ],
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => false,
        'rewrite'            => false,
        'menu_icon'          => 'dashicons-location',
        'supports'           => ['title', 'thumbnail', 'custom-fields'],
        'show_in_rest'       => true,
        'exclude_from_search'=> true,
    ]);
}

/**
 * Fix car_model permalink to include brand slug.
 */
add_filter('post_type_link', function (string $post_link, WP_Post $post): string {
    if ($post->post_type !== 'car_model') {
        return $post_link;
    }

    $brands = get_the_terms($post->ID, 'car_brand');
    if ($brands && !is_wp_error($brands)) {
        $brand_slug = $brands[0]->slug;
    } else {
        $brand_slug = 'uncategorized';
    }

    return str_replace('%car_brand%', $brand_slug, $post_link);
}, 10, 2);
