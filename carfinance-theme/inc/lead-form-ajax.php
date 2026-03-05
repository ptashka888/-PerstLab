<?php
/**
 * Lead Form AJAX Handler
 *
 * Registers the cf_lead CPT for storing form submissions,
 * handles AJAX submission, saves to DB, and sends email.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

/* ==========================================================================
   CPT: cf_lead — stores submitted leads
   ========================================================================== */

add_action('init', 'cf_register_lead_cpt');

function cf_register_lead_cpt(): void {
    register_post_type('cf_lead', [
        'labels' => [
            'name'          => 'Заявки',
            'singular_name' => 'Заявка',
            'add_new'       => 'Добавить заявку',
            'all_items'     => 'Все заявки',
            'view_item'     => 'Посмотреть заявку',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-email-alt',
        'supports'            => ['title'],
        'show_in_rest'        => false,
        'exclude_from_search' => true,
        'capabilities'        => [
            'create_posts' => 'do_not_allow',
        ],
        'map_meta_cap'        => true,
    ]);
}

/* ==========================================================================
   Admin columns for cf_lead
   ========================================================================== */

add_filter('manage_cf_lead_posts_columns', function (array $columns): array {
    return [
        'cb'               => $columns['cb'],
        'title'            => 'Заявка',
        'cf_lead_phone'    => 'Телефон',
        'cf_lead_interest' => 'Интерес',
        'cf_lead_page'     => 'Страница',
        'date'             => 'Дата',
    ];
});

add_action('manage_cf_lead_posts_custom_column', function (string $column, int $post_id): void {
    match ($column) {
        'cf_lead_phone'    => print esc_html(get_post_meta($post_id, 'cf_lead_phone', true)),
        'cf_lead_interest' => print esc_html(get_post_meta($post_id, 'cf_lead_interest', true) ?: '—'),
        'cf_lead_page'     => (function () use ($post_id) {
            $url = get_post_meta($post_id, 'cf_lead_page', true);
            if ($url) {
                echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html(parse_url($url, PHP_URL_PATH)) . '</a>';
            } else {
                echo '—';
            }
        })(),
        default => null,
    };
}, 10, 2);

/* ==========================================================================
   AJAX Handlers
   ========================================================================== */

add_action('wp_ajax_cf_lead',        'cf_handle_lead_form');
add_action('wp_ajax_nopriv_cf_lead', 'cf_handle_lead_form');

function cf_handle_lead_form(): void {
    // Verify nonce
    if (!check_ajax_referer('cf_lead_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Ошибка безопасности. Обновите страницу и попробуйте снова.'], 403);
    }

    // Sanitize & validate
    $name     = sanitize_text_field(wp_unslash($_POST['name']     ?? ''));
    $phone    = sanitize_text_field(wp_unslash($_POST['phone']    ?? ''));
    $interest = sanitize_text_field(wp_unslash($_POST['interest'] ?? ''));
    $message  = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $page_url = esc_url_raw(wp_unslash($_POST['page_url'] ?? ''));

    if (empty($name)) {
        wp_send_json_error(['message' => 'Пожалуйста, укажите ваше имя.'], 422);
    }
    if (empty($phone)) {
        wp_send_json_error(['message' => 'Пожалуйста, укажите номер телефона.'], 422);
    }

    // Store in WordPress
    $title   = sprintf('[%s] %s — %s', wp_date('d.m.Y H:i'), $name, $phone);
    $post_id = wp_insert_post([
        'post_title'  => $title,
        'post_type'   => 'cf_lead',
        'post_status' => 'publish',
    ]);

    if (!is_wp_error($post_id)) {
        update_post_meta($post_id, 'cf_lead_name',     $name);
        update_post_meta($post_id, 'cf_lead_phone',    $phone);
        update_post_meta($post_id, 'cf_lead_interest', $interest);
        update_post_meta($post_id, 'cf_lead_message',  $message);
        update_post_meta($post_id, 'cf_lead_page',     $page_url);
        update_post_meta($post_id, 'cf_lead_ip',       sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''));
    }

    // Send email notification
    $admin_email = get_option('admin_email');
    $site_name   = get_option('blogname');

    $subject = sprintf('[%s] Новая заявка: %s (%s)', $site_name, $name, $phone);

    $body  = "Новая заявка с сайта {$site_name}\n";
    $body .= str_repeat('─', 40) . "\n\n";
    $body .= "Имя:        {$name}\n";
    $body .= "Телефон:    {$phone}\n";
    if ($interest) {
        $body .= "Интересует: {$interest}\n";
    }
    if ($message) {
        $body .= "\nКомментарий:\n{$message}\n";
    }
    $body .= "\n" . str_repeat('─', 40) . "\n";
    $body .= "Дата:       " . wp_date('d.m.Y H:i') . "\n";
    if ($page_url) {
        $body .= "Страница:   {$page_url}\n";
    }
    $body .= "\nПросмотреть заявки в админке:\n" . admin_url('edit.php?post_type=cf_lead');

    wp_mail($admin_email, $subject, $body);

    wp_send_json_success(['message' => 'Заявка принята! Мы свяжемся с вами в течение 15 минут.']);
}
