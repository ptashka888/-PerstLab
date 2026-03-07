<?php
/**
 * Plugin Name: CF Demo Data
 * Description: Заполняет базу демо-контентом для тестирования темы CarFinance MSK. Кнопки «Залить» и «Удалить» в Инструменты → Демо-данные.
 * Version:     1.0.0
 * Author:      CarFinance MSK
 */

defined( 'ABSPATH' ) || exit;

define( 'CFDEMO_TAG', '_cf_demo' ); // meta key that marks demo content

/* ================================================================
   Admin page
   ================================================================ */

add_action( 'admin_menu', function (): void {
	add_management_page(
		'Демо-данные CarFinance',
		'Демо-данные CF',
		'manage_options',
		'cf-demo-data',
		'cfdemo_render_page'
	);
} );

function cfdemo_render_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Нет доступа.' );
	}

	$action = '';
	$notice = '';

	if (
		isset( $_POST['cfdemo_action'], $_POST['cfdemo_nonce'] ) &&
		wp_verify_nonce( wp_unslash( $_POST['cfdemo_nonce'] ), 'cfdemo_action' )
	) {
		$action = sanitize_key( wp_unslash( $_POST['cfdemo_action'] ) );

		if ( $action === 'seed' ) {
			$counts = cfdemo_seed_all();
			$notice = sprintf(
				'<div class="notice notice-success"><p>✅ Демо-данные добавлены: ' .
				'таксономий — %d термин(ов), ' .
				'моделей авто — %d, лотов — %d, кейсов — %d, ' .
				'услуг — %d, записей блога — %d, страниц — %d.</p></div>',
				$counts['terms'],
				$counts['car_model'],
				$counts['auction_lot'],
				$counts['case_study'],
				$counts['service_page'],
				$counts['post'],
				$counts['page']
			);
		} elseif ( $action === 'remove' ) {
			$deleted = cfdemo_remove_all();
			$notice  = sprintf(
				'<div class="notice notice-warning"><p>🗑️ Демо-данные удалены: %d записей.</p></div>',
				$deleted
			);
		}
	}

	$existing = cfdemo_count_existing();
	?>
	<div class="wrap">
		<h1>🚗 Демо-данные CarFinance MSK</h1>
		<?php echo $notice; // already escaped above ?>

		<div style="max-width:680px;background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;margin-top:16px">
			<h2 style="margin-top:0">Что будет создано</h2>
			<ul style="list-style:disc;padding-left:20px">
				<li><strong>Таксономии:</strong> 5 стран, 6 марок, 5 типов кузова, 4 типа двигателя, 4 коробки, 3 привода, 4 ценовых диапазона</li>
				<li><strong>Модели авто (car_model):</strong> 8 штук (Toyota, KIA, Hyundai, Chery, Haval, Lexus, Honda, Mazda)</li>
				<li><strong>Лоты аукционов (auction_lot):</strong> 6 штук</li>
				<li><strong>Кейсы клиентов (case_study):</strong> 5 штук</li>
				<li><strong>Услуги (service_page):</strong> 3 штуки</li>
				<li><strong>Записи блога:</strong> 4 статьи с текстом</li>
				<li><strong>Страницы:</strong> country-pages (Корея, Япония, Китай, США, ОАЭ) + calculator + services</li>
			</ul>
			<p style="color:#646970;font-size:13px">Все демо-записи помечаются скрытым мета-полем <code><?php echo CFDEMO_TAG; ?></code> и удаляются кнопкой ниже.</p>

			<?php if ( $existing > 0 ) : ?>
				<p style="background:#fff8e1;border-left:4px solid #f0c033;padding:8px 12px;border-radius:0 4px 4px 0">
					⚠️ В базе уже есть <strong><?php echo $existing; ?></strong> демо-записей.
					Повторное нажатие «Залить» добавит новые (дублей не будет — проверяет по заголовку).
				</p>
			<?php endif; ?>

			<form method="post" style="display:flex;gap:12px;margin-top:20px;flex-wrap:wrap">
				<?php wp_nonce_field( 'cfdemo_action', 'cfdemo_nonce' ); ?>
				<button type="submit" name="cfdemo_action" value="seed"
					class="button button-primary button-large">
					▶ Залить демо-данные
				</button>
				<?php if ( $existing > 0 ) : ?>
					<button type="submit" name="cfdemo_action" value="remove"
						class="button button-large"
						style="color:#b32d2e;border-color:#b32d2e"
						onclick="return confirm('Удалить все <?php echo $existing; ?> демо-записей?')">
						🗑 Удалить демо-данные
					</button>
				<?php endif; ?>
			</form>
		</div>
	</div>
	<?php
}

/* ================================================================
   Seed everything
   ================================================================ */

function cfdemo_seed_all(): array {
	$counts = [
		'terms'        => 0,
		'car_model'    => 0,
		'auction_lot'  => 0,
		'case_study'   => 0,
		'service_page' => 0,
		'post'         => 0,
		'page'         => 0,
	];

	$counts['terms'] = cfdemo_seed_taxonomies();

	foreach ( cfdemo_car_models() as $data ) {
		if ( cfdemo_insert_post( $data ) ) {
			$counts['car_model']++;
		}
	}
	foreach ( cfdemo_auction_lots() as $data ) {
		if ( cfdemo_insert_post( $data ) ) {
			$counts['auction_lot']++;
		}
	}
	foreach ( cfdemo_case_studies() as $data ) {
		if ( cfdemo_insert_post( $data ) ) {
			$counts['case_study']++;
		}
	}
	foreach ( cfdemo_service_pages() as $data ) {
		if ( cfdemo_insert_post( $data ) ) {
			$counts['service_page']++;
		}
	}
	foreach ( cfdemo_blog_posts() as $data ) {
		if ( cfdemo_insert_post( $data ) ) {
			$counts['post']++;
		}
	}
	foreach ( cfdemo_pages() as $data ) {
		if ( cfdemo_insert_post( $data ) ) {
			$counts['page']++;
		}
	}

	return $counts;
}

/* ================================================================
   Taxonomies
   ================================================================ */

