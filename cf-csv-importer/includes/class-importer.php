<?php
/**
 * CF_Importer — core batch import engine.
 *
 * Reads the CSV in chunks of BATCH_SIZE rows.
 * Import session is stored as a WordPress transient keyed by a random token.
 *
 * Session transient structure:
 * {
 *   file_path  : string  — absolute path to uploaded CSV
 *   post_type  : string  — CPT slug
 *   delimiter  : string  — , or ; or \t
 *   encoding   : string  — utf-8 | windows-1251
 *   headers    : array   — first CSV row (column names)
 *   total_rows : int     — data row count (excluding header)
 *   mapping    : array   — { csv_column => wp_field_key }
 *   options    : array   — { skip_duplicates, update_existing, duplicate_key, sideload_images }
 * }
 *
 * Progress transient structure (key: cfi_progress_{session_key}):
 * {
 *   imported : int
 *   skipped  : int
 *   errors   : string[]
 * }
 *
 * @package CF_CSV_Importer
 */

defined( 'ABSPATH' ) || exit;

class CF_Importer {

	const BATCH_SIZE    = 50;
	const TRANSIENT_TTL = 7200; // 2 hours

	/* ----------------------------------------------------------------
	 * Session management
	 * ---------------------------------------------------------------- */

	/**
	 * Store an import session and return its key.
	 *
	 * @param array $session Session data.
	 * @return string Session key.
	 */
	public static function create_session( array $session ): string {
		$key = wp_generate_password( 24, false );
		set_transient( 'cfi_session_' . $key, $session, self::TRANSIENT_TTL );
		set_transient( 'cfi_progress_' . $key, [
			'imported' => 0,
			'skipped'  => 0,
			'errors'   => [],
		], self::TRANSIENT_TTL );
		return $key;
	}

	/**
	 * Load a session by key.
	 *
	 * @param string $key Session key.
	 * @return array|false Session data or false if expired/missing.
	 */
	public static function get_session( string $key ) {
		return get_transient( 'cfi_session_' . $key );
	}

	/**
	 * Update the mapping inside an existing session.
	 *
	 * @param string $key     Session key.
	 * @param array  $mapping CSV-column => WP-field mapping.
	 * @param array  $options Import options.
	 * @return bool
	 */
	public static function update_session_mapping( string $key, array $mapping, array $options ): bool {
		$session = self::get_session( $key );
		if ( ! $session ) {
			return false;
		}
		$session['mapping'] = $mapping;
		$session['options'] = $options;
		set_transient( 'cfi_session_' . $key, $session, self::TRANSIENT_TTL );
		return true;
	}

	/**
	 * Delete session + progress transients and the uploaded file.
	 *
	 * @param string $key Session key.
	 */
	public static function cleanup_session( string $key ): void {
		$session = self::get_session( $key );
		if ( $session && ! empty( $session['file_path'] ) && file_exists( $session['file_path'] ) ) {
			@unlink( $session['file_path'] );
		}
		delete_transient( 'cfi_session_' . $key );
		delete_transient( 'cfi_progress_' . $key );
	}

	/* ----------------------------------------------------------------
	 * CSV parsing
	 * ---------------------------------------------------------------- */

	/**
	 * Parse headers from a CSV file.
	 *
	 * @param string $file_path Absolute path to CSV.
	 * @param string $delimiter CSV delimiter character.
	 * @param string $encoding  File encoding (utf-8 | windows-1251).
	 * @return string[]|WP_Error Column headers or error.
	 */
	public static function parse_headers( string $file_path, string $delimiter, string $encoding ) {
		$handle = self::open_csv( $file_path );
		if ( is_wp_error( $handle ) ) {
			return $handle;
		}

		$row = fgetcsv( $handle, 0, $delimiter );
		fclose( $handle );

		if ( ! $row ) {
			return new WP_Error( 'empty_csv', 'CSV-файл пуст или заголовок не найден.' );
		}

		return self::maybe_recode( $row, $encoding );
	}

