<?php
/**
 * Template Name: Страница страны
 * Template Post Type: page
 *
 * Universal landing page for all 5 countries:
 * /korea/, /japan/, /china/, /usa/, /uae/
 *
 * 10 blocks per spec: Hero, Why this country, Popular models,
 * Live lots, Calculator, Process, What we check, Cases, FAQ, Video reviews.
 *
 * Unique blocks:
 *  - USA: Clean Title vs Salvage, How Copart/IAAI works
 *  - UAE: Why UAE is profitable, Popular models LC300/Patrol/LX
 *
 * @package CarFinance
 */

get_header();

// Determine country from page slug
$slug    = get_post_field('post_name', get_the_ID());
$country = cf_get_country_data($slug);

if (empty($country)) {
    // Fallback: try custom field
    $slug    = get_post_meta(get_the_ID(), 'cf_country_slug', true) ?: 'korea';
    $country = cf_get_country_data($slug);
}

$country_name  = $country['name'] ?? 'Страна';
$country_flag  = $country['flag'] ?? '';
$country_css   = $country['hero_css'] ?? '';

// Country-specific data
$country_details = [
    'korea' => [
        'h1'        => 'Авто из Кореи под ключ',
        'subtitle'  => 'Hyundai, KIA, Genesis, Samsung, BMW, Mercedes — левый руль, честная история, доставка 3-4 недели',
        'price'     => 'от 1 200 000',
        'term'      => '3-4 недели',
        'advantages' => [
            'Левый руль — не нужна переделка',
            'Честная страховая история каждого авто',
            'Encar — крупнейшая площадка с 300 000+ авто',
            'Доступны новые и б/у авто',
            'Премиум-марки дешевле на 30-40%',
        ],
        'popular_models' => ['KIA Carnival', 'Hyundai Santa Fe', 'Hyundai Palisade', 'Genesis GV80', 'KIA Sorento'],
        'process_steps' => [
            'Заявка и обсуждение бюджета',
            'Поиск на Encar / SKEncar',
            'Инспекция и проверка',
            'Выкуп и оформление',
            'Паром Корея — Владивосток',
            'Растаможка и СБКТС',
            'Доставка до вашего города',
        ],
    ],
    'japan' => [
        'h1'        => 'Авто из Японии с аукционов',
        'subtitle'  => 'Toyota, Honda, Nissan, Mazda, Subaru — прозрачные аукционы, честный пробег, доставка 3-5 недель',
        'price'     => 'от 900 000',
        'term'      => '3-5 недель',
        'advantages' => [
            'Аукционная система — самая прозрачная в мире',
            'Аукционный лист с независимой оценкой',
            'Минимальный пробег (японцы мало ездят)',
            'Отсутствие реагентов — нет коррозии',
            'Большой выбор минивэнов и кей-каров',
        ],
        'popular_models' => ['Toyota Alphard', 'Honda Freed', 'Nissan Note e-Power', 'Toyota Land Cruiser', 'Suzuki Jimny'],
        'process_steps' => [
            'Заявка и обсуждение',
            'Мониторинг аукционов USS/AA/JU',
            'Расшифровка аукционного листа',
            'Участие в торгах',
            'Доставка морем до Владивостока',
            'Растаможка, СБКТС, ЭПТС',
            'Доставка до вашего города',
        ],
    ],
    'china' => [
        'h1'        => 'Авто из Китая под заказ',
        'subtitle'  => 'Geely, Changan, Chery, BYD, Zeekr, NIO — новые автомобили напрямую с завода',
        'price'     => 'от 1 500 000',
        'term'      => '4-6 недель',
        'advantages' => [
            'Новые авто с гарантией',
            'Максимальная комплектация по цене базовой',
            'Электромобили и гибриды передовых технологий',
            'Левый руль',
            'Цены на 20-30% ниже российского рынка',
        ],
        'popular_models' => ['Geely Monjaro', 'Changan CS75 Plus', 'Chery Tiggo 8 Pro', 'Zeekr 001', 'BYD Han'],
        'process_steps' => [
            'Заявка и подбор модели',
            'Заказ с завода / дилера',
            'Контроль качества',
            'Доставка в РФ',
            'Сертификация (СБКТС)',
            'Растаможка и ЭПТС',
            'Передача клиенту',
        ],
    ],
    'usa' => [
        'h1'        => 'Авто из США',
        'subtitle'  => 'Copart, IAAI — американские авто и мировые бренды по лучшим ценам',
        'price'     => 'от 1 800 000',
        'term'      => '6-10 недель',
        'advantages' => [
            'Экономия до 40% от российской цены',
            'Эксклюзивные модели и комплектации',
            'Доступ к Copart и IAAI',
            'Подробная история (Carfax / AutoCheck)',
            'Левый руль',
        ],
        'popular_models' => ['Toyota Camry', 'Tesla Model 3', 'Ford Mustang', 'Chevrolet Tahoe', 'BMW X5'],
        'process_steps' => [
            'Заявка и бюджет',
            'Поиск на Copart / IAAI',
            'Проверка Carfax / AutoCheck',
            'Участие в торгах',
            'Доставка контейнером в РФ',
            'Растаможка',
            'Ремонт (если нужен) и передача',
        ],
    ],
    'uae' => [
        'h1'        => 'Авто из ОАЭ под заказ',
        'subtitle'  => 'Параллельный импорт — Toyota LC300, Nissan Patrol, Lexus LX, премиум-сегмент',
        'price'     => 'от 3 000 000',
        'term'      => '2-4 недели',
        'advantages' => [
            'Быстрая доставка — 2-4 недели',
            'Модели, недоступные в России',
            'Новые авто с минимальным пробегом',
            'Хорошие комплектации для жаркого климата',
            'Параллельный импорт — законная схема',
        ],
        'popular_models' => ['Toyota Land Cruiser 300', 'Nissan Patrol', 'Lexus LX 600', 'Toyota Hilux', 'Range Rover'],
        'process_steps' => [
            'Заявка и обсуждение',
            'Поиск у дилеров ОАЭ',
            'Осмотр и проверка',
            'Покупка и оформление',
            'Доставка авиа / морем',
            'Растаможка и сертификация',
            'Передача клиенту',
        ],
    ],
];

