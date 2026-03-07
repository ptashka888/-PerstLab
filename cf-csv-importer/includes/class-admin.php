<?php
/**
 * CF_CSV_Admin — 3-step import wizard in WP Admin → Инструменты → Импорт CSV.
 *
 * Step 1: Upload CSV + choose CPT, delimiter, encoding.
 * Step 2: Map CSV columns to WordPress fields.
 * Step 3: Run batch import with live progress bar.
 *
 * @package CF_CSV_Importer
 */

defined( 'ABSPATH' ) || exit;

class CF_CSV_Admin {

	const PAGE_SLUG = 'cf-csv-importer';
	const MAX_MB    = 50; // Maximum CSV upload size in megabytes.

	/* ----------------------------------------------------------------
	 * Bootstrap
	 * ---------------------------------------------------------------- */

	public static function init(): void {
		add_action( 'admin_menu', [ self::class, 'add_menu' ] );
		add_action( 'admin_enqueue_scripts', [ self::class, 'enqueue_assets' ] );
		add_action( 'wp_ajax_cfi_process_batch', [ self::class, 'ajax_process_batch' ] );
	}

	public static function add_menu(): void {
		add_management_page(
			'Импорт CSV — CarFinance',
			'Импорт CSV авто',
			'manage_options',
			self::PAGE_SLUG,
			[ self::class, 'render_page' ]
		);
	}