function cfdemo_seed_taxonomies(): int {
	$count = 0;

	$terms = [
		'car_country'      => [ 'Корея', 'Япония', 'Китай', 'США', 'ОАЭ' ],
		'car_brand'        => [ 'Toyota', 'KIA', 'Hyundai', 'Chery', 'Haval', 'Lexus', 'Honda', 'Mazda' ],
		'car_type'         => [ 'Кроссовер', 'Седан', 'Минивэн', 'Купе', 'Внедорожник' ],
		'engine_type'      => [ 'Бензиновый', 'Дизельный', 'Электрический', 'Гибридный' ],
		'transmission_type'=> [ 'Автомат', 'Механика', 'Вариатор', 'Робот' ],
		'drive_type'       => [ 'Передний', 'Задний', 'Полный' ],
		'price_range'      => [ 'До 2 млн ₽', '2–3 млн ₽', '3–5 млн ₽', 'От 5 млн ₽' ],
		'catalog_tag'      => [ 'Популярное', 'Новинки 2024', 'Хит продаж', 'Под заказ' ],
	];

	foreach ( $terms as $taxonomy => $names ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		foreach ( $names as $name ) {
			if ( ! get_term_by( 'name', $name, $taxonomy ) ) {
				$result = wp_insert_term( $name, $taxonomy );
				if ( ! is_wp_error( $result ) ) {
					$count++;
				}
			}
		}
	}

	return $count;
}

/* ================================================================
   Car models
   ================================================================ */