$details = $country_details[$slug] ?? $country_details['korea'];
?>

<!-- ===== HERO ===== -->
<section class="cf-country-hero <?php echo esc_attr($country_css); ?>">
  <div class="cf-container">
    <div style="display:grid;grid-template-columns:1fr auto;gap:48px;align-items:center;">
      <div>
        <h1><?php echo esc_html($country_flag . ' ' . $details['h1']); ?></h1>
        <p style="font-size:1.125rem;opacity:0.9;margin-top:12px;"><?php echo esc_html($details['subtitle']); ?></p>

        <div class="cf-country-hero__stats">
          <div class="cf-country-hero__stat">
            <div class="cf-country-hero__stat-value"><?php echo esc_html($details['price']); ?> &#8381;</div>
            <div class="cf-country-hero__stat-label">Цена под ключ</div>
          </div>
          <div class="cf-country-hero__stat">
            <div class="cf-country-hero__stat-value"><?php echo esc_html($details['term']); ?></div>
            <div class="cf-country-hero__stat-label">Срок доставки</div>
          </div>
        </div>

        <div class="cf-mt-4">
          <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead" style="color:#fff;">Рассчитать стоимость</a>
          <a href="#country-calc" class="cf-btn cf-btn--outline cf-btn--lg" style="color:#fff;border-color:rgba(255,255,255,0.4);">Калькулятор</a>
        </div>
      </div>

      <!-- Quick lead form -->
      <div class="cf-lead-form" style="min-width:360px;">
        <h3>Узнайте стоимость из <?php echo esc_html($country_name); ?></h3>
        <form action="#" method="post" data-lead-form>
          <?php wp_nonce_field('cf_lead_form', 'cf_lead_nonce'); ?>
          <input type="hidden" name="country" value="<?php echo esc_attr($slug); ?>">
          <input type="text" name="name" class="cf-input" placeholder="Ваше имя" required>
          <input type="tel" name="phone" class="cf-input" placeholder="+7 (___) ___-__-__" required>
          <input type="text" name="car" class="cf-input" placeholder="Какой автомобиль ищете?">
          <button type="submit" class="cf-btn cf-btn--secondary cf-btn--block">Получить расчёт</button>
        </form>
      </div>
    </div>
  </div>
