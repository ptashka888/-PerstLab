<?php
/**
 * Theme Footer
 *
 * SILO footer navigation (Level 1 links only between cocoons),
 * contacts, social links, copyright.
 *
 * @package CarFinance
 */

defined('ABSPATH') || exit;
?>

</main><!-- #cf-main -->

<!-- ===== FOOTER ===== -->
<footer class="cf-footer" role="contentinfo">
  <div class="cf-container">

    <div class="cf-footer__grid">

      <!-- Brand column -->
      <div class="cf-footer__brand">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="cf-logo" style="margin-bottom:8px;">
          Car<span>Finance</span>
        </a>
        <p>Импорт и подбор автомобилей из Кореи, Японии, Китая, США и ОАЭ. Полный цикл: поиск, проверка, покупка, доставка, растаможка, постановка на учёт.</p>
        <div style="margin-top:16px;display:flex;gap:12px;">
          <a href="https://t.me/carfinance_msk" target="_blank" rel="noopener" aria-label="Telegram">Telegram</a>
          <a href="https://wa.me/7XXXXXXXXXX" target="_blank" rel="noopener" aria-label="WhatsApp">WhatsApp</a>
          <a href="https://www.instagram.com/carfinance_msk/" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a>
          <a href="https://www.youtube.com/@carfinance_msk" target="_blank" rel="noopener" aria-label="YouTube">YouTube</a>
        </div>
      </div>

      <!-- Directions (Level 1 SILO links — inter-cocoon) -->
      <div>
        <h4>Направления</h4>
        <ul class="cf-footer__links">
          <li><a href="<?php echo esc_url(home_url('/korea/')); ?>">Авто из Кореи</a></li>
          <li><a href="<?php echo esc_url(home_url('/japan/')); ?>">Авто из Японии</a></li>
          <li><a href="<?php echo esc_url(home_url('/china/')); ?>">Авто из Китая</a></li>
          <li><a href="<?php echo esc_url(home_url('/usa/')); ?>">Авто из США</a></li>
          <li><a href="<?php echo esc_url(home_url('/uae/')); ?>">Авто из ОАЭ</a></li>
          <li><a href="<?php echo esc_url(home_url('/catalog/')); ?>">Каталог</a></li>
        </ul>
      </div>

      <!-- Services -->
      <div>
        <h4>Услуги</h4>
        <ul class="cf-footer__links">
          <li><a href="<?php echo esc_url(home_url('/avtopodborshchik/')); ?>">Автоподбор</a></li>
          <li><a href="<?php echo esc_url(home_url('/kupit-avto-s-probegom/')); ?>">Авто с пробегом</a></li>
          <li><a href="<?php echo esc_url(home_url('/calculator/')); ?>">Калькулятор растаможки</a></li>
          <li><a href="<?php echo esc_url(home_url('/proverka-avto-gibdd/')); ?>">Проверка авто</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Все услуги</a></li>
        </ul>
      </div>

      <!-- Info -->
      <div>
        <h4>Информация</h4>
        <ul class="cf-footer__links">
          <li><a href="<?php echo esc_url(home_url('/o-kompanii/')); ?>">О компании</a></li>
          <li><a href="<?php echo esc_url(home_url('/kejsy/')); ?>">Кейсы</a></li>
          <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">Блог</a></li>
          <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a></li>
          <li><a href="<?php echo esc_url(home_url('/kontakty/')); ?>">Контакты</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom bar -->
    <div class="cf-footer__bottom">
      <span>&copy; <?php echo date('Y'); ?> CarFinance MSK. Все права защищены.</span>
      <span>
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Политика конфиденциальности</a>
        &nbsp;|&nbsp;
        <a href="<?php echo esc_url(home_url('/oferta/')); ?>">Договор оферты</a>
      </span>
    </div>

  </div>
</footer>

<!-- Lead Modal (lazy-loaded) -->
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
        <option value="korea">Авто из Кореи</option>
        <option value="japan">Авто из Японии</option>
        <option value="china">Авто из Китая</option>
        <option value="usa">Авто из США</option>
        <option value="uae">Авто из ОАЭ</option>
        <option value="selection">Автоподбор б/у</option>
        <option value="other">Другое</option>
      </select>
      <textarea name="message" class="cf-input" placeholder="Комментарий (необязательно)" rows="3"></textarea>
      <button type="submit" class="cf-btn cf-btn--primary cf-btn--block">Отправить заявку</button>
      <p style="font-size:0.75rem;color:var(--cf-gray-500);margin-top:8px;">Нажимая кнопку, вы соглашаетесь с <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">политикой конфиденциальности</a></p>
    </form>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
