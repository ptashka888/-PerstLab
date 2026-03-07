<?php
/**
 * Template Name: Country Landing
 * Template for: /avto-iz-korei/, /avto-iz-yaponii/, /avto-iz-kitaya/, /avto-iz-usa/, /avto-iz-oae/
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

get_header();

// ── Country detection ─────────────────────────────────────────────────────────
$slug = get_post_field('post_name', get_the_ID());
$country_map = [
    'avto-iz-korei'   => 'korea',
    'avto-iz-yaponii' => 'japan',
    'avto-iz-kitaya'  => 'china',
    'avto-iz-usa'     => 'usa',
    'avto-iz-oae'     => 'uae',
];
$country_code = $country_map[$slug] ?? '';
$country_data = $country_code ? cf_get_country_data($country_code) : [];
$country_name = $country_data['name'] ?? 'этой страны';
$country_flag = $country_data['flag'] ?? '';

// ── Fallback country-specific content ────────────────────────────────────────
$country_defaults = [
    'korea' => [
        'name_from'    => 'Кореи',
        'delivery'     => '14–25 дней',
        'price_from'   => '1 200 000',
        'popular'      => 'KIA, Hyundai, Genesis',
        'intro'        => '<p>Корея — второй по популярности источник автомобилей для российского рынка. Здесь производятся KIA, Hyundai, Genesis — надёжные, хорошо оснащённые машины по доступным ценам. Корейские автомобили отличаются богатой комплектацией даже в базовых версиях и высоким уровнем безопасности.</p><p>Мы работаем напрямую с корейскими дилерами и аукционами с 2016 года. Наш офис в Краснодаре специализируется именно на корейском направлении.</p>',
        'advantages'   => [
            ['icon' => '💰', 'title' => 'Выгодные цены',        'text' => 'Автомобили из Кореи дешевле российских аналогов на 20–35% благодаря прямым закупкам'],
            ['icon' => '⚙️', 'title' => 'Богатые комплектации', 'text' => 'Даже базовые версии KIA и Hyundai имеют подогрев, камеры и продвинутые системы безопасности'],
            ['icon' => '📋', 'title' => 'Чистая история',       'text' => 'Проверяем каждый автомобиль по базам Carfax Korea и официальным реестрам ДТП'],
            ['icon' => '⚡', 'title' => 'Быстрая доставка',     'text' => 'Паромная линия Владивосток — Пусан. Среднее время доставки 14–25 дней'],
            ['icon' => '🏆', 'title' => 'Гарантия',             'text' => 'Предоставляем гарантию на техническое состояние автомобиля на 6 месяцев'],
            ['icon' => '📱', 'title' => 'Онлайн-трансляция',    'text' => 'Участвуете в подборе в реальном времени — фото, видео и аукцион онлайн'],
        ],
        'cost_example' => [
            'auction'  => ['label' => 'Цена на аукционе',  'value' => '900 000 ₽'],
            'delivery' => ['label' => 'Доставка до РФ',    'value' => '120 000 ₽'],
            'customs'  => ['label' => 'Таможня + СБКТС',   'value' => '250 000 ₽'],
            'total'    => ['label' => 'Итого на руки',      'value' => '1 270 000 ₽'],
        ],
        'quote'        => 'KIA и Hyundai из Кореи — лучшее соотношение цена/качество на сегодня. Берём машины напрямую у дилеров, без пробега по РФ.',
    ],
    'japan' => [
        'name_from'    => 'Японии',
        'delivery'     => '18–35 дней',
        'price_from'   => '900 000',
        'popular'      => 'Toyota, Honda, Mazda, Subaru',
        'intro'        => '<p>Японский авторынок — один из самых развитых в мире. Японцы меняют автомобили каждые 3–5 лет, поэтому рынок подержанных машин огромен: тысячи аукционов каждую неделю, строгие технические нормы и честная история каждого лота.</p><p>Наш офис во Владивостоке работает с японскими аукционами USS, TAA, JU, CAA напрямую. Мы участвуем в торгах ежедневно и знаем, как купить лот по минимальной цене.</p>',
        'advantages'   => [
            ['icon' => '🔍', 'title' => 'Аукционный лист',      'text' => 'Каждый автомобиль имеет детальный аукционный лист с оценкой от А до С по состоянию'],
            ['icon' => '💡', 'title' => 'Низкий пробег',        'text' => 'Японцы ездят мало — реальный пробег 40–80 тыс. км для 5-летнего авто норма'],
            ['icon' => '🛡️', 'title' => 'Честная история',      'text' => 'Проверяем по базам JEVIC, ASNET и аукционным реестрам — никаких сюрпризов'],
            ['icon' => '⚡', 'title' => 'Гибриды и электро',    'text' => 'Огромный выбор гибридов Toyota и Honda, недоступных официально в России'],
            ['icon' => '💰', 'title' => 'Конструктор',          'text' => 'Ввоз через НМТП по схеме конструктора — экономия на пошлинах до 400 000 ₽'],
            ['icon' => '🏢', 'title' => 'Офис во Владивостоке', 'text' => 'Собственный офис и склад во Владивостоке — полный контроль до передачи авто'],
        ],
        'cost_example' => [
            'auction'  => ['label' => 'Цена на аукционе',  'value' => '700 000 ₽'],
            'delivery' => ['label' => 'Доставка до РФ',    'value' => '130 000 ₽'],
            'customs'  => ['label' => 'Таможня + СБКТС',   'value' => '220 000 ₽'],
            'total'    => ['label' => 'Итого на руки',      'value' => '1 050 000 ₽'],
        ],
        'quote'        => 'Японские аукционы — моя специализация уже 8 лет. Мы знаем каждый нюанс и умеем покупать там, где другие переплачивают.',
    ],
    'china' => [
        'name_from'    => 'Китая',
        'delivery'     => '20–35 дней',
        'price_from'   => '1 000 000',
        'popular'      => 'Chery, Haval, Geely, BYD',
        'intro'        => '<p>Китайский автопром совершил революцию за последние 5 лет. Современные Chery Tiggo, Haval H9, Geely Atlas Pro и BYD SEAL — это качественные автомобили с богатым оснащением по доступным ценам. Мы привозим новые автомобили напрямую с заводов и у проверенных китайских дилеров.</p><p>С 2022 года Китай стал главным источником новых автомобилей для России. Мы предлагаем весь спектр китайских марок с полным пакетом документов и гарантией.</p>',
        'advantages'   => [
            ['icon' => '🚗', 'title' => 'Новые авто',             'text' => 'Привозим новые автомобили с пробегом 0 км с заводскими гарантиями'],
            ['icon' => '💰', 'title' => 'Лучшие цены',           'text' => 'Прямые поставки без посредников экономят 150–300 тыс. руб. от рыночных цен'],
            ['icon' => '⚡', 'title' => 'Электромобили',         'text' => 'Огромный выбор электрических и гибридных моделей BYD, Zeekr, NIO'],
            ['icon' => '📦', 'title' => 'Быстрая поставка',      'text' => 'Стабильные морские маршруты — Тяньцзинь/Шанхай → Владивосток за 20–35 дней'],
            ['icon' => '🛡️', 'title' => 'Гарантия завода',       'text' => 'Официальная гарантия производителя или наша фирменная гарантия на 1 год'],
            ['icon' => '📋', 'title' => 'Полный пакет',          'text' => 'СБКТС, ЭПТС, ПТС, регистрация — берём на себя все документы'],
        ],
        'cost_example' => [
            'auction'  => ['label' => 'Цена у дилера',     'value' => '750 000 ₽'],
            'delivery' => ['label' => 'Доставка до РФ',    'value' => '150 000 ₽'],
            'customs'  => ['label' => 'Таможня + СБКТС',   'value' => '280 000 ₽'],
            'total'    => ['label' => 'Итого на руки',      'value' => '1 180 000 ₽'],
        ],
        'quote'        => 'Китайский авторынок сегодня — это качество на уровне японцев по ценам ниже корейцев. Мы первые наладили прямые поставки из Тяньцзиня в 2021 году.',
    ],
    'usa' => [
        'name_from'    => 'США',
        'delivery'     => '35–50 дней',
        'price_from'   => '1 500 000',
        'popular'      => 'Tesla, Ford, Chevrolet, Lincoln',
        'intro'        => '<p>США — источник уникальных автомобилей, которые сложно найти в России: Tesla, роскошные Lincoln и Cadillac, мощные американские пикапы Ford F-150 и RAM 1500. Мы работаем с крупнейшими американскими аукционами Copart и IAAI, привозя автомобили после страховых случаев с последующим восстановлением — это самый экономичный способ купить американский автомобиль.</p>',
        'advantages'   => [
            ['icon' => '⚡', 'title' => 'Tesla и электро',       'text' => 'Широкий выбор Tesla Model 3, Y, S по ценам значительно ниже официальных'],
            ['icon' => '🚚', 'title' => 'Американские пикапы',   'text' => 'Ford F-150, Chevrolet Silverado, RAM — мощные и практичные рабочие машины'],
            ['icon' => '💎', 'title' => 'Американская роскошь',  'text' => 'Lincoln Navigator, Cadillac Escalade с богатыми комплектациями по выгодным ценам'],
            ['icon' => '🔧', 'title' => 'Восстановление под ключ','text' => 'Полное восстановление после страховых случаев на нашем сервисном центре'],
            ['icon' => '🌊', 'title' => 'Морская доставка',      'text' => 'Линии через Западное побережье или транзит через ОАЭ для оптимальной логистики'],
            ['icon' => '📋', 'title' => 'Чистый Title',          'text' => 'Работаем как с Clean Title, так и с Salvage + восстановление'],
        ],
        'cost_example' => [
            'auction'  => ['label' => 'Цена на Copart/IAAI', 'value' => '1 100 000 ₽'],
            'delivery' => ['label' => 'Доставка до РФ',      'value' => '180 000 ₽'],
            'customs'  => ['label' => 'Таможня + СБКТС',     'value' => '320 000 ₽'],
            'total'    => ['label' => 'Итого на руки',        'value' => '1 600 000 ₽'],
        ],
        'quote'        => 'Автомобиль из США — это уникальность и выгода одновременно. Tesla из Copart обойдётся вам на 40% дешевле новой из салона.',
    ],
    'uae' => [
        'name_from'    => 'ОАЭ',
        'delivery'     => '25–40 дней',
        'price_from'   => '2 000 000',
        'popular'      => 'Land Rover, Lexus, BMW, Porsche',
        'intro'        => '<p>ОАЭ — мировой центр премиального авторынка. Дубай и Абу-Даби переполнены практически новыми люксовыми автомобилями, которые продаются ниже рыночной стоимости. Богатые резиденты ОАЭ меняют автомобили каждые 1–2 года, создавая уникальный рынок для покупателей из России.</p><p>Мы сотрудничаем с дубайскими дилерами с 2020 года и знаем, как найти чистый люксовый автомобиль с минимальным пробегом по выгодной цене.</p>',
        'advantages'   => [
            ['icon' => '💎', 'title' => 'Премиум по цене среднего', 'text' => 'Land Rover, Lexus, Porsche с пробегом 20–40 тыс. км по ценам как у российских аналогов'],
            ['icon' => '☀️', 'title' => 'Климат без соли',          'text' => 'В ОАЭ нет зимы и реагентов — кузов и ходовая в идеальном состоянии'],
            ['icon' => '🔍', 'title' => 'Строгая проверка',         'text' => 'Проверяем каждый автомобиль по базам Carfax, VIN и у официальных дилеров ОАЭ'],
            ['icon' => '📱', 'title' => 'Онлайн-подбор',            'text' => 'Смотрите автомобили вживую через видеозвонок с нашим агентом в Дубае'],
            ['icon' => '🛳️', 'title' => 'Прямая линия',             'text' => 'Морская линия Дубай → Новороссийск — один из самых удобных маршрутов'],
            ['icon' => '⭐', 'title' => 'Гарантия истории',          'text' => 'Предоставляем полную историю сервисного обслуживания и документы ОАЭ'],
        ],
        'cost_example' => [
            'auction'  => ['label' => 'Цена в Дубае',       'value' => '1 500 000 ₽'],
            'delivery' => ['label' => 'Доставка до РФ',     'value' => '180 000 ₽'],
            'customs'  => ['label' => 'Таможня + СБКТС',    'value' => '380 000 ₽'],
            'total'    => ['label' => 'Итого на руки',       'value' => '2 060 000 ₽'],
        ],
        'quote'        => 'Дубай — это рай для ценителей премиум-класса. Мы привозим Lexus и Land Rover с пробегом 30 тыс. км по ценам подержанных Toyota в России.',
    ],
];

$cd = $country_defaults[$country_code] ?? [];
$name_from = $cd['name_from'] ?? $country_name;

// ── 1. Hero ───────────────────────────────────────────────────────────────────
cf_block('hero', [
    'variant' => 'country',
    'country' => $country_code,
]);

// ── 2. Key facts strip ────────────────────────────────────────────────────────
if (!empty($cd)) : ?>
<div class="cf-country-strip">
    <div class="cf-container">
        <div class="cf-country-strip__grid">
            <div class="cf-country-strip__item">
                <span class="cf-country-strip__icon">⚡</span>
                <span class="cf-country-strip__label">Срок доставки</span>
                <strong class="cf-country-strip__value"><?php echo esc_html($cd['delivery']); ?></strong>
            </div>
            <div class="cf-country-strip__item">
                <span class="cf-country-strip__icon">💰</span>
                <span class="cf-country-strip__label">Цены от</span>
                <strong class="cf-country-strip__value"><?php echo esc_html($cd['price_from']); ?> ₽</strong>
            </div>
            <div class="cf-country-strip__item">
                <span class="cf-country-strip__icon"><?php echo esc_html($country_flag); ?></span>
                <span class="cf-country-strip__label">Популярные марки</span>
                <strong class="cf-country-strip__value"><?php echo esc_html($cd['popular']); ?></strong>
            </div>
            <div class="cf-country-strip__item">
                <span class="cf-country-strip__icon">✅</span>
                <span class="cf-country-strip__label">Выполнено заказов</span>
                <strong class="cf-country-strip__value">3100+</strong>
            </div>
        </div>
    </div>
</div>
<?php endif;

// ── 3. Intro text ─────────────────────────────────────────────────────────────
$intro = cf_get_field('cf_country_intro', get_the_ID()) ?: ($cd['intro'] ?? '');
if ($intro) : ?>
    <section class="cf-section">
        <div class="cf-container cf-container--narrow">
            <div class="cf-content"><?php echo wp_kses_post($intro); ?></div>
        </div>
    </section>
<?php endif;

// ── 4. Advantages ─────────────────────────────────────────────────────────────
$advantages = cf_get_field('cf_country_advantages', get_the_ID());
$adv_data   = !empty($advantages) ? $advantages : ($cd['advantages'] ?? []);
if (!empty($adv_data)) : ?>
    <section class="cf-section cf-section--alt">
        <div class="cf-container">
            <div class="cf-section-header cf-section-header--center">
                <h2 class="cf-section-header__title">Почему авто из <?php echo esc_html($name_from); ?>?</h2>
                <p class="cf-section-header__subtitle">6 весомых причин выбрать это направление</p>
            </div>
            <div class="cf-advantages-grid">
                <?php foreach ($adv_data as $adv) :
                    // Support both ACF format and fallback format
                    $icon  = $adv['icon']           ?? $adv['cf_adv_icon']  ?? '✓';
                    $title = $adv['title']           ?? $adv['cf_adv_title'] ?? '';
                    $text  = $adv['text']            ?? $adv['cf_adv_text']  ?? '';
                ?>
                    <div class="cf-advantages-grid__card">
                        <span class="cf-advantages-grid__icon"><?php echo esc_html($icon); ?></span>
                        <div class="cf-advantages-grid__body">
                            <h3 class="cf-advantages-grid__title"><?php echo esc_html($title); ?></h3>
                            <p class="cf-advantages-grid__text"><?php echo esc_html($text); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif;

// ── 5. Popular models ─────────────────────────────────────────────────────────
cf_block('country-models', ['country' => $country_code, 'limit' => 8]);

// ── 6. Cost breakdown example ─────────────────────────────────────────────────
if (!empty($cd['cost_example'])) :
    $ce = $cd['cost_example'];
?>
<section class="cf-section">
    <div class="cf-container">
        <div class="cf-cost-example">
            <div class="cf-cost-example__header">
                <h2 class="cf-cost-example__title">Пример расчёта стоимости</h2>
                <p class="cf-cost-example__subtitle">Из чего складывается цена автомобиля из <?php echo esc_html($name_from); ?></p>
            </div>
            <div class="cf-cost-example__grid">
                <?php $i = 0; foreach ($ce as $key => $row) :
                    $is_total = ($key === 'total');
                    $i++;
                ?>
                    <div class="cf-cost-example__row<?php echo $is_total ? ' cf-cost-example__row--total' : ''; ?>">
                        <?php if (!$is_total) : ?>
                            <span class="cf-cost-example__num"><?php echo $i; ?></span>
                        <?php endif; ?>
                        <span class="cf-cost-example__label"><?php echo esc_html($row['label']); ?></span>
                        <span class="cf-cost-example__value"><?php echo esc_html($row['value']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="cf-cost-example__note">* Расчёт приблизительный и может варьироваться в зависимости от модели, года и курса валют. Точный расчёт — бесплатно по заявке.</p>
            <a href="#cf-modal" class="cf-btn cf-btn--primary" data-modal="lead">Получить точный расчёт</a>
        </div>
    </div>
</section>
<?php endif;

// ── 7. Founder quote ──────────────────────────────────────────────────────────
if (!empty($cd['quote'])) : ?>
<div class="cf-country-founder-quote">
    <div class="cf-container cf-container--narrow">
        <blockquote class="cf-country-founder-quote__block">
            <p><?php echo esc_html($cd['quote']); ?></p>
            <footer>
                <cite>Артем Бараниченко, CEO CarFinance MSK</cite>
            </footer>
        </blockquote>
    </div>
</div>
<?php endif;

// ── 8. Steps ──────────────────────────────────────────────────────────────────
cf_block('steps', ['variant' => 'country']);

// ── 9. Calculator ─────────────────────────────────────────────────────────────
cf_block('calculator', ['variant' => 'turnkey', 'country' => $country_code]);

// ── 10. Cases ─────────────────────────────────────────────────────────────────
cf_block('cases', ['variant' => 'grid', 'limit' => 4, 'country' => $country_code]);

// ── 11. Company trust signals ─────────────────────────────────────────────────
?>
<section class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-trust-strip">
            <div class="cf-trust-strip__item">
                <strong>8 лет</strong>
                <span>на рынке автоимпорта</span>
            </div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item">
                <strong>3100+</strong>
                <span>доставленных авто</span>
            </div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item">
                <strong>95%</strong>
                <span>клиентов рекомендуют</span>
            </div>
            <div class="cf-trust-strip__sep">·</div>
            <div class="cf-trust-strip__item">
                <strong>4 офиса</strong>
                <span>Владивосток, Москва, Краснодар, Сочи</span>
            </div>
        </div>
    </div>
</section>

<?php
// ── 12. Video Reviews ─────────────────────────────────────────────────────────
cf_block('reviews-video', ['variant' => 'country', 'country' => $country_code]);

// ── 13. FAQ ───────────────────────────────────────────────────────────────────
cf_block('faq', ['source' => 'country_' . $country_code]);

// ── 14. Comparison ────────────────────────────────────────────────────────────
cf_block('comparison-table', ['variant' => 'countries']);

// ── 15. CTA ───────────────────────────────────────────────────────────────────
cf_block('cta-final', ['variant' => 'default']);

// ── 16. SEO text ──────────────────────────────────────────────────────────────
$seo_text = cf_get_field('cf_country_seo_text', get_the_ID());
if ($seo_text) : ?>
    <section class="cf-section">
        <div class="cf-container">
            <div class="cf-content cf-content--seo"><?php echo wp_kses_post($seo_text); ?></div>
        </div>
    </section>
<?php endif;

// ── 17. Interlinking ──────────────────────────────────────────────────────────
cf_block('interlinking', ['position' => 'footer']);

get_footer();