function cfdemo_car_models(): array {
	return [
		[
			'post_type'  => 'car_model',
			'post_title' => 'Toyota Camry 2023 — с аукциона Японии',
			'post_name'  => 'toyota-camry-2023',
			'post_content' => '<p>Toyota Camry — флагманский бизнес-седан с японских аукционов. Идеальный выбор для тех, кто ценит комфорт, надёжность и статус.</p><p>Автомобиль прошёл полную проверку на аукционе USS Tokyo. Оценка 4.5 из 5. Один владелец, сервисная история подтверждена.</p><h2>Преимущества покупки через CarFinance MSK</h2><ul><li>Юридическая чистота — проверка по базам США, Японии, Кореи</li><li>Полная растаможка под ключ</li><li>Доставка за 45–60 дней</li><li>Гарантия соответствия описанию</li></ul>',
			'post_excerpt' => 'Бизнес-седан с аукциона Японии. Оценка 4.5, один владелец, пробег 38 000 км. Цена под ключ от 3 200 000 ₽.',
			'post_status'  => 'publish',
			'meta'  => [
				'cf_price_from'    => 3200000,
				'cf_price_to'      => 3600000,
				'cf_price_turnkey' => 3200000,
				'cf_year'          => 2023,
				'cf_engine_cc'     => 2500,
				'cf_hp'            => 200,
				'cf_engine_type'   => 'petrol',
				'cf_transmission'  => 'auto',
				'cf_drive'         => 'fwd',
				'cf_fuel'          => 'АИ-95',
				'cf_seats'         => 5,
				'cf_color'         => 'Белый перламутр',
				'cf_delivery_days' => 55,
				'cf_generation'    => 'XV70',
			],
			'tax'   => [
				'car_brand'   => [ 'Toyota' ],
				'car_country' => [ 'Япония' ],
				'car_type'    => [ 'Седан' ],
				'engine_type' => [ 'Бензиновый' ],
				'transmission_type' => [ 'Автомат' ],
				'drive_type'  => [ 'Передний' ],
				'price_range' => [ '3–5 млн ₽' ],
				'catalog_tag' => [ 'Хит продаж' ],
			],
		],
		[
			'post_type'  => 'car_model',
			'post_title' => 'KIA Sportage 2023 NQ5 — из Кореи',
			'post_name'  => 'kia-sportage-2023',
			'post_content' => '<p>KIA Sportage пятого поколения — самый популярный корейский кроссовер среди наших клиентов. Новая платформа, современный салон, богатое оснащение.</p><p>Поставляем напрямую из Кореи через официальных дилеров. Полный пакет документов, СБКТС, постановка на учёт входят в стоимость.</p>',
			'post_excerpt' => 'Кроссовер 5-го поколения из Кореи. Новый, нулевой пробег. Полный комплект документов. Цена от 2 850 000 ₽.',
			'post_status'  => 'publish',
			'meta'  => [
				'cf_price_from'    => 2850000,
				'cf_price_to'      => 3200000,
				'cf_price_turnkey' => 2850000,
				'cf_year'          => 2023,
				'cf_engine_cc'     => 2000,
				'cf_hp'            => 150,
				'cf_engine_type'   => 'petrol',
				'cf_transmission'  => 'auto',
				'cf_drive'         => 'awd',
				'cf_fuel'          => 'АИ-95',
				'cf_seats'         => 5,
				'cf_clearance'     => 182,
				'cf_color'         => 'Серебристый',
				'cf_delivery_days' => 35,
				'cf_generation'    => 'NQ5',
			],
			'tax'   => [
				'car_brand'   => [ 'KIA' ],
				'car_country' => [ 'Корея' ],
				'car_type'    => [ 'Кроссовер' ],
				'engine_type' => [ 'Бензиновый' ],
				'transmission_type' => [ 'Автомат' ],
				'drive_type'  => [ 'Полный' ],
				'price_range' => [ '2–3 млн ₽' ],
				'catalog_tag' => [ 'Популярное', 'Хит продаж' ],
			],
		],
		[
			'post_type'  => 'car_model',
			'post_title' => 'Hyundai Tucson 2022 — корейская сборка',
			'post_name'  => 'hyundai-tucson-2022',
			'post_content' => '<p>Hyundai Tucson четвёртого поколения — стильный кроссовер с уникальным дизайном и продвинутыми технологиями. Корейская сборка, максимальная комплектация.</p>',
			'post_excerpt' => 'Кроссовер 4-го поколения. Дизайн Parametric Dynamics, цифровая приборная панель. Цена от 2 650 000 ₽.',
			'post_status'  => 'publish',
			'meta'  => [
				'cf_price_from'    => 2650000,
				'cf_price_to'      => 2950000,
				'cf_price_turnkey' => 2650000,
				'cf_year'          => 2022,
				'cf_engine_cc'     => 2000,
				'cf_hp'            => 156,
				'cf_engine_type'   => 'petrol',
				'cf_transmission'  => 'auto',
				'cf_drive'         => 'awd',
				'cf_fuel'          => 'АИ-95',
				'cf_seats'         => 5,
				'cf_delivery_days' => 35,
			],
			'tax'   => [
				'car_brand'   => [ 'Hyundai' ],
				'car_country' => [ 'Корея' ],
				'car_type'    => [ 'Кроссовер' ],
				'price_range' => [ '2–3 млн ₽' ],
				'catalog_tag' => [ 'Популярное' ],
			],
		],
		[
			'post_type'  => 'car_model',
			'post_title' => 'Chery Tiggo 8 Pro 2023 — китайский флагман',
			'post_name'  => 'chery-tiggo-8-pro-2023',
			'post_content' => '<p>Chery Tiggo 8 Pro — 7-местный семейный кроссовер с богатым оснащением. Оптимальный выбор для большой семьи по доступной цене.</p><p>Поставляем официально через импортёра. Сертификат СБКТС, гарантия производителя 3 года / 100 000 км.</p>',
			'post_excerpt' => '7-местный кроссовер из Китая. Панорамная крыша, кожаный салон, адаптивный круиз. Цена от 2 200 000 ₽.',
			'post_status'  => 'publish',
			'meta'  => [
				'cf_price_from'    => 2200000,
				'cf_price_to'      => 2600000,
				'cf_price_turnkey' => 2200000,
				'cf_year'          => 2023,
				'cf_engine_cc'     => 2000,
				'cf_hp'            => 197,
				'cf_engine_type'   => 'petrol',
				'cf_transmission'  => 'auto',
				'cf_drive'         => 'fwd',
				'cf_fuel'          => 'АИ-95',
				'cf_seats'         => 7,
				'cf_delivery_days' => 30,
			],
			'tax'   => [
				'car_brand'   => [ 'Chery' ],
				'car_country' => [ 'Китай' ],
				'car_type'    => [ 'Кроссовер' ],
				'price_range' => [ '2–3 млн ₽' ],
				'catalog_tag' => [ 'Новинки 2024' ],
			],
		],
		[
			'post_type'  => 'car_model',
			'post_title' => 'Haval H6 2023 — новое поколение',
			'post_name'  => 'haval-h6-2023',
			'post_content' => '<p>Haval H6 — самый продаваемый китайский кроссовер в России. Современная платформа Lemon, гибридная версия PHEV доступна под заказ.</p>',
			'post_excerpt' => 'Кроссовер третьего поколения из Китая. Платформа Lemon, мягкий гибрид. Цена от 1 950 000 ₽.',
			'post_status'  => 'publish',
			'meta'  => [
				'cf_price_from'    => 1950000,
				'cf_price_to'      => 2350000,
				'cf_price_turnkey' => 1950000,
				'cf_year'          => 2023,
				'cf_engine_cc'     => 1500,
				'cf_hp'            => 169,
				'cf_engine_type'   => 'hybrid',
				'cf_transmission'  => 'robot',
				'cf_drive'         => 'fwd',
				'cf_fuel'          => 'АИ-95',
				'cf_seats'         => 5,
				'cf_delivery_days' => 28,
			],
			'tax'   => [
				'car_brand'   => [ 'Haval' ],
				'car_country' => [ 'Китай' ],
				'car_type'    => [ 'Кроссовер' ],
				'price_range' => [ 'До 2 млн ₽' ],
			],
		],
		[
			'post_type'  => 'car_model',
			'post_title' => 'Lexus LX 600 2022 — из ОАЭ',
			'post_name'  => 'lexus-lx-600-2022',
			'post_content' => '<p>Lexus LX 600 — флагманский внедорожник премиального класса. Поставляем из ОАЭ по параллельному импорту. Праворульный вариант — из Японии.</p><p>Полная комплектация VIP: массаж, вентиляция, 4-зонный климат, панорамная крыша, 22" диски.</p>',
			'post_excerpt' => 'Флагманский внедорожник. Из ОАЭ по параллельному импорту. Полная комплектация. Цена от 8 900 000 ₽.',
			'post_status'  => 'publish',
			'meta'  => [
				'cf_price_from'    => 8900000,
				'cf_price_to'      => 11000000,
				'cf_price_turnkey' => 8900000,
				'cf_year'          => 2022,
				'cf_engine_cc'     => 3500,
				'cf_hp'            => 415,
				'cf_engine_type'   => 'petrol',
				'cf_transmission'  => 'auto',
				'cf_drive'         => 'awd',
				'cf_fuel'          => 'АИ-98',
				'cf_seats'         => 7,
				'cf_delivery_days' => 25,
			],
			'tax'   => [
				'car_brand'   => [ 'Lexus' ],
				'car_country' => [ 'ОАЭ' ],
				'car_type'    => [ 'Внедорожник' ],
				'engine_type' => [ 'Бензиновый' ],
				'price_range' => [ 'От 5 млн ₽' ],
			],
		],
		[
			'post_type'  => 'car_model',
			'post_title' => 'Honda Fit 2021 — с аукциона USS',
			'post_name'  => 'honda-fit-2021',
			'post_content' => '<p>Honda Fit четвёртого поколения с гибридной системой e:HEV. Экономичный городской хэтчбек с аукциона USS. Отличное состояние, оценка 4.</p>',
			'post_excerpt' => 'Гибридный городской хэтчбек из Японии. Оценка USS 4, пробег 22 000 км. Цена от 1 450 000 ₽.',
			'post_status'  => 'publish',
			'meta'  => [
				'cf_price_from'    => 1450000,
				'cf_price_to'      => 1750000,
				'cf_price_turnkey' => 1450000,
				'cf_year'          => 2021,
				'cf_engine_cc'     => 1500,
				'cf_hp'            => 109,
				'cf_engine_type'   => 'hybrid',
				'cf_transmission'  => 'cvt',
				'cf_drive'         => 'fwd',
				'cf_fuel'          => 'АИ-92',
				'cf_seats'         => 5,
				'cf_delivery_days' => 50,
			],
			'tax'   => [
				'car_brand'   => [ 'Honda' ],
				'car_country' => [ 'Япония' ],
				'car_type'    => [ 'Кроссовер' ],
				'engine_type' => [ 'Гибридный' ],
				'price_range' => [ 'До 2 млн ₽' ],
				'catalog_tag' => [ 'Популярное' ],
			],
		],
		[
			'post_type'  => 'car_model',
			'post_title' => 'Mazda CX-5 2022 — из США (Copart)',
			'post_name'  => 'mazda-cx-5-2022-usa',
			'post_content' => '<p>Mazda CX-5 с аукциона Copart США. Автомобиль прошёл полную техническую экспертизу. Кузов восстановлен сертифицированным кузовным центром. Документы CARFAX прилагаются.</p>',
			'post_excerpt' => 'Кроссовер с аукциона Copart. Документы CARFAX. После кузовного восстановления. Цена от 1 850 000 ₽.',
			'post_status'  => 'publish',
			'meta'  => [
				'cf_price_from'    => 1850000,
				'cf_price_to'      => 2100000,
				'cf_price_turnkey' => 1850000,
				'cf_year'          => 2022,
				'cf_engine_cc'     => 2500,
				'cf_hp'            => 194,
				'cf_engine_type'   => 'petrol',
				'cf_transmission'  => 'auto',
				'cf_drive'         => 'awd',
				'cf_fuel'          => 'АИ-95',
				'cf_seats'         => 5,
				'cf_delivery_days' => 70,
			],
			'tax'   => [
				'car_brand'   => [ 'Mazda' ],
				'car_country' => [ 'США' ],
				'car_type'    => [ 'Кроссовер' ],
				'price_range' => [ 'До 2 млн ₽' ],
				'catalog_tag' => [ 'Под заказ' ],
			],
		],
	];
}

