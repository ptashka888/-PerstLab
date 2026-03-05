<?php
defined('ABSPATH') || exit;

$address  = '';
$phone    = '';
$email    = '';
$hours    = '';
$lat      = '55.7558';
$lng      = '37.6173';

if (function_exists('cf_get_field')) {
    $address = cf_get_field('cf_address', 'option') ?: '';
    $phone   = cf_get_field('cf_phone', 'option') ?: '';
    $email   = cf_get_field('cf_email', 'option') ?: '';
    $hours   = cf_get_field('cf_working_hours', 'option') ?: '';
    $lat     = cf_get_field('cf_map_lat', 'option') ?: $lat;
    $lng     = cf_get_field('cf_map_lng', 'option') ?: $lng;
}

if (empty($address)) {
    $address = 'г. Москва, ул. Примерная, д. 1';
}
if (empty($phone)) {
    $phone = '+7 (495) 123-45-67';
}
if (empty($email)) {
    $email = 'info@carfinance-msk.ru';
}
if (empty($hours)) {
    $hours = 'Пн–Пт: 9:00–20:00, Сб: 10:00–18:00';
}

$phone_clean = preg_replace('/[^+\d]/', '', $phone);
?>
<section class="cf-contacts">
    <div class="cf-contacts__container">
        <h2 class="cf-contacts__title">Контакты</h2>

        <div class="cf-contacts__grid">
            <div class="cf-contacts__info">
                <ul class="cf-contacts__list">
                    <li class="cf-contacts__item">
                        <span class="cf-contacts__icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <div class="cf-contacts__text">
                            <span class="cf-contacts__label">Адрес</span>
                            <span class="cf-contacts__value"><?php echo esc_html($address); ?></span>
                        </div>
                    </li>

                    <li class="cf-contacts__item">
                        <span class="cf-contacts__icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        </span>
                        <div class="cf-contacts__text">
                            <span class="cf-contacts__label">Телефон</span>
                            <a class="cf-contacts__value cf-contacts__value--phone" href="tel:<?php echo esc_attr($phone_clean); ?>">
                                <?php echo esc_html($phone); ?>
                            </a>
                        </div>
                    </li>

                    <li class="cf-contacts__item">
                        <span class="cf-contacts__icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <div class="cf-contacts__text">
                            <span class="cf-contacts__label">Email</span>
                            <a class="cf-contacts__value" href="mailto:<?php echo esc_attr($email); ?>">
                                <?php echo esc_html($email); ?>
                            </a>
                        </div>
                    </li>

                    <li class="cf-contacts__item">
                        <span class="cf-contacts__icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </span>
                        <div class="cf-contacts__text">
                            <span class="cf-contacts__label">Режим работы</span>
                            <span class="cf-contacts__value"><?php echo esc_html($hours); ?></span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="cf-contacts__map-col">
                <div
                    id="cf-map"
                    class="cf-contacts__map"
                    data-lat="<?php echo esc_attr($lat); ?>"
                    data-lng="<?php echo esc_attr($lng); ?>"
                >
                    <div class="cf-contacts__map-placeholder">
                        Загрузка карты&hellip;
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