	/**
	 * Count data rows in a CSV (fast line-count, minus 1 for header).
	 *
	 * @param string $file_path Absolute path.
	 * @return int
	 */
	public static function count_rows( string $file_path ): int {
		$count  = 0;
		$handle = @fopen( $file_path, 'r' );
		if ( ! $handle ) {
			return 0;
		}
		// Skip header.
		fgets( $handle );
		while ( ! feof( $handle ) ) {
			$line = fgets( $handle );
			if ( trim( (string) $line ) !== '' ) {
				$count++;
			}
		}
		fclose( $handle );
		return $count;
	}

	/* ----------------------------------------------------------------
	 * Batch processing
	 * ---------------------------------------------------------------- */

	/**
	 * Process one batch of rows starting at $offset.
	 *
	 * @param string $session_key Import session key.
	 * @param int    $offset      Data-row offset (0 = first data row, after header).
	 * @return array {
	 *   imported   : int
	 *   skipped    : int
	 *   errors     : string[]
	 *   next_offset: int
	 *   done       : bool
	 *   processed  : int
	 * }|array{ error: string }
	 */
	public static function process_batch( string $session_key, int $offset ): array {
		$session = self::get_session( $session_key );
		if ( ! $session ) {
			return [ 'error' => 'Сессия импорта не найдена или истекла.' ];
		}

		$handle = self::open_csv( $session['file_path'] );
		if ( is_wp_error( $handle ) ) {
			return [ 'error' => $handle->get_error_message() ];
		}

		$delimiter = $session['delimiter'];
		$encoding  = $session['encoding'];

		// Read header row.
		$headers = fgetcsv( $handle, 0, $delimiter );
		if ( ! $headers ) {
			fclose( $handle );
			return [ 'error' => 'Не удалось прочитать заголовки CSV.' ];
		}
		$headers = self::maybe_recode( $headers, $encoding );

		// Seek to offset (skip already-processed rows).
		$skipped_lines = 0;
		while ( $skipped_lines < $offset ) {
			if ( fgetcsv( $handle, 0, $delimiter ) === false ) {
				break;
			}
			$skipped_lines++;
		}

		$imported  = 0;
		$skipped   = 0;
		$errors    = [];
		$processed = 0;

		while ( $processed < self::BATCH_SIZE ) {
			$row = fgetcsv( $handle, 0, $delimiter );
			if ( $row === false ) {
				break;
			}

			// Skip completely blank rows.
			if ( implode( '', $row ) === '' ) {
				$processed++;
				continue;
			}

			$row    = self::maybe_recode( $row, $encoding );
			$result = self::import_row(
				$row,
				$headers,
				$session['post_type'],
				$session['mapping'],
				$session['options']
			);

			if ( $result === 'imported' ) {
				$imported++;
			} elseif ( $result === 'skipped' ) {
				$skipped++;
			} else {
				$errors[] = sprintf( 'Строка %d: %s', $offset + $processed + 2, $result );
			}

			$processed++;
		}

		fclose( $handle );

		// Accumulate into progress transient.
		$progress = get_transient( 'cfi_progress_' . $session_key );
		if ( $progress ) {
			$progress['imported'] += $imported;
			$progress['skipped']  += $skipped;
			$progress['errors']    = array_merge( $progress['errors'], $errors );
			set_transient( 'cfi_progress_' . $session_key, $progress, self::TRANSIENT_TTL );
		}

		$done = ( $processed < self::BATCH_SIZE );

		return [
			'imported'    => $imported,
			'skipped'     => $skipped,
			'errors'      => $errors,
			'next_offset' => $offset + $processed,
			'done'        => $done,
			'processed'   => $processed,
		];
	}

	/* ----------------------------------------------------------------
	 * Single-row import
	 * ---------------------------------------------------------------- */

