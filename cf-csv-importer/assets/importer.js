/**
 * CF CSV Importer — Admin JS
 *
 * Drives the Step 3 progress bar via sequential AJAX batch calls.
 * Each call sends `offset` and receives the next offset + stats.
 * Continues until `done === true`.
 *
 * Global: cfiData { ajaxUrl, nonce }
 */
jQuery( function ( $ ) {
	'use strict';

	var $startBtn     = $( '#cfi-start-import' );
	var $progressWrap = $( '#cfi-progress-wrap' );
	var $bar          = $( '#cfi-progress-bar' );
	var $statImported = $( '#cfi-stat-imported' );
	var $statSkipped  = $( '#cfi-stat-skipped' );
	var $statErrors   = $( '#cfi-stat-errors' );
	var $doneBox      = $( '#cfi-done' );
	var $doneImported = $( '#cfi-done-imported' );
	var $doneSkipped  = $( '#cfi-done-skipped' );
	var $doneErrors   = $( '#cfi-done-errors' );
	var $errorBox     = $( '#cfi-error-box' );
	var $errorList    = $( '#cfi-error-list' );
	var $startWrap    = $( '#cfi-start-wrap' );

	// Cumulative counters.
	var totalImported = 0;
	var totalSkipped  = 0;
	var allErrors     = [];
	var currentOffset = 0;
	var retries       = 0;
	var MAX_RETRIES   = 3;

	if ( ! $startBtn.length ) {
		return; // Not on step 3.
	}

	$startBtn.on( 'click', function () {
		var sessionKey = $startBtn.data( 'session' );
		var totalRows  = parseInt( $startBtn.data( 'total' ), 10 ) || 0;

		if ( ! sessionKey ) {
			alert( 'Ошибка: отсутствует ключ сессии.' );
			return;
		}

		$startBtn.prop( 'disabled', true ).text( 'Импортируется…' );
		$startWrap.find( '.description' ).remove();
		$progressWrap.show();

		runBatch( sessionKey, totalRows );
	} );

	/**
	 * Send one batch request and schedule the next.
	 *
	 * @param {string} sessionKey Import session key.
	 * @param {number} totalRows  Total data rows (for % calculation).
	 */
	function runBatch( sessionKey, totalRows ) {
		$.ajax( {
			url:      cfiData.ajaxUrl,
			method:   'POST',
			dataType: 'json',
			data: {
				action:      'cfi_process_batch',
				nonce:       cfiData.nonce,
				session_key: sessionKey,
				offset:      currentOffset,
			},
			timeout: 120000, // 2 min per batch (image sideloading can be slow)
		} )
		.done( function ( response ) {
			retries = 0;

			if ( ! response.success ) {
				showFatalError( response.data || 'Неизвестная ошибка сервера.' );
				return;
			}

			var d = response.data;

			totalImported += d.imported;
			totalSkipped  += d.skipped;
			if ( d.errors && d.errors.length ) {
				allErrors = allErrors.concat( d.errors );
			}
			currentOffset = d.next_offset;

			var pct = totalRows > 0
				? Math.min( 100, Math.round( ( currentOffset / totalRows ) * 100 ) )
				: ( d.done ? 100 : 50 );

			updateProgress( pct );

			if ( d.done ) {
				showComplete();
			} else {
				// Continue immediately — no setTimeout needed (server batching is the throttle).
				runBatch( sessionKey, totalRows );
			}
		} )
		.fail( function ( xhr, status ) {
			if ( status === 'timeout' || retries < MAX_RETRIES ) {
				retries++;
				var delay = retries * 2000; // 2s, 4s, 6s
				setTimeout( function () {
					runBatch( sessionKey, totalRows );
				}, delay );
			} else {
				showFatalError( 'Сервер не отвечает. Попробуйте обновить страницу и возобновить.' );
			}
		} );
	}

	/**
	 * Update progress bar and counters.
	 *
	 * @param {number} pct Percentage 0–100.
	 */
	function updateProgress( pct ) {
		$bar.css( 'width', pct + '%' ).text( pct + '%' );
		$statImported.text( totalImported.toLocaleString( 'ru-RU' ) );
		$statSkipped.text( totalSkipped.toLocaleString( 'ru-RU' ) );
		$statErrors.text( allErrors.length );
	}

	/**
	 * Show the completion state.
	 */
	function showComplete() {
		$bar.css( 'width', '100%' ).text( '100%' ).addClass( 'cfi-bar-done' );
		$doneImported.text( totalImported.toLocaleString( 'ru-RU' ) );
		$doneSkipped.text( totalSkipped.toLocaleString( 'ru-RU' ) );
		$doneErrors.text( allErrors.length );
		$doneBox.show();
		$startWrap.hide();

		if ( allErrors.length ) {
			var items = allErrors.slice( 0, 50 ).map( function ( e ) {
				return '<li>' + escHtml( e ) + '</li>';
			} );
			$errorList.html( items.join( '' ) );
			$errorBox.show();
			if ( allErrors.length > 50 ) {
				$errorList.append(
					'<li><em>… и ещё ' + ( allErrors.length - 50 ) + ' ошибок. Проверьте лог сервера.</em></li>'
				);
			}
		}
	}

	/**
	 * Show an unrecoverable error and stop.
	 *
	 * @param {string} msg Error message.
	 */
	function showFatalError( msg ) {
		$startBtn.prop( 'disabled', false ).text( '▶ Повторить' );
		$errorList.html( '<li class="cfi-fatal">' + escHtml( msg ) + '</li>' );
		$errorBox.show();
	}

	/**
	 * Minimal HTML escape to safely insert text into the DOM.
	 *
	 * @param {string} str Raw string.
	 * @return {string}
	 */
	function escHtml( str ) {
		return String( str )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' );
	}
} );
