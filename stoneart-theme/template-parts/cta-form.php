<?php
/**
 * CTA Form Template Part — reusable lead form section
 *
 * @package StoneArt
 */

$title    = $args['title']    ?? 'Рассчитать стоимость изделия';
$subtitle = $args['subtitle'] ?? 'Оставьте контакт — пришлём расчёт в течение 30 минут';
$bg_dark  = $args['dark']     ?? true;
$section_id = 'cta-form-section';
?>

<section class="sa-section <?php echo $bg_dark ? 'sa-section--dark' : 'sa-section--light'; ?>"
         id="<?php echo esc_attr($section_id); ?>">
    <div class="sa-container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;" class="sa-cta-layout">

            <!-- Text -->
            <div>
                <h2 style="font-size:2rem;font-weight:900;line-height:1.2;margin-bottom:1rem;font-family:var(--sa-font-serif);<?php echo $bg_dark ? 'color:#fff;' : ''; ?>">
                    <?php echo esc_html($title); ?>
                </h2>
                <p style="font-size:1.1rem;<?php echo $bg_dark ? 'color:rgba(255,255,255,0.75);' : 'color:var(--sa-gray-600);'; ?>margin-bottom:1.5rem;">
                    <?php echo esc_html($subtitle); ?>
                </p>

                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.75rem;">
                    <?php
                    $bullets = [
                        ['icon' => 'fa-solid fa-ruler-combined', 'text' => 'Бесплатный выезд замерщика'],
                        ['icon' => 'fa-solid fa-file-contract',  'text' => 'Договор с фиксированной ценой'],
                        ['icon' => 'fa-solid fa-shield-halved',  'text' => 'Гарантия 10 лет на изделие'],
                        ['icon' => 'fa-solid fa-truck',          'text' => 'Доставка и монтаж включены'],
                    ];
                    foreach ($bullets as $b) : ?>
                        <li style="display:flex;align-items:center;gap:0.75rem;<?php echo $bg_dark ? 'color:#fff;' : ''; ?>font-size:0.95rem;">
                            <i class="<?php echo esc_attr($b['icon']); ?>"
                               style="color:var(--sa-accent);font-size:1.1rem;flex-shrink:0;"></i>
                            <?php echo esc_html($b['text']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Form -->
            <div>
                <div class="sa-card" style="padding:2rem;background:<?php echo $bg_dark ? 'rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);' : '#fff;border:1px solid var(--sa-gray-200);'; ?>border-radius:var(--sa-radius-lg);">
                    <form class="sa-cta-form" method="post" novalidate>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                            <div>
                                <label class="sa-label" style="<?php echo $bg_dark ? 'color:#fff;' : ''; ?>">Имя</label>
                                <input type="text" name="name" class="sa-input" placeholder="Ваше имя" required>
                            </div>
                            <div>
                                <label class="sa-label" style="<?php echo $bg_dark ? 'color:#fff;' : ''; ?>">Телефон</label>
                                <input type="tel" name="phone" class="sa-input" placeholder="+7 (___) ___-__-__" required>
                            </div>
                        </div>
                        <div style="margin-bottom:1rem;">
                            <label class="sa-label" style="<?php echo $bg_dark ? 'color:#fff;' : ''; ?>">Что вас интересует?</label>
                            <select name="product" class="sa-input" style="cursor:pointer;">
                                <option value="">Выберите изделие</option>
                                <option value="stoleshnitsa">Столешница</option>
                                <option value="lestnitsa">Лестница / ступени</option>
                                <option value="kamin">Камин / портал</option>
                                <option value="pol">Пол / облицовка</option>
                                <option value="rakoviny">Раковина / мойка</option>
                                <option value="vanna">Ванна из камня</option>
                                <option value="fasad">Фасад / экстерьер</option>
                                <option value="other">Другое</option>
                            </select>
                        </div>
                        <div style="margin-bottom:1.5rem;">
                            <label class="sa-label" style="<?php echo $bg_dark ? 'color:#fff;' : ''; ?>">Комментарий (необязательно)</label>
                            <textarea name="message" class="sa-input" rows="3" placeholder="Примерные размеры, материал, особые пожелания..."></textarea>
                        </div>
                        <button type="submit" class="sa-btn sa-btn--primary" style="width:100%;font-size:1rem;padding:0.875rem;">
                            <i class="fa-solid fa-paper-plane"></i> Отправить заявку
                        </button>
                        <p style="font-size:0.75rem;<?php echo $bg_dark ? 'color:rgba(255,255,255,0.5);' : 'color:var(--sa-gray-500);'; ?>text-align:center;margin-top:0.75rem;">
                            Нажимая кнопку, вы соглашаетесь с
                            <?php $priv = get_page_by_path('privacy'); ?>
                            <a href="<?php echo $priv ? esc_url(get_permalink($priv)) : '#'; ?>"
                               style="<?php echo $bg_dark ? 'color:var(--sa-accent);' : ''; ?>">политикой конфиденциальности</a>
                        </p>
                        <?php wp_nonce_field('sa_nonce', 'nonce'); ?>
                        <input type="hidden" name="form_type" value="contact">
                    </form>
                </div>

                <!-- Alt contact -->
                <div style="display:flex;gap:1rem;justify-content:center;margin-top:1.25rem;flex-wrap:wrap;">
                    <a href="<?php echo esc_attr(sa_phone_href()); ?>"
                       style="<?php echo $bg_dark ? 'color:#fff;' : 'color:var(--sa-gray-700);'; ?>font-size:0.9rem;text-decoration:none;">
                        <i class="fa-solid fa-phone"></i> <?php echo esc_html(sa_phone()); ?>
                    </a>
                    <?php if (sa_whatsapp() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_whatsapp()); ?>"
                           style="color:#22c55e;font-size:0.9rem;text-decoration:none;">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                    <?php endif; ?>
                    <?php if (sa_telegram() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_telegram()); ?>"
                           style="color:#3b82f6;font-size:0.9rem;text-decoration:none;">
                            <i class="fa-brands fa-telegram"></i> Telegram
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
