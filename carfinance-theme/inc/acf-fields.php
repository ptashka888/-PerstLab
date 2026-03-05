<?php
/**
 * ACF Pro Integration
 *
 * Registers ACF options pages, JSON save/load paths,
 * admin notices, and fallback meta boxes when ACF is not active.
 *
 * @package CarFinance
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Initialize ACF options pages.
 *
 * Hooked to `acf/init`.
 */
function cf_acf_init() {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        return;
    }

    acf_add_options_page( [
        'page_title' => 'Настройки CarFinance',
        'menu_title' => 'CF Настройки',
        'menu_slug'  => 'cf-settings',
        'capability' => 'manage_options',
        'position'   => 2,
        'icon_url'   => 'dashicons-car',
    ] );

    acf_add_options_sub_page( [
        'page_title'  => 'Калькулятор — курсы и ставки',
        'menu_title'  => 'Калькулятор',
        'parent_slug' => 'cf-settings',
    ] );

    acf_add_options_sub_page( [
        'page_title'  => 'Контакты и соцсети',
        'menu_title'  => 'Контакты',
        'parent_slug' => 'cf-settings',
    ] );
}
add_action( 'acf/init', 'cf_acf_init' );

/**
 * Set ACF JSON save path to theme directory.
 *
 * @param string $path Default save path.
 * @return string Custom save path.
 */
function cf_acf_json_save_path( $path ) {
    $custom_path = defined( 'CF_DIR' ) ? CF_DIR . '/acf-json' : get_stylesheet_directory() . '/acf-json';

    if ( ! is_dir( $custom_path ) ) {
        wp_mkdir_p( $custom_path );
    }

    return $custom_path;
}
add_filter( 'acf/settings/save_json', 'cf_acf_json_save_path' );

/**
 * Add ACF JSON load path from theme directory.
 *
 * @param array $paths Existing load paths.
 * @return array Modified load paths.
 */
function cf_acf_json_load_paths( $paths ) {
    $custom_path = defined( 'CF_DIR' ) ? CF_DIR . '/acf-json' : get_stylesheet_directory() . '/acf-json';

    if ( is_dir( $custom_path ) ) {
        $paths[] = $custom_path;
    }

    return $paths;
}
add_filter( 'acf/settings/load_json', 'cf_acf_json_load_paths' );

/**
 * Show admin notice if ACF Pro is not active.
 */
function cf_acf_admin_notice() {
    if ( function_exists( 'acf_add_options_page' ) ) {
        return;
    }

    $screen = get_current_screen();
    if ( ! $screen || $screen->id === 'plugins' ) {
        // Don't nag on the plugins page where they might be activating it.
    }

    echo '<div class="notice notice-warning is-dismissible">';
    echo '<p><strong>CarFinance MSK:</strong> Тема CarFinance MSK требует плагин ACF Pro для полной функциональности. Базовые функции работают без ACF.</p>';
    echo '</div>';
}
add_action( 'admin_notices', 'cf_acf_admin_notice' );

/**
 * Register fallback meta boxes when ACF is not active.
 *
 * Provides basic fields for car_model CPT: price, year, engine.
 */