/* ================================================================
   Auction lots
   ================================================================ */

function cfdemo_auction_lots(): array {
	return [
		[
			'post_type'   => 'auction_lot',
			'post_title'  => 'Toyota Alphard 2022 — USS Osaka — лот #OSK-2024-0193',
			'post_name'   => 'lot-toyota-alphard-2022-osk',
			'post_status' => 'publish',
			'meta' => [
				'lot_vin'          => 'AGH30-0093245',
				'lot_lot_number'   => 'OSK-2024-0193',
				'lot_price_usd'    => 28500,
				'lot_price_rub'    => 2650000,
				'lot_year'         => 2022,
				'lot_mileage'      => 41000,
				'lot_engine_cc'    => 2500,
				'lot_hp'           => 182,
				'lot_fuel'         => 'АИ-95',
				'lot_transmission' => 'CVT',
				'lot_color'        => 'Белый перламутр',
				'lot_grade'        => '4.5',
				'lot_source'       => 'USS Osaka',
				'lot_status'       => 'active',
				'lot_auction_date' => '2024-03-25',
				'lot_location'     => 'Osaka, Япония',
			],
			'tax' => [
				'car_brand'   => [ 'Toyota' ],
				'car_country' => [ 'Япония' ],
				'car_type'    => [ 'Минивэн' ],
			],
		],
		[
			'post_type'   => 'auction_lot',
			'post_title'  => 'KIA Carnival 2021 — TAA Chubu — лот #TAA-8821',
			'post_name'   => 'lot-kia-carnival-2021',
			'post_status' => 'publish',
			'meta' => [
				'lot_vin'          => 'KA4R3814XNB013829',
				'lot_lot_number'   => 'TAA-8821',
				'lot_price_usd'    => 21000,
				'lot_price_rub'    => 1980000,
				'lot_year'         => 2021,
				'lot_mileage'      => 55000,
				'lot_engine_cc'    => 3500,
				'lot_hp'           => 272,
				'lot_fuel'         => 'АИ-95',
				'lot_transmission' => 'Автомат',
				'lot_color'        => 'Серый металлик',
				'lot_grade'        => '4',
				'lot_source'       => 'TAA Chubu',
				'lot_status'       => 'active',
				'lot_auction_date' => '2024-03-28',
				'lot_location'     => 'Nagoya, Япония',
			],
			'tax' => [
				'car_brand'   => [ 'KIA' ],
				'car_country' => [ 'Корея' ],
				'car_type'    => [ 'Минивэн' ],
			],
		],
		[
			'post_type'   => 'auction_lot',
			'post_title'  => 'Lexus RX 350 2020 — USS Tokyo — лот #TKO-5502',
			'post_name'   => 'lot-lexus-rx350-2020',
			'post_status' => 'publish',
			'meta' => [
				'lot_vin'          => '2T2BZMCA5LC142853',
				'lot_lot_number'   => 'TKO-5502',
				'lot_price_usd'    => 32000,
				'lot_price_rub'    => 3100000,
				'lot_year'         => 2020,
				'lot_mileage'      => 38000,
				'lot_engine_cc'    => 3500,
				'lot_hp'           => 249,
				'lot_fuel'         => 'АИ-98',
				'lot_transmission' => 'Автомат',
				'lot_color'        => 'Чёрный',
				'lot_grade'        => '5',
				'lot_source'       => 'USS Tokyo',
				'lot_status'       => 'active',
				'lot_auction_date' => '2024-04-02',
				'lot_location'     => 'Tokyo, Япония',
			],
			'tax' => [
				'car_brand'   => [ 'Lexus' ],
				'car_country' => [ 'Япония' ],
				'car_type'    => [ 'Кроссовер' ],
			],
		],
		[
			'post_type'   => 'auction_lot',
			'post_title'  => 'Toyota RAV4 2021 — JU Fukuoka — лот #JU-F-3310',
			'post_name'   => 'lot-toyota-rav4-2021',
			'post_status' => 'publish',
			'meta' => [
				'lot_vin'          => 'JTMRWRFV9MD099153',
				'lot_lot_number'   => 'JU-F-3310',
				'lot_price_usd'    => 24000,
				'lot_price_rub'    => 2250000,
				'lot_year'         => 2021,
				'lot_mileage'      => 49000,
				'lot_engine_cc'    => 2000,
				'lot_hp'           => 149,
				'lot_fuel'         => 'Гибрид',
				'lot_transmission' => 'CVT',
				'lot_color'        => 'Синий металлик',
				'lot_grade'        => '4',
				'lot_source'       => 'JU Fukuoka',
				'lot_status'       => 'active',
				'lot_auction_date' => '2024-04-05',
				'lot_location'     => 'Fukuoka, Япония',
			],
			'tax' => [
				'car_brand'   => [ 'Toyota' ],
				'car_country' => [ 'Япония' ],
				'car_type'    => [ 'Кроссовер' ],
			],
		],
		[
			'post_type'   => 'auction_lot',
			'post_title'  => 'Hyundai Palisade 2022 — Корея — дилер',
			'post_name'   => 'lot-hyundai-palisade-2022',
			'post_status' => 'publish',
			'meta' => [
				'lot_vin'          => 'KM8R4DHE5NU418833',
				'lot_lot_number'   => 'KR-DEALER-0044',
				'lot_price_usd'    => 38000,
				'lot_price_rub'    => 3450000,
				'lot_year'         => 2022,
				'lot_mileage'      => 0,
				'lot_engine_cc'    => 2200,
				'lot_hp'           => 202,
				'lot_fuel'         => 'Дизель',
				'lot_transmission' => 'Автомат',
				'lot_color'        => 'Графит',
				'lot_grade'        => '5',
				'lot_source'       => 'Korea Direct',
				'lot_status'       => 'active',
				'lot_auction_date' => '2024-04-10',
				'lot_location'     => 'Seoul, Корея',
			],
			'tax' => [
				'car_brand'   => [ 'Hyundai' ],
				'car_country' => [ 'Корея' ],
				'car_type'    => [ 'Внедорожник' ],
			],
		],
		[
			'post_type'   => 'auction_lot',
			'post_title'  => 'Honda CR-V 2021 — SOLD — USS Nagoya',
			'post_name'   => 'lot-honda-crv-2021-sold',
			'post_status' => 'publish',
			'meta' => [
				'lot_vin'          => '7FART6H89ME003156',
				'lot_lot_number'   => 'NGY-2021-7743',
				'lot_price_usd'    => 19500,
				'lot_price_rub'    => 1850000,
				'lot_year'         => 2021,
				'lot_mileage'      => 61000,
				'lot_engine_cc'    => 1500,
				'lot_hp'           => 173,
				'lot_fuel'         => 'АИ-95 Турбо',
				'lot_transmission' => 'CVT',
				'lot_color'        => 'Серебристый',
				'lot_grade'        => '4',
				'lot_source'       => 'USS Nagoya',
				'lot_status'       => 'sold',
				'lot_auction_date' => '2024-02-15',
				'lot_location'     => 'Nagoya, Япония',
			],
			'tax' => [
				'car_brand'   => [ 'Honda' ],
				'car_country' => [ 'Япония' ],
				'car_type'    => [ 'Кроссовер' ],
			],
		],
	];
}

