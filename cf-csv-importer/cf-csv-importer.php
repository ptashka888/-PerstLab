<?php
/**
 * Plugin Name: CF CSV Importer
 * Plugin URI:  https://carfinance-msk.ru
 * Description: Flexible CSV importer for CarFinance CPTs: car_model, auction_lot, case_study. Batch processing, column mapping, duplicate detection, image sideloading.
 * Version:     1.0.0
 * Author:      CarFinance MSK
 * License:     GPL-2.0+
 * Text Domain: cf-importer
 *
 * @package CF_CSV_Importer
 */

defined( 'ABSPATH' ) || exit;

define( 'CFI_VERSION', '1.0.0' );
define( 'CFI_DIR', plugin_dir_path( __FILE__ ) );
define( 'CFI_URL', plugin_dir_url( __FILE__ ) );

require_once CFI_DIR . 'includes/class-field-map.php';
require_once CFI_DIR . 'includes/class-importer.php';
require_once CFI_DIR . 'includes/class-admin.php';

add_action( 'plugins_loaded', [ 'CF_CSV_Admin', 'init' ] );

/**
 * Create upload directory on activation.
 */
register_activation_hook( __FILE__, function (): void {
	$upload_dir = wp_upload_dir();
	$cfi_dir    = trailingslashit( $upload_dir['basedir'] ) . 'cfi-imports';
	if ( ! is_dir( $cfi_dir ) ) {
		wp_mkdir_p( $cfi_dir );
		// Protect directory from direct access.
		file_put_contents( $cfi_dir . '/.htaccess', "deny from all\n" );
	}
} );

/**
 * Clean up temp files on deactivation.
 */
register_deactivation_hook( __FILE__, function (): void {
	// Remove expired transients — WordPress handles this, but clean our upload dir.
	$upload_dir = wp_upload_dir();
	$cfi_dir    = trailingslashit( $upload_dir['basedir'] ) . 'cfi-imports';
	if ( is_dir( $cfi_dir ) ) {
		$files = glob( $cfi_dir . '/*.csv' );
		if ( $files ) {
			foreach ( $files as $file ) {
				@unlink( $file );
			}
		}
	}
} );
