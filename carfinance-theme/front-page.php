<?php
/**
 * Homepage Template — 18 blocks
 *
 * Implements the full homepage specification:
 * 1. Hero + country buttons + lead form
 * 2. Achievements & counters
 * 3. Dealer scams (6 cards)
 * 4. Risks of buying alone (comparison)
 * 5. What we check — 48-point checklist
 * 6. Country cards + comparison table
 * 7. Live auction lots
 * 8. Service pricing (3 packages)
 * 9. Calculator (3 steps)
 * 10. Cases (before/after)
 * 11. How we work — 8-step timeline
 * 12. Personal responsibility (founder)
 * 13. Team cards
 * 14. Services spectrum (5 cards)
 * 15. Why it's profitable — Dealer vs Us
 * 16. Video reviews
 * 17. FAQ (Schema.org/FAQPage)
 * 18. Final CTA
 *
 * @package CarFinance
 */

get_header();
?>

<!-- ===== BLOCK 1: HERO ===== -->
<section class="cf-hero" id="hero">
  <div class="cf-container" style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;">
    <div class="cf-hero__content">
      <h1>Импорт и подбор автомобилей из&nbsp;Кореи, Японии, Китая, США и&nbsp;ОАЭ</h1>
      <p class="cf-hero__subtitle">Полный цикл: от&nbsp;поиска на&nbsp;аукционе до&nbsp;постановки на&nbsp;учёт. Экономия от&nbsp;300&nbsp;000&nbsp;&#8381; по&nbsp;сравнению с&nbsp;дилером.</p>

      <!-- 5 country buttons -->
      <div class="cf-countries">
        <a href="<?php echo esc_url(home_url('/korea/')); ?>" class="cf-countries__btn">
          <span class="flag">&#x1F1F0;&#x1F1F7;</span> Корея
        </a>
        <a href="<?php echo esc_url(home_url('/japan/')); ?>" class="cf-countries__btn">
          <span class="flag">&#x1F1EF;&#x1F1F5;</span> Япония
        </a>
        <a href="<?php echo esc_url(home_url('/china/')); ?>" class="cf-countries__btn">
          <span class="flag">&#x1F1E8;&#x1F1F3;</span> Китай
        </a>
        <a href="<?php echo esc_url(home_url('/usa/')); ?>" class="cf-countries__btn">
          <span class="flag">&#x1F1FA;&#x1F1F8;</span> США
        </a>
        <a href="<?php echo esc_url(home_url('/uae/')); ?>" class="cf-countries__btn">
          <span class="flag">&#x1F1E6;&#x1F1EA;</span> ОАЭ
        </a>
      </div>
    </div>

    <!-- Lead capture form -->
    <div class="cf-lead-form">
      <h3>Узнайте стоимость авто под ключ</h3>
      <form action="#" method="post" data-lead-form>
        <?php wp_nonce_field('cf_lead_form', 'cf_lead_nonce'); ?>
        <input type="text" name="name" class="cf-input" placeholder="Ваше имя" required>
        <input type="tel" name="phone" class="cf-input" placeholder="+7 (___) ___-__-__" required>
        <select name="country" class="cf-input">
          <option value="">Откуда привезти?</option>
          <option value="korea">Корея</option>
          <option value="japan">Япония</option>
          <option value="china">Китай</option>
          <option value="usa">США</option>
          <option value="uae">ОАЭ</option>
        </select>
        <input type="text" name="car" class="cf-input" placeholder="Какой автомобиль ищете?">
        <button type="submit" class="cf-btn cf-btn--secondary cf-btn--block cf-btn--lg">Рассчитать стоимость</button>
        <p style="font-size:0.75rem;color:rgba(255,255,255,0.5);margin-top:8px;text-align:center;">Ответим в течение 15 минут</p>
      </form>
    </div>
  </div>
</section>


<!-- ===== BLOCK 2: ACHIEVEMENTS & COUNTERS ===== -->
<section class="cf-section" id="achievements">
  <div class="cf-container">
    <div class="cf-counters">
      <div class="cf-counter">
        <div class="cf-counter__number" data-counter="1200">1 200+</div>
        <div class="cf-counter__label">Автомобилей доставлено</div>
      </div>
      <div class="cf-counter">
        <div class="cf-counter__number" data-counter="7">7 лет</div>
        <div class="cf-counter__label">На рынке автоимпорта</div>
      </div>
      <div class="cf-counter">
        <div class="cf-counter__number" data-counter="5">5 стран</div>
        <div class="cf-counter__label">Направлений поставок</div>
      </div>
      <div class="cf-counter">
        <div class="cf-counter__number">98%</div>
        <div class="cf-counter__label">Клиентов рекомендуют нас</div>
      </div>
    </div>
  </div>
</section>


