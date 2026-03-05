<?php
/**
 * Theme Footer
 *
 * SILO footer: all Level 1 links visible to GoogleBot,
 * contacts, social links, lead modal, copyright.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;

$countries = cf_get_country_data();
$phone = function_exists('get_field') ? get_field('site_phone_moscow', 'option') : '+7 (XXX) XXX-XX-XX';
$telegram = function_exists('get_field') ? get_field('site_telegram', 'option') : 'carfinance_msk';
$whatsapp = function_exists('get_field') ? get_field('site_whatsapp', 'option') : '';
$footer_cta = function_exists('get_field') ? get_field('footer_cta_text', 'option') : '';
?>

</main><!-- #cf-main -->

<footer class="cf-footer" role="contentinfo">
  <div class="cf-container">

    <div class="cf-footer__grid">

      <!-- Brand column -->
      <div class="cf-footer__brand">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="cf-logo">
          Car<span>Finance</span>
        </a>
        <p>Импорт и подбор автомобилей из Кореи, Японии, Китая, США и ОАЭ. Полный цикл: поиск, проверка, покупка, доставка, растаможка, постановка на учёт.</p>
        <div class="cf-footer__social">
          <?php if ($telegram) : ?>
            <a href="<?php echo esc_url('https://t.me/' . $telegram); ?>" target="_blank" rel="noopener" aria-label="Telegram">Telegram</a>
          <?php endif; ?>
          <?php if ($whatsapp) : ?>
            <a href="<?php echo esc_url('https://wa.me/' . preg_replace('/\D/', '', $whatsapp)); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">WhatsApp</a>
          <?php endif; ?>
          <a href="https://www.instagram.com/carfinance_msk/" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a>
          <a href="https://www.youtube.com/@carfinance_msk" target="_blank" rel="noopener" aria-label="YouTube">YouTube</a>
        </div>
      </div>

      <!-- Directions (Level 1 SILO links) -->
      <div>
        <h4>Направления</h4>
        <ul class="cf-footer__links">
          <?php foreach ($countries as $code => $c) : ?>
            <li><a href="<?php echo esc_url(home_url($c['url'])); ?>"><?php echo esc_html('Авто ' . $c['name_from']); ?></a></li>
          <?php endforeach; ?>
          <li><a href="<?php echo esc_url(home_url('/catalog/')); ?>">Каталог</a></li>
        </ul>
      </div>

      <!-- Services -->
      <div>
        <h4>Услуги</h4>
        <ul class="cf-footer__links">
          <li><a href="<?php echo esc_url(home_url('/services/avtopodborshchik/')); ?>">Автоподбор</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/import-pod-klyuch/')); ?>">Импорт под ключ</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/kredit-lizing/')); ?>">Кредит / Лизинг</a></li>
          <li><a href="<?php echo esc_url(home_url('/calculator/')); ?>">Калькулятор растаможки</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Все услуги</a></li>
        </ul>
      </div>

      <!-- Info -->
      <div>
        <h4>Информация</h4>
        <ul class="cf-footer__links">
          <li><a href="<?php echo esc_url(home_url('/o-kompanii/')); ?>">О компании</a></li>
          <li><a href="<?php echo esc_url(home_url('/cases/')); ?>">Кейсы</a></li>
          <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">Блог</a></li>
          <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="cf-footer__bottom">
      <span>&copy; <?php echo esc_html(date('Y')); ?> CarFinance MSK. Все права защищены.</span>
      <span>
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Политика конфиденциальности</a>
        &nbsp;|&nbsp;
        <a href="<?php echo esc_url(home_url('/oferta/')); ?>">Договор оферты</a>
      </span>
    </div>

  </div>
</footer>

<!-- Lead Modal -->
<div class="cf-modal" id="cf-lead-modal" role="dialog" aria-modal="true" aria-label="Оставить заявку" style="display:none;">
  <div class="cf-modal__overlay" data-modal-close></div>
  <div class="cf-modal__content">
    <button class="cf-modal__close" data-modal-close aria-label="Закрыть">&times;</button>
    <h3>Оставить заявку</h3>
    <p>Укажите ваши данные и мы свяжемся с вами в течение 15 минут</p>
    <form class="cf-lead-form" action="#" method="post" data-lead-form>
      <?php wp_nonce_field('cf_lead_form', 'cf_lead_nonce'); ?>
      <input type="text" name="name" class="cf-input" placeholder="Ваше имя" required>
      <input type="tel" name="phone" class="cf-input" placeholder="+7 (___) ___-__-__" required>
      <select name="interest" class="cf-input">
        <option value="">Что вас интересует?</option>
        <?php foreach ($countries as $code => $c) : ?>
          <option value="<?php echo esc_attr($code); ?>"><?php echo esc_html('Авто ' . $c['name_from']); ?></option>
        <?php endforeach; ?>
        <option value="selection">Автоподбор б/у</option>
        <option value="other">Другое</option>
      </select>
      <textarea name="message" class="cf-input" placeholder="Комментарий (необязательно)" rows="3"></textarea>
      <button type="submit" class="cf-btn cf-btn--primary cf-btn--block">Отправить заявку</button>
      <p class="cf-lead-form__disclaimer">Нажимая кнопку, вы соглашаетесь с <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">политикой конфиденциальности</a></p>
    </form>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