	public static function enqueue_assets( string $hook ): void {
		if ( $hook !== 'tools_page_' . self::PAGE_SLUG ) {
			return;
		}
		wp_enqueue_script(
			'cfi-admin',
			CFI_URL . 'assets/importer.js',
			[ 'jquery' ],
			CFI_VERSION,
			true
		);
		wp_localize_script( 'cfi-admin', 'cfiData', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'cfi_nonce' ),
		] );
		wp_enqueue_style( 'cfi-admin', CFI_URL . 'assets/importer.css', [], CFI_VERSION );
	}

	/* ----------------------------------------------------------------
	 * Main page router
	 * ---------------------------------------------------------------- */

	public static function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Недостаточно прав.' );
		}

		$step        = isset( $_GET['step'] ) ? (int) $_GET['step'] : 1;
		$session_key = sanitize_text_field( wp_unslash( $_GET['session'] ?? '' ) );

		echo '<div class="wrap cfi-wrap">';
		echo '<h1>📥 Импорт CSV — CarFinance MSK</h1>';

		// Handle POST submissions.
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {

			if ( isset( $_POST['cfi_upload_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['cfi_upload_nonce'] ), 'cfi_upload' ) ) {
				$result = self::handle_upload();
				if ( is_array( $result ) && isset( $result['session_key'] ) ) {
					wp_safe_redirect(
						add_query_arg( [
							'page'    => self::PAGE_SLUG,
							'step'    => 2,
							'session' => $result['session_key'],
						], admin_url( 'tools.php' ) )
					);
					exit;
				}
				self::notice( 'error', is_string( $result ) ? $result : 'Ошибка загрузки файла.' );
			}

			if ( isset( $_POST['cfi_mapping_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['cfi_mapping_nonce'] ), 'cfi_mapping' ) ) {
				$result = self::handle_mapping( $session_key );
				if ( true === $result ) {
					wp_safe_redirect(
						add_query_arg( [
							'page'    => self::PAGE_SLUG,
							'step'    => 3,
							'session' => $session_key,
						], admin_url( 'tools.php' ) )
					);
					exit;
				}
				self::notice( 'error', is_string( $result ) ? $result : 'Ошибка сохранения маппинга.' );
			}
		}

		self::render_steps_nav( $step );

		switch ( $step ) {
			case 2:
				self::render_step_mapping( $session_key );
				break;
			case 3:
				self::render_step_import( $session_key );
				break;
			default:
				self::render_step_upload();
				break;
		}

		echo '</div>'; // .cfi-wrap
	}

	/* ----------------------------------------------------------------
	 * Step 1 — Upload
	 * ---------------------------------------------------------------- */

	private static function render_step_upload(): void {
		$schema    = CF_Field_Map::get_schema();
		$max_bytes = self::MAX_MB * 1024 * 1024;
		?>
		<div class="cfi-card">
			<h2>Шаг 1 — Загрузка файла</h2>
			<p>Поддерживаемые форматы: <strong>.csv</strong>. Максимальный размер: <strong><?php echo self::MAX_MB; ?> МБ</strong>.</p>

			<form method="post" enctype="multipart/form-data" class="cfi-form">
				<?php wp_nonce_field( 'cfi_upload', 'cfi_upload_nonce' ); ?>

				<table class="form-table">
					<tr>
						<th><label for="cfi_post_type">Тип объектов *</label></th>
						<td>
							<select id="cfi_post_type" name="cfi_post_type" required>
								<option value="">— Выберите —</option>
								<?php foreach ( $schema as $slug => $def ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>">
										<?php echo esc_html( $def['label'] ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="cfi_file">CSV-файл *</label></th>
						<td>
							<input type="file" id="cfi_file" name="cfi_file" accept=".csv,text/csv" required>
							<p class="description">Первая строка файла должна содержать названия колонок.</p>
						</td>
					</tr>
					<tr>
						<th><label for="cfi_delimiter">Разделитель</label></th>
						<td>
							<select id="cfi_delimiter" name="cfi_delimiter">
								<option value=",">, (запятая)</option>
								<option value=";">; (точка с запятой)</option>
								<option value="&#9;">Tab (табуляция)</option>
								<option value="|">| (вертикальная черта)</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="cfi_encoding">Кодировка</label></th>
						<td>
							<select id="cfi_encoding" name="cfi_encoding">
								<option value="utf-8">UTF-8 (стандарт)</option>
								<option value="windows-1251">Windows-1251 (1С, Excel RU)</option>
							</select>
						</td>
					</tr>
				</table>

				<p class="cfi-hint">
					<strong>Совет:</strong> экспортируйте из Excel через «Сохранить как → CSV UTF-8 (разделители — запятые)».
					Для 1С выбирайте Windows-1251 + точка с запятой.
				</p>

				<p><button type="submit" class="button button-primary button-large">Загрузить и настроить маппинг →</button></p>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle CSV upload from Step 1.
	 *
	 * @return array{session_key: string}|string Session data or error message.
	 */
	private static function handle_upload() {
		// Validate file.
		if ( empty( $_FILES['cfi_file'] ) || $_FILES['cfi_file']['error'] !== UPLOAD_ERR_OK ) {
			return 'Ошибка загрузки файла. Код: ' . ( $_FILES['cfi_file']['error'] ?? 'нет файла' );
		}

		$max_bytes = self::MAX_MB * 1024 * 1024;
		if ( $_FILES['cfi_file']['size'] > $max_bytes ) {
			return sprintf( 'Файл превышает лимит %d МБ.', self::MAX_MB );
		}

		$tmp_name  = $_FILES['cfi_file']['tmp_name'];
		$mime      = mime_content_type( $tmp_name );
		$allowed   = [ 'text/plain', 'text/csv', 'application/csv', 'application/octet-stream' ];
		if ( ! in_array( $mime, $allowed, true ) ) {
			return 'Неверный тип файла: ' . esc_html( $mime ) . '. Ожидается CSV.';
		}

		// Move to protected uploads dir.
		$upload_dir = wp_upload_dir();
		$cfi_dir    = trailingslashit( $upload_dir['basedir'] ) . 'cfi-imports';
		wp_mkdir_p( $cfi_dir );

		$filename  = sanitize_file_name( uniqid( 'cfi_', true ) . '.csv' );
		$file_path = $cfi_dir . '/' . $filename;

		if ( ! move_uploaded_file( $tmp_name, $file_path ) ) {
			return 'Не удалось переместить загруженный файл.';
		}

		$post_type = sanitize_key( wp_unslash( $_POST['cfi_post_type'] ?? '' ) );
		$delimiter = stripslashes( $_POST['cfi_delimiter'] ?? ',' );
		// Decode tab escape that can't survive HTML select properly.
		if ( $delimiter === 'tab' || $delimiter === '\\t' ) {
			$delimiter = "\t";
		}
		$encoding  = sanitize_text_field( wp_unslash( $_POST['cfi_encoding'] ?? 'utf-8' ) );

		$schema = CF_Field_Map::get_schema();
		if ( ! isset( $schema[ $post_type ] ) ) {
			@unlink( $file_path );
			return 'Неизвестный тип объектов: ' . esc_html( $post_type );
		}

		$headers = CF_Importer::parse_headers( $file_path, $delimiter, $encoding );
		if ( is_wp_error( $headers ) ) {
			@unlink( $file_path );
			return $headers->get_error_message();
		}

		$total_rows = CF_Importer::count_rows( $file_path );

		$session_key = CF_Importer::create_session( [
			'file_path'  => $file_path,
			'post_type'  => $post_type,
			'delimiter'  => $delimiter,
			'encoding'   => $encoding,
			'headers'    => $headers,
			'total_rows' => $total_rows,
			'mapping'    => [],
			'options'    => [],
		] );

		return [ 'session_key' => $session_key ];
	}

	/* ----------------------------------------------------------------
	 * Step 2 — Column mapping
	 * ---------------------------------------------------------------- */

	private static function render_step_mapping( string $session_key ): void {
		$session = CF_Importer::get_session( $session_key );
		if ( ! $session ) {
			self::notice( 'error', 'Сессия не найдена. Начните сначала.' );
			self::render_step_upload();
			return;
		}

		$headers     = $session['headers'];
		$post_type   = $session['post_type'];
		$total_rows  = $session['total_rows'];
		$field_opts  = CF_Field_Map::get_flat_options( $post_type );
		$schema      = CF_Field_Map::get_schema();
		$dup_keys    = $schema[ $post_type ]['duplicate_keys'] ?? [];
		?>
		<div class="cfi-card">
			<h2>Шаг 2 — Маппинг колонок</h2>
			<p>
				Найдено колонок: <strong><?php echo count( $headers ); ?></strong>&nbsp;
				Строк данных: <strong><?php echo number_format( $total_rows, 0, '.', ' ' ); ?></strong>
			</p>
			<p>Для каждой колонки CSV выберите соответствующее поле WordPress. Ненужные колонки — «Пропустить».</p>

			<form method="post" class="cfi-form">
				<?php wp_nonce_field( 'cfi_mapping', 'cfi_mapping_nonce' ); ?>
				<input type="hidden" name="cfi_session_key" value="<?php echo esc_attr( $session_key ); ?>">

				<h3>Маппинг колонок</h3>
				<table class="widefat cfi-mapping-table">
					<thead>
						<tr>
							<th style="width:35%">Колонка CSV</th>
							<th>Поле WordPress</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $headers as $col ) : ?>
							<tr>
								<td><code><?php echo esc_html( $col ); ?></code></td>
								<td>
									<select name="cfi_mapping[<?php echo esc_attr( $col ); ?>]" class="cfi-field-select">
										<?php foreach ( $field_opts as $key => $label ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>"
												<?php
												// Auto-guess: if field key equals column name (case-insensitive).
												$guess = strtolower( str_replace( [ '-', ' ' ], '_', $col ) );
												selected( strtolower( $key ), $guess );
												?>>
												<?php echo esc_html( $label ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<h3 style="margin-top:24px">Параметры импорта</h3>
				<table class="form-table">
					<tr>
						<th>Дублирование</th>
						<td>
							<label>
								<input type="checkbox" name="cfi_skip_duplicates" value="1" checked>
								Пропускать дубликаты (не создавать повторно)
							</label><br>
							<label style="margin-top:6px;display:block">
								<input type="checkbox" name="cfi_update_existing" value="1">
								Обновлять существующие записи
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="cfi_duplicate_key">Ключ дедупликации</label></th>
						<td>
							<select id="cfi_duplicate_key" name="cfi_duplicate_key">
								<?php foreach ( $dup_keys as $key => $label ) : ?>
									<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
							<p class="description">По какому полю определять дубликат.</p>
						</td>
					</tr>
					<tr>
						<th>Изображения</th>
						<td>
							<label>
								<input type="checkbox" name="cfi_sideload_images" value="1" checked>
								Загружать изображения из URL (медленнее, но зато миниатюры сразу)
							</label>
						</td>
					</tr>
				</table>

				<p>
					<a href="<?php echo esc_url( admin_url( 'tools.php?page=' . self::PAGE_SLUG ) ); ?>" class="button">← Назад</a>
					&nbsp;
					<button type="submit" class="button button-primary button-large">Сохранить маппинг и начать импорт →</button>
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Save column mapping from Step 2 into the session.
	 *
	 * @param string $session_key Session key.
	 * @return true|string true on success, error message on failure.
	 */
	private static function handle_mapping( string $session_key ) {
		if ( ! $session_key ) {
			return 'Отсутствует ключ сессии.';
		}

		$raw_mapping = $_POST['cfi_mapping'] ?? [];
		if ( ! is_array( $raw_mapping ) ) {
			return 'Неверный формат маппинга.';
		}

		$mapping = [];
		foreach ( $raw_mapping as $col => $field ) {
			$mapping[ sanitize_text_field( wp_unslash( $col ) ) ] = sanitize_key( wp_unslash( $field ) );
		}

		$options = [
			'skip_duplicates'  => ! empty( $_POST['cfi_skip_duplicates'] ),
			'update_existing'  => ! empty( $_POST['cfi_update_existing'] ),
			'duplicate_key'    => sanitize_text_field( wp_unslash( $_POST['cfi_duplicate_key'] ?? 'post_title' ) ),
			'sideload_images'  => ! empty( $_POST['cfi_sideload_images'] ),
		];

		$saved = CF_Importer::update_session_mapping( $session_key, $mapping, $options );
		return $saved ? true : 'Не удалось сохранить маппинг — сессия не найдена.';
	}

	/* ----------------------------------------------------------------
	 * Step 3 — Import progress
	 * ---------------------------------------------------------------- */

	private static function render_step_import( string $session_key ): void {
		$session = CF_Importer::get_session( $session_key );
		if ( ! $session || empty( $session['mapping'] ) ) {
			self::notice( 'error', 'Сессия не найдена или маппинг не настроен.' );
			return;
		}

		$total     = (int) ( $session['total_rows'] ?? 0 );
		$post_type = esc_html( $session['post_type'] );
		?>
		<div class="cfi-card" id="cfi-import-wrap">
			<h2>Шаг 3 — Импорт</h2>
			<p>
				Тип: <strong><?php echo $post_type; ?></strong>&nbsp;|&nbsp;
				Всего строк: <strong><?php echo number_format( $total, 0, '.', ' ' ); ?></strong>
			</p>

			<div class="cfi-progress-wrap" id="cfi-progress-wrap" style="display:none">
				<div class="cfi-progress-track">
					<div class="cfi-progress-bar" id="cfi-progress-bar">0%</div>
				</div>
				<div class="cfi-stats" id="cfi-stats">
					Добавлено: <strong id="cfi-stat-imported">0</strong>&nbsp;&nbsp;
					Пропущено: <strong id="cfi-stat-skipped">0</strong>&nbsp;&nbsp;
					Ошибок: <strong id="cfi-stat-errors">0</strong>
				</div>
			</div>

			<div id="cfi-done" style="display:none">
				<div class="notice notice-success inline" style="margin:16px 0">
					<p><strong>Импорт завершён!</strong></p>
					<p>
						Добавлено: <strong id="cfi-done-imported">0</strong>&nbsp;|&nbsp;
						Пропущено: <strong id="cfi-done-skipped">0</strong>&nbsp;|&nbsp;
						Ошибок: <strong id="cfi-done-errors">0</strong>
					</p>
				</div>
				<p>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . $session['post_type'] ) ); ?>" class="button button-primary">
						Перейти к записям →
					</a>
					&nbsp;
					<a href="<?php echo esc_url( admin_url( 'tools.php?page=' . self::PAGE_SLUG ) ); ?>" class="button">
						Новый импорт
					</a>
				</p>
			</div>

			<div id="cfi-error-box" style="display:none">
				<h4>Ошибки (первые 50):</h4>
				<ul id="cfi-error-list" class="cfi-error-list"></ul>
			</div>

			<p id="cfi-start-wrap">
				<button id="cfi-start-import" class="button button-primary button-large"
					data-session="<?php echo esc_attr( $session_key ); ?>"
					data-total="<?php echo esc_attr( $total ); ?>">
					▶ Запустить импорт
				</button>
			</p>
		</div>
		<?php
	}

	/* ----------------------------------------------------------------
	 * AJAX: process one batch
	 * ---------------------------------------------------------------- */

	public static function ajax_process_batch(): void {
		check_ajax_referer( 'cfi_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Недостаточно прав.' );
		}

		$session_key = sanitize_text_field( wp_unslash( $_POST['session_key'] ?? '' ) );
		$offset      = max( 0, (int) ( $_POST['offset'] ?? 0 ) );

		if ( ! $session_key ) {
			wp_send_json_error( 'Отсутствует ключ сессии.' );
		}

		$result = CF_Importer::process_batch( $session_key, $offset );

		if ( isset( $result['error'] ) ) {
			wp_send_json_error( $result['error'] );
		}

		// Clean up session file when done.
		if ( $result['done'] ) {
			CF_Importer::cleanup_session( $session_key );
		}

		wp_send_json_success( $result );
	}

	/* ----------------------------------------------------------------
	 * Helpers
	 * ---------------------------------------------------------------- */

	private static function notice( string $type, string $message ): void {
		printf(
			'<div class="notice notice-%s"><p>%s</p></div>',
			esc_attr( $type ),
			esc_html( $message )
		);
	}

	private static function render_steps_nav( int $current ): void {
		$steps = [
			1 => 'Загрузка файла',
			2 => 'Маппинг колонок',
			3 => 'Импорт',
		];
		echo '<ol class="cfi-steps">';
		foreach ( $steps as $n => $label ) {
			$class = 'cfi-step';
			if ( $n < $current ) {
				$class .= ' done';
			} elseif ( $n === $current ) {
				$class .= ' active';
			}
			printf( '<li class="%s">%d. %s</li>', esc_attr( $class ), $n, esc_html( $label ) );
		}
		echo '</ol>';
	}
}
