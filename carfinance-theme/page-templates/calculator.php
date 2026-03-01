<?php
/**
 * Template Name: Калькулятор
 * Template Post Type: page
 *
 * Three calculators in one:
 * 1. Customs calculator (Japan / Korea / China / USA / UAE)
 * 2. Ownership cost calculator
 * 3. Car constructor calculator (Japan)
 *
 * @package CarFinance
 */

get_header();
?>

<section class="cf-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h1>Калькулятор стоимости авто под ключ</h1>
      <p>Рассчитайте полную стоимость автомобиля с растаможкой, доставкой и всеми платежами</p>
    </div>

    <div class="cf-calculator">
      <!-- Calculator type tabs -->
      <div class="cf-calculator__tabs" style="margin-bottom:8px;">
        <button class="cf-calculator__tab cf-calculator__tab--active" data-calc-type="customs">Растаможка</button>
        <button class="cf-calculator__tab" data-calc-type="ownership">Стоимость владения</button>
        <button class="cf-calculator__tab" data-calc-type="constructor">Конструктор (Япония)</button>
      </div>

      <!-- === TAB 1: Customs Calculator === -->
      <div class="cf-calc-panel" id="calc-customs">
        <!-- Country sub-tabs -->
        <div class="cf-calculator__tabs">
          <button class="cf-calculator__tab cf-calculator__tab--active" data-calc-country="korea">Корея</button>
          <button class="cf-calculator__tab" data-calc-country="japan">Япония</button>
          <button class="cf-calculator__tab" data-calc-country="china">Китай</button>
          <button class="cf-calculator__tab" data-calc-country="usa">США</button>
          <button class="cf-calculator__tab" data-calc-country="uae">ОАЭ</button>
        </div>

        <form id="cf-calc-form">
          <div class="cf-calc-form__row">
            <div class="cf-calc-form__group">
              <label for="calc-price">Цена автомобиля (&#8381;)</label>
              <input type="number" id="calc-price" name="price_fob" placeholder="1 500 000" min="0" step="10000" required>
            </div>
            <div class="cf-calc-form__group">
              <label for="calc-year">Год выпуска</label>
              <select id="calc-year" name="year">
                <?php for ($y = date('Y') + 1; $y >= 2000; $y--) : ?>
                  <option value="<?php echo $y; ?>" <?php selected($y, date('Y')); ?>><?php echo $y; ?></option>
                <?php endfor; ?>
              </select>
            </div>
          </div>

          <div class="cf-calc-form__row">
            <div class="cf-calc-form__group">
              <label for="calc-engine">Объём двигателя (куб.см)</label>
              <select id="calc-engine" name="engine_cc">
                <option value="660">660 (кей-кар)</option>
                <option value="1000">до 1 000</option>
                <option value="1500">1 001 — 1 500</option>
                <option value="1800">1 501 — 1 800</option>
                <option value="2000" selected>1 801 — 2 000</option>
                <option value="2300">2 001 — 2 300</option>
                <option value="2500">2 301 — 2 500</option>
                <option value="3000">2 501 — 3 000</option>
                <option value="3500">3 001 — 3 500</option>
                <option value="4000">3 501 — 4 000</option>
                <option value="5000">более 4 000</option>
              </select>
            </div>
            <div class="cf-calc-form__group">
              <label for="calc-fuel">Тип топлива</label>
              <select id="calc-fuel" name="fuel_type">
                <option value="gasoline">Бензин</option>
                <option value="diesel">Дизель</option>
                <option value="hybrid">Гибрид</option>
                <option value="electric">Электро (0 куб.см)</option>
              </select>
            </div>
          </div>

          <div class="cf-calc-form__row">
            <div class="cf-calc-form__group">
              <label for="calc-city">Город доставки</label>
              <select id="calc-city" name="city">
                <option value="vladivostok">Владивосток</option>
                <option value="moscow" selected>Москва</option>
                <option value="krasnodar">Краснодар</option>
                <option value="novosibirsk">Новосибирск</option>
                <option value="ekaterinburg">Екатеринбург</option>
                <option value="sochi">Сочи</option>
              </select>
            </div>
            <div class="cf-calc-form__group">
              <label for="calc-person">Тип лица</label>
              <select id="calc-person" name="person_type">
                <option value="physical">Физическое лицо</option>
                <option value="legal">Юридическое лицо</option>
              </select>
            </div>
          </div>

          <button type="submit" class="cf-btn cf-btn--primary cf-btn--lg cf-btn--block">Рассчитать стоимость под ключ</button>
        </form>

        <!-- Result -->
        <div class="cf-calc-result" id="cf-calc-result" style="display:none;">
          <div class="cf-calc-result__total" id="calc-total"></div>
          <ul class="cf-calc-result__breakdown" id="calc-breakdown"></ul>
          <p style="font-size:0.8125rem;color:var(--cf-gray-500);margin-top:16px;">* Расчёт приблизительный. Для точной стоимости оставьте заявку — мы рассчитаем за 15 минут.</p>
          <div style="display:flex;gap:12px;margin-top:16px;">
            <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary" data-modal="lead">Получить точный расчёт</a>
            <button class="cf-btn cf-btn--outline" onclick="window.print()">Распечатать</button>
          </div>
        </div>
      </div>

      <!-- === TAB 2: Ownership Cost Calculator === -->
      <div class="cf-calc-panel" id="calc-ownership" style="display:none;">
        <form id="cf-ownership-form">
          <div class="cf-calc-form__row">
            <div class="cf-calc-form__group">
              <label>Марка и модель</label>
              <input type="text" name="model" placeholder="Например: Toyota Camry" required>
            </div>
            <div class="cf-calc-form__group">
              <label>Год выпуска</label>
              <input type="number" name="year" value="2022" min="2000" max="2026">
            </div>
          </div>
          <div class="cf-calc-form__row">
            <div class="cf-calc-form__group">
              <label>Пробег в год (км)</label>
              <input type="number" name="annual_km" value="15000" min="0" step="1000">
            </div>
            <div class="cf-calc-form__group">
              <label>Регион</label>
              <select name="region">
                <option value="moscow">Москва</option>
                <option value="spb">Санкт-Петербург</option>
                <option value="region">Регион</option>
              </select>
            </div>
          </div>
          <div class="cf-calc-form__row">
            <div class="cf-calc-form__group">
              <label>Тип топлива</label>
              <select name="fuel_type">
                <option value="gasoline">Бензин АИ-92</option>
                <option value="gasoline95">Бензин АИ-95</option>
                <option value="diesel">Дизель</option>
                <option value="electric">Электро</option>
              </select>
            </div>
            <div class="cf-calc-form__group">
              <label>Расход (л/100 км)</label>
              <input type="number" name="consumption" value="8" min="1" max="30" step="0.5">
            </div>
          </div>
          <button type="submit" class="cf-btn cf-btn--primary cf-btn--lg cf-btn--block">Рассчитать стоимость владения</button>
        </form>

        <div class="cf-calc-result" id="cf-ownership-result" style="display:none;">
          <h3>Стоимость владения за 1 год:</h3>
          <div class="cf-calc-result__total" id="ownership-total"></div>
          <ul class="cf-calc-result__breakdown" id="ownership-breakdown"></ul>
        </div>
      </div>

      <!-- === TAB 3: Constructor Calculator === -->
      <div class="cf-calc-panel" id="calc-constructor" style="display:none;">
        <div class="cf-mb-3">
          <p>Расчёт стоимости авто-конструктора из Японии. Сравните: целый авто vs конструктор.</p>
        </div>
        <form id="cf-constructor-form">
          <div class="cf-calc-form__row">
            <div class="cf-calc-form__group">
              <label>Аукционная цена (JPY)</label>
              <input type="number" name="price_jpy" placeholder="500000" min="0" step="10000" required>
            </div>
            <div class="cf-calc-form__group">
              <label>Объём двигателя (куб.см)</label>
              <select name="engine_cc">
                <option value="660">660</option>
                <option value="1500">1500</option>
                <option value="2000" selected>2000</option>
                <option value="2500">2500</option>
                <option value="3000">3000</option>
              </select>
            </div>
          </div>
          <button type="submit" class="cf-btn cf-btn--primary cf-btn--lg cf-btn--block">Сравнить: целый vs конструктор</button>
        </form>

        <div class="cf-calc-result" id="cf-constructor-result" style="display:none;">
          <div id="constructor-comparison"></div>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ===== INFO SECTION ===== -->
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Из чего складывается стоимость</h2>
    </div>

    <div class="cf-grid cf-grid--3">
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Таможенная пошлина</h4>
          <p class="cf-card__text">Зависит от возраста авто, объёма двигателя и стоимости. Для физлиц — единая ставка.</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Утилизационный сбор</h4>
          <p class="cf-card__text">Обязательный платёж. Зависит от объёма двигателя и возраста автомобиля.</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">СБКТС</h4>
          <p class="cf-card__text">Сертификация безопасности конструкции. Обязательна для всех ввозимых авто.</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">ЭПТС</h4>
          <p class="cf-card__text">Электронный паспорт транспортного средства. Выдаётся после прохождения СБКТС.</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Доставка</h4>
          <p class="cf-card__text">Фрахт морем до порта + доставка автовозом/ЖД контейнером до вашего города.</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Брокер + Комиссия</h4>
          <p class="cf-card__text">Таможенный брокер и наша комиссия за полное сопровождение сделки.</p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===== CTA ===== -->
<section class="cf-cta">
  <div class="cf-container">
    <h2>Нужен точный расчёт?</h2>
    <p>Оставьте заявку — мы рассчитаем стоимость конкретного автомобиля за 15 минут</p>
    <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Получить точный расчёт</a>
  </div>
</section>

<?php get_footer(); ?>