/* ================================================================
   Case studies
   ================================================================ */

function cfdemo_case_studies(): array {
	return [
		[
			'post_type'    => 'case_study',
			'post_title'   => 'Привезли Toyota RAV4 Hybrid из Кореи за 49 дней',
			'post_name'    => 'kejs-toyota-rav4-hybrid-iz-korei',
			'post_content' => '<p>Клиент обратился в CarFinance MSK в ноябре 2023 года. Бюджет — 2 800 000 ₽ под ключ. Задача: найти Toyota RAV4 гибрид 2022–2023 года в хорошем состоянии.</p><h2>Что сделали</h2><ol><li>Провели анализ рынка — нашли 12 подходящих вариантов на аукционах USS и TAA.</li><li>Согласовали с клиентом топ-3 лота по фото и истории обслуживания.</li><li>Выкупили Toyota RAV4 Hybrid 2022 на USS Osaka. Оценка 4.5, пробег 34 000 км.</li><li>Организовали доставку в порт Владивосток (18 дней).</li><li>Полная растаможка, СБКТС, ЭПТС, постановка на учёт (14 дней).</li></ol><h2>Результат</h2><p>Автомобиль получен за 49 дней. Итоговая цена — 2 710 000 ₽. Клиент сэкономил 340 000 ₽ по сравнению с аналогичным предложением у московских перекупщиков.</p>',
			'post_excerpt' => 'Toyota RAV4 Hybrid 2022, пробег 34 000 км. Срок 49 дней. Экономия 340 000 ₽.',
			'post_status'  => 'publish',
			'meta' => [
				'case_order_id'    => 'CF-2023-1147',
				'case_client_name' => 'Михаил Д.',
				'case_client_city' => 'Москва',
				'case_budget'      => 2800000,
				'case_price_paid'  => 2710000,
				'case_savings'     => 340000,
				'case_duration'    => 49,
				'case_model_name'  => 'Toyota RAV4 Hybrid 2022',
				'case_year'        => 2022,
				'case_mileage'     => 34000,
				'case_rating'      => 5,
				'case_source'      => 'Япония, USS Osaka',
				'case_review_text' => 'Всё прошло чётко по плану. Менеджер на связи 24/7, все документы объяснили заранее. Очень доволен — рекомендую!',
			],
			'tax' => [
				'car_brand'   => [ 'Toyota' ],
				'car_country' => [ 'Япония' ],
				'car_type'    => [ 'Кроссовер' ],
			],
		],
		[
			'post_type'    => 'case_study',
			'post_title'   => 'KIA Carnival из Кореи — семейный минивэн за 35 дней',
			'post_name'    => 'kejs-kia-carnival-iz-korei',
			'post_content' => '<p>Семья из Санкт-Петербурга хотела 7-местный минивэн для поездок с детьми. Бюджет 3 200 000 ₽. Рассматривали KIA Carnival и Toyota Alphard.</p><p>Подобрали KIA Carnival 2022 у официального дилера в Сеуле. Нулевой пробег, максимальная комплектация Premium. Срок поставки — 35 дней.</p>',
			'post_excerpt' => 'KIA Carnival 2022 нулевой. Из Кореи напрямую от дилера. 35 дней. Полный пакет документов.',
			'post_status'  => 'publish',
			'meta' => [
				'case_order_id'    => 'CF-2023-1203',
				'case_client_name' => 'Ольга и Дмитрий К.',
				'case_client_city' => 'Санкт-Петербург',
				'case_budget'      => 3200000,
				'case_price_paid'  => 3050000,
				'case_savings'     => 280000,
				'case_duration'    => 35,
				'case_model_name'  => 'KIA Carnival 2022',
				'case_year'        => 2022,
				'case_mileage'     => 0,
				'case_rating'      => 5,
				'case_source'      => 'Корея, официальный дилер',
				'case_review_text' => 'Взяли новый Carnival для семьи. Всё оформили за 35 дней, документы идеальные. Дети в восторге!',
			],
			'tax' => [
				'car_brand'   => [ 'KIA' ],
				'car_country' => [ 'Корея' ],
				'car_type'    => [ 'Минивэн' ],
			],
		],
		[
			'post_type'    => 'case_study',
			'post_title'   => 'Haval Jolion из Китая за 27 дней — первый клиент из Краснодара',
			'post_name'    => 'kejs-haval-jolion-iz-kitaya',
			'post_content' => '<p>Клиент из Краснодара обратился с запросом на бюджетный китайский кроссовер. Haval Jolion 2023 — оптимальный выбор по соотношению цена/оснащение.</p><p>Привезли через нашего партнёра-импортёра. Срок от оплаты до выдачи ключей — 27 дней. Рекорд для китайского направления.</p>',
			'post_excerpt' => 'Haval Jolion 2023 из Китая. Кратчайший срок — 27 дней. Бюджет до 1 700 000 ₽.',
			'post_status'  => 'publish',
			'meta' => [
				'case_order_id'    => 'CF-2024-0078',
				'case_client_name' => 'Андрей В.',
				'case_client_city' => 'Краснодар',
				'case_budget'      => 1700000,
				'case_price_paid'  => 1650000,
				'case_savings'     => 180000,
				'case_duration'    => 27,
				'case_model_name'  => 'Haval Jolion 2023',
				'case_year'        => 2023,
				'case_mileage'     => 0,
				'case_rating'      => 5,
				'case_source'      => 'Китай, прямая поставка',
				'case_review_text' => 'Быстро и чётко. Не ожидал, что за 27 дней уже буду ездить на новой машине!',
			],
			'tax' => [
				'car_brand'   => [ 'Haval' ],
				'car_country' => [ 'Китай' ],
				'car_type'    => [ 'Кроссовер' ],
			],
		],
		[
			'post_type'    => 'case_study',
			'post_title'   => 'Lexus LX 600 из ОАЭ — параллельный импорт без наценок',
			'post_name'    => 'kejs-lexus-lx600-iz-oae',
			'post_content' => '<p>Клиент-предприниматель из Москвы искал Lexus LX 600 в максимальной комплектации. Официальный дилер — 14 500 000 ₽ с ожиданием 6 месяцев. Через CarFinance MSK — 9 200 000 ₽, срок 22 дня.</p>',
			'post_excerpt' => 'Lexus LX 600 VIP из ОАЭ. Экономия 5 300 000 ₽ относительно официального дилера. Срок 22 дня.',
			'post_status'  => 'publish',
			'meta' => [
				'case_order_id'    => 'CF-2024-0031',
				'case_client_name' => 'Артём С.',
				'case_client_city' => 'Москва',
				'case_budget'      => 14500000,
				'case_price_paid'  => 9200000,
				'case_savings'     => 5300000,
				'case_duration'    => 22,
				'case_model_name'  => 'Lexus LX 600 2022',
				'case_year'        => 2022,
				'case_mileage'     => 8000,
				'case_rating'      => 5,
				'case_source'      => 'ОАЭ, Дубай',
				'case_review_text' => 'Сэкономил больше 5 миллионов. Машина пришла в идеальном состоянии. Всем рекомендую.',
			],
			'tax' => [
				'car_brand'   => [ 'Lexus' ],
				'car_country' => [ 'ОАЭ' ],
				'car_type'    => [ 'Внедорожник' ],
			],
		],
		[
			'post_type'    => 'case_study',
			'post_title'   => 'Mazda CX-5 из США с Copart — восстановление и сертификация',
			'post_name'    => 'kejs-mazda-cx5-iz-usa',
			'post_content' => '<p>Mazda CX-5 2022 с аукциона Copart. Фронтальный удар. Наш партнёрский кузовной центр полностью восстановил автомобиль. Итог: машина как новая за 55% от рыночной цены.</p>',
			'post_excerpt' => 'Mazda CX-5 2022 с Copart. Восстановление кузова. 55% от рыночной стоимости. Срок 68 дней.',
			'post_status'  => 'publish',
			'meta' => [
				'case_order_id'    => 'CF-2023-0991',
				'case_client_name' => 'Павел Н.',
				'case_client_city' => 'Екатеринбург',
				'case_budget'      => 2500000,
				'case_price_paid'  => 1990000,
				'case_savings'     => 760000,
				'case_duration'    => 68,
				'case_model_name'  => 'Mazda CX-5 2022',
				'case_year'        => 2022,
				'case_mileage'     => 31000,
				'case_rating'      => 4,
				'case_source'      => 'США, Copart',
				'case_review_text' => 'Риск был минимальный — всё контролировали специалисты CF. Получил отличный автомобиль почти за полцены.',
			],
			'tax' => [
				'car_brand'   => [ 'Mazda' ],
				'car_country' => [ 'США' ],
				'car_type'    => [ 'Кроссовер' ],
			],
		],
	];
}

