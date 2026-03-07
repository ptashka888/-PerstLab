<?php
$phone    = get_theme_mod('kv_phone',    '+7 (800) 555-00-00');
$phone2   = get_theme_mod('kv_phone2',   '');
$email    = get_theme_mod('kv_email',    'info@kovka.ru');
$address  = get_theme_mod('kv_address',  'ул. Кузнечная, 1');
$city     = get_theme_mod('kv_city',     'Москва');
$worktime = get_theme_mod('kv_worktime', 'Пн–Пт: 9:00–19:00, Сб: 10:00–16:00');
$vk       = get_theme_mod('kv_vk',      '');
$tg       = get_theme_mod('kv_tg',      '');
$yt       = get_theme_mod('kv_youtube', '');
$wa       = get_theme_mod('kv_whatsapp','');
?>

<!-- Модальное окно заявки -->
<div class="kv-modal" id="kv-modal" role="dialog" aria-modal="true" aria-labelledby="kv-modal-title">
    <div class="kv-modal__backdrop kv-modal-close"></div>
    <div class="kv-modal__box">
        <button class="kv-modal__close kv-modal-close" aria-label="Закрыть">✕</button>
        <h3 id="kv-modal-title">Рассчитать стоимость</h3>
        <p>Оставьте заявку — перезвоним за 15 минут и подберём вариант под бюджет</p>
        <?php echo kv_lead_form('modal', 'Получить расчёт бесплатно'); ?>
    </div>
</div>

<!-- WhatsApp плавающая кнопка -->
<?php if ($wa) : ?>
<a href="https://wa.me/<?= preg_replace('/\D/', '', $wa) ?>?text=Здравствуйте!%20Хочу%20узнать%20стоимость."
   class="kv-wa-btn" target="_blank" rel="noopener" aria-label="WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor" width="28" height="28"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>
<style>
.kv-wa-btn {
    position: fixed;
    bottom: 88px;
    right: 24px;
    z-index: 900;
    width: 56px;
    height: 56px;
    background: #25D366;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(37,211,102,.45);
    transition: transform .25s ease, box-shadow .25s ease;
}
.kv-wa-btn:hover { transform: scale(1.08); box-shadow: 0 8px 28px rgba(37,211,102,.55); color: #fff; }
</style>
<?php endif; ?>

<!-- Кнопка "Наверх" -->
<button class="kv-totop" id="kv-totop" aria-label="Наверх">↑</button>
<style>
.kv-totop {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 900;
    width: 48px;
    height: 48px;
    background: var(--kv-text);
    color: #fff;
    border-radius: 50%;
    font-size: 1.1rem;
    opacity: 0;
    transition: all .25s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}
.kv-totop.visible { opacity: 1; }
.kv-totop:hover { background: var(--kv-accent); transform: translateY(-3px); }
</style>

<!-- FOOTER -->
<footer class="kv-footer" role="contentinfo">
    <div class="kv-container">
        <div class="kv-footer-grid">

            <!-- О компании -->
            <div>
                <a href="<?php echo home_url('/'); ?>" class="kv-logo" style="margin-bottom:16px">
                    <div class="kv-logo__icon">⚒️</div>
                    <div>
                        <span class="kv-logo__text" style="color:#fff"><?php bloginfo('name'); ?></span>
                        <span class="kv-logo__sub">Художественная ковка</span>
                    </div>
                </a>
                <p class="kv-footer-desc">
                    Производство кованых изделий по индивидуальным проектам с 2005 года.
                    Ворота, заборы, лестницы, мебель и художественная ковка.
                    Гарантия 25 лет. Монтаж по всей России.
                </p>
                <div class="kv-footer-social">
                    <?php if ($vk) : ?><a href="<?= esc_url($vk) ?>" target="_blank" rel="noopener" aria-label="ВКонтакте">ВК</a><?php endif; ?>
                    <?php if ($tg) : ?><a href="<?= esc_url($tg) ?>" target="_blank" rel="noopener" aria-label="Telegram">TG</a><?php endif; ?>
                    <?php if ($yt) : ?><a href="<?= esc_url($yt) ?>" target="_blank" rel="noopener" aria-label="YouTube">YT</a><?php endif; ?>
                    <?php if ($wa) : ?><a href="https://wa.me/<?= preg_replace('/\D/', '', $wa) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">WA</a><?php endif; ?>
                </div>
            </div>

            <!-- Каталог -->
            <div>
                <h4>Каталог</h4>
                <ul>
                    <?php foreach (kv_get_category_data() as $slug => $cat) : ?>
                    <li><a href="<?= esc_url(home_url('/' . $slug . '/')) ?>"><?= esc_html($cat['name']) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?php echo home_url('/portfolio/'); ?>">Портфолио работ</a></li>
                </ul>
            </div>

            <!-- Компания -->
            <div>
                <h4>Компания</h4>
                <ul>
                    <li><a href="<?php echo home_url('/about/'); ?>">О нас</a></li>
                    <li><a href="<?php echo home_url('/calculator/'); ?>">Калькулятор цены</a></li>
                    <li><a href="<?php echo home_url('/blog/'); ?>">Блог</a></li>
                    <li><a href="<?php echo home_url('/contacts/'); ?>">Контакты</a></li>
                    <li><a href="<?php echo home_url('/privacy/'); ?>">Политика конфиденциальности</a></li>
                    <li><a href="<?php echo home_url('/dostavka/'); ?>">Доставка и монтаж</a></li>
                    <li><a href="<?php echo home_url('/garantiya/'); ?>">Гарантия</a></li>
                </ul>
            </div>

            <!-- Контакты -->
            <div>
                <h4>Контакты</h4>
                <div class="kv-footer-contacts">
                    <strong>📞 Телефон</strong>
                    <a href="tel:<?= preg_replace('/\D/', '', $phone) ?>"><?= esc_html($phone) ?></a>
                    <?php if ($phone2) : ?><br><a href="tel:<?= preg_replace('/\D/', '', $phone2) ?>"><?= esc_html($phone2) ?></a><?php endif; ?>

                    <strong style="margin-top:14px">📧 Email</strong>
                    <a href="mailto:<?= esc_html($email) ?>"><?= esc_html($email) ?></a>

                    <strong style="margin-top:14px">📍 Адрес</strong>
                    <span><?= esc_html($address) ?>, <?= esc_html($city) ?></span>

                    <strong style="margin-top:14px">🕐 Режим работы</strong>
                    <span><?= esc_html($worktime) ?></span>

                    <div style="margin-top:20px">
                        <a href="#kv-modal" class="kv-btn kv-btn--primary kv-btn--sm kv-modal-open" style="width:100%;justify-content:center">
                            Заказать расчёт
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer bottom -->
    <div style="border-top:1px solid rgba(255,255,255,.08)">
        <div class="kv-container">
            <div class="kv-footer-bottom">
                <span>© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Все права защищены.</span>
                <span>Художественная ковка по индивидуальным проектам</span>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