</section>


<!-- ===== WHY THIS COUNTRY ===== -->
<section class="cf-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Почему авто из <?php echo esc_html($country_name); ?>?</h2>
    </div>
    <div class="cf-grid cf-grid--5">
      <?php foreach ($details['advantages'] as $adv) : ?>
        <div class="cf-card">
          <div class="cf-card__body cf-text-center">
            <div style="font-size:1.5rem;color:var(--cf-accent);margin-bottom:8px;">&#10003;</div>
            <p style="font-weight:600;"><?php echo esc_html($adv); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ===== POPULAR MODELS ===== -->
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Популярные модели из <?php echo esc_html($country_name); ?></h2>
    </div>

    <div class="cf-grid cf-grid--5">
      <?php foreach ($details['popular_models'] as $model_name) : ?>
        <div class="cf-card">
          <div class="cf-card__body cf-text-center">
            <h4 class="cf-card__title"><?php echo esc_html($model_name); ?></h4>
            <a href="<?php echo esc_url(home_url('/catalog/')); ?>" class="cf-btn cf-btn--outline cf-btn--sm cf-mt-2">Смотреть лоты</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ===== LIVE LOTS (filtered by country) ===== -->
<section class="cf-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Актуальные лоты из <?php echo esc_html($country_name); ?></h2>
    </div>

    <div class="cf-grid cf-grid--4">
      <?php
      $lots = get_posts([
          'post_type'      => 'auction_lot',
          'posts_per_page' => 8,
          'meta_key'       => 'cf_lot_status',
          'meta_value'     => 'active',
          'tax_query'      => [
              [
                  'taxonomy' => 'cf_country',
                  'field'    => 'slug',
                  'terms'    => $slug,
              ],
          ],
      ]);

      if ($lots) :
          foreach ($lots as $lot) :
              $price_rub = get_post_meta($lot->ID, 'cf_lot_price_rub', true);
              $year      = get_post_meta($lot->ID, 'cf_lot_year', true);
              $mileage   = get_post_meta($lot->ID, 'cf_lot_mileage', true);
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
            </div>
            <?php if ($price_rub) : ?>
              <div class="cf-lot-card__price"><?php echo cf_format_price((int)$price_rub); ?></div>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; else : ?>
        <p style="grid-column:1/-1;" class="cf-text-center">Оставьте заявку — мы найдём лучшие варианты для вас.</p>
      <?php endif; ?>
    </div>
  </div>
</section>