/* ================================================================
   Service pages
   ================================================================ */

function cfdemo_service_pages(): array {
	return [
		[
			'post_type'    => 'service_page',
			'post_title'   => 'Импорт авто под ключ из Кореи, Китая, Японии',
			'post_name'    => 'import-pod-klyuch',
			'post_content' => '<p>Полный цикл: подбор → аукцион → выкуп → логистика → таможня → СБКТС → постановка на учёт.</p><h2>Что входит в услугу</h2><ul><li>Мониторинг аукционов в реальном времени</li><li>Отчёт о состоянии автомобиля + фото</li><li>Выкуп и страхование груза</li><li>Морская доставка во Владивосток</li><li>Растаможка «под ключ»</li><li>Оформление СБКТС и ЭПТС</li><li>Постановка на учёт в ГИБДД</li></ul>',
			'post_excerpt' => 'Полный цикл доставки автомобиля из-за рубежа. Вы получаете готовое авто с документами РФ.',
			'post_status'  => 'publish',
			'meta' => [
				'cf_service_icon'       => '🚢',
				'cf_service_short_desc' => 'Полный цикл: подбор, аукцион, выкуп, логистика, таможня, СБКТС, постановка на учёт.',
			],
		],
		[
			'post_type'    => 'service_page',
			'post_title'   => 'Кредит и лизинг на авто из Кореи и Китая',
			'post_name'    => 'kredit-lizing',
			'post_content' => '<p>Помогаем оформить автокредит или лизинг на импортированный автомобиль. Работаем с 12 банками-партнёрами. Одобрение за 15 минут онлайн.</p><h2>Условия кредитования</h2><ul><li>Ставка от 5,9% годовых</li><li>Срок до 7 лет</li><li>Первоначальный взнос от 0%</li><li>Без скрытых комиссий</li></ul>',
			'post_excerpt' => 'Автокредит и лизинг на импортные авто. Ставки от 5,9%, срок до 7 лет. Одобрение за 15 минут.',
			'post_status'  => 'publish',
			'meta' => [
				'cf_service_icon'       => '💳',
				'cf_service_short_desc' => 'Автокредит и лизинг на импортные авто. 12 банков-партнёров. Ставки от 5,9% годовых.',
			],
		],
		[
			'post_type'    => 'service_page',
			'post_title'   => 'Трейд-ин — обмен вашего авто на импортное',
			'post_name'    => 'trade-in',
			'post_content' => '<p>Сдайте ваш автомобиль в счёт стоимости нового. Честная оценка за 15 минут, зачёт стоимости при оформлении.</p><p>Принимаем любые марки и модели. Возраст и состояние — обсуждаем индивидуально.</p>',
			'post_excerpt' => 'Зачёт вашего авто в счёт импортного. Оценка за 15 минут. Без торговли и очередей.',
			'post_status'  => 'publish',
			'meta' => [
				'cf_service_icon'       => '🔄',
				'cf_service_short_desc' => 'Честная оценка вашего авто за 15 минут. Мгновенный зачёт без торговли.',
			],
		],
	];
}

