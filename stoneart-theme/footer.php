<?php
/**
 * Footer Template
 *
 * @package StoneArt
 */
?>

<!-- PDF Catalog Section -->
<?php get_template_part('template-parts/pdf-catalog'); ?>

<!-- Footer -->
<footer class="sa-footer" itemscope itemtype="https://schema.org/Organization">
    <div class="sa-container">
        <div class="sa-footer__mega">

            <!-- Col 1: Brand -->
            <div class="sa-footer__brand">
                <div class="sa-footer__logo">
                    <i class="fa-solid fa-gem"></i>Stone<span>Art</span>
                </div>
                <p class="sa-footer__desc" itemprop="description">
                    Производство изделий из натурального (мрамор, гранит, оникс, травертин) и искусственного (кварц, акрил) камня. Москва и вся Россия.
                </p>
                <meta itemprop="name" content="<?php echo esc_attr(sa_company_name()); ?>">
                <div class="sa-footer__socials">
                    <?php if (sa_option('sa_vk') && sa_option('sa_vk') !== '#') : ?>
                        <a href="<?php echo esc_url(sa_option('sa_vk')); ?>" aria-label="ВКонтакте" class="sa-footer__social-link">
                            <i class="fa-brands fa-vk"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (sa_whatsapp() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_whatsapp()); ?>" aria-label="WhatsApp" class="sa-footer__social-link sa-footer__social-link--wa">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (sa_telegram() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_telegram()); ?>" aria-label="Telegram" class="sa-footer__social-link sa-footer__social-link--tg">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (sa_option('sa_youtube') && sa_option('sa_youtube') !== '#') : ?>
                        <a href="<?php echo esc_url(sa_option('sa_youtube')); ?>" aria-label="YouTube" class="sa-footer__social-link sa-footer__social-link--yt">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Col 2: Products -->
            <div>
                <h4 class="sa-footer__heading">Изделия</h4>
                <?php if (has_nav_menu('footer_1')) : ?>
                    <?php wp_nav_menu(['theme_location' => 'footer_1', 'container' => false, 'menu_class' => 'sa-footer__links', 'depth' => 1]); ?>
                <?php else : ?>
                    <ul class="sa-footer__links">
                        <?php
                        $product_pages = [
                            'stoleshnitsy'      => 'Столешницы',
                            'lestnitsy'         => 'Лестницы и ступени',
                            'kaminy'            => 'Камины и порталы',
                            'poly-i-oblitsovka' => 'Полы и облицовка',
                            'rakoviny'          => 'Раковины и мойки',
                            'vanny'             => 'Ванны из камня',
                            'fasady'            => 'Фасады',
                            'pamyatniki'        => 'Памятники',
                        ];
                        foreach ($product_pages as $slug => $lbl) {
                            $pg = get_page_by_path($slug);
                            $url = $pg ? get_permalink($pg) : home_url("/{$slug}/");
                            echo '<li><a href="' . esc_url($url) . '">' . esc_html($lbl) . '</a></li>';
                        }
                        ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Col 3: Materials -->
            <div>
                <h4 class="sa-footer__heading">Материалы</h4>
                <?php if (has_nav_menu('footer_3')) : ?>
                    <?php wp_nav_menu(['theme_location' => 'footer_3', 'container' => false, 'menu_class' => 'sa-footer__links', 'depth' => 1]); ?>
                <?php else : ?>
                    <ul class="sa-footer__links">
                        <?php
                        $material_pages = [
                            'materialy-mramor'   => 'Мрамор',
                            'materialy-granit'   => 'Гранит',
                            'materialy-oniks'    => 'Оникс',
                            'materialy-travertin'=> 'Травертин',
                            'materialy-kvartsit' => 'Кварцит',
                            'materials'          => 'Все материалы →',
                        ];
                        foreach ($material_pages as $slug => $lbl) {
                            $pg = get_page_by_path($slug);
                            $url = $pg ? get_permalink($pg) : home_url("/{$slug}/");
                            echo '<li><a href="' . esc_url($url) . '">' . esc_html($lbl) . '</a></li>';
                        }
                        ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Col 4: Services + Company -->
            <div>
                <h4 class="sa-footer__heading">Услуги</h4>
                <?php if (has_nav_menu('footer_2')) : ?>
                    <?php wp_nav_menu(['theme_location' => 'footer_2', 'container' => false, 'menu_class' => 'sa-footer__links', 'depth' => 1]); ?>
                <?php else : ?>
                    <ul class="sa-footer__links">
                        <?php
                        $service_pages = [
                            'uslugi-zamer'          => 'Выезд замерщика',
                            'uslugi-proektirovanie' => '3D-проектирование',
                            'uslugi-montazh'        => 'Монтаж и установка',
                            'uslugi-restavratsiya'  => 'Реставрация камня',
                            'uslugi-ukhod'          => 'Уход за камнем',
                            'calculator'            => 'Калькулятор стоимости',
                        ];
                        foreach ($service_pages as $slug => $lbl) {
                            $pg = get_page_by_path($slug);
                            $url = $pg ? get_permalink($pg) : home_url("/{$slug}/");
                            echo '<li><a href="' . esc_url($url) . '">' . esc_html($lbl) . '</a></li>';
                        }
                        ?>
                    </ul>
                <?php endif; ?>

                <h4 class="sa-footer__heading" style="margin-top:1.5rem;">О компании</h4>
                <?php if (has_nav_menu('footer_4')) : ?>
                    <?php wp_nav_menu(['theme_location' => 'footer_4', 'container' => false, 'menu_class' => 'sa-footer__links', 'depth' => 1]); ?>
                <?php else : ?>
                    <ul class="sa-footer__links">
                        <?php
                        $company_pages = [
                            'about'                  => 'О производстве',
                            'portfolio-page'         => 'Портфолио работ',
                            'o-kompanii-showroom'    => 'Шоурум',
                            'o-kompanii-otzyvy'      => 'Отзывы клиентов',
                            'garantii'               => 'Гарантии',
                            'oplata'                 => 'Оплата',
                            'blog'                   => 'Блог о камне',
                            'contacts'               => 'Контакты',
                        ];
                        foreach ($company_pages as $slug => $lbl) {
                            $pg = get_page_by_path($slug);
                            $url = $pg ? get_permalink($pg) : home_url("/{$slug}/");
                            echo '<li><a href="' . esc_url($url) . '">' . esc_html($lbl) . '</a></li>';
                        }
                        ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Col 5: Contacts -->
            <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                <h4 class="sa-footer__heading">Контакты</h4>
                <div class="sa-footer__contact-item">
                    <i class="fa-solid fa-phone sa-text-primary"></i>
                    <a href="<?php echo esc_attr(sa_phone_href()); ?>" class="sa-footer__contact-phone" itemprop="telephone">
                        <?php echo esc_html(sa_phone()); ?>
                    </a>
                </div>
                <?php
                $phone2 = sa_option('sa_phone2');
                if ($phone2) : ?>
                    <div class="sa-footer__contact-item">
                        <i class="fa-solid fa-phone sa-text-primary"></i>
                        <a href="tel:<?php echo preg_replace('/[^\d+]/', '', $phone2); ?>"><?php echo esc_html($phone2); ?></a>
                    </div>
                <?php endif; ?>
                <div class="sa-footer__contact-item">
                    <i class="fa-solid fa-envelope sa-text-primary"></i>
                    <a href="mailto:<?php echo esc_attr(sa_email()); ?>" itemprop="email"><?php echo esc_html(sa_email()); ?></a>
                </div>
                <div class="sa-footer__contact-item">
                    <i class="fa-solid fa-location-dot sa-text-primary"></i>
                    <span itemprop="streetAddress"><?php echo esc_html(sa_address()); ?></span>
                </div>
                <div class="sa-footer__contact-item">
                    <i class="fa-regular fa-clock sa-text-primary"></i>
                    <span><?php echo esc_html(sa_hours()); ?></span>
                </div>
                <meta itemprop="addressLocality" content="Москва">
                <meta itemprop="addressCountry" content="RU">

                <div style="margin-top:1.25rem;">
                    <?php if (sa_whatsapp() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_whatsapp()); ?>" class="sa-btn sa-btn--sm" style="background:#22c55e;color:#fff;margin-right:0.5rem;">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                    <?php endif; ?>
                    <?php if (sa_telegram() !== '#') : ?>
                        <a href="<?php echo esc_url(sa_telegram()); ?>" class="sa-btn sa-btn--sm" style="background:#3b82f6;color:#fff;">
                            <i class="fa-brands fa-telegram"></i> Telegram
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SEO Footer Links -->
        <div class="sa-footer__seo">
            <?php
            $default_seo = 'Популярные запросы: ';
            $seo_links = [
                'stoleshnitsy'      => 'столешница из камня на заказ',
                'lestnitsy'         => 'лестницы из натурального камня',
                'kaminy'            => 'каминный портал из мрамора',
                'materialy-mramor'  => 'мрамор цена м2',
                'materialy-granit'  => 'гранит каталог слэбов',
                'uslugi-montazh'    => 'монтаж каменных изделий Москва',
                'portfolio-page'    => 'портфолио работ из камня',
            ];
            $seo_parts = [];
            foreach ($seo_links as $slug => $anchor) {
                $pg = get_page_by_path($slug);
                if ($pg) {
                    $seo_parts[] = '<a href="' . esc_url(get_permalink($pg)) . '">' . esc_html($anchor) . '</a>';
                }
            }
            echo wp_kses_post(sa_option('sa_footer_seo_text', $default_seo . implode(', ', $seo_parts) . '.'));
            ?>
        </div>

        <div class="sa-footer__bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(sa_company_name()); ?>. Все права защищены.</p>
            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                <?php
                $legal_pages = ['privacy' => 'Политика конфиденциальности', 'oplata' => 'Оплата', 'garantii' => 'Гарантии'];
                foreach ($legal_pages as $slug => $lbl) {
                    $pg = get_page_by_path($slug);
                    if ($pg) echo '<a href="' . esc_url(get_permalink($pg)) . '">' . esc_html($lbl) . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</footer>

<!-- Smart Banner -->
<div id="smart-banner" class="sa-smart-banner">
    <div class="sa-smart-banner__content">
        <div class="sa-smart-banner__icon">
            <i class="fa-solid fa-percent"></i>
        </div>
        <div>
            <p class="sa-smart-banner__title">Скидка до 30 000 ₽ на заказ</p>
            <p class="sa-smart-banner__subtitle">Пройдите тест и зафиксируйте выгоду за своим номером.</p>
        </div>
    </div>
    <div class="sa-smart-banner__actions">
        <a href="<?php
            $calc = get_page_by_path('calculator');
            echo $calc ? esc_url(get_permalink($calc)) : '#quiz-section';
        ?>" class="sa-btn sa-btn--primary" style="white-space:nowrap;font-size:0.875rem;">Пройти тест</a>
        <button id="close-banner" class="sa-smart-banner__close" aria-label="Закрыть"><i class="fa-solid fa-xmark"></i></button>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
