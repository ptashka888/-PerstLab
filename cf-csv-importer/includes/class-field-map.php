<?php
/**
 * CF_Field_Map — defines all importable fields per CPT.
 *
 * Each CPT entry has:
 *   post_fields  — WP_Post columns (post_title, post_content, etc.)
 *   meta_fields  — custom meta keys with label + sanitizer callback name
 *   taxonomies   — taxonomy slug => label
 *   image_field  — which meta key carries the image URL (optional)
 *   duplicate_keys — suggested dedup fields shown in the UI
 *
 * To add a new CPT: copy one entry, adjust keys, add to get_schema() array.
 *
 * @package CF_CSV_Importer
 */

defined( 'ABSPATH' ) || exit;

class CF_Field_Map {

	/**
	 * Return full schema for all supported post types.
	 *
	 * @return array<string, array>
	 */
	public static function get_schema(): array {
		return [

			/* ----------------------------------------------------------------
			 * car_model — Catalog pages
			 * ---------------------------------------------------------------- */
			'car_model' => [
				'label'          => 'Модели авто (car_model)',
				'image_field'    => 'cf_image_url',
				'duplicate_keys' => [
					'post_title' => 'Название модели',
					'meta:cf_vin' => 'VIN',
				],
				'post_fields'    => [
					'post_title'   => 'Название модели *',
					'post_content' => 'Описание (HTML)',
					'post_excerpt' => 'Краткое описание',
					'post_name'    => 'Slug (URL)',
					'post_status'  => 'Статус (publish / draft)',
				],
				'meta_fields'    => [
					'cf_price_from'        => [ 'label' => 'Цена от (₽)',              'sanitize' => 'absint' ],
					'cf_price_to'          => [ 'label' => 'Цена до (₽)',              'sanitize' => 'absint' ],
					'cf_price_turnkey'     => [ 'label' => 'Цена под ключ (₽)',        'sanitize' => 'absint' ],
					'cf_year'              => [ 'label' => 'Год выпуска',              'sanitize' => 'absint' ],
					'cf_engine_cc'         => [ 'label' => 'Объём двигателя (куб.см)', 'sanitize' => 'absint' ],
					'cf_hp'                => [ 'label' => 'Мощность (л.с.)',          'sanitize' => 'absint' ],
					'cf_torque'            => [ 'label' => 'Крутящий момент (Нм)',     'sanitize' => 'absint' ],
					'cf_engine_type'       => [ 'label' => 'Тип двигателя (petrol / diesel / electric / hybrid)', 'sanitize' => 'sanitize_text_field' ],
					'cf_fuel'              => [ 'label' => 'Топливо (строка)',         'sanitize' => 'sanitize_text_field' ],
					'cf_transmission'      => [ 'label' => 'Трансмиссия (auto / manual / robot / cvt)', 'sanitize' => 'sanitize_text_field' ],
					'cf_drive'             => [ 'label' => 'Привод (fwd / rwd / awd)', 'sanitize' => 'sanitize_text_field' ],
					'cf_clearance'         => [ 'label' => 'Клиренс (мм)',             'sanitize' => 'absint' ],
					'cf_length'            => [ 'label' => 'Длина (мм)',               'sanitize' => 'absint' ],
					'cf_width'             => [ 'label' => 'Ширина (мм)',              'sanitize' => 'absint' ],
					'cf_height'            => [ 'label' => 'Высота (мм)',              'sanitize' => 'absint' ],
					'cf_wheelbase'         => [ 'label' => 'Колёсная база (мм)',       'sanitize' => 'absint' ],
					'cf_seats'             => [ 'label' => 'Кол-во мест',             'sanitize' => 'absint' ],
					'cf_color'             => [ 'label' => 'Цвет',                    'sanitize' => 'sanitize_text_field' ],
					'cf_vin'               => [ 'label' => 'VIN',                     'sanitize' => 'sanitize_text_field' ],
					'cf_customs_duty'      => [ 'label' => 'Размер пошлины (₽)',      'sanitize' => 'absint' ],
					'cf_delivery_days'     => [ 'label' => 'Срок доставки (дни)',     'sanitize' => 'absint' ],
					'cf_generation'        => [ 'label' => 'Поколение',               'sanitize' => 'sanitize_text_field' ],
					'cf_image_url'         => [ 'label' => 'URL главного фото',       'sanitize' => 'esc_url_raw' ],
				],
				'taxonomies'     => [
					'car_brand'        => 'Марка',
					'car_country'      => 'Страна',
					'car_type'         => 'Тип кузова',
					'engine_type'      => 'Тип двигателя (таксономия)',
					'transmission_type'=> 'Трансмиссия (таксономия)',
					'drive_type'       => 'Привод (таксономия)',
					'price_range'      => 'Ценовой диапазон',
					'catalog_tag'      => 'Теги каталога',
				],
			],

			/* ----------------------------------------------------------------
			 * auction_lot — Live auction lots
			 * ---------------------------------------------------------------- */
			'auction_lot' => [
				'label'          => 'Лоты аукционов (auction_lot)',
				'image_field'    => 'lot_image_url',
				'duplicate_keys' => [
					'post_title'  => 'Название лота',
					'meta:lot_vin' => 'VIN',
					'meta:lot_lot_number' => 'Номер лота',
				],
				'post_fields'    => [
					'post_title'  => 'Название лота *',
					'post_status' => 'Статус (publish / draft)',
				],
				'meta_fields'    => [
					'lot_vin'          => [ 'label' => 'VIN',                         'sanitize' => 'sanitize_text_field' ],
					'lot_lot_number'   => [ 'label' => 'Номер лота',                  'sanitize' => 'sanitize_text_field' ],
					'lot_price_usd'    => [ 'label' => 'Цена аукциона ($)',           'sanitize' => 'absint' ],
					'lot_price_rub'    => [ 'label' => 'Цена под ключ (₽)',          'sanitize' => 'absint' ],
					'lot_year'         => [ 'label' => 'Год',                         'sanitize' => 'absint' ],
					'lot_mileage'      => [ 'label' => 'Пробег (км)',                 'sanitize' => 'absint' ],
					'lot_engine_cc'    => [ 'label' => 'Объём (куб.см)',              'sanitize' => 'absint' ],
					'lot_hp'           => [ 'label' => 'Мощность (л.с.)',             'sanitize' => 'absint' ],
					'lot_fuel'         => [ 'label' => 'Топливо',                     'sanitize' => 'sanitize_text_field' ],
					'lot_transmission' => [ 'label' => 'Трансмиссия',                'sanitize' => 'sanitize_text_field' ],
					'lot_color'        => [ 'label' => 'Цвет',                        'sanitize' => 'sanitize_text_field' ],
					'lot_grade'        => [ 'label' => 'Оценка (3A / 4 / 4.5 / 5)',  'sanitize' => 'sanitize_text_field' ],
					'lot_source'       => [ 'label' => 'Аукцион (USS / TAA / JU / …)', 'sanitize' => 'sanitize_text_field' ],
					'lot_status'       => [ 'label' => 'Статус (active / sold / reserved)', 'sanitize' => 'sanitize_text_field' ],
					'lot_auction_date' => [ 'label' => 'Дата аукциона (YYYY-MM-DD)', 'sanitize' => 'sanitize_text_field' ],
					'lot_location'     => [ 'label' => 'Порт отгрузки',              'sanitize' => 'sanitize_text_field' ],
					'lot_image_url'    => [ 'label' => 'URL фото',                   'sanitize' => 'esc_url_raw' ],
				],
				'taxonomies'     => [
					'car_brand'   => 'Марка',
					'car_country' => 'Страна',
					'car_type'    => 'Тип кузова',
				],
			],

			/* ----------------------------------------------------------------
			 * case_study — Client success stories
			 * ---------------------------------------------------------------- */
			'case_study' => [
				'label'          => 'Кейсы клиентов (case_study)',
				'image_field'    => 'case_image_url',
				'duplicate_keys' => [
					'post_title'   => 'Заголовок кейса',
					'meta:case_order_id' => 'ID заказа',
				],
				'post_fields'    => [
					'post_title'   => 'Заголовок кейса *',
					'post_content' => 'Текст кейса (HTML)',
					'post_excerpt' => 'Краткое описание',
					'post_status'  => 'Статус (publish / draft)',
				],
				'meta_fields'    => [
					'case_order_id'    => [ 'label' => 'ID заказа',              'sanitize' => 'sanitize_text_field' ],
					'case_client_name' => [ 'label' => 'Имя клиента',           'sanitize' => 'sanitize_text_field' ],
					'case_client_city' => [ 'label' => 'Город клиента',         'sanitize' => 'sanitize_text_field' ],
					'case_budget'      => [ 'label' => 'Бюджет клиента (₽)',    'sanitize' => 'absint' ],
					'case_price_paid'  => [ 'label' => 'Итоговая цена (₽)',     'sanitize' => 'absint' ],
					'case_savings'     => [ 'label' => 'Экономия (₽)',          'sanitize' => 'absint' ],
					'case_duration'    => [ 'label' => 'Срок выполнения (дни)', 'sanitize' => 'absint' ],
					'case_model_name'  => [ 'label' => 'Модель авто (строка)', 'sanitize' => 'sanitize_text_field' ],
					'case_year'        => [ 'label' => 'Год авто',              'sanitize' => 'absint' ],
					'case_mileage'     => [ 'label' => 'Пробег (км)',           'sanitize' => 'absint' ],
					'case_rating'      => [ 'label' => 'Оценка клиента (1-5)', 'sanitize' => 'absint' ],
					'case_source'      => [ 'label' => 'Откуда привезли',       'sanitize' => 'sanitize_text_field' ],
					'case_review_text' => [ 'label' => 'Отзыв клиента',        'sanitize' => 'sanitize_textarea_field' ],
					'case_image_url'   => [ 'label' => 'URL фото авто',        'sanitize' => 'esc_url_raw' ],
				],
				'taxonomies'     => [
					'car_brand'   => 'Марка',
					'car_country' => 'Страна',
					'car_type'    => 'Тип кузова',
				],
			],

		];
	}

	/**
	 * Return flat list of all field options for a CPT, used to build select dropdowns.
	 * Format: [ 'field_key' => 'Group: Label' ]
	 *
	 * @param string $post_type CPT slug.
	 * @return array<string, string>
	 */
	public static function get_flat_options( string $post_type ): array {
		$schema = self::get_schema();
		if ( ! isset( $schema[ $post_type ] ) ) {
			return [];
		}

		$cpt     = $schema[ $post_type ];
		$options = [ '__skip__' => '— Пропустить колонку —' ];

		foreach ( $cpt['post_fields'] as $key => $label ) {
			$options[ $key ] = 'Пост: ' . $label;
		}
		foreach ( $cpt['meta_fields'] as $key => $def ) {
			$options[ $key ] = 'Мета: ' . $def['label'];
		}
		foreach ( $cpt['taxonomies'] as $tax => $label ) {
			$options[ $tax ] = 'Таксономия: ' . $label;
		}

		return $options;
	}
}