<!-- ===== BLOCK 3: DEALER SCAMS ===== -->
<section class="cf-section cf-section--gray" id="dealer-scams">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Что творится в автосалонах</h2>
      <p>6 распространённых схем обмана, которые используют недобросовестные дилеры</p>
    </div>

    <div class="cf-scams__grid">
      <div class="cf-scam-card">
        <div class="cf-scam-card__icon">&#9888;</div>
        <h3 class="cf-scam-card__title">Навязывание допов</h3>
        <p class="cf-card__text">Сигнализация за 150 000, защита за 80 000, коврики за 40 000. Итого +300 000 к цене авто.</p>
      </div>
      <div class="cf-scam-card">
        <div class="cf-scam-card__icon">&#9888;</div>
        <h3 class="cf-scam-card__title">Подмена комплектации</h3>
        <p class="cf-card__text">Обещают «максималку», а по факту — базовая с дешёвой допустановкой.</p>
      </div>
      <div class="cf-scam-card">
        <div class="cf-scam-card__icon">&#9888;</div>
        <h3 class="cf-scam-card__title">Кредитные ловушки</h3>
        <p class="cf-card__text">Низкая цена только при покупке в кредит под 25% годовых со страховкой на 5 лет.</p>
      </div>
      <div class="cf-scam-card">
        <div class="cf-scam-card__icon">&#9888;</div>
        <h3 class="cf-scam-card__title">Скрученный пробег</h3>
        <p class="cf-card__text">На trade-in пробег 80 000, по факту — 200 000+. Проверки не проводятся.</p>
      </div>
      <div class="cf-scam-card">
        <div class="cf-scam-card__icon">&#9888;</div>
        <h3 class="cf-scam-card__title">«Последний экземпляр»</h3>
        <p class="cf-card__text">Давление на срочность: «Завтра заберут». Классическая манипуляция для ускорения сделки.</p>
      </div>
      <div class="cf-scam-card">
        <div class="cf-scam-card__icon">&#9888;</div>
        <h3 class="cf-scam-card__title">Предоплата без гарантий</h3>
        <p class="cf-card__text">Берут 100 000 задаток, затем меняют условия. Вернуть деньги крайне сложно.</p>
      </div>
    </div>
  </div>
</section>


<!-- ===== BLOCK 4: RISKS — ALONE vs WITH US ===== -->
<section class="cf-section" id="risks">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Самостоятельно vs С нами</h2>
      <p>Сравните риски покупки авто самому и с профессиональным подбором</p>
    </div>

    <table class="cf-compare-table">
      <thead>
        <tr>
          <th>Критерий</th>
          <th>Самостоятельно</th>
          <th>С CarFinance</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Знание рынка</td>
          <td class="cross">Поверхностное</td>
          <td class="check">7 лет экспертизы</td>
        </tr>
        <tr>
          <td>Проверка авто</td>
          <td class="cross">На глаз + толщиномер</td>
          <td class="check">48 пунктов + OBD2 + эндоскоп</td>
        </tr>
        <tr>
          <td>Юридическая чистота</td>
          <td class="cross">Самостоятельная проверка</td>
          <td class="check">Проверка по 12 базам</td>
        </tr>
        <tr>
          <td>Торг</td>
          <td class="cross">Без аргументов</td>
          <td class="check">Снижаем цену на 5-15%</td>
        </tr>
        <tr>
          <td>Время</td>
          <td class="cross">2-4 недели поиска</td>
          <td class="check">Находим за 3-7 дней</td>
        </tr>
        <tr>
          <td>Гарантия</td>
          <td class="cross">Нет</td>
          <td class="check">Договор + гарантия возврата</td>
        </tr>
        <tr>
          <td>Скрытые дефекты</td>
          <td class="cross">Высокий риск</td>
          <td class="check">Выявляем до покупки</td>
        </tr>
        <tr>
          <td>Итоговая переплата</td>
          <td class="cross">до 500 000 &#8381;</td>
          <td class="check">Экономия от 300 000 &#8381;</td>
        </tr>
      </tbody>
    </table>
  </div>
</section>