function cf_acf_register_fallback_fields() {
    if ( function_exists( 'acf_add_options_page' ) ) {
        return;
    }

    add_meta_box(
        'cf_car_model_fields',
        'Параметры автомобиля',
        'cf_fallback_car_model_meta_box',
        'car_model',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cf_acf_register_fallback_fields' );

/**
 * Render fallback meta box for car_model.
 *
 * @param WP_Post $post Current post object.
 */
function cf_fallback_car_model_meta_box( $post ) {
    wp_nonce_field( 'cf_car_model_meta', 'cf_car_model_meta_nonce' );

    $price_from   = get_post_meta( $post->ID, 'cf_price_from', true );
    $year         = get_post_meta( $post->ID, 'cf_year', true );
    $engine_cc    = get_post_meta( $post->ID, 'cf_engine_cc', true );
    $engine_type  = get_post_meta( $post->ID, 'cf_engine_type', true );
    $fuel         = get_post_meta( $post->ID, 'cf_fuel', true );
    $transmission = get_post_meta( $post->ID, 'cf_transmission', true );
    $drive        = get_post_meta( $post->ID, 'cf_drive', true );
    $hp           = get_post_meta( $post->ID, 'cf_hp', true );

    $engine_types = [
        ''        => '— Выберите —',
        'petrol'  => 'Бензин',
        'diesel'  => 'Дизель',
        'electric' => 'Электро',
        'hybrid'  => 'Гибрид',
    ];

    $transmissions = [
        ''         => '— Выберите —',
        'auto'     => 'Автомат',
        'manual'   => 'Механика',
        'robot'    => 'Робот',
        'cvt'      => 'Вариатор',
    ];

    $drives = [
        ''    => '— Выберите —',
        'fwd' => 'Передний',
        'rwd' => 'Задний',
        'awd' => 'Полный',
    ];
    ?>
    <table class="form-table">
        <tr>
            <th><label for="cf_price_from">Цена от (₽)</label></th>
            <td>
                <input type="number" id="cf_price_from" name="cf_price_from"
                       value="<?php echo esc_attr( $price_from ); ?>"
                       class="regular-text" min="0" step="1000">
            </td>
        </tr>
        <tr>
            <th><label for="cf_year">Год выпуска</label></th>
            <td>
                <input type="number" id="cf_year" name="cf_year"
                       value="<?php echo esc_attr( $year ); ?>"
                       class="small-text" min="1990" max="<?php echo esc_attr( gmdate( 'Y' ) + 1 ); ?>">
            </td>
        </tr>
        <tr>
            <th><label for="cf_engine_cc">Объём двигателя (куб.см)</label></th>
            <td>
                <input type="number" id="cf_engine_cc" name="cf_engine_cc"
                       value="<?php echo esc_attr( $engine_cc ); ?>"
                       class="small-text" min="0" max="10000">
            </td>
        </tr>
        <tr>
            <th><label for="cf_hp">Мощность (л.с.)</label></th>
            <td>
                <input type="number" id="cf_hp" name="cf_hp"
                       value="<?php echo esc_attr( $hp ); ?>"
                       class="small-text" min="0" max="2000">
            </td>
        </tr>
        <tr>
            <th><label for="cf_engine_type">Тип двигателя</label></th>
            <td>
                <select id="cf_engine_type" name="cf_engine_type">
                    <?php foreach ( $engine_types as $val => $label ) : ?>
                        <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $engine_type, $val ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="cf_fuel">Топливо</label></th>
            <td>
                <input type="text" id="cf_fuel" name="cf_fuel"
                       value="<?php echo esc_attr( $fuel ); ?>"
                       class="regular-text" placeholder="АИ-95, ДТ, электро...">
            </td>
        </tr>
        <tr>
            <th><label for="cf_transmission">Трансмиссия</label></th>
            <td>
                <select id="cf_transmission" name="cf_transmission">
                    <?php foreach ( $transmissions as $val => $label ) : ?>
                        <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $transmission, $val ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="cf_drive">Привод</label></th>
            <td>
                <select id="cf_drive" name="cf_drive">
                    <?php foreach ( $drives as $val => $label ) : ?>
                        <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $drive, $val ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save fallback meta box data for car_model.
 *
 * @param int $post_id Post ID.
 */
function cf_fallback_car_model_meta_save( $post_id ) {
    // Verify nonce.
    if ( ! isset( $_POST['cf_car_model_meta_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['cf_car_model_meta_nonce'], 'cf_car_model_meta' ) ) {
        return;
    }

    // Check autosave.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Only for car_model.
    if ( get_post_type( $post_id ) !== 'car_model' ) {
        return;
    }

    $fields = [
        'cf_price_from'   => 'absint',
        'cf_year'         => 'absint',
        'cf_engine_cc'    => 'absint',
        'cf_hp'           => 'absint',
        'cf_engine_type'  => 'sanitize_text_field',
        'cf_fuel'         => 'sanitize_text_field',
        'cf_transmission' => 'sanitize_text_field',
        'cf_drive'        => 'sanitize_text_field',
    ];

    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[ $key ] ) ) {
            $value = call_user_func( $sanitizer, wp_unslash( $_POST[ $key ] ) );
            update_post_meta( $post_id, $key, $value );
        }
    }
}
add_action( 'save_post', 'cf_fallback_car_model_meta_save' );