/* ================================================================
   Blog posts
   ================================================================ */

function cfdemo_blog_posts(): array {
	return [
		[
			'post_type'    => 'post',
			'post_title'   => 'Как растаможить автомобиль из Кореи в 2024 году: полный гид',
			'post_name'    => 'kak-rastamozit-avto-iz-korei-2024',
			'post_content' => '<p>Растаможка автомобиля из Кореи — многоэтапный процесс, требующий знания таможенного законодательства. В этой статье разберём все шаги, расходы и подводные камни.</p>

<h2>Основные расходы при растаможке</h2>
<p>Итоговая стоимость растаможки зависит от нескольких факторов:</p>
<ul>
<li><strong>Таможенная пошлина</strong> — от 15% до 48% от стоимости авто в зависимости от объёма двигателя и возраста.</li>
<li><strong>Утилизационный сбор</strong> — от 3 400 до 5 200 ₽ для физических лиц (для юридических — значительно выше).</li>
<li><strong>СБКТС</strong> — одобрение типа транспортного средства, от 35 000 до 60 000 ₽.</li>
<li><strong>ЭПТС</strong> — электронный паспорт транспортного средства, 600 ₽.</li>
<li><strong>Брокерские услуги</strong> — от 25 000 до 50 000 ₽.</li>
</ul>

<h2>Шаги растаможки</h2>
<ol>
<li>Прибытие автомобиля в порт Владивосток (14–21 день с момента отгрузки).</li>
<li>Подача таможенной декларации (ДТ) через аккредитованного брокера.</li>
<li>Оплата всех таможенных платежей.</li>
<li>Прохождение таможенного осмотра.</li>
<li>Выпуск ТС и получение документов.</li>
<li>Оформление СБКТС и ЭПТС (7–14 дней).</li>
<li>Постановка на учёт в ГИБДД.</li>
</ol>

<h2>Сроки и реалии</h2>
<p>Полный процесс от выкупа на аукционе до получения ключей занимает 35–60 дней. CarFinance MSK берёт на себя все этапы — вы получаете только готовый автомобиль.</p>',
			'post_excerpt' => 'Полный гид по растаможке авто из Кореи в 2024 году: расходы, сроки, документы, подводные камни.',
			'post_status'  => 'publish',
		],
		[
			'post_type'    => 'post',
			'post_title'   => 'Топ-5 корейских кроссоверов 2023–2024: что выбрать',
			'post_name'    => 'top-5-koreiskikh-krosoverov-2023-2024',
			'post_content' => '<p>Корея стала главным поставщиком доступных и качественных кроссоверов на российский рынок. Разбираем пятёрку лучших моделей 2023–2024 года.</p>

<h2>1. KIA Sportage NQ5 (2022–2023)</h2>
<p>Самый популярный кроссовер среди наших клиентов. Новая платформа, богатое оснащение, два варианта двигателя: 1.6T и 2.0 NA. Цена под ключ — от 2 850 000 ₽.</p>

<h2>2. Hyundai Tucson (2022–2023)</h2>
<p>Параметрический дизайн, цифровая приборная панель, эффективная гибридная версия. Чуть дороже Sportage, но класс интерьера выше. Цена — от 2 650 000 ₽.</p>

<h2>3. KIA Sorento (2021–2023)</h2>
<p>7-местный кроссовер с просторным третьим рядом. Доступен с дизельным 2.2 и бензиновым 1.6T Hybrid. Цена — от 3 100 000 ₽.</p>

<h2>4. Genesis GV80 (2021–2022)</h2>
<p>Премиальный кроссовер уровня BMW X5 по цене вдвое ниже. Только с японских аукционов. Цена — от 4 200 000 ₽.</p>

<h2>5. Hyundai Palisade (2022–2023)</h2>
<p>Флагманский 7-местный SUV Hyundai. Просторный, тихий, богатый. Дизель 2.2 — идеально для дальних поездок. Цена — от 3 450 000 ₽.</p>',
			'post_excerpt' => 'KIA Sportage, Hyundai Tucson, Sorento, Genesis GV80, Palisade — сравниваем топ-5 корейских кроссоверов.',
			'post_status'  => 'publish',
		],
		[
			'post_type'    => 'post',
			'post_title'   => 'Китайские электромобили в России: реальность 2024',
			'post_name'    => 'kitajskie-elektromobili-rossiya-2024',
			'post_content' => '<p>Китайский электроавтомобиль в России — уже не экзотика. Разбираем, какие модели реально доступны, сколько стоят и что с инфраструктурой зарядки.</p>

<h2>Что привозим из Китая</h2>
<ul>
<li><strong>BYD Atto 3</strong> — самый доступный китайский EV. Запас хода 480 км. Цена от 2 800 000 ₽.</li>
<li><strong>BYD Han EV</strong> — флагманский седан с запасом хода 600 км. Цена от 4 500 000 ₽.</li>
<li><strong>ZEEKR 001</strong> — спортивный лифтбек с мощностью 544 л.с. Цена от 5 200 000 ₽.</li>
<li><strong>Chery OMODA E5</strong> — кроссовер нового поколения. Цена от 2 600 000 ₽.</li>
</ul>

<h2>Зарядная инфраструктура</h2>
<p>Главный вопрос — зарядка. В Москве и Санкт-Петербурге проблем нет: сотни зарядных станций от ChargePoint, Яндекс Зарядки, Росэнергоатома. В регионах — сложнее, но сеть активно расширяется.</p>

<h2>Итог</h2>
<p>Китайский EV — выгодная альтернатива Tesla и европейским маркам при текущем курсе. Привозим под заказ за 30–40 дней.</p>',
			'post_excerpt' => 'BYD, ZEEKR, Chery OMODA — китайские электромобили в России в 2024 году. Цены, реальность, зарядка.',
			'post_status'  => 'publish',
		],
		[
			'post_type'    => 'post',
			'post_title'   => 'Параллельный импорт авто из ОАЭ: как это работает',
			'post_name'    => 'parallelnyj-import-iz-oae',
			'post_content' => '<p>ОАЭ стали одним из главных каналов параллельного импорта автомобилей в Россию. Из Дубая везут Lexus, Toyota Land Cruiser, Mercedes, BMW — по ценам, которых нет у российских дилеров.</p>

<h2>Почему ОАЭ</h2>
<ul>
<li>Нет НДС на автомобили — налог 0%</li>
<li>Низкие таможенные пошлины при ввозе в Эмираты</li>
<li>Огромный рынок — тысячи автомобилей ежедневно</li>
<li>Левый руль — документы подходят для РФ</li>
</ul>

<h2>Что везут из ОАЭ</h2>
<p>Топ запросов: Lexus LX/GX, Toyota Land Cruiser 300/200, Mercedes G-Class, BMW X7, Cadillac Escalade. Все — в максимальных комплектациях, которые официально не продавались в России.</p>

<h2>Стоимость и сроки</h2>
<p>Доставка из Дубая до Москвы — от 18 до 25 дней. Логистика: автовоз → паром → таможня Новороссийск. Цены на 30–60% ниже официальных дилеров.</p>',
			'post_excerpt' => 'Как работает параллельный импорт из ОАЭ, что везут, сколько стоит доставка и растаможка.',
			'post_status'  => 'publish',
		],
	];
}

