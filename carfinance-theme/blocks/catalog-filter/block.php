<?php
defined('ABSPATH') || exit;

$mode = $args['mode'] ?? 'full-page';

$countries  = get_terms(['taxonomy' => 'car_country', 'hide_empty' => true]);
$brands     = get_terms(['taxonomy' => 'car_brand', 'hide_empty' => true]);
$body_types = get_terms(['taxonomy' => 'car_type', 'hide_empty' => true]);

$fuel_options = [
    ''           => 'Любое',
    'benzin'     => 'Бензин',
    'dizel'      => 'Дизель',
    'gibrid'     => 'Гибрид',
    'elektro'    => 'Электро',
    'gaz'        => 'Газ',
];

$transmission_options = [
    ''       => 'Любая',
    'akpp'   => 'АКПП',
    'mkpp'   => 'МКПП',
    'robot'  => 'Робот',
    'cvt'    => 'Вариатор',
];

$drive_options = [
    ''        => 'Любой',
    'fwd'     => 'Передний',
    'rwd'     => 'Задний',
    'awd'     => 'Полный',
];

$sort_options = [
    'price_asc'  => 'По цене ↑',
    'price_desc' => 'По цене ↓',
    'year_desc'  => 'По году',
    'popular'    => 'По популярности',
];
?>
<div class="cf-catalog-filter cf-catalog-filter--<?php echo esc_attr($mode); ?>">
    <form id="cf-catalog-filter" class="cf-catalog-filter__form"
          action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
          method="POST">
        <?php wp_nonce_field('cf_catalog_filter', 'cf_filter_nonce'); ?>
        <input type="hidden" name="action" value="cf_filter_catalog">

        <div class="cf-catalog-filter__panel">
            <!-- Страна -->
            <div class="cf-catalog-filter__group">
                <label class="cf-catalog-filter__label">
                    Страна
                    <button type="button" class="cf-catalog-filter__reset" data-target="cf-filter-country">&times;</button>
                </label>
                <div class="cf-catalog-filter__buttons" id="cf-filter-country">
                    <?php if ($countries && ! is_wp_error($countries)) :
                        foreach ($countries as $c) :
                            $cd = function_exists('cf_get_country_data') ? cf_get_country_data($c->slug) : [];
                    ?>
                        <label class="cf-catalog-filter__btn-check">
                            <input type="checkbox" name="country[]" value="<?php echo esc_attr($c->slug); ?>">
                            <span><?php echo esc_html($cd['name'] ?? $c->name); ?></span>
                        </label>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Марка -->
            <div class="cf-catalog-filter__group">
                <label class="cf-catalog-filter__label">
                    Марка
                    <button type="button" class="cf-catalog-filter__reset" data-target="cf-filter-brand">&times;</button>
                </label>
                <select name="brand" id="cf-filter-brand" class="cf-catalog-filter__select">
                    <option value="">Все марки</option>
                    <?php if ($brands && ! is_wp_error($brands)) :
                        foreach ($brands as $b) : ?>
                            <option value="<?php echo esc_attr($b->slug); ?>"><?php echo esc_html($b->name); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <!-- Тип кузова -->
            <div class="cf-catalog-filter__group">
                <label class="cf-catalog-filter__label">
                    Тип кузова
                    <button type="button" class="cf-catalog-filter__reset" data-target="cf-filter-body">&times;</button>
                </label>
                <div class="cf-catalog-filter__checkboxes" id="cf-filter-body">
                    <?php if ($body_types && ! is_wp_error($body_types)) :
                        foreach ($body_types as $bt) : ?>
                            <label class="cf-catalog-filter__checkbox">
                                <input type="checkbox" name="body_type[]" value="<?php echo esc_attr($bt->slug); ?>">
                                <span><?php echo esc_html($bt->name); ?></span>
                            </label>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Год -->
            <div class="cf-catalog-filter__group">
                <label class="cf-catalog-filter__label">
                    Год
                    <button type="button" class="cf-catalog-filter__reset" data-target="cf-filter-year">&times;</button>
                </label>
                <div class="cf-catalog-filter__range" id="cf-filter-year">
                    <input type="number" name="year_from" placeholder="от" min="1990" max="2030" class="cf-catalog-filter__input">
                    <span class="cf-catalog-filter__sep">&mdash;</span>
                    <input type="number" name="year_to" placeholder="до" min="1990" max="2030" class="cf-catalog-filter__input">
                </div>
            </div>

            <!-- Цена -->
            <div class="cf-catalog-filter__group">
                <label class="cf-catalog-filter__label">
                    Цена, ₽
                    <button type="button" class="cf-catalog-filter__reset" data-target="cf-filter-price">&times;</button>
                </label>
                <div class="cf-catalog-filter__range" id="cf-filter-price">
                    <input type="number" name="price_from" placeholder="от" min="0" step="100000" class="cf-catalog-filter__input">
                    <span class="cf-catalog-filter__sep">&mdash;</span>
                    <input type="number" name="price_to" placeholder="до" min="0" step="100000" class="cf-catalog-filter__input">
                </div>
            </div>

            <!-- Топливо -->
            <div class="cf-catalog-filter__group">
                <label class="cf-catalog-filter__label">Топливо</label>
                <select name="fuel" class="cf-catalog-filter__select">
                    <?php foreach ($fuel_options as $val => $label) : ?>
                        <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- КПП -->
            <div class="cf-catalog-filter__group">
                <label class="cf-catalog-filter__label">КПП</label>
                <select name="transmission" class="cf-catalog-filter__select">
                    <?php foreach ($transmission_options as $val => $label) : ?>
                        <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Привод -->
            <div class="cf-catalog-filter__group">
                <label class="cf-catalog-filter__label">Привод</label>
                <select name="drive" class="cf-catalog-filter__select">
                    <?php foreach ($drive_options as $val => $label) : ?>
                        <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="cf-catalog-filter__actions">
                <button type="submit" class="cf-btn cf-btn--primary">Показать</button>
                <button type="reset" class="cf-btn cf-btn--outline">Сбросить</button>
            </div>
        </div>

        <!-- Sort -->
        <div class="cf-catalog-filter__toolbar">
            <div class="cf-catalog-filter__active" id="cf-active-filters"></div>
            <div class="cf-catalog-filter__sort">
                <label for="cf-sort">Сортировка:</label>
                <select name="sort" id="cf-sort" class="cf-catalog-filter__select">
                    <?php foreach ($sort_options as $val => $label) : ?>
                        <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <!-- Results -->
    <div class="cf-catalog-filter__results" id="cf-catalog-results">
        <div class="cf-catalog-filter__spinner" style="display:none;">
            <div class="cf-spinner"></div>
        </div>
        <div class="cf-catalog-filter__grid"></div>
    </div>

    <!-- Pagination -->
    <div class="cf-catalog-filter__pagination" id="cf-catalog-pagination"></div>
</div>
