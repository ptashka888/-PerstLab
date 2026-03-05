<?php
defined('ABSPATH') || exit;

$variant  = $args['variant'] ?? 'full';
$country  = $args['country'] ?? '';

$countries = [
    'korea' => 'Корея',
    'japan' => 'Япония',
    'china' => 'Китай',
    'usa'   => 'США',
    'uae'   => 'ОАЭ',
];

$currencies = [
    'USD' => 'USD',
    'EUR' => 'EUR',
    'KRW' => 'KRW',
    'JPY' => 'JPY',
    'CNY' => 'CNY',
    'AED' => 'AED',
];

$engine_types = [
    'petrol'   => 'Бензин',
    'diesel'   => 'Дизель',
    'hybrid'   => 'Гибрид',
    'electric' => 'Электро',
];

$current_year = (int) date('Y');

$tabs = [];
if ($variant === 'full') {
    $tabs = [
        'turnkey'   => 'Под ключ',
        'customs'   => 'Растаможка',
        'ownership' => 'Стоимость владения',
    ];
}
?>
<section class="cf-calculator cf-calculator--<?php echo esc_attr($variant); ?>">
    <div class="cf-calculator__container">
        <h2 class="cf-calculator__title">Калькулятор стоимости</h2>

        <?php if (!empty($tabs)) : ?>
            <div class="cf-calculator__tabs" role="tablist">
                <?php $first = true; ?>
                <?php foreach ($tabs as $tab_key => $tab_label) : ?>
                    <button
                        class="cf-calculator__tab<?php echo $first ? ' cf-calculator__tab--active' : ''; ?>"
                        role="tab"
                        aria-selected="<?php echo $first ? 'true' : 'false'; ?>"
                        data-tab="<?php echo esc_attr($tab_key); ?>"
                    >
                        <?php echo esc_html($tab_label); ?>
                    </button>
                    <?php $first = false; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form id="cf-calculator-form" class="cf-calculator__form" data-variant="<?php echo esc_attr($variant); ?>">
            <div class="cf-calculator__fields">
                <div class="cf-calculator__field">
                    <label class="cf-calculator__label" for="cf-calc-country">Страна</label>
                    <select class="cf-calculator__select" id="cf-calc-country" name="country">
                        <option value="">Выберите страну</option>
                        <?php foreach ($countries as $code => $name) : ?>
                            <option value="<?php echo esc_attr($code); ?>"<?php selected($country, $code); ?>>
                                <?php echo esc_html($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="cf-calculator__field cf-calculator__field--price">
                    <label class="cf-calculator__label" for="cf-calc-price">Стоимость автомобиля</label>
                    <div class="cf-calculator__input-group">
                        <input
                            class="cf-calculator__input"
                            type="number"
                            id="cf-calc-price"
                            name="price"
                            min="0"
                            placeholder="Введите цену"
                            required
                        >
                        <select class="cf-calculator__currency" name="currency">
                            <?php foreach ($currencies as $code => $label) : ?>
                                <option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="cf-calculator__field">
                    <label class="cf-calculator__label" for="cf-calc-year">Год выпуска</label>
                    <select class="cf-calculator__select" id="cf-calc-year" name="year">
                        <?php for ($y = $current_year; $y >= 2005; $y--) : ?>
                            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="cf-calculator__field">
                    <label class="cf-calculator__label" for="cf-calc-engine-volume">Объём двигателя (куб. см)</label>
                    <input
                        class="cf-calculator__input"
                        type="number"
                        id="cf-calc-engine-volume"
                        name="engine_volume"
                        min="0"
                        max="10000"
                        placeholder="Например, 2000"
                        required
                    >
                </div>

                <div class="cf-calculator__field">
                    <label class="cf-calculator__label" for="cf-calc-engine-type">Тип двигателя</label>
                    <select class="cf-calculator__select" id="cf-calc-engine-type" name="engine_type">
                        <?php foreach ($engine_types as $val => $label) : ?>
                            <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="cf-calculator__submit cf-btn cf-btn--primary">
                Рассчитать стоимость
            </button>
        </form>

        <div id="cf-calculator-results" class="cf-calculator__results" hidden>
            <h3 class="cf-calculator__results-title">Результаты расчёта</h3>
            <table class="cf-calculator__results-table">
                <tbody>
                    <tr>
                        <td>Стоимость автомобиля</td>
                        <td class="cf-calculator__result-value" data-field="car_price">—</td>
                    </tr>
                    <tr>
                        <td>Таможенная пошлина</td>
                        <td class="cf-calculator__result-value" data-field="customs_duty">—</td>
                    </tr>
                    <tr>
                        <td>Утилизационный сбор</td>
                        <td class="cf-calculator__result-value" data-field="utilization_fee">—</td>
                    </tr>
                    <tr>
                        <td>СБКТС</td>
                        <td class="cf-calculator__result-value" data-field="sbkts">—</td>
                    </tr>
                    <tr>
                        <td>ЭПТС</td>
                        <td class="cf-calculator__result-value" data-field="epts">—</td>
                    </tr>
                    <tr>
                        <td>Услуги брокера</td>
                        <td class="cf-calculator__result-value" data-field="broker">—</td>
                    </tr>
                    <tr>
                        <td>Доставка</td>
                        <td class="cf-calculator__result-value" data-field="freight">—</td>
                    </tr>
                    <tr>
                        <td>Комиссия</td>
                        <td class="cf-calculator__result-value" data-field="commission">—</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="cf-calculator__results-total">
                        <td>Итого</td>
                        <td class="cf-calculator__result-value" data-field="total">—</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>
