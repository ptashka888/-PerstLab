<?php
/**
 * ACF PRO Field Groups (Programmatic Registration)
 *
 * @package StoneArt
 */

defined('ABSPATH') || exit;

function sa_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // ================================================================
    // Options: Company Info
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_company',
        'title'    => 'Контактная информация',
        'fields'   => [
            [
                'key'   => 'field_sa_company_name',
                'label' => 'Название компании',
                'name'  => 'sa_company_name',
                'type'  => 'text',
                'default_value' => 'StoneArt',
            ],
            [
                'key'   => 'field_sa_phone',
                'label' => 'Телефон',
                'name'  => 'sa_phone',
                'type'  => 'text',
                'default_value' => '+7 (495) 000-00-00',
            ],
            [
                'key'   => 'field_sa_email',
                'label' => 'E-mail',
                'name'  => 'sa_email',
                'type'  => 'email',
                'default_value' => 'info@stoneart.ru',
            ],
            [
                'key'   => 'field_sa_address',
                'label' => 'Адрес',
                'name'  => 'sa_address',
                'type'  => 'text',
                'default_value' => 'г. Москва, ул. Каменная, д. 1 (Шоурум)',
            ],
            [
                'key'   => 'field_sa_hours',
                'label' => 'Режим работы',
                'name'  => 'sa_hours',
                'type'  => 'text',
                'default_value' => 'Пн-Вс: 09:00 — 20:00',
            ],
            [
                'key'   => 'field_sa_whatsapp',
                'label' => 'WhatsApp ссылка',
                'name'  => 'sa_whatsapp',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_sa_telegram',
                'label' => 'Telegram ссылка',
                'name'  => 'sa_telegram',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_sa_map_embed',
                'label' => 'Код карты (iframe)',
                'name'  => 'sa_map_embed',
                'type'  => 'textarea',
                'instructions' => 'Вставьте iframe-код Яндекс.Карт или Google Maps',
            ],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-kontakty']],
        ],
    ]);

    // ================================================================
    // Options: Social Media
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_social',
        'title'    => 'Соцсети и мессенджеры',
        'fields'   => [
            [
                'key'   => 'field_sa_vk',
                'label' => 'VK',
                'name'  => 'sa_vk',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_sa_instagram',
                'label' => 'Instagram',
                'name'  => 'sa_instagram',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_sa_youtube',
                'label' => 'YouTube',
                'name'  => 'sa_youtube',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_sa_yandex_zen',
                'label' => 'Яндекс.Дзен',
                'name'  => 'sa_yandex_zen',
                'type'  => 'url',
            ],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-soczseti']],
        ],
    ]);

    // ================================================================
    // Options: SEO
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_seo',
        'title'    => 'SEO настройки',
        'fields'   => [
            [
                'key'   => 'field_sa_footer_seo_text',
                'label' => 'SEO-текст подвала',
                'name'  => 'sa_footer_seo_text',
                'type'  => 'wysiwyg',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'   => 'field_sa_gtm_id',
                'label' => 'Google Tag Manager ID',
                'name'  => 'sa_gtm_id',
                'type'  => 'text',
                'placeholder' => 'GTM-XXXXXXX',
            ],
            [
                'key'   => 'field_sa_ym_id',
                'label' => 'Яндекс.Метрика ID',
                'name'  => 'sa_ym_id',
                'type'  => 'text',
            ],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-seo']],
        ],
    ]);

    // ================================================================
    // Hero Section
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_hero',
        'title'    => 'Настройки Hero-секции',
        'fields'   => [
            [
                'key'   => 'field_sa_hero_title',
                'label' => 'Заголовок (основной)',
                'name'  => 'sa_hero_title',
                'type'  => 'text',
                'default_value' => 'Изделия из натурального и искусственного камня',
            ],
            [
                'key'   => 'field_sa_hero_accent',
                'label' => 'Акцентная часть заголовка',
                'name'  => 'sa_hero_accent',
                'type'  => 'text',
                'default_value' => 'на заказ в Москве',
            ],
            [
                'key'   => 'field_sa_hero_bg',
                'label' => 'Фоновое изображение',
                'name'  => 'sa_hero_bg',
                'type'  => 'image',
                'return_format' => 'array',
            ],
            [
                'key'    => 'field_sa_hero_features',
                'label'  => 'Преимущества',
                'name'   => 'sa_hero_features',
                'type'   => 'repeater',
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'key'   => 'field_sa_feature_text',
                        'label' => 'Текст',
                        'name'  => 'sa_feature_text',
                        'type'  => 'text',
                    ],
                ],
            ],
            [
                'key'   => 'field_sa_hero_promo',
                'label' => 'Текст промо-баннера',
                'name'  => 'sa_hero_promo',
                'type'  => 'text',
                'default_value' => 'Пройдите тест за 1 минуту и получите скидку до 30 000 ₽',
            ],
            [
                'key'   => 'field_sa_hero_cta_text',
                'label' => 'Текст кнопки CTA',
                'name'  => 'sa_hero_cta_text',
                'type'  => 'text',
                'default_value' => 'Рассчитать стоимость',
            ],
            [
                'key'   => 'field_sa_hero_cta_url',
                'label' => 'Ссылка CTA',
                'name'  => 'sa_hero_cta_url',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_sa_hero_gift',
                'label' => 'Текст подарка',
                'name'  => 'sa_hero_gift',
                'type'  => 'text',
                'default_value' => 'Подарок за прохождение — набор по уходу за камнем.',
            ],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'stoneart-settings']],
        ],
        'menu_order' => 0,
    ]);

    // ================================================================
    // Materials Visualizer
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_materials',
        'title'    => 'Визуализатор материалов',
        'fields'   => [
            [
                'key'   => 'field_sa_visualizer_title',
                'label' => 'Заголовок секции',
                'name'  => 'sa_visualizer_title',
                'type'  => 'text',
                'default_value' => 'Выберите свой идеальный материал',
            ],
            [
                'key'    => 'field_sa_materials_list',
                'label'  => 'Материалы',
                'name'   => 'sa_materials_list',
                'type'   => 'repeater',
                'layout' => 'block',
                'sub_fields' => [
                    ['key' => 'field_sa_mat_key', 'label' => 'Ключ (англ)', 'name' => 'sa_mat_key', 'type' => 'text', 'wrapper' => ['width' => '20']],
                    ['key' => 'field_sa_mat_title', 'label' => 'Название', 'name' => 'sa_mat_title', 'type' => 'text', 'wrapper' => ['width' => '30']],
                    ['key' => 'field_sa_mat_short', 'label' => 'Краткое описание', 'name' => 'sa_mat_short', 'type' => 'text', 'wrapper' => ['width' => '50']],
                    ['key' => 'field_sa_mat_desc', 'label' => 'Полное описание', 'name' => 'sa_mat_desc', 'type' => 'textarea', 'rows' => 2],
                    ['key' => 'field_sa_mat_image', 'label' => 'Изображение', 'name' => 'sa_mat_image', 'type' => 'image', 'return_format' => 'array', 'wrapper' => ['width' => '33']],
                    ['key' => 'field_sa_mat_badge1', 'label' => 'Бейдж 1', 'name' => 'sa_mat_badge1', 'type' => 'text', 'wrapper' => ['width' => '33']],
                    ['key' => 'field_sa_mat_badge2', 'label' => 'Бейдж 2', 'name' => 'sa_mat_badge2', 'type' => 'text', 'wrapper' => ['width' => '33']],
                ],
            ],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'stoneart-settings']],
        ],
        'menu_order' => 10,
    ]);

    // ================================================================
    // About Section
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_about',
        'title'    => 'Секция "О компании"',
        'fields'   => [
            ['key' => 'field_sa_about_title', 'label' => 'Заголовок', 'name' => 'sa_about_title', 'type' => 'text', 'default_value' => 'Искусство в каждом камне'],
            ['key' => 'field_sa_about_text_1', 'label' => 'Текст 1', 'name' => 'sa_about_text_1', 'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0],
            ['key' => 'field_sa_about_text_2', 'label' => 'Текст 2', 'name' => 'sa_about_text_2', 'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0],
            ['key' => 'field_sa_about_image', 'label' => 'Фото', 'name' => 'sa_about_image', 'type' => 'image', 'return_format' => 'array'],
            [
                'key'    => 'field_sa_about_stats',
                'label'  => 'Статистика',
                'name'   => 'sa_about_stats',
                'type'   => 'repeater',
                'layout' => 'table',
                'max'    => 4,
                'sub_fields' => [
                    ['key' => 'field_sa_stat_number', 'label' => 'Число', 'name' => 'sa_stat_number', 'type' => 'text'],
                    ['key' => 'field_sa_stat_label', 'label' => 'Подпись', 'name' => 'sa_stat_label', 'type' => 'text'],
                ],
            ],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'stoneart-settings']],
        ],
        'menu_order' => 20,
    ]);

    // ================================================================
    // Process Steps
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_process',
        'title'    => 'Процесс работы',
        'fields'   => [
            ['key' => 'field_sa_process_title', 'label' => 'Заголовок', 'name' => 'sa_process_title', 'type' => 'text', 'default_value' => 'Простой процесс работы (до 14 дней)'],
            [
                'key'    => 'field_sa_process_steps',
                'label'  => 'Шаги',
                'name'   => 'sa_process_steps',
                'type'   => 'repeater',
                'layout' => 'table',
                'max'    => 6,
                'sub_fields' => [
                    ['key' => 'field_sa_step_title', 'label' => 'Название', 'name' => 'sa_step_title', 'type' => 'text'],
                    ['key' => 'field_sa_step_text', 'label' => 'Описание', 'name' => 'sa_step_text', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'stoneart-settings']],
        ],
        'menu_order' => 30,
    ]);

    // ================================================================
    // Team (CPT fields)
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_team_fields',
        'title'    => 'Данные сотрудника',
        'fields'   => [
            ['key' => 'field_sa_team_role', 'label' => 'Должность', 'name' => 'sa_team_role', 'type' => 'text'],
            ['key' => 'field_sa_team_bio', 'label' => 'Краткая биография', 'name' => 'sa_team_bio', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_sa_team_experience', 'label' => 'Опыт (лет)', 'name' => 'sa_team_experience', 'type' => 'number'],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'sa_team']],
        ],
    ]);

    // ================================================================
    // Review (CPT fields)
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_review_fields',
        'title'    => 'Данные отзыва',
        'fields'   => [
            ['key' => 'field_sa_review_author', 'label' => 'Имя автора', 'name' => 'sa_review_author', 'type' => 'text'],
            ['key' => 'field_sa_review_location', 'label' => 'Локация', 'name' => 'sa_review_location', 'type' => 'text'],
            ['key' => 'field_sa_review_rating', 'label' => 'Рейтинг (1-5)', 'name' => 'sa_review_rating', 'type' => 'number', 'min' => 1, 'max' => 5, 'default_value' => 5],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'sa_review']],
        ],
    ]);

    // ================================================================
    // Portfolio (CPT fields)
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_portfolio_fields',
        'title'    => 'Данные проекта',
        'fields'   => [
            ['key' => 'field_sa_portfolio_material', 'label' => 'Материал', 'name' => 'sa_portfolio_material', 'type' => 'text', 'wrapper' => ['width' => '25']],
            ['key' => 'field_sa_portfolio_area', 'label' => 'Площадь', 'name' => 'sa_portfolio_area', 'type' => 'text', 'wrapper' => ['width' => '25']],
            ['key' => 'field_sa_portfolio_duration', 'label' => 'Срок работ', 'name' => 'sa_portfolio_duration', 'type' => 'text', 'wrapper' => ['width' => '25']],
            ['key' => 'field_sa_portfolio_location', 'label' => 'Объект', 'name' => 'sa_portfolio_location', 'type' => 'text', 'wrapper' => ['width' => '25']],
            [
                'key'   => 'field_sa_portfolio_gallery',
                'label' => 'Галерея',
                'name'  => 'sa_portfolio_gallery',
                'type'  => 'gallery',
                'return_format' => 'array',
            ],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'sa_portfolio']],
        ],
    ]);

    // ================================================================
    // Service (CPT fields)
    // ================================================================
    acf_add_local_field_group([
        'key'      => 'group_sa_service_fields',
        'title'    => 'Данные услуги',
        'fields'   => [
            ['key' => 'field_sa_service_price', 'label' => 'Цена от (₽/п.м.)', 'name' => 'sa_service_price', 'type' => 'text'],
            ['key' => 'field_sa_service_features', 'label' => 'Особенности (по строке)', 'name' => 'sa_service_features', 'type' => 'textarea', 'rows' => 4, 'instructions' => 'Каждая строка — отдельная особенность'],
            ['key' => 'field_sa_service_icon', 'label' => 'Иконка (Font Awesome класс)', 'name' => 'sa_service_icon', 'type' => 'text', 'placeholder' => 'fa-solid fa-kitchen-set'],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'sa_service']],
        ],
    ]);
}
add_action('acf/init', 'sa_register_acf_fields');
