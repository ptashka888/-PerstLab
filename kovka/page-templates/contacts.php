<?php
/**
 * Template Name: Контакты
 * Template Post Type: page
 */
get_header();
$phone    = get_theme_mod('kv_phone',    '+7 (800) 555-00-00');
$phone2   = get_theme_mod('kv_phone2',   '');
$email    = get_theme_mod('kv_email',    'info@kovka.ru');
$address  = get_theme_mod('kv_address',  'ул. Кузнечная, 1');
$city     = get_theme_mod('kv_city',     'Москва');
$worktime = get_theme_mod('kv_worktime', 'Пн–Пт: 9:00–19:00, Сб: 10:00–16:00');
$map_url  = get_theme_mod('kv_map_url',  '');
$wa       = get_theme_mod('kv_whatsapp', '');
$tg       = get_theme_mod('kv_tg',       '');
$vk       = get_theme_mod('kv_vk',       '');
?>

<section class="kv-page-hero">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
        <h1 style="margin-top:16px">Контакты</h1>
        <p class="kv-lead">Шоу-рум в Москве. Монтаж по всей России. Ответим за 15 минут.</p>
    </div>
</section>

<section class="kv-section">
    <div class="kv-container">
        <div class="kv-grid-2" style="gap:48px;align-items:start">

            <!-- Форма обратной связи -->
            <div>
                <h2 style="margin-bottom:8px">Написать нам</h2>
                <p class="kv-lead" style="margin-bottom:28px">Опишите задачу — ответим с расчётом и предложением</p>
                <form class="kv-lead-form" data-source="contacts">
                    <?php wp_nonce_field('kv_nonce', 'kv_lead_nonce'); ?>
                    <div class="kv-form-group">
                        <label>Ваше имя</label>
                        <input type="text" name="name" class="kv-input" placeholder="Иван">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div class="kv-form-group">
                            <label>Телефон *</label>
                            <input type="tel" name="phone" class="kv-input" placeholder="+7 (___) ___-__-__" required>
                        </div>
                        <div class="kv-form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="kv-input" placeholder="mail@mail.ru">
                        </div>
                    </div>
                    <div class="kv-form-group">
                        <label>Тип изделия</label>
                        <select class="kv-select" name="category">
                            <option value="">Не выбрано</option>
                            <?php foreach (kv_get_category_data() as $slug => $cat) : ?>
                            <option value="<?= esc_attr($slug) ?>"><?= esc_html($cat['name']) ?></option>
                            <?php endforeach; ?>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                    <div class="kv-form-group">
                        <label>Сообщение / описание заказа</label>
                        <textarea name="message" class="kv-textarea" rows="5" placeholder="Опишите изделие: размеры (ширина × высота), стиль, покрытие, сроки, адрес монтажа…"></textarea>
                    </div>
                    <button type="submit" class="kv-btn kv-btn--primary kv-btn--lg" style="width:100%">
                        Отправить заявку
                    </button>
                    <p style="font-size:.72rem;color:var(--kv-text-muted);margin-top:10px;text-align:center">
                        Нажимая кнопку, вы соглашаетесь с <a href="/privacy">политикой конфиденциальности</a>
                    </p>
                    <div class="kv-form-result" style="display:none;margin-top:12px;padding:16px;border-radius:8px;text-align:center"></div>
                </form>
            </div>

            <!-- Контактная информация -->
            <div style="display:flex;flex-direction:column;gap:20px">

                <div class="kv-contact-card">
                    <h3 style="margin-bottom:20px">Наши контакты</h3>
                    <div class="kv-contact-item">
                        <div class="kv-contact-item__icon">📞</div>
                        <div>
                            <div class="kv-contact-item__label">Телефон</div>
                            <div class="kv-contact-item__val">
                                <a href="tel:<?= preg_replace('/\D/', '', $phone) ?>" style="color:var(--kv-text)"><?= esc_html($phone) ?></a>
                                <?php if ($phone2) : ?><br><a href="tel:<?= preg_replace('/\D/', '', $phone2) ?>" style="color:var(--kv-text)"><?= esc_html($phone2) ?></a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="kv-contact-item">
                        <div class="kv-contact-item__icon">📧</div>
                        <div>
                            <div class="kv-contact-item__label">Email</div>
                            <div class="kv-contact-item__val"><a href="mailto:<?= esc_html($email) ?>" style="color:var(--kv-text)"><?= esc_html($email) ?></a></div>
                        </div>
                    </div>
                    <div class="kv-contact-item">
                        <div class="kv-contact-item__icon">📍</div>
                        <div>
                            <div class="kv-contact-item__label">Адрес шоу-рума</div>
                            <div class="kv-contact-item__val"><?= esc_html($address) ?>, <?= esc_html($city) ?></div>
                        </div>
                    </div>
                    <div class="kv-contact-item">
                        <div class="kv-contact-item__icon">🕐</div>
                        <div>
                            <div class="kv-contact-item__label">Режим работы</div>
                            <div class="kv-contact-item__val"><?= esc_html($worktime) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Мессенджеры -->
                <div class="kv-contact-card">
                    <h4 style="margin-bottom:16px">Написать в мессенджер</h4>
                    <div style="display:flex;gap:12px;flex-wrap:wrap">
                        <?php if ($wa) : ?>
                        <a href="https://wa.me/<?= preg_replace('/\D/', '', $wa) ?>?text=Здравствуйте!%20Хочу%20узнать%20стоимость."
                           class="kv-btn kv-btn--secondary" target="_blank" rel="noopener" style="background:#25D366;color:#fff;border-color:#25D366">
                            WhatsApp
                        </a>
                        <?php endif; ?>
                        <?php if ($tg) : ?>
                        <a href="<?= esc_url($tg) ?>" class="kv-btn kv-btn--secondary" target="_blank" rel="noopener" style="background:#229ED9;color:#fff;border-color:#229ED9">
                            Telegram
                        </a>
                        <?php endif; ?>
                        <?php if ($vk) : ?>
                        <a href="<?= esc_url($vk) ?>" class="kv-btn kv-btn--secondary" target="_blank" rel="noopener" style="background:#4A76A8;color:#fff;border-color:#4A76A8">
                            ВКонтакте
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Реквизиты -->
                <div class="kv-contact-card">
                    <h4 style="margin-bottom:14px">Реквизиты компании</h4>
                    <div style="font-size:.875rem;color:var(--kv-text-muted);line-height:1.8">
                        <div>ООО «<?php bloginfo('name'); ?>»</div>
                        <div>ИНН: 7700000000 · КПП: 770001001</div>
                        <div>ОГРН: 1057700000000</div>
                        <div>р/с: 40702810000000000000</div>
                        <div>Банк: ПАО Сбербанк</div>
                        <div>БИК: 044525225</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Карта -->
        <?php if ($map_url) : ?>
        <div class="kv-map kv-mt-48">
            <iframe src="<?= esc_url($map_url) ?>" allowfullscreen loading="lazy" title="Наш адрес на карте"></iframe>
        </div>
        <?php else : ?>
        <div class="kv-map kv-mt-48" style="height:420px;background:var(--kv-bg);display:flex;align-items:center;justify-content:center;border-radius:20px;border:1px solid var(--kv-border)">
            <div style="text-align:center;color:var(--kv-text-muted)">
                <div style="font-size:3rem;margin-bottom:12px">📍</div>
                <div>Настройте карту в Внешний вид → Настройки темы → Ссылка на карту (iframe src)</div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