<!-- ===== BLOCK 5: WHAT WE CHECK — 48 POINTS ===== -->
<section class="cf-section cf-section--gray" id="checklist">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Что мы проверяем: чек-лист из 48 пунктов</h2>
      <p>Профессиональная диагностика каждого автомобиля перед покупкой</p>
    </div>

    <div class="cf-grid cf-grid--4">
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Кузов и ЛКП</h4>
          <p class="cf-card__text">Толщиномер всех элементов, проверка на ДТП, ржавчина, подкрасы, зазоры</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Двигатель</h4>
          <p class="cf-card__text">OBD2 сканер, эндоскопия цилиндров, компрессия, масложор, течи</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Трансмиссия</h4>
          <p class="cf-card__text">Проверка АКПП/МКПП, вариатора, масло, вибрации, толчки</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Ходовая часть</h4>
          <p class="cf-card__text">Подвеска, рулевое, тормоза, шины, люфты, подшипники</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Электрика</h4>
          <p class="cf-card__text">Все системы, датчики, бортовой компьютер, блоки управления</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Салон</h4>
          <p class="cf-card__text">Состояние обивки, кресел, руля, панели, запах, следы затопления</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Юридическая чистота</h4>
          <p class="cf-card__text">VIN, ПТС, залог, ДТП, розыск, ограничения, утильсбор</p>
        </div>
      </div>
      <div class="cf-card">
        <div class="cf-card__body">
          <h4 class="cf-card__title">Тест-драйв</h4>
          <p class="cf-card__text">Поведение на дороге, шумы, вибрации, работа всех систем</p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===== BLOCK 6: COUNTRY CARDS + COMPARISON TABLE ===== -->
<section class="cf-section" id="countries-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>5 направлений импорта</h2>
      <p>Выберите страну — мы подберём лучший вариант именно для вас</p>
    </div>

    <div class="cf-grid cf-grid--5" style="margin-bottom:48px;">
      <?php
      $country_cards = [
          ['slug' => 'korea', 'flag' => '&#x1F1F0;&#x1F1F7;', 'name' => 'Корея', 'desc' => 'Hyundai, KIA, Genesis, Samsung — левый руль, новые и б/у', 'price' => 'от 1.2 млн'],
          ['slug' => 'japan', 'flag' => '&#x1F1EF;&#x1F1F5;', 'name' => 'Япония', 'desc' => 'Toyota, Honda, Nissan, Mazda — аукционы, честный пробег', 'price' => 'от 900 тыс'],
          ['slug' => 'china', 'flag' => '&#x1F1E8;&#x1F1F3;', 'name' => 'Китай', 'desc' => 'Geely, Changan, Chery, BYD — новые авто напрямую', 'price' => 'от 1.5 млн'],
          ['slug' => 'usa',   'flag' => '&#x1F1FA;&#x1F1F8;', 'name' => 'США', 'desc' => 'Copart, IAAI — американские авто и мировые бренды', 'price' => 'от 1.8 млн'],
          ['slug' => 'uae',   'flag' => '&#x1F1E6;&#x1F1EA;', 'name' => 'ОАЭ', 'desc' => 'Параллельный импорт — LC300, Patrol, LX, премиум', 'price' => 'от 3 млн'],
      ];
      foreach ($country_cards as $cc) :
      ?>
        <a href="<?php echo esc_url(home_url('/' . $cc['slug'] . '/')); ?>" class="cf-card" style="text-decoration:none;">
          <div class="cf-card__body cf-text-center">
            <div style="font-size:3rem;margin-bottom:12px;"><?php echo $cc['flag']; ?></div>
            <h3 class="cf-card__title"><?php echo esc_html($cc['name']); ?></h3>
            <p class="cf-card__text"><?php echo esc_html($cc['desc']); ?></p>
            <div class="cf-card__price cf-mt-2"><?php echo esc_html($cc['price']); ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Comparison table -->
    <h3 class="cf-text-center cf-mb-3">Сравнение направлений</h3>
    <table class="cf-compare-table">
      <thead>
        <tr>
          <th>Параметр</th>
          <th>Корея</th>
          <th>Япония</th>
          <th>Китай</th>
          <th>США</th>
          <th>ОАЭ</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Руль</td><td>Левый</td><td>Правый</td><td>Левый</td><td>Левый</td><td>Левый</td></tr>
        <tr><td>Состояние</td><td>Б/у и новые</td><td>Б/у</td><td>Новые</td><td>Б/у</td><td>Новые</td></tr>
        <tr><td>Срок доставки</td><td>3-4 нед</td><td>3-5 нед</td><td>4-6 нед</td><td>6-10 нед</td><td>2-4 нед</td></tr>
        <tr><td>Растаможка</td><td>Средняя</td><td>Средняя</td><td>Средняя</td><td>Высокая</td><td>Высокая</td></tr>
        <tr><td>Экономия</td><td>до 30%</td><td>до 25%</td><td>до 20%</td><td>до 40%</td><td>до 35%</td></tr>
      </tbody>
    </table>
  </div>
</section>


