<?php
/**
 * Template: Car Model Archive (Catalog)
 * URL: /catalog/
 * Horizontal filter panel + AJAX car grid
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

// Determine page title from current context
if ( is_tax( 'car_brand' ) ) {
    $term       = get_queried_object();
    $page_title = 'Каталог ' . $term->name;
    $page_sub   = 'Все модели марки ' . $term->name . ' с доставкой под ключ';
} elseif ( is_tax( 'car_country' ) ) {
    $term       = get_queried_object();
    $page_title = 'Авто из ' . $term->name;
    $page_sub   = 'Каталог автомобилей из ' . $term->name . ' — цены, фото, характеристики';
} elseif ( is_tax( 'car_type' ) ) {
    $term       = get_queried_object();
    $page_title = $term->name . ' из-за рубежа';
    $page_sub   = 'Купить ' . mb_strtolower( $term->name ) . ' из Японии, Кореи, Китая под ключ';
} elseif ( is_tax( 'price_range' ) ) {
    $term       = get_queried_object();
    $page_title = 'Автомобили ' . $term->name;
    $page_sub   = 'Подбор и доставка авто в ценовом диапазоне ' . $term->name . ' под ключ';
} else {
    $page_title = 'Каталог автомобилей';
    $page_sub   = 'Подберите автомобиль из-за рубежа по вашим параметрам';
}

// Pre-populate filter from URL params
$f_country  = sanitize_text_field( $_GET['country'] ?? '' );
$f_brand    = sanitize_text_field( $_GET['brand'] ?? '' );
$f_type     = sanitize_text_field( $_GET['body_type'] ?? '' );
$f_year_f   = absint( $_GET['year_from'] ?? 0 );
$f_year_t   = absint( $_GET['year_to'] ?? 0 );
$f_price_f  = absint( $_GET['price_from'] ?? 0 );
$f_price_t  = absint( $_GET['price_to'] ?? 0 );
$f_cond     = sanitize_text_field( $_GET['condition'] ?? 'all' );

// Countries list for filter
$countries = get_terms( ['taxonomy' => 'car_country', 'hide_empty' => true] );
// Brands list for filter
$brands    = get_terms( ['taxonomy' => 'car_brand', 'hide_empty' => true, 'orderby' => 'name', 'number' => 200] );
// Body types
$body_types = get_terms( ['taxonomy' => 'car_type', 'hide_empty' => true, 'orderby' => 'name'] );
// Engine types
$engine_types = get_terms( ['taxonomy' => 'engine_type', 'hide_empty' => true] );
// Transmission types
$trans_types = get_terms( ['taxonomy' => 'transmission_type', 'hide_empty' => true] );
// Drive types
$drive_types = get_terms( ['taxonomy' => 'drive_type', 'hide_empty' => true] );
?>

<!-- Page header -->
<section class="cf-section cf-section--compact">
    <div class="cf-container">
        <h1 class="cf-catalog__heading"><?php echo esc_html( $page_title ); ?></h1>
        <p class="cf-catalog__subheading"><?php echo esc_html( $page_sub ); ?></p>
    </div>
</section>

<!-- ======================================================
     Horizontal Filter Panel
     ====================================================== -->
<div class="cf-filter-bar" id="cf-filter-bar">
    <div class="cf-container">
        <form id="cf-catalog-filter" class="cf-filter-bar__form" novalidate>

            <!-- ROW 1: Main filters (always visible) -->
            <div class="cf-filter-bar__row cf-filter-bar__row--main">

                <!-- Condition -->
                <div class="cf-filter-bar__field cf-filter-bar__field--radio">
                    <label class="cf-filter-bar__label">Состояние</label>
                    <div class="cf-filter-bar__radio-group">
                        <label class="cf-filter-bar__radio-btn">
                            <input type="radio" name="condition" value="all" <?php checked( $f_cond, 'all' ); ?>>
                            <span>Все</span>
                        </label>
                        <label class="cf-filter-bar__radio-btn">
                            <input type="radio" name="condition" value="new" <?php checked( $f_cond, 'new' ); ?>>
                            <span>Новые</span>
                        </label>
                        <label class="cf-filter-bar__radio-btn">
                            <input type="radio" name="condition" value="used" <?php checked( $f_cond, 'used' ); ?>>
                            <span>С пробегом</span>
                        </label>
                    </div>
                </div>

                <!-- Country -->
                <div class="cf-filter-bar__field">
                    <label class="cf-filter-bar__label" for="cf-filter-country">Страна</label>
                    <select name="country" id="cf-filter-country" class="cf-filter-bar__select">
                        <option value="">Все страны</option>
                        <?php if ( ! is_wp_error( $countries ) ) foreach ( $countries as $c ) : ?>
                            <option value="<?php echo esc_attr( $c->slug ); ?>" <?php selected( $f_country, $c->slug ); ?>>
                                <?php echo esc_html( $c->name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Brand -->
                <div class="cf-filter-bar__field">
                    <label class="cf-filter-bar__label" for="cf-filter-brand">Марка</label>
                    <select name="brand" id="cf-filter-brand" class="cf-filter-bar__select">
                        <option value="">Все марки</option>
                        <?php if ( ! is_wp_error( $brands ) ) foreach ( $brands as $b ) : ?>
                            <option value="<?php echo esc_attr( $b->slug ); ?>" <?php selected( $f_brand, $b->slug ); ?>>
                                <?php echo esc_html( $b->name ); ?> (<?php echo esc_html( $b->count ); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Model (populated dynamically) -->
                <div class="cf-filter-bar__field">
                    <label class="cf-filter-bar__label" for="cf-filter-model">Модель</label>
                    <select name="model" id="cf-filter-model" class="cf-filter-bar__select" disabled>
                        <option value="">Выберите марку</option>
                    </select>
                </div>

                <!-- Year range -->
                <div class="cf-filter-bar__field cf-filter-bar__field--range">
                    <label class="cf-filter-bar__label">Год</label>
                    <div class="cf-filter-bar__range">
                        <input type="number" name="year_from" class="cf-filter-bar__input" placeholder="от"
                               min="2000" max="<?php echo esc_attr( date('Y') ); ?>"
                               value="<?php echo $f_year_f ?: ''; ?>">
                        <span class="cf-filter-bar__sep">—</span>
                        <input type="number" name="year_to" class="cf-filter-bar__input" placeholder="до"
                               min="2000" max="<?php echo esc_attr( date('Y') ); ?>"
                               value="<?php echo $f_year_t ?: ''; ?>">
                    </div>
                </div>

                <!-- Price range -->
                <div class="cf-filter-bar__field cf-filter-bar__field--range">
                    <label class="cf-filter-bar__label">Цена, ₽</label>
                    <div class="cf-filter-bar__range">
                        <input type="number" name="price_from" class="cf-filter-bar__input" placeholder="от"
                               min="0" step="100000"
                               value="<?php echo $f_price_f ?: ''; ?>">
                        <span class="cf-filter-bar__sep">—</span>
                        <input type="number" name="price_to" class="cf-filter-bar__input" placeholder="до"
                               min="0" step="100000"
                               value="<?php echo $f_price_t ?: ''; ?>">
                    </div>
                </div>

                <!-- Search button -->
                <div class="cf-filter-bar__field cf-filter-bar__field--action">
                    <button type="submit" class="cf-btn cf-btn--primary cf-filter-bar__btn-search">
                        Найти
                    </button>
                </div>
            </div>

            <!-- Toggle expanded filters -->
            <div class="cf-filter-bar__toggle-row">
                <button type="button" class="cf-filter-bar__toggle-btn" id="cf-filter-expand" aria-expanded="false">
                    Все фильтры <span class="cf-filter-bar__active-count" id="cf-active-count" style="display:none"></span>
                    <span class="cf-filter-bar__toggle-arrow">▼</span>
                </button>
                <button type="button" class="cf-filter-bar__reset cf-filter-reset" style="display:none" id="cf-filter-reset-btn">
                    Сбросить всё ✕
                </button>
            </div>

            <!-- ROW 2: Extended filters (collapsed by default) -->
            <div class="cf-filter-bar__row cf-filter-bar__row--extended" id="cf-filter-extended" hidden>

                <!-- Mileage -->
                <div class="cf-filter-bar__field cf-filter-bar__field--range">
                    <label class="cf-filter-bar__label">Пробег, км</label>
                    <div class="cf-filter-bar__range">
                        <input type="number" name="mileage_from" class="cf-filter-bar__input" placeholder="от" min="0" step="1000">
                        <span class="cf-filter-bar__sep">—</span>
                        <input type="number" name="mileage_to" class="cf-filter-bar__input" placeholder="до" min="0" step="1000">
                    </div>
                </div>

                <!-- Engine volume -->
                <div class="cf-filter-bar__field cf-filter-bar__field--range">
                    <label class="cf-filter-bar__label">Объём, л</label>
                    <div class="cf-filter-bar__range">
                        <input type="number" name="engine_from" class="cf-filter-bar__input" placeholder="от" min="0.9" max="7" step="0.1">
                        <span class="cf-filter-bar__sep">—</span>
                        <input type="number" name="engine_to" class="cf-filter-bar__input" placeholder="до" min="0.9" max="7" step="0.1">
                    </div>
                </div>

                <!-- Horsepower -->
                <div class="cf-filter-bar__field cf-filter-bar__field--range">
                    <label class="cf-filter-bar__label">Мощность, л.с.</label>
                    <div class="cf-filter-bar__range">
                        <input type="number" name="power_from" class="cf-filter-bar__input" placeholder="от" min="50" step="10">
                        <span class="cf-filter-bar__sep">—</span>
                        <input type="number" name="power_to" class="cf-filter-bar__input" placeholder="до" min="50" step="10">
                    </div>
                </div>

                <!-- Engine type (checkboxes) -->
                <div class="cf-filter-bar__field cf-filter-bar__field--checks">
                    <label class="cf-filter-bar__label">Двигатель</label>
                    <div class="cf-filter-bar__chips">
                        <?php if ( ! is_wp_error( $engine_types ) ) foreach ( $engine_types as $et ) : ?>
                            <label class="cf-filter-bar__chip-check">
                                <input type="checkbox" name="fuel[]" value="<?php echo esc_attr( $et->slug ); ?>">
                                <span><?php echo esc_html( $et->name ); ?></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if ( is_wp_error( $engine_types ) || empty( $engine_types ) ) : ?>
                            <?php foreach ( ['petrol' => 'Бензин', 'diesel' => 'Дизель', 'hybrid' => 'Гибрид', 'electric' => 'Электро', 'gas' => 'ГБО'] as $slug => $name ) : ?>
                                <label class="cf-filter-bar__chip-check">
                                    <input type="checkbox" name="fuel[]" value="<?php echo esc_attr( $slug ); ?>">
                                    <span><?php echo esc_html( $name ); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Transmission (checkboxes) -->
                <div class="cf-filter-bar__field cf-filter-bar__field--checks">
                    <label class="cf-filter-bar__label">КПП</label>
                    <div class="cf-filter-bar__chips">
                        <?php if ( ! is_wp_error( $trans_types ) && ! empty( $trans_types ) ) foreach ( $trans_types as $tt ) : ?>
                            <label class="cf-filter-bar__chip-check">
                                <input type="checkbox" name="transmission[]" value="<?php echo esc_attr( $tt->slug ); ?>">
                                <span><?php echo esc_html( $tt->name ); ?></span>
                            </label>
                        <?php else : ?>
                            <?php foreach ( ['automatic' => 'АКПП', 'manual' => 'МКПП', 'robot' => 'Робот', 'variator' => 'Вариатор'] as $slug => $name ) : ?>
                                <label class="cf-filter-bar__chip-check">
                                    <input type="checkbox" name="transmission[]" value="<?php echo esc_attr( $slug ); ?>">
                                    <span><?php echo esc_html( $name ); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Drive type (checkboxes) -->
                <div class="cf-filter-bar__field cf-filter-bar__field--checks">
                    <label class="cf-filter-bar__label">Привод</label>
                    <div class="cf-filter-bar__chips">
                        <?php if ( ! is_wp_error( $drive_types ) && ! empty( $drive_types ) ) foreach ( $drive_types as $dt ) : ?>
                            <label class="cf-filter-bar__chip-check">
                                <input type="checkbox" name="drive[]" value="<?php echo esc_attr( $dt->slug ); ?>">
                                <span><?php echo esc_html( $dt->name ); ?></span>
                            </label>
                        <?php else : ?>
                            <?php foreach ( ['fwd' => 'Передний', 'rwd' => 'Задний', 'awd' => 'Полный'] as $slug => $name ) : ?>
                                <label class="cf-filter-bar__chip-check">
                                    <input type="checkbox" name="drive[]" value="<?php echo esc_attr( $slug ); ?>">
                                    <span><?php echo esc_html( $name ); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Body type (checkboxes) -->
                <div class="cf-filter-bar__field cf-filter-bar__field--checks">
                    <label class="cf-filter-bar__label">Кузов</label>
                    <div class="cf-filter-bar__chips">
                        <?php if ( ! is_wp_error( $body_types ) ) foreach ( $body_types as $bt ) : ?>
                            <label class="cf-filter-bar__chip-check">
                                <input type="checkbox" name="body_type[]" value="<?php echo esc_attr( $bt->slug ); ?>">
                                <span><?php echo esc_html( $bt->name ); ?></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if ( is_wp_error( $body_types ) || empty( $body_types ) ) : ?>
                            <?php foreach ( ['sedan' => 'Седан', 'krossover' => 'Кроссовер', 'hetchbek' => 'Хэтчбек', 'miniven' => 'Минивэн', 'pikap' => 'Пикап', 'kupe' => 'Купе', 'universal' => 'Универсал', 'furgonet' => 'Фургон'] as $slug => $name ) : ?>
                                <label class="cf-filter-bar__chip-check">
                                    <input type="checkbox" name="body_type[]" value="<?php echo esc_attr( $slug ); ?>">
                                    <span><?php echo esc_html( $name ); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Steering -->
                <div class="cf-filter-bar__field">
                    <label class="cf-filter-bar__label" for="cf-filter-steering">Руль</label>
                    <select name="steering" id="cf-filter-steering" class="cf-filter-bar__select">
                        <option value="">Любой</option>
                        <option value="left">Левый</option>
                        <option value="right">Правый</option>
                    </select>
                </div>

                <!-- Seats -->
                <div class="cf-filter-bar__field">
                    <label class="cf-filter-bar__label" for="cf-filter-seats">Мест</label>
                    <select name="seats" id="cf-filter-seats" class="cf-filter-bar__select">
                        <option value="">Любое</option>
                        <?php foreach ( [2, 5, 7, 9] as $s ) : ?>
                            <option value="<?php echo esc_attr( $s ); ?>"><?php echo esc_html( $s ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Accident free -->
                <div class="cf-filter-bar__field cf-filter-bar__field--check-single">
                    <label class="cf-filter-bar__checkbox-label">
                        <input type="checkbox" name="accident_free" value="1" id="cf-filter-accident-free">
                        <span>Без ДТП</span>
                    </label>
                </div>

            </div><!-- /.cf-filter-bar__row--extended -->

        </form><!-- /#cf-catalog-filter -->
    </div>
</div><!-- /.cf-filter-bar -->

<!-- ======================================================
     Active filter chips
     ====================================================== -->
<div class="cf-container">
    <div class="cf-filter-chips" id="cf-filter-chips"></div>
</div>

<!-- ======================================================
     Catalog Results
     ====================================================== -->
<div class="cf-catalog cf-catalog--full">
    <div class="cf-container">

        <!-- Toolbar: count / sort / view -->
        <div class="cf-catalog__toolbar">
            <div class="cf-catalog__count">
                Найдено: <strong><span id="cf-catalog-count"><?php echo esc_html( $wp_query->found_posts ); ?></span></strong> авто
            </div>
            <div class="cf-catalog__toolbar-right">
                <div class="cf-catalog__sort">
                    <select id="cf-sort" class="cf-filter-bar__select">
                        <option value="">По дате</option>
                        <option value="price_asc">Цена ↑</option>
                        <option value="price_desc">Цена ↓</option>
                        <option value="year_desc">Сначала новые</option>
                        <option value="mileage_asc">Пробег ↑</option>
                        <option value="popular">По популярности</option>
                    </select>
                </div>
                <div class="cf-catalog__view-toggle">
                    <button class="cf-catalog__view cf-catalog__view--grid active" data-view="grid" aria-label="Сетка" title="Сетка">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M1 1h6v6H1zm8 0h6v6H9zm-8 8h6v6H1zm8 0h6v6H9z"/></svg>
                    </button>
                    <button class="cf-catalog__view cf-catalog__view--list" data-view="list" aria-label="Список" title="Список">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M1 2h14v2H1zm0 5h14v2H1zm0 5h14v2H1z"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Car grid / list -->
        <div id="cf-catalog-results" class="cf-catalog__grid">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                    // Render card: use cf_block if available, else inline fallback
                    if ( function_exists( 'cf_block' ) ) {
                        cf_block( 'car-card', ['post_id' => get_the_ID()] );
                    } else {
                        $post_id   = get_the_ID();
                        $price     = get_post_meta( $post_id, 'cf_price_from', true );
                        $year      = get_post_meta( $post_id, 'cf_year', true );
                        $mileage   = get_post_meta( $post_id, 'cf_mileage', true );
                        $engine    = get_post_meta( $post_id, 'cf_engine_cc', true );
                        $brands_t  = get_the_terms( $post_id, 'car_brand' );
                        $brand_n   = $brands_t && ! is_wp_error( $brands_t ) ? $brands_t[0]->name : '';
                        ?>
                        <article class="cf-car-card">
                            <a href="<?php the_permalink(); ?>" class="cf-car-card__link">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="cf-car-card__image">
                                        <?php the_post_thumbnail( 'cf-card', ['loading' => 'lazy', 'class' => 'cf-car-card__img'] ); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="cf-car-card__body">
                                    <?php if ( $brand_n ) : ?>
                                        <span class="cf-car-card__brand"><?php echo esc_html( $brand_n ); ?></span>
                                    <?php endif; ?>
                                    <h3 class="cf-car-card__title"><?php the_title(); ?></h3>
                                    <ul class="cf-car-card__specs">
                                        <?php if ( $year ) : ?><li><?php echo esc_html( $year ); ?> г.</li><?php endif; ?>
                                        <?php if ( $mileage ) : ?><li><?php echo esc_html( number_format( (int) $mileage, 0, '', ' ' ) ); ?> км</li><?php endif; ?>
                                        <?php if ( $engine ) : ?><li><?php echo esc_html( number_format( $engine / 1000, 1, '.', '' ) ); ?> л</li><?php endif; ?>
                                    </ul>
                                    <?php if ( $price ) : ?>
                                        <p class="cf-car-card__price">от <?php echo esc_html( number_format( (int) $price, 0, '', ' ' ) ); ?> ₽</p>
                                    <?php else : ?>
                                        <p class="cf-car-card__price cf-car-card__price--request">По запросу</p>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </article>
                        <?php
                    }
                    ?>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="cf-catalog__empty">
                    <p>Автомобили не найдены. Попробуйте изменить параметры фильтра.</p>
                    <button type="button" class="cf-btn cf-btn--secondary cf-filter-reset">Сбросить фильтры</button>
                </div>
            <?php endif; ?>
        </div><!-- /#cf-catalog-results -->

        <!-- Pagination -->
        <div class="cf-catalog__pagination" id="cf-catalog-pagination">
            <?php if ( $wp_query->max_num_pages > 1 ) : ?>
                <button class="cf-btn cf-btn--secondary cf-btn--wide" id="cf-load-more"
                        data-page="1"
                        data-max="<?php echo esc_attr( $wp_query->max_num_pages ); ?>">
                    Показать ещё
                </button>
                <div class="cf-catalog__classic-pagination">
                    <?php
                    echo paginate_links( [
                        'total'     => $wp_query->max_num_pages,
                        'prev_text' => '← Назад',
                        'next_text' => 'Вперёд →',
                    ] );
                    ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div><!-- /.cf-catalog -->

<!-- Mobile filter drawer -->
<div class="cf-filter-drawer" id="cf-filter-drawer" aria-hidden="true">
    <div class="cf-filter-drawer__overlay" id="cf-drawer-overlay"></div>
    <div class="cf-filter-drawer__panel">
        <div class="cf-filter-drawer__header">
            <span class="cf-filter-drawer__title">Фильтры</span>
            <button class="cf-filter-drawer__close" id="cf-drawer-close" aria-label="Закрыть">✕</button>
        </div>
        <div class="cf-filter-drawer__body">
            <!-- Drawer body is dynamically populated via JS from the main form -->
        </div>
        <div class="cf-filter-drawer__footer">
            <button type="button" class="cf-btn cf-btn--primary cf-btn--full" id="cf-drawer-apply">
                Показать <span id="cf-drawer-count">...</span> авто
            </button>
        </div>
    </div>
</div>

<!-- Mobile filter trigger (shown only on mobile) -->
<div class="cf-filter-mobile-trigger">
    <div class="cf-container cf-filter-mobile-trigger__inner">
        <button type="button" class="cf-btn cf-btn--secondary cf-filter-mobile-trigger__btn" id="cf-mobile-filter-open">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M1 3h14v1.5L9 9v6l-2-1V9L1 4.5V3z"/></svg>
            Фильтры
            <span class="cf-filter-mobile-trigger__count" id="cf-mobile-active-count" style="display:none"></span>
        </button>
        <select class="cf-filter-bar__select cf-filter-mobile-trigger__sort" id="cf-sort-mobile">
            <option value="">По дате</option>
            <option value="price_asc">Цена ↑</option>
            <option value="price_desc">Цена ↓</option>
            <option value="year_desc">Сначала новые</option>
        </select>
    </div>
</div>

<?php
// Taxonomy SEO text block (if term has description)
if ( is_tax() ) {
    $term = get_queried_object();
    if ( $term && ! empty( $term->description ) ) : ?>
        <div class="cf-container">
            <div class="cf-catalog__seo-text cf-content">
                <div class="cf-catalog__seo-text-inner">
                    <?php echo wp_kses_post( $term->description ); ?>
                </div>
                <button class="cf-catalog__seo-toggle" id="cf-seo-toggle" aria-expanded="false">
                    Читать полностью ▼
                </button>
            </div>
        </div>
    <?php endif;
}

// Interlinking footer block
if ( function_exists( 'cf_block' ) ) {
    cf_block( 'interlinking', ['position' => 'footer'] );
}

get_footer();