	/**
	 * Import one CSV row.
	 *
	 * @param array  $row       Row values.
	 * @param array  $headers   Column names (same order as $row).
	 * @param string $post_type CPT slug.
	 * @param array  $mapping   CSV-column => WP-field key.
	 * @param array  $options   Import options.
	 * @return string 'imported' | 'skipped' | error message
	 */
	private static function import_row(
		array $row,
		array $headers,
		string $post_type,
		array $mapping,
		array $options
	): string {
		// Build data map: field_key => raw_value.
		$data = [];
		foreach ( $headers as $i => $header ) {
			$field = $mapping[ $header ] ?? '__skip__';
			if ( $field && $field !== '__skip__' ) {
				$data[ $field ] = isset( $row[ $i ] ) ? trim( $row[ $i ] ) : '';
			}
		}

		if ( empty( $data['post_title'] ) ) {
			return 'Нет заголовка (post_title пустой).';
		}

		$title = sanitize_text_field( $data['post_title'] );

		// Duplicate detection.
		$existing_id = null;
		if ( ! empty( $options['skip_duplicates'] ) || ! empty( $options['update_existing'] ) ) {
			$existing_id = self::find_duplicate( $title, $data, $post_type, $options['duplicate_key'] ?? 'post_title' );
		}

		if ( $existing_id ) {
			if ( ! empty( $options['update_existing'] ) ) {
				self::save_post_data( $existing_id, $data, $post_type, $options );
				return 'imported';
			}
			return 'skipped';
		}

		// Create new post.
		$post_arr = [
			'post_type'    => $post_type,
			'post_title'   => $title,
			'post_status'  => self::sanitize_status( $data['post_status'] ?? '' ),
			'post_content' => ! empty( $data['post_content'] ) ? wp_kses_post( $data['post_content'] ) : '',
			'post_excerpt' => ! empty( $data['post_excerpt'] ) ? sanitize_textarea_field( $data['post_excerpt'] ) : '',
		];

		if ( ! empty( $data['post_name'] ) ) {
			$post_arr['post_name'] = sanitize_title( $data['post_name'] );
		}

		$post_id = wp_insert_post( $post_arr, true );

		if ( is_wp_error( $post_id ) ) {
			return $post_id->get_error_message();
		}

		self::save_post_data( $post_id, $data, $post_type, $options );

		return 'imported';
	}

	/**
	 * Save meta, taxonomies, and (optionally) featured image for a post.
	 *
	 * @param int    $post_id   Post ID.
	 * @param array  $data      field_key => raw_value map.
	 * @param string $post_type CPT slug.
	 * @param array  $options   Import options.
	 */
	private static function save_post_data( int $post_id, array $data, string $post_type, array $options ): void {
		$schema     = CF_Field_Map::get_schema();
		$cpt        = $schema[ $post_type ] ?? [];
		$meta_schema = $cpt['meta_fields'] ?? [];
		$tax_list   = array_keys( $cpt['taxonomies'] ?? [] );
		$image_field = $cpt['image_field'] ?? '';

		// Meta fields.
		foreach ( $meta_schema as $meta_key => $def ) {
			if ( ! array_key_exists( $meta_key, $data ) || $data[ $meta_key ] === '' ) {
				continue;
			}
			if ( $meta_key === $image_field ) {
				continue; // handled separately below
			}
			$sanitizer = $def['sanitize'];
			$value     = is_callable( $sanitizer )
				? $sanitizer( $data[ $meta_key ] )
				: call_user_func( $sanitizer, $data[ $meta_key ] );
			update_post_meta( $post_id, $meta_key, $value );
		}

		// Taxonomies — create terms on the fly if needed.
		foreach ( $tax_list as $tax ) {
			if ( empty( $data[ $tax ] ) ) {
				continue;
			}
			$term_names = array_filter( array_map( 'trim', explode( ',', $data[ $tax ] ) ) );
			$term_ids   = [];
			foreach ( $term_names as $term_name ) {
				$term = get_term_by( 'name', $term_name, $tax );
				if ( ! $term ) {
					$inserted = wp_insert_term( $term_name, $tax );
					if ( ! is_wp_error( $inserted ) ) {
						$term_ids[] = (int) $inserted['term_id'];
					}
				} else {
					$term_ids[] = (int) $term->term_id;
				}
			}
			if ( $term_ids ) {
				wp_set_post_terms( $post_id, $term_ids, $tax );
			}
		}

		// Featured image from URL.
		if (
			! empty( $options['sideload_images'] ) &&
			$image_field &&
			! empty( $data[ $image_field ] ) &&
			filter_var( $data[ $image_field ], FILTER_VALIDATE_URL )
		) {
			self::maybe_sideload_image( $post_id, $data[ $image_field ] );
		}
	}