/* ================================================================
   Pages (country + service pages)
   ================================================================ */

function cfdemo_pages(): array {
	$pages = [];

	$country_pages = [
		[ 'slug' => 'avto-iz-korei',   'title' => 'Автомобили из Кореи под заказ',       'template' => 'page-country.php' ],
		[ 'slug' => 'avto-iz-yaponii', 'title' => 'Авто из Японии с аукционов',           'template' => 'page-country.php' ],
		[ 'slug' => 'avto-iz-kitaya',  'title' => 'Китайские авто: EV, гибриды',          'template' => 'page-country.php' ],
		[ 'slug' => 'avto-iz-usa',     'title' => 'Авто из США: Copart, IAAI',            'template' => 'page-country.php' ],
		[ 'slug' => 'avto-iz-oae',     'title' => 'Авто из ОАЭ: параллельный импорт',     'template' => 'page-country.php' ],
		[ 'slug' => 'calculator',      'title' => 'Калькулятор растаможки авто 2024',     'template' => 'page-calculator.php' ],
		[ 'slug' => 'services',        'title' => 'Услуги — CarFinance MSK',              'template' => 'page-service.php' ],
		[ 'slug' => 'o-kompanii',      'title' => 'О компании',                           'template' => '' ],
		[ 'slug' => 'blog',            'title' => 'Блог об импорте авто',                 'template' => '' ],
	];

	foreach ( $country_pages as $p ) {
		$pages[] = [
			'post_type'   => 'page',
			'post_title'  => $p['title'],
			'post_name'   => $p['slug'],
			'post_status' => 'publish',
			'meta'        => array_filter( [ '_wp_page_template' => $p['template'] ] ),
			'tax'         => [],
		];
	}

	return $pages;
}

/* ================================================================
   Core insert helper
   ================================================================ */

/**
 * Insert a post if it doesn't already exist (checked by title + post_type).
 *
 * @param array $data  Post data with keys: post_type, post_title, post_name,
 *                     post_content, post_excerpt, post_status, meta[], tax[].
 * @return bool True if inserted, false if skipped (already exists).
 */
function cfdemo_insert_post( array $data ): bool {
	$existing = get_page_by_title( $data['post_title'], OBJECT, $data['post_type'] );
	if ( $existing ) {
		return false;
	}

	$post_id = wp_insert_post( [
		'post_type'    => $data['post_type'],
		'post_title'   => $data['post_title'],
		'post_name'    => $data['post_name'] ?? sanitize_title( $data['post_title'] ),
		'post_content' => $data['post_content'] ?? '',
		'post_excerpt' => $data['post_excerpt'] ?? '',
		'post_status'  => $data['post_status'] ?? 'publish',
	], true );

	if ( is_wp_error( $post_id ) ) {
		return false;
	}

	// Mark as demo.
	update_post_meta( $post_id, CFDEMO_TAG, '1' );

	// Save meta.
	foreach ( $data['meta'] ?? [] as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	// Set taxonomy terms.
	foreach ( $data['tax'] ?? [] as $taxonomy => $terms ) {
		if ( ! taxonomy_exists( $taxonomy ) || empty( $terms ) ) {
			continue;
		}
		$term_ids = [];
		foreach ( $terms as $term_name ) {
			$term = get_term_by( 'name', $term_name, $taxonomy );
			if ( $term ) {
				$term_ids[] = (int) $term->term_id;
			} else {
				$inserted = wp_insert_term( $term_name, $taxonomy );
				if ( ! is_wp_error( $inserted ) ) {
					$term_ids[] = (int) $inserted['term_id'];
				}
			}
		}
		if ( $term_ids ) {
			wp_set_post_terms( $post_id, $term_ids, $taxonomy );
		}
	}

	return true;
}

/* ================================================================
   Remove all demo content
   ================================================================ */

function cfdemo_remove_all(): int {
	$deleted = 0;

	$posts = get_posts( [
		'post_type'   => 'any',
		'numberposts' => -1,
		'fields'      => 'ids',
		'meta_key'    => CFDEMO_TAG, // phpcs:ignore WordPress.DB.SlowDBQuery
		'meta_value'  => '1',       // phpcs:ignore WordPress.DB.SlowDBQuery
	] );

	foreach ( $posts as $post_id ) {
		if ( wp_delete_post( (int) $post_id, true ) ) {
			$deleted++;
		}
	}

	return $deleted;
}

/* ================================================================
   Count existing demo records
   ================================================================ */

function cfdemo_count_existing(): int {
	global $wpdb;
	return (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = '1'",
		CFDEMO_TAG
	) );
}
