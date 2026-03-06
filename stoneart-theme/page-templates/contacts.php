<?php
/**
 * Template Name: Контакты
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<section class="sa-section sa-section--white" itemscope itemtype="https://schema.org/LocalBusiness">
    <meta itemprop="name" content="<?php echo esc_attr(sa_company_name()); ?>">
    <div class="sa-container" style="max-width:64rem;">
        <div class="sa-contact-grid">
            <!-- Contact Info -->
            <div>
                <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:2rem;" class="sa-font-serif">Свяжитесь с нами</h2>

                <div class="sa-contact-info__item" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                    <div class="sa-contact-info__icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <div class="sa-contact-info__label">Адрес шоурума</div>
                        <div class="sa-contact-info__value" itemprop="streetAddress"><?php echo esc_html(sa_address()); ?></div>
                        <meta itemprop="addressLocality" content="Москва">
                        <meta itemprop="addressCountry" content="RU">
                    </div>
                </div>

                <div class="sa-contact-info__item">
                    <div class="sa-contact-info__icon"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <div class="sa-contact-info__label">Телефон</div>
                        <div class="sa-contact-info__value">
                            <a href="<?php echo esc_attr(sa_phone_href()); ?>" style="font-weight:700;font-size:1.25rem;color:var(--sa-gray-900);" itemprop="telephone"><?php echo esc_html(sa_phone()); ?></a>
                        </div>
                    </div>
                </div>

                <div class="sa-contact-info__item">
                    <div class="sa-contact-info__icon"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <div class="sa-contact-info__label">E-mail</div>
                        <div class="sa-contact-info__value">
                            <a href="mailto:<?php echo esc_attr(sa_email()); ?>" itemprop="email"><?php echo esc_html(sa_email()); ?></a>
                        </div>
                    </div>
                </div>

                <div class="sa-contact-info__item">
                    <div class="sa-contact-info__icon"><i class="fa-regular fa-clock"></i></div>
                    <div>
                        <div class="sa-contact-info__label">Режим работы</div>
                        <div class="sa-contact-info__value"><?php echo esc_html(sa_hours()); ?></div>
                        <meta itemprop="openingHours" content="Mo-Su 09:00-20:00">
                    </div>
                </div>

                <div style="display:flex;gap:1rem;margin-top:2rem;">
                    <?php if (sa_whatsapp() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_whatsapp()); ?>" class="sa-btn sa-btn--dark" style="background:#25D366;color:#fff;">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                    <?php endif; ?>
                    <?php if (sa_telegram() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_telegram()); ?>" class="sa-btn sa-btn--dark" style="background:#0088cc;color:#fff;">
                            <i class="fa-brands fa-telegram"></i> Telegram
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:2rem;" class="sa-font-serif">Написать нам</h2>
                <form id="contact-form" class="sa-contact-form">
                    <div class="sa-form-group">
                        <label for="contact-name">Ваше имя</label>
                        <input type="text" id="contact-name" name="name" placeholder="Иван Иванов" required>
                    </div>
                    <div class="sa-form-group">
                        <label for="contact-phone">Телефон</label>
                        <input type="tel" id="contact-phone" name="phone" placeholder="+7 (999) 000-00-00" required>
                    </div>
                    <div class="sa-form-group">
                        <label for="contact-email">E-mail</label>
                        <input type="email" id="contact-email" name="email" placeholder="ivan@example.com">
                    </div>
                    <div class="sa-form-group">
                        <label for="contact-message">Сообщение</label>
                        <textarea id="contact-message" name="message" rows="4" placeholder="Опишите ваш проект..."></textarea>
                    </div>
                    <button type="submit" class="sa-btn sa-btn--primary" style="width:100%;">Отправить сообщение</button>
                    <p style="font-size:0.75rem;color:var(--sa-gray-400);margin-top:1rem;text-align:center;">
                        <i class="fa-solid fa-lock"></i> Нажимая кнопку, вы соглашаетесь с
                        <?php $privacy = get_page_by_path('privacy'); ?>
                        <a href="<?php echo $privacy ? esc_url(get_permalink($privacy)) : '#'; ?>" style="color:var(--sa-primary-hover);">политикой конфиденциальности</a>.
                    </p>
                </form>
            </div>
        </div>

        <!-- Map placeholder -->
        <?php
        $map_embed = sa_option('sa_map_embed', '');
        if ($map_embed) : ?>
            <div style="margin-top:3rem;border-radius:var(--sa-radius-xl);overflow:hidden;height:400px;">
                <?php echo $map_embed; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