	/* ----------------------------------------------------------------
	 * Helpers
	 * ---------------------------------------------------------------- */

	/**
	 * Find a duplicate post by title or meta field.
	 *
	 * @param string $title         Post title.
	 * @param array  $data          Field data map.
	 * @param string $post_type     CPT slug.
	 * @param string $duplicate_key 'post_title' or 'meta:KEY'.
	 * @return int|null Post ID or null.
	 */
	private static function find_duplicate( string $title, array $data, string $post_type, string $duplicate_key ): ?int {
		if ( $duplicate_key === 'post_title' ) {
			$post = get_page_by_title( $title, OBJECT, $post_type );
			return $post ? (int) $post->ID : null;
		}

		if ( strpos( $duplicate_key, 'meta:' ) === 0 ) {
			$meta_key = substr( $duplicate_key, 5 );
			if ( empty( $data[ $meta_key ] ) ) {
				return null;
			}
			$posts = get_posts( [
				'post_type'   => $post_type,
				'numberposts' => 1,
				'fields'      => 'ids',
				'meta_query'  => [ // phpcs:ignore WordPress.DB.SlowDBQuery
					[
						'key'   => $meta_key,
						'value' => sanitize_text_field( $data[ $meta_key ] ),
					],
				],
			] );
			return ! empty( $posts ) ? (int) $posts[0] : null;
		}

		return null;
	}

	/**
	 * Sideload an image URL and set it as the post thumbnail.
	 * Skips if post already has a thumbnail.
	 *
	 * @param int    $post_id   Post ID.
	 * @param string $image_url Remote URL.
	 */
	private static function maybe_sideload_image( int $post_id, string $image_url ): void {
		if ( has_post_thumbnail( $post_id ) ) {
			return;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$tmp = download_url( $image_url );
		if ( is_wp_error( $tmp ) ) {
			return;
		}

		$file_array = [
			'name'     => basename( wp_parse_url( $image_url, PHP_URL_PATH ) ) ?: 'import-image.jpg',
			'tmp_name' => $tmp,
		];

		$attachment_id = media_handle_sideload( $file_array, $post_id );
		@unlink( $tmp );

		if ( ! is_wp_error( $attachment_id ) ) {
			set_post_thumbnail( $post_id, $attachment_id );
		}
	}

	/**
	 * Open a CSV file for reading.
	 *
	 * @param string $file_path Absolute path.
	 * @return resource|WP_Error
	 */
	private static function open_csv( string $file_path ) {
		if ( ! file_exists( $file_path ) ) {
			return new WP_Error( 'file_missing', 'Файл CSV не найден: ' . esc_html( basename( $file_path ) ) );
		}
		$handle = @fopen( $file_path, 'r' );
		if ( ! $handle ) {
			return new WP_Error( 'file_open', 'Не удалось открыть CSV-файл.' );
		}
		return $handle;
	}

	/**
	 * Re-encode an array of strings from Windows-1251 to UTF-8 if needed.
	 *
	 * @param string[] $row      Array of strings.
	 * @param string   $encoding Source encoding (utf-8 | windows-1251).
	 * @return string[]
	 */
	private static function maybe_recode( array $row, string $encoding ): array {
		if ( strtolower( $encoding ) === 'windows-1251' ) {
			return array_map(
				fn( string $v ): string => mb_convert_encoding( $v, 'UTF-8', 'Windows-1251' ),
				$row
			);
		}
		return $row;
	}

	/**
	 * Sanitize post_status value; default to 'publish'.
	 *
	 * @param string $status Raw value.
	 * @return string
	 */
	private static function sanitize_status( string $status ): string {
		$allowed = [ 'publish', 'draft', 'pending', 'private' ];
		$status  = strtolower( trim( $status ) );
		return in_array( $status, $allowed, true ) ? $status : 'publish';
	}
}
