<?php
/**
 * Template Name: Калькулятор стоимости
 * Template Post Type: page
 */
get_header();
?>

<section class="kv-page-hero">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
        <h1 style="margin-top:16px">Калькулятор стоимости ковки</h1>
        <p class="kv-lead">Рассчитайте ориентировочную цену за 2 минуты. Точную стоимость уточним после бесплатного замера.</p>
    </div>
</section>

<section class="kv-section">
    <div class="kv-container">
        <div class="kv-calc" id="kv-calculator">

            <!-- Табы категорий -->
            <div class="kv-calc__tabs" role="tablist">
                <button class="kv-calc-tab active" data-tab="gates"     role="tab" aria-selected="true">🚪 Ворота</button>
                <button class="kv-calc-tab"         data-tab="fence"    role="tab">🏠 Заборы</button>
                <button class="kv-calc-tab"         data-tab="stairs"   role="tab">🪜 Лестницы</button>
                <button class="kv-calc-tab"         data-tab="furniture"role="tab">🪑 Мебель</button>
                <button class="kv-calc-tab"         data-tab="decor"    role="tab">✨ Декор</button>
            </div>

            <!-- ВОРОТА -->
            <div class="kv-calc__panel active" data-panel="gates" role="tabpanel">
                <div>
                    <h3 style="margin-bottom:24px">Параметры ворот</h3>
                    <div class="kv-form-group">
                        <label>Тип ворот</label>
                        <select class="kv-select calc-type" data-panel="gates">
                            <option value="swing" data-base="18000">Распашные (цена за 1 м²)</option>
                            <option value="slide" data-base="14000">Откатные (цена за 1 м²)</option>
                            <option value="wicket" data-base="22000">Калитка кованая</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Ширина: <strong id="gates-width-val">3</strong> м</label>
                        <input type="range" class="kv-range" id="gates-width" min="1" max="12" value="3" step="0.5">
                    </div>
                    <div class="kv-form-group">
                        <label>Высота: <strong id="gates-height-val">2</strong> м</label>
                        <input type="range" class="kv-range" id="gates-height" min="1" max="4" value="2" step="0.1">
                    </div>
                    <div class="kv-form-group">
                        <label>Покрытие</label>
                        <select class="kv-select" id="gates-coating">
                            <option value="1.0">Порошковая окраска (стандарт)</option>
                            <option value="1.35">Горячее цинкование (+35%)</option>
                            <option value="1.2">Патина (+20%)</option>
                            <option value="0.85">Без покрытия (−15%)</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Сложность узора</label>
                        <select class="kv-select" id="gates-design">
                            <option value="1.0">Простой (прямые линии)</option>
                            <option value="1.25">Средний (завитки, листья)</option>
                            <option value="1.6">Сложный (художественная ковка)</option>
                        </select>
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-bottom:20px">
                        <input type="checkbox" id="gates-install" style="accent-color:var(--kv-accent)"> Включить монтаж (+25%)
                    </label>
                    <button class="kv-btn kv-btn--primary" style="width:100%" onclick="kvCalcPanel('gates')">
                        Рассчитать стоимость
                    </button>
                </div>
                <div class="kv-calc__result" id="gates-result" style="display:none;flex-direction:column;gap:16px">
                    <div>
                        <span class="kv-calc__result-label">Ориентировочная стоимость</span>
                        <span class="kv-calc__result-val" id="gates-result-val">—</span>
                        <span class="kv-calc__result-note">Без стоимости монтажа фундамента и электрики автоматики</span>
                    </div>
                    <div style="background:rgba(255,255,255,.1);border-radius:10px;padding:16px">
                        <div style="font-size:.82rem;opacity:.8;margin-bottom:8px">Срок изготовления</div>
                        <div style="font-size:1.2rem;font-weight:700">14–21 рабочий день</div>
                    </div>
                    <a href="#kv-modal" class="kv-btn kv-btn--white kv-btn--lg kv-modal-open" style="text-align:center">
                        Оформить заявку →
                    </a>
                </div>
            </div>

            <!-- ЗАБОРЫ -->
            <div class="kv-calc__panel" data-panel="fence" role="tabpanel">
                <div>
                    <h3 style="margin-bottom:24px">Параметры забора</h3>
                    <div class="kv-form-group">
                        <label>Длина забора: <strong id="fence-length-val">10</strong> м</label>
                        <input type="range" class="kv-range" id="fence-length" min="2" max="200" value="10" step="1">
                    </div>
                    <div class="kv-form-group">
                        <label>Высота секции: <strong id="fence-height-val">1.5</strong> м</label>
                        <input type="range" class="kv-range" id="fence-height" min="0.8" max="3" value="1.5" step="0.1">
                    </div>
                    <div class="kv-form-group">
                        <label>Тип секции</label>
                        <select class="kv-select" id="fence-type">
                            <option value="4500">Кованая секция (классика)</option>
                            <option value="3200">Профильная труба + элементы ковки</option>
                            <option value="6800">Художественная ковка (сложный узор)</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Опорные столбы</label>
                        <select class="kv-select" id="fence-posts">
                            <option value="0">Заказчик устанавливает сам</option>
                            <option value="4500">Металлические столбы (+4 500 ₽/шт)</option>
                            <option value="8000">Кованые столбы (+8 000 ₽/шт)</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Покрытие</label>
                        <select class="kv-select" id="fence-coating">
                            <option value="1.0">Порошковая окраска</option>
                            <option value="1.35">Горячее цинкование (+35%)</option>
                        </select>
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-bottom:20px">
                        <input type="checkbox" id="fence-install" style="accent-color:var(--kv-accent)"> Включить монтаж (+25%)
                    </label>
                    <button class="kv-btn kv-btn--primary" style="width:100%" onclick="kvCalcPanel('fence')">
                        Рассчитать стоимость
                    </button>
                </div>
                <div class="kv-calc__result" id="fence-result" style="display:none;flex-direction:column;gap:16px">
                    <div>
                        <span class="kv-calc__result-label">Ориентировочная стоимость</span>
                        <span class="kv-calc__result-val" id="fence-result-val">—</span>
                        <span class="kv-calc__result-note">Цена за весь периметр с выбранными параметрами</span>
                    </div>
                    <a href="#kv-modal" class="kv-btn kv-btn--white kv-btn--lg kv-modal-open" style="text-align:center">
                        Оформить заявку →
                    </a>
                </div>
            </div>

            <!-- ЛЕСТНИЦЫ -->
            <div class="kv-calc__panel" data-panel="stairs" role="tabpanel">
                <div>
                    <h3 style="margin-bottom:24px">Параметры лестницы</h3>
                    <div class="kv-form-group">
                        <label>Тип лестницы</label>
                        <select class="kv-select" id="stairs-type">
                            <option value="4800">Маршевая прямая</option>
                            <option value="6200">Маршевая с поворотом 90°</option>
                            <option value="8500">Маршевая с поворотом 180°</option>
                            <option value="12000">Винтовая</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Количество ступеней: <strong id="stairs-steps-val">12</strong></label>
                        <input type="range" class="kv-range" id="stairs-steps" min="3" max="40" value="12" step="1">
                    </div>
                    <div class="kv-form-group">
                        <label>Ширина лестницы (м)</label>
                        <select class="kv-select" id="stairs-width">
                            <option value="0.9">0.9 м (стандарт)</option>
                            <option value="1.1">1.1 м</option>
                            <option value="1.3">1.3 м</option>
                            <option value="1.6">1.6 м (широкая)</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Материал ступеней</label>
                        <select class="kv-select" id="stairs-step-mat">
                            <option value="1.0">Дерево (сосна, дуб)</option>
                            <option value="1.2">Стекло закалённое</option>
                            <option value="0.9">Металл (рифлёный лист)</option>
                        </select>
                    </div>
                    <button class="kv-btn kv-btn--primary" style="width:100%;margin-top:20px" onclick="kvCalcPanel('stairs')">
                        Рассчитать стоимость
                    </button>
                </div>
                <div class="kv-calc__result" id="stairs-result" style="display:none;flex-direction:column;gap:16px">
                    <div>
                        <span class="kv-calc__result-label">Ориентировочная стоимость</span>
                        <span class="kv-calc__result-val" id="stairs-result-val">—</span>
                        <span class="kv-calc__result-note">Без стоимости монтажа в перекрытие</span>
                    </div>
                    <a href="#kv-modal" class="kv-btn kv-btn--white kv-btn--lg kv-modal-open" style="text-align:center">
                        Оформить заявку →
                    </a>
                </div>
            </div>

            <!-- МЕБЕЛЬ -->
            <div class="kv-calc__panel" data-panel="furniture" role="tabpanel">
                <div>
                    <h3 style="margin-bottom:24px">Параметры мебели</h3>
                    <div class="kv-form-group">
                        <label>Тип изделия</label>
                        <select class="kv-select" id="furniture-type">
                            <option value="28000">Стол обеденный (металл + стекло)</option>
                            <option value="65000">Кровать кованая</option>
                            <option value="12000">Стул / кресло</option>
                            <option value="18000">Скамья / лавка</option>
                            <option value="22000">Вешалка / стойка</option>
                            <option value="35000">Диван кованый</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Количество</label>
                        <input type="number" class="kv-input" id="furniture-qty" value="1" min="1" max="50">
                    </div>
                    <div class="kv-form-group">
                        <label>Сложность</label>
                        <select class="kv-select" id="furniture-design">
                            <option value="1.0">Простой (лаконичный)</option>
                            <option value="1.3">Средний (с элементами ковки)</option>
                            <option value="1.7">Сложный (полная художественная ковка)</option>
                        </select>
                    </div>
                    <button class="kv-btn kv-btn--primary" style="width:100%;margin-top:20px" onclick="kvCalcPanel('furniture')">
                        Рассчитать стоимость
                    </button>
                </div>
                <div class="kv-calc__result" id="furniture-result" style="display:none;flex-direction:column;gap:16px">
                    <div>
                        <span class="kv-calc__result-label">Ориентировочная стоимость</span>
                        <span class="kv-calc__result-val" id="furniture-result-val">—</span>
                    </div>
                    <a href="#kv-modal" class="kv-btn kv-btn--white kv-btn--lg kv-modal-open" style="text-align:center">
                        Оформить заявку →
                    </a>
                </div>
            </div>

            <!-- ДЕКОР -->
            <div class="kv-calc__panel" data-panel="decor" role="tabpanel">
                <div>
                    <h3 style="margin-bottom:24px">Декоративные изделия</h3>
                    <div class="kv-form-group">
                        <label>Тип изделия</label>
                        <select class="kv-select" id="decor-type">
                            <option value="1800">Подсвечник</option>
                            <option value="4500">Подставка для цветов</option>
                            <option value="8500">Ваза кованая</option>
                            <option value="22000">Панно кованое (1 м²)</option>
                            <option value="35000">Скульптура малая</option>
                            <option value="90000">Скульптура крупная</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Количество</label>
                        <input type="number" class="kv-input" id="decor-qty" value="1" min="1" max="100">
                    </div>
                    <div class="kv-form-group">
                        <label>Покрытие</label>
                        <select class="kv-select" id="decor-coating">
                            <option value="1.0">Порошок</option>
                            <option value="1.2">Патина золото</option>
                            <option value="1.15">Патина серебро</option>
                            <option value="1.25">Патина медь/бронза</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Упаковка</label>
                        <select class="kv-select" id="decor-pack">
                            <option value="0">Без подарочной упаковки</option>
                            <option value="500">Подарочная коробка (+500 ₽)</option>
                            <option value="1200">Премиум-упаковка (+1 200 ₽)</option>
                        </select>
                    </div>
                    <button class="kv-btn kv-btn--primary" style="width:100%;margin-top:20px" onclick="kvCalcPanel('decor')">
                        Рассчитать стоимость
                    </button>
                </div>
                <div class="kv-calc__result" id="decor-result" style="display:none;flex-direction:column;gap:16px">
                    <div>
                        <span class="kv-calc__result-label">Ориентировочная стоимость</span>
                        <span class="kv-calc__result-val" id="decor-result-val">—</span>
                    </div>
                    <a href="#kv-modal" class="kv-btn kv-btn--white kv-btn--lg kv-modal-open" style="text-align:center">
                        Оформить заявку →
                    </a>
                </div>
            </div>
        </div><!-- /.kv-calc -->

        <!-- Дисклеймер -->
        <div class="kv-notice kv-mt-24">
            📌 Калькулятор даёт <strong>ориентировочную стоимость</strong>.
            Финальная цена согласовывается после бесплатного выезда замерщика.
            Цена фиксируется в договоре и не меняется.
        </div>
    </div>