<!-- ===== BLOCK 7: LIVE AUCTION LOTS ===== -->
<section class="cf-section cf-section--gray" id="lots">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Актуальные лоты с аукционов</h2>
      <p>Живые предложения, обновляются ежедневно</p>
    </div>

    <div class="cf-grid cf-grid--4">
      <?php
      $lots = get_posts([
          'post_type'      => 'auction_lot',
          'posts_per_page' => 8,
          'meta_key'       => 'cf_lot_status',
          'meta_value'     => 'active',
          'orderby'        => 'date',
          'order'          => 'DESC',
      ]);

      if ($lots) :
          foreach ($lots as $lot) :
              $price_rub = get_post_meta($lot->ID, 'cf_lot_price_rub', true);
              $year      = get_post_meta($lot->ID, 'cf_lot_year', true);
              $mileage   = get_post_meta($lot->ID, 'cf_lot_mileage', true);
              $engine    = get_post_meta($lot->ID, 'cf_lot_engine_cc', true);
              $grade     = get_post_meta($lot->ID, 'cf_lot_grade', true);
      ?>
        <article class="cf-lot-card">
          <?php if (has_post_thumbnail($lot->ID)) : ?>
            <img class="cf-lot-card__img"
                 src="<?php echo get_the_post_thumbnail_url($lot->ID, 'cf-lot'); ?>"
                 alt="<?php echo esc_attr($lot->post_title); ?>"
                 loading="lazy" width="480" height="360">
          <?php endif; ?>
          <div class="cf-lot-card__body">
            <h3 class="cf-lot-card__title">
              <a href="<?php echo get_permalink($lot->ID); ?>"><?php echo esc_html($lot->post_title); ?></a>
            </h3>
            <div class="cf-lot-card__specs">
              <?php if ($year) : ?><span>Год: <?php echo esc_html($year); ?></span><?php endif; ?>
              <?php if ($mileage) : ?><span>Пробег: <?php echo number_format((int)$mileage, 0, ',', ' '); ?> км</span><?php endif; ?>
              <?php if ($engine) : ?><span>Двигатель: <?php echo esc_html($engine); ?> cc</span><?php endif; ?>
              <?php if ($grade) : ?><span>Оценка: <?php echo esc_html($grade); ?></span><?php endif; ?>
            </div>
            <?php if ($price_rub) : ?>
              <div class="cf-lot-card__price"><?php echo cf_format_price((int) $price_rub); ?></div>
              <div class="cf-lot-card__price-note">под ключ</div>
            <?php endif; ?>
          </div>
        </article>
      <?php
          endforeach;
      else :
      ?>
        <div class="cf-text-center" style="grid-column:1/-1;">
          <p>Лоты скоро появятся. Оставьте заявку, и мы подберём авто под ваш запрос.</p>
          <a href="#cf-lead-modal" class="cf-btn cf-btn--primary cf-mt-2" data-modal="lead">Оставить заявку</a>
        </div>
      <?php endif; ?>
    </div>

    <div class="cf-text-center cf-mt-4">
      <a href="<?php echo esc_url(home_url('/catalog/')); ?>" class="cf-btn cf-btn--outline">Смотреть все лоты</a>
    </div>
  </div>
</section>


<!-- ===== BLOCK 8: PRICING — 3 PACKAGES ===== -->
<section class="cf-section" id="pricing">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Стоимость услуг</h2>
      <p>Прозрачные цены без скрытых платежей</p>
    </div>

    <div class="cf-pricing">
      <!-- Basic -->
      <div class="cf-pricing__card">
        <div class="cf-pricing__name">Базовый</div>
        <div class="cf-pricing__price">от 25 000 &#8381;</div>
        <ul class="cf-pricing__features">
          <li><span class="check">&#10003;</span> Подбор б/у авто в вашем городе</li>
          <li><span class="check">&#10003;</span> Проверка по базам</li>
          <li><span class="check">&#10003;</span> Осмотр с толщиномером</li>
          <li><span class="check">&#10003;</span> Торг с продавцом</li>
          <li><span class="check">&#10003;</span> Отчёт с фото</li>
        </ul>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--outline cf-btn--block" data-modal="lead">Выбрать</a>
      </div>

      <!-- Standard (featured) -->
      <div class="cf-pricing__card cf-pricing__card--featured">
        <div class="cf-pricing__badge">Популярный</div>
        <div class="cf-pricing__name">Стандарт</div>
        <div class="cf-pricing__price">от 45 000 &#8381;</div>
        <ul class="cf-pricing__features">
          <li><span class="check">&#10003;</span> Всё из «Базового»</li>
          <li><span class="check">&#10003;</span> OBD2 диагностика</li>
          <li><span class="check">&#10003;</span> Эндоскопия двигателя</li>
          <li><span class="check">&#10003;</span> Проверка ходовой на подъёмнике</li>
          <li><span class="check">&#10003;</span> Тест-драйв с экспертом</li>
          <li><span class="check">&#10003;</span> Помощь в оформлении</li>
        </ul>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--primary cf-btn--block" data-modal="lead">Выбрать</a>
      </div>

      <!-- Premium -->
      <div class="cf-pricing__card">
        <div class="cf-pricing__name">Под ключ</div>
        <div class="cf-pricing__price">от 5%</div>
        <ul class="cf-pricing__features">
          <li><span class="check">&#10003;</span> Всё из «Стандарта»</li>
          <li><span class="check">&#10003;</span> Покупка на аукционе</li>
          <li><span class="check">&#10003;</span> Доставка морем</li>
          <li><span class="check">&#10003;</span> Полная растаможка</li>
          <li><span class="check">&#10003;</span> СБКТС + ЭПТС</li>
          <li><span class="check">&#10003;</span> Постановка на учёт</li>
          <li><span class="check">&#10003;</span> Доставка до вашего города</li>
        </ul>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--outline cf-btn--block" data-modal="lead">Выбрать</a>
      </div>
    </div>
  </div>
