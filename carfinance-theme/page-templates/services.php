<?php
/**
 * Template Name: Услуги
 * Template Post Type: page
 *
 * Services overview with cards linking to SILO pillars.
 * Schema.org/Service.
 *
 * @package CarFinance
 */

get_header();
?>

<section class="cf-section" style="padding-top:32px;">
  <div class="cf-container">
    <div class="cf-section__header">
      <h1>Услуги CarFinance MSK</h1>
      <p>Полный спектр услуг по импорту и подбору автомобилей</p>
    </div>

    <div class="cf-grid cf-grid--3">
      <?php
      $services_data = [
          [
              'title' => 'Автоподбор',
              'desc'  => 'Профессиональный подбор б/у автомобиля на вторичном рынке. Проверка по 48 пунктам, торг, оформление.',
              'url'   => '/avtopodborshchik/',
              'price' => 'от 25 000 &#8381;',
              'icon'  => '&#128269;',
          ],
          [
              'title' => 'Авто из Кореи',
              'desc'  => 'Покупка авто на Encar, доставка паромом, полная растаможка и постановка на учёт.',
              'url'   => '/korea/',
              'price' => 'от 5% стоимости',
              'icon'  => '&#x1F1F0;&#x1F1F7;',
          ],
          [
              'title' => 'Авто из Японии',
              'desc'  => 'Покупка на аукционах USS, AA Japan, JU. Расшифровка аукционного листа, доставка.',
              'url'   => '/japan/',
              'price' => 'от 5% стоимости',
              'icon'  => '&#x1F1EF;&#x1F1F5;',
          ],
          [
              'title' => 'Авто из Китая',
              'desc'  => 'Новые авто Geely, Changan, Chery, BYD напрямую с завода. Сертификация и ЭПТС.',
              'url'   => '/china/',
              'price' => 'от 5% стоимости',
              'icon'  => '&#x1F1E8;&#x1F1F3;',
          ],
          [
              'title' => 'Авто из США',
              'desc'  => 'Copart, IAAI — аукционы США. Проверка Carfax, доставка контейнером.',
              'url'   => '/usa/',
              'price' => 'от 5% стоимости',
              'icon'  => '&#x1F1FA;&#x1F1F8;',
          ],
          [
              'title' => 'Авто из ОАЭ',
              'desc'  => 'Параллельный импорт: LC300, Patrol, LX600 и другие модели из Эмиратов.',
              'url'   => '/uae/',
              'price' => 'от 5% стоимости',
              'icon'  => '&#x1F1E6;&#x1F1EA;',
          ],
          [
              'title' => 'Проверка авто',
              'desc'  => 'Выездная диагностика б/у автомобиля: толщиномер, OBD2, эндоскоп, тест-драйв.',
              'url'   => '/kupit-avto-s-probegom/diagnostika/',
              'price' => 'от 5 000 &#8381;',
              'icon'  => '&#128270;',
          ],
          [
              'title' => 'Растаможка',
              'desc'  => 'Полное таможенное оформление: пошлина, утильсбор, СБКТС, ЭПТС.',
              'url'   => '/calculator/',
              'price' => 'от 30 000 &#8381;',
              'icon'  => '&#128196;',
          ],
          [
              'title' => 'Доставка по России',
              'desc'  => 'Автовозом или ЖД контейнером из Владивостока до вашего города.',
              'url'   => '/services/',
              'price' => 'от 50 000 &#8381;',
              'icon'  => '&#128666;',
          ],
      ];

      foreach ($services_data as $service) :
      ?>
        <a href="<?php echo esc_url(home_url($service['url'])); ?>" class="cf-card" style="text-decoration:none;">
          <div class="cf-card__body">
            <div style="font-size:2.5rem;margin-bottom:12px;"><?php echo $service['icon']; ?></div>
            <h3 class="cf-card__title"><?php echo esc_html($service['title']); ?></h3>
            <p class="cf-card__text"><?php echo esc_html($service['desc']); ?></p>
            <div class="cf-card__price cf-mt-2"><?php echo $service['price']; ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Pricing packages -->
<section class="cf-section cf-section--gray">
  <div class="cf-container">
    <div class="cf-section__header">
      <h2>Пакеты услуг</h2>
    </div>
    <div class="cf-pricing">
      <div class="cf-pricing__card">
        <div class="cf-pricing__name">Базовый</div>
        <div class="cf-pricing__price">от 25 000 &#8381;</div>
        <ul class="cf-pricing__features">
          <li><span class="check">&#10003;</span> Подбор в вашем городе</li>
          <li><span class="check">&#10003;</span> Проверка по базам</li>
          <li><span class="check">&#10003;</span> Осмотр с толщиномером</li>
          <li><span class="check">&#10003;</span> Торг с продавцом</li>
        </ul>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--outline cf-btn--block" data-modal="lead">Заказать</a>
      </div>
      <div class="cf-pricing__card cf-pricing__card--featured">
        <div class="cf-pricing__badge">Популярный</div>
        <div class="cf-pricing__name">Стандарт</div>
        <div class="cf-pricing__price">от 45 000 &#8381;</div>
        <ul class="cf-pricing__features">
          <li><span class="check">&#10003;</span> Всё из «Базового»</li>
          <li><span class="check">&#10003;</span> OBD2 + эндоскопия</li>
          <li><span class="check">&#10003;</span> Проверка на подъёмнике</li>
          <li><span class="check">&#10003;</span> Тест-драйв</li>
          <li><span class="check">&#10003;</span> Оформление</li>
        </ul>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--primary cf-btn--block" data-modal="lead">Заказать</a>
      </div>
      <div class="cf-pricing__card">
        <div class="cf-pricing__name">Под ключ</div>
        <div class="cf-pricing__price">от 5%</div>
        <ul class="cf-pricing__features">
          <li><span class="check">&#10003;</span> Всё из «Стандарта»</li>
          <li><span class="check">&#10003;</span> Покупка на аукционе</li>
          <li><span class="check">&#10003;</span> Доставка + растаможка</li>
          <li><span class="check">&#10003;</span> СБКТС + ЭПТС</li>
          <li><span class="check">&#10003;</span> Постановка на учёт</li>
        </ul>
        <a href="#cf-lead-modal" class="cf-btn cf-btn--outline cf-btn--block" data-modal="lead">Заказать</a>
      </div>
    </div>
  </div>
</section>

<section class="cf-cta">
  <div class="cf-container">
    <h2>Нужна помощь с выбором?</h2>
    <p>Расскажите о вашей задаче — подберём оптимальный пакет услуг</p>
    <a href="#cf-lead-modal" class="cf-btn cf-btn--secondary cf-btn--lg" data-modal="lead">Получить консультацию</a>
  </div>
</section>

<?php get_footer(); ?>