</section>

<!-- FAQ калькулятора -->
<section class="kv-section kv-section--subtle">
    <div class="kv-container">
        <div class="kv-section-head"><h2>Часто спрашивают о ценах</h2></div>
        <div style="max-width:760px;margin:0 auto">
            <?php
            $faq_items = [
                ['q' => 'Почему цена в калькуляторе отличается от финальной?',
                 'a' => 'Калькулятор рассчитывает по средним параметрам. Финальная цена зависит от точных размеров (которые снимает наш замерщик), конкретного узора, типа металла и объёма монтажных работ. Разница обычно ±10%.'],
                ['q' => 'Входит ли монтаж в стоимость?',
                 'a' => 'Стоимость монтажа рассчитывается отдельно. В калькуляторе можно добавить монтаж (+25% от стоимости изделия). Точная цена монтажа зависит от сложности установки и региона.'],
                ['q' => 'Есть ли скидки при заказе нескольких изделий?',
                 'a' => 'Да. При заказе от 3 изделий — скидка 5%, от 5 — 10%, для застройщиков и дизайнеров — отдельные условия. Обсуждается индивидуально.'],
                ['q' => 'Можно ли изменить заказ после договора?',
                 'a' => 'До запуска производства — изменения бесплатны. После запуска — возможны небольшие корректировки, стоимость уточняется.'],
            ];
            foreach ($faq_items as $faq) : ?>
            <div class="kv-faq-item">
                <div class="kv-faq-question" tabindex="0"><span><?= esc_html($faq['q']) ?></span><div class="kv-faq-icon">+</div></div>
                <div class="kv-faq-answer"><?= esc_html($faq['a']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