</section>


<!-- ===== BLOCK 9: CALCULATOR ===== -->
<section class="cf-section cf-section--gray" id="calculator">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Калькулятор «Цена под ключ»</h2>
      <p>Рассчитайте полную стоимость автомобиля с доставкой и растаможкой</p>
    </div>

    <div class="cf-calculator">
      <!-- Tabs -->
      <div class="cf-calculator__tabs">
        <button class="cf-calculator__tab cf-calculator__tab--active" data-calc-country="korea">Корея</button>
        <button class="cf-calculator__tab" data-calc-country="japan">Япония</button>
        <button class="cf-calculator__tab" data-calc-country="china">Китай</button>
        <button class="cf-calculator__tab" data-calc-country="usa">США</button>
        <button class="cf-calculator__tab" data-calc-country="uae">ОАЭ</button>
      </div>

      <!-- Form -->
      <form id="cf-calc-form">
        <div class="cf-calc-form__row">
          <div class="cf-calc-form__group">
            <label for="calc-price">Цена автомобиля (&#8381;)</label>
            <input type="number" id="calc-price" name="price_fob" placeholder="Например: 1500000" min="0" step="10000" required>
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
              <option value="1000">до 1000</option>
              <option value="1500">1001 — 1500</option>
              <option value="1800">1501 — 1800</option>
              <option value="2000" selected>1801 — 2000</option>
              <option value="2300">2001 — 2300</option>
              <option value="3000">2301 — 3000</option>
              <option value="3500">3001 — 3500</option>
              <option value="4000">более 3500</option>
            </select>
          </div>
          <div class="cf-calc-form__group">
            <label for="calc-fuel">Тип топлива</label>
            <select id="calc-fuel" name="fuel_type">
              <option value="gasoline">Бензин</option>
              <option value="diesel">Дизель</option>
              <option value="hybrid">Гибрид</option>
              <option value="electric">Электро</option>
            </select>
          </div>
        </div>

        <button type="submit" class="cf-btn cf-btn--primary cf-btn--lg cf-btn--block">Рассчитать стоимость под ключ</button>
      </form>

      <!-- Result -->
      <div class="cf-calc-result" id="cf-calc-result" style="display:none;">
        <div class="cf-calc-result__total" id="calc-total"></div>
        <ul class="cf-calc-result__breakdown" id="calc-breakdown"></ul>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--block cf-mt-3" data-modal="lead">Получить точный расчёт от эксперта</a>
      </div>
    </div>
  </div>
</section>


<!-- ===== BLOCK 10: CASES ===== -->
<section class="cf-section" id="cases">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Кейсы клиентов</h2>
      <p>Реальные примеры подбора и доставки авто</p>
    </div>

    <?php
    $cases = get_posts([
        'post_type'      => 'case_study',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if ($cases) :
        foreach ($cases as $case) :
            $model    = get_post_meta($case->ID, 'cf_case_model', true);
            $budget   = get_post_meta($case->ID, 'cf_case_budget', true);
            $savings  = get_post_meta($case->ID, 'cf_case_savings', true);
            $found    = get_post_meta($case->ID, 'cf_case_found', true);
    ?>
      <div class="cf-case cf-mb-4">
        <div class="cf-case__gallery">
          <?php if (has_post_thumbnail($case->ID)) : ?>
            <img src="<?php echo get_the_post_thumbnail_url($case->ID, 'cf-gallery'); ?>"
                 alt="<?php echo esc_attr($case->post_title); ?>"
                 loading="lazy" width="800" height="600"
                 style="grid-column:1/-1;">
          <?php endif; ?>
        </div>
        <div class="cf-case__info">
          <?php if ($model) : ?>
            <div class="cf-case__badge"><?php echo esc_html($model); ?></div>
          <?php endif; ?>
          <h3><?php echo esc_html($case->post_title); ?></h3>
          <p class="cf-mt-1"><?php echo cf_excerpt($case->post_content, 30); ?></p>
          <?php if ($budget) : ?><p><strong>Бюджет:</strong> <?php echo esc_html($budget); ?></p><?php endif; ?>
          <?php if ($found) : ?><p><strong>Что нашли:</strong> <?php echo esc_html($found); ?></p><?php endif; ?>
          <?php if ($savings) : ?>
            <div class="cf-case__savings">Экономия: <?php echo cf_format_price((int) $savings); ?></div>
          <?php endif; ?>
          <a href="<?php echo get_permalink($case->ID); ?>" class="cf-btn cf-btn--outline cf-btn--sm cf-mt-2">Подробнее</a>
        </div>
      </div>
    <?php
        endforeach;
    else :
    ?>
      <p class="cf-text-center">Кейсы скоро появятся.</p>
    <?php endif; ?>

    <div class="cf-text-center">
      <a href="<?php echo esc_url(home_url('/kejsy/')); ?>" class="cf-btn cf-btn--outline">Все кейсы</a>
    </div>
  </div>
</section>


<!-- ===== BLOCK 11: HOW WE WORK — 8-STEP TIMELINE ===== -->
<section class="cf-section cf-section--gray" id="how-we-work">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Как мы работаем</h2>
      <p>8 простых шагов от заявки до ключей в руках</p>
    </div>

    <div class="cf-timeline" style="max-width:700px;margin:0 auto;">
      <?php
      $steps = [
          ['title' => 'Заявка и консультация', 'text' => 'Вы оставляете заявку, мы обсуждаем ваши пожелания, бюджет и сроки.'],
          ['title' => 'Подбор вариантов', 'text' => 'Находим 5-10 лучших вариантов на аукционах и площадках выбранной страны.'],
          ['title' => 'Проверка и отчёт', 'text' => 'Проводим полную диагностику, присылаем фотоотчёт и заключение.'],
          ['title' => 'Покупка / Выкуп', 'text' => 'Торгуемся и выкупаем автомобиль по лучшей цене.'],
          ['title' => 'Доставка', 'text' => 'Организуем доставку морем до порта (Владивосток / Новороссийск).'],
          ['title' => 'Растаможка', 'text' => 'Оформляем все таможенные документы, оплачиваем пошлины и утильсбор.'],
          ['title' => 'СБКТС и ЭПТС', 'text' => 'Проходим сертификацию и получаем электронный ПТС.'],
          ['title' => 'Передача авто', 'text' => 'Доставляем автомобиль в ваш город, помогаем с постановкой на учёт.'],
      ];
      foreach ($steps as $i => $step) :
      ?>
        <div class="cf-timeline__step">
          <div class="cf-timeline__number"><?php echo $i + 1; ?></div>
          <div class="cf-timeline__content">
            <h4><?php echo esc_html($step['title']); ?></h4>
            <p><?php echo esc_html($step['text']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ===== BLOCK 12: PERSONAL RESPONSIBILITY (FOUNDER) ===== -->
<section class="cf-section" id="founder">
  <div class="cf-container">
    <div style="display:grid;grid-template-columns:300px 1fr;gap:48px;align-items:center;max-width:900px;margin:0 auto;">
      <div>
        <img src="<?php echo esc_url(CF_URI . '/assets/img/founder-placeholder.jpg'); ?>"
             alt="Основатель CarFinance"
             width="300" height="380"
             style="border-radius:var(--cf-radius);object-fit:cover;width:100%;height:auto;"
             loading="lazy">
      </div>
      <div>
        <h2>Личная ответственность</h2>
        <p class="cf-mt-2" style="font-size:1.125rem;">Я лично контролирую каждую сделку и несу персональную ответственность за результат. Моя репутация — это моя гарантия.</p>
        <p class="cf-mt-2"><strong>Иван Лещенко</strong><br>Основатель CarFinance MSK<br>7 лет в автоимпорте, 1 200+ доставленных авто</p>
        <div class="cf-mt-3" style="display:flex;gap:12px;">
          <a href="https://t.me/carfinance_msk" class="cf-btn cf-btn--outline cf-btn--sm" target="_blank" rel="noopener">Telegram</a>
          <a href="https://www.instagram.com/carfinance_msk/" class="cf-btn cf-btn--outline cf-btn--sm" target="_blank" rel="noopener">Instagram</a>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===== BLOCK 13: TEAM ===== -->
<section class="cf-section cf-section--gray" id="team">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Наша команда</h2>
      <p>Профессионалы с опытом в автоимпорте и автоподборе</p>
    </div>

    <div class="cf-team__grid">
      <?php
      $team = get_posts([
          'post_type'      => 'cf_team',
          'posts_per_page' => 8,
          'orderby'        => 'menu_order',
          'order'          => 'ASC',
      ]);

      if ($team) :
          foreach ($team as $member) :
              $role = get_post_meta($member->ID, 'cf_team_role', true);
      ?>
        <div class="cf-team-member">
          <?php if (has_post_thumbnail($member->ID)) : ?>
            <img class="cf-team-member__photo"
                 src="<?php echo get_the_post_thumbnail_url($member->ID, 'cf-team'); ?>"
                 alt="<?php echo esc_attr($member->post_title); ?>"
                 loading="lazy" width="120" height="120">
          <?php endif; ?>
          <div class="cf-team-member__name"><?php echo esc_html($member->post_title); ?></div>
          <?php if ($role) : ?>
            <div class="cf-team-member__role"><?php echo esc_html($role); ?></div>
          <?php endif; ?>
        </div>
      <?php
          endforeach;
      else :
          // Placeholder team
          $placeholders = [
              ['name' => 'Иван', 'role' => 'Основатель'],
              ['name' => 'Алексей', 'role' => 'Менеджер по Корее'],
              ['name' => 'Дмитрий', 'role' => 'Менеджер по Японии'],
              ['name' => 'Елена', 'role' => 'Логистика'],
          ];
          foreach ($placeholders as $ph) :
      ?>
        <div class="cf-team-member">
          <div class="cf-team-member__photo" style="background:var(--cf-gray-300);display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--cf-gray-500);width:120px;height:120px;border-radius:50%;margin:0 auto 16px;">&#128100;</div>
          <div class="cf-team-member__name"><?php echo esc_html($ph['name']); ?></div>
          <div class="cf-team-member__role"><?php echo esc_html($ph['role']); ?></div>
        </div>
      <?php
          endforeach;
      endif;
      ?>
    </div>
  </div>
</section>


<!-- ===== BLOCK 14: SERVICES SPECTRUM ===== -->
<section class="cf-section" id="services-spectrum">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Спектр услуг</h2>
      <p>Полный цикл работы с автомобилями</p>
    </div>

    <div class="cf-grid cf-grid--5">
      <?php
      $services = [
          ['icon' => '&#128269;', 'title' => 'Автоподбор', 'url' => '/avtopodborshchik/', 'desc' => 'Подбор б/у авто на вторичном рынке'],
          ['icon' => '&#128674;', 'title' => 'Импорт', 'url' => '/services/', 'desc' => 'Доставка авто из 5 стран'],
          ['icon' => '&#128270;', 'title' => 'Диагностика', 'url' => '/kupit-avto-s-probegom/diagnostika/', 'desc' => 'Проверка перед покупкой'],
          ['icon' => '&#128196;', 'title' => 'Растаможка', 'url' => '/calculator/', 'desc' => 'Полное таможенное оформление'],
          ['icon' => '&#128666;', 'title' => 'Доставка', 'url' => '/services/', 'desc' => 'Логистика до вашего города'],
      ];
      foreach ($services as $s) :
      ?>
        <a href="<?php echo esc_url(home_url($s['url'])); ?>" class="cf-card" style="text-decoration:none;">
          <div class="cf-card__body cf-text-center">
            <div style="font-size:2.5rem;margin-bottom:12px;"><?php echo $s['icon']; ?></div>
            <h4 class="cf-card__title"><?php echo esc_html($s['title']); ?></h4>
            <p class="cf-card__text"><?php echo esc_html($s['desc']); ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ===== BLOCK 15: DEALER vs US ===== -->
<section class="cf-section cf-section--gray" id="why-profitable">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Почему выгодно работать с нами</h2>
      <p>Реальный кейс: KIA Carnival 2022 из Кореи</p>
    </div>

    <table class="cf-compare-table" style="max-width:800px;margin:0 auto;">
      <thead>
        <tr>
          <th>Параметр</th>
          <th>Дилер в России</th>
          <th>CarFinance из Кореи</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Цена авто</td><td>5 200 000 &#8381;</td><td>3 800 000 &#8381;</td></tr>
        <tr><td>Комплектация</td><td>Базовая</td><td>Максимальная</td></tr>
        <tr><td>Пробег</td><td>Новый</td><td>15 000 км</td></tr>
        <tr><td>Допы салона</td><td>+350 000 &#8381;</td><td>0 &#8381;</td></tr>
        <tr><td>Гарантия</td><td>3 года</td><td>Полная проверка + страховка</td></tr>
        <tr>
          <td><strong>Итого</strong></td>
          <td class="cross"><strong>5 550 000 &#8381;</strong></td>
          <td class="check"><strong>3 800 000 &#8381;</strong></td>
        </tr>
        <tr>
          <td colspan="2"></td>
          <td class="check" style="font-size:1.25rem;"><strong>Экономия: 1 750 000 &#8381;</strong></td>
        </tr>
      </tbody>
    </table>
  </div>
</section>


<!-- ===== BLOCK 16: VIDEO REVIEWS ===== -->
<section class="cf-section" id="video-reviews">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Видеоотзывы клиентов</h2>
      <p>Реальные истории наших клиентов</p>
    </div>

    <div class="cf-grid cf-grid--3">
      <?php
      $reviews = get_posts([
          'post_type'      => 'cf_review',
          'posts_per_page' => 3,
          'meta_query'     => [
              [
                  'key'     => 'cf_review_video',
                  'compare' => '!=',
                  'value'   => '',
              ],
          ],
      ]);

      if ($reviews) :
          foreach ($reviews as $review) :
              $video  = get_post_meta($review->ID, 'cf_review_video', true);
              $author = get_post_meta($review->ID, 'cf_review_author', true);
              $model  = get_post_meta($review->ID, 'cf_review_model', true);
              // Extract YouTube ID
              $yt_id = '';
              if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([a-zA-Z0-9_-]{11})/', $video, $m)) {
                  $yt_id = $m[1];
              }
      ?>
        <div class="cf-card">
          <?php if ($yt_id) : ?>
            <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">
              <iframe src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr($yt_id); ?>"
                      title="<?php echo esc_attr($review->post_title); ?>"
                      style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;"
                      loading="lazy" allowfullscreen></iframe>
            </div>
          <?php endif; ?>
          <div class="cf-card__body">
            <h4 class="cf-card__title"><?php echo esc_html($review->post_title); ?></h4>
            <?php if ($author) : ?><p class="cf-card__text"><?php echo esc_html($author); ?></p><?php endif; ?>
            <?php if ($model) : ?><p class="cf-card__text"><?php echo esc_html($model); ?></p><?php endif; ?>
          </div>
        </div>
      <?php
          endforeach;
      else :
      ?>
        <p class="cf-text-center" style="grid-column:1/-1;">Видеоотзывы скоро появятся.</p>
      <?php endif; ?>
    </div>
  </div>
</section>


<!-- ===== BLOCK 17: FAQ (Schema.org/FAQPage) ===== -->
<section class="cf-section cf-section--gray" id="faq">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Часто задаваемые вопросы</h2>
    </div>

    <div class="cf-faq__list">
      <?php
      $homepage_faqs = [
          ['q' => 'Сколько времени занимает доставка автомобиля?', 'a' => 'Сроки зависят от страны: Корея — 3-4 недели, Япония — 3-5 недель, Китай — 4-6 недель, США — 6-10 недель, ОАЭ — 2-4 недели. Точные сроки обсуждаем после выбора автомобиля.'],
          ['q' => 'Какие скрытые расходы могут возникнуть?', 'a' => 'У нас нет скрытых расходов. Калькулятор показывает полную стоимость: цена авто + доставка + таможенная пошлина + утильсбор + СБКТС + ЭПТС + наша комиссия. Всё прописано в договоре.'],
          ['q' => 'Как гарантируется безопасность сделки?', 'a' => 'Мы работаем по договору, вы получаете полный фотоотчёт и видео автомобиля до покупки, все платежи прозрачны. Мы застрахованы и несём финансовую ответственность.'],
          ['q' => 'Можно ли вернуть автомобиль?', 'a' => 'Если автомобиль не соответствует описанию — да, мы решим вопрос. Все условия возврата прописаны в договоре. За 7 лет работы таких случаев не было.'],
          ['q' => 'Какие автомобили сейчас популярны для импорта?', 'a' => 'Из Кореи: KIA Carnival, Hyundai Santa Fe, Genesis GV80. Из Японии: Toyota Alphard, Honda Freed, Nissan Note e-Power. Из Китая: Geely Monjaro, Zeekr 001. Из ОАЭ: Toyota LC300, Nissan Patrol.'],
          ['q' => 'Работаете ли вы с регионами?', 'a' => 'Да, мы доставляем автомобили по всей России. Основные офисы в Москве, Владивостоке, Краснодаре, Сочи и Уссурийске. Доставка до вашего города включена в расчёт.'],
          ['q' => 'Нужна ли предоплата?', 'a' => 'Для начала работы достаточно небольшого аванса (обычно 50 000 — 100 000 ₽). Основная оплата — после вашего одобрения конкретного автомобиля. Все платежи фиксируются в договоре.'],
          ['q' => 'Помогаете ли вы с постановкой на учёт?', 'a' => 'Да, при покупке пакета «Под ключ» мы полностью сопровождаем процесс: СБКТС, получение ЭПТС и помощь в регистрации в ГИБДД.'],
      ];

      foreach ($homepage_faqs as $faq) :
      ?>
        <div class="cf-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
          <button class="cf-faq__question" itemprop="name" aria-expanded="false">
            <?php echo esc_html($faq['q']); ?>
          </button>
          <div class="cf-faq__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
            <div itemprop="text"><?php echo esc_html($faq['a']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ===== BLOCK 18: FINAL CTA ===== -->
<section class="cf-cta" id="final-cta">
  <div class="cf-container">
    <h2>Сколько вы переплачиваете дилеру?</h2>
    <p>Узнайте реальную стоимость автомобиля вашей мечты с доставкой из-за рубежа. Бесплатная консультация за 15 минут.</p>
    <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Получить бесплатный расчёт</a>
  </div>
</section>

<?php get_footer(); ?>