<!-- ===== COUNTRY-SPECIFIC CALCULATOR ===== -->
<section class="cf-section cf-section--gray" id="country-calc">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Калькулятор: авто из <?php echo esc_html($country_name); ?> под ключ</h2>
    </div>
    <div class="cf-calculator">
      <form id="cf-calc-form">
        <input type="hidden" name="country" value="<?php echo esc_attr($slug); ?>">
        <div class="cf-calc-form__row">
          <div class="cf-calc-form__group">
            <label>Цена автомобиля (&#8381;)</label>
            <input type="number" name="price_fob" placeholder="1500000" min="0" step="10000" required>
          </div>
          <div class="cf-calc-form__group">
            <label>Год выпуска</label>
            <select name="year">
              <?php for ($y = date('Y') + 1; $y >= 2000; $y--) : ?>
                <option value="<?php echo $y; ?>" <?php selected($y, date('Y')); ?>><?php echo $y; ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        <div class="cf-calc-form__row">
          <div class="cf-calc-form__group">
            <label>Объём двигателя (куб.см)</label>
            <select name="engine_cc">
              <option value="1000">до 1000</option>
              <option value="1500">1001 — 1500</option>
              <option value="2000" selected>1801 — 2000</option>
              <option value="3000">2001 — 3000</option>
              <option value="4000">более 3000</option>
            </select>
          </div>
          <div class="cf-calc-form__group">
            <label>Тип топлива</label>
            <select name="fuel_type">
              <option value="gasoline">Бензин</option>
              <option value="diesel">Дизель</option>
              <option value="hybrid">Гибрид</option>
              <option value="electric">Электро</option>
            </select>
          </div>
        </div>
        <button type="submit" class="cf-btn cf-btn--primary cf-btn--lg cf-btn--block">Рассчитать</button>
      </form>
      <div class="cf-calc-result" id="cf-calc-result" style="display:none;">
        <div class="cf-calc-result__total" id="calc-total"></div>
        <ul class="cf-calc-result__breakdown" id="calc-breakdown"></ul>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--block cf-mt-3" data-modal="lead">Получить точный расчёт</a>
      </div>
    </div>
  </div>
</section>


<!-- ===== PROCESS (country-specific) ===== -->
<section class="cf-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Процесс покупки из <?php echo esc_html($country_name); ?></h2>
    </div>
    <div class="cf-timeline" style="max-width:700px;margin:0 auto;">
      <?php foreach ($details['process_steps'] as $i => $step) : ?>
        <div class="cf-timeline__step">
          <div class="cf-timeline__number"><?php echo $i + 1; ?></div>
          <div class="cf-timeline__content">
            <h4><?php echo esc_html($step); ?></h4>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<?php if ($slug === 'usa') : ?>
<!-- ===== USA UNIQUE: CLEAN TITLE vs SALVAGE ===== -->
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Чистый титул vs Salvage: что выбрать?</h2>
      <p>Разбираемся в американских статусах автомобилей</p>
    </div>
    <table class="cf-compare-table" style="max-width:800px;margin:0 auto;">
      <thead><tr><th>Параметр</th><th>Clean Title</th><th>Salvage / Rebuilt</th></tr></thead>
      <tbody>
        <tr><td>Состояние</td><td class="check">Без серьёзных повреждений</td><td class="cross">Восстановлен после ДТП/стихии</td></tr>
        <tr><td>Цена</td><td>Рыночная</td><td class="check">На 30-50% ниже</td></tr>
        <tr><td>Риски</td><td class="check">Минимальные</td><td class="cross">Требует детальной проверки</td></tr>
        <tr><td>Перепродажа в РФ</td><td class="check">Без проблем</td><td>Возможна с дисконтом</td></tr>
      </tbody>
    </table>

    <div class="cf-mt-4">
      <div class="cf-section__header"><h3>Как работают Copart и IAAI</h3></div>
      <p class="cf-text-center" style="max-width:700px;margin:0 auto;">Copart и IAAI — крупнейшие аукционные площадки США. Мы имеем прямой дилерский доступ, что позволяет участвовать в торгах без посредников. Каждый автомобиль проверяем по Carfax и AutoCheck перед ставкой.</p>
    </div>
  </div>
</section>
<?php endif; ?>


<?php if ($slug === 'uae') : ?>
<!-- ===== UAE UNIQUE: WHY UAE ===== -->
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Почему ОАЭ сейчас выгодно?</h2>
    </div>
    <div class="cf-grid cf-grid--3">
      <div class="cf-card"><div class="cf-card__body">
        <h4 class="cf-card__title">Параллельный импорт</h4>
        <p class="cf-card__text">Легальная схема ввоза авто, недоступных через официальных дилеров в РФ.</p>
      </div></div>
      <div class="cf-card"><div class="cf-card__body">
        <h4 class="cf-card__title">Быстрая доставка</h4>
        <p class="cf-card__text">Всего 2-4 недели — быстрее, чем из любой другой страны.</p>
      </div></div>
      <div class="cf-card"><div class="cf-card__body">
        <h4 class="cf-card__title">Премиум-сегмент</h4>
        <p class="cf-card__text">LC300, Patrol, LX600 — в максимальных комплектациях по конкурентным ценам.</p>
      </div></div>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ===== CASES (filtered by country) ===== -->
<section class="cf-section <?php echo ($slug === 'usa' || $slug === 'uae') ? '' : 'cf-section--gray'; ?>">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Кейсы из <?php echo esc_html($country_name); ?></h2>
    </div>
    <?php
    $cases = get_posts([
        'post_type'      => 'case_study',
        'posts_per_page' => 3,
        'tax_query'      => [['taxonomy' => 'cf_country', 'field' => 'slug', 'terms' => $slug]],
    ]);

    if ($cases) :
        foreach ($cases as $case) :
            $model   = get_post_meta($case->ID, 'cf_case_model', true);
            $savings = get_post_meta($case->ID, 'cf_case_savings', true);
    ?>
      <div class="cf-case cf-mb-3">
        <?php if (has_post_thumbnail($case->ID)) : ?>
          <div class="cf-case__gallery">
            <img src="<?php echo get_the_post_thumbnail_url($case->ID, 'cf-gallery'); ?>"
                 alt="<?php echo esc_attr($case->post_title); ?>" loading="lazy"
                 style="grid-column:1/-1;" width="800" height="600">
          </div>
        <?php endif; ?>
        <div class="cf-case__info">
          <?php if ($model) : ?><div class="cf-case__badge"><?php echo esc_html($model); ?></div><?php endif; ?>
          <h3><?php echo esc_html($case->post_title); ?></h3>
          <p class="cf-mt-1"><?php echo cf_excerpt($case->post_content, 25); ?></p>
          <?php if ($savings) : ?>
            <div class="cf-case__savings">Экономия: <?php echo cf_format_price((int) $savings); ?></div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; else : ?>
      <p class="cf-text-center">Кейсы скоро будут добавлены.</p>
    <?php endif; ?>
  </div>
</section>


<!-- ===== FAQ ===== -->
<section class="cf-section">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Вопросы про авто из <?php echo esc_html($country_name); ?></h2>
    </div>
    <div class="cf-faq__list">
      <?php
      $country_faqs = get_post_meta(get_the_ID(), 'cf_faqs', true);
      if (!is_array($country_faqs)) {
          $country_faqs = [
              ['question' => 'Сколько стоит доставка из ' . $country_name . '?', 'answer' => 'Стоимость зависит от модели и города назначения. Воспользуйтесь калькулятором выше для точного расчёта.'],
              ['question' => 'Какие документы нужны?', 'answer' => 'С нашей стороны мы готовим все документы: инвойс, коносамент, GTD, СБКТС, ЭПТС. От вас — только паспорт.'],
              ['question' => 'Есть ли гарантия?', 'answer' => 'Да, мы работаем по договору и несём финансовую ответственность за соответствие автомобиля описанию.'],
          ];
      }

      foreach ($country_faqs as $faq) :
      ?>
        <div class="cf-faq__item">
          <button class="cf-faq__question" aria-expanded="false"><?php echo esc_html($faq['question']); ?></button>
          <div class="cf-faq__answer"><p><?php echo esc_html($faq['answer']); ?></p></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ===== CTA ===== -->
<section class="cf-cta">
  <div class="cf-container">
    <h2>Узнайте стоимость авто из <?php echo esc_html($country_name); ?></h2>
    <p>Бесплатная консультация и расчёт за 15 минут</p>
    <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Получить расчёт</a>
  </div>
</section>

<?php get_footer(); ?>
