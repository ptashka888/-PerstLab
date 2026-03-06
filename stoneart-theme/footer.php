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
        <div class="sa-footer__grid">
            <div>
                <div class="sa-footer__logo">
                    <i class="fa-solid fa-gem"></i>Stone<span>Art</span>
                </div>
                <p class="sa-footer__desc" itemprop="description">
                    Производство изделий из натурального (мрамор, гранит) и искусственного (кварц, акрил) камня премиум-класса в Москве.
                </p>
                <meta itemprop="name" content="<?php echo esc_attr(sa_company_name()); ?>">
            </div>

            <div>
                <h4 class="sa-footer__heading">Изделия</h4>
                <?php if (has_nav_menu('footer_1')) : ?>
                    <?php wp_nav_menu([
                        'theme_location' => 'footer_1',
                        'container'      => false,
                        'menu_class'     => 'sa-footer__links',
                        'depth'          => 1,
                    ]); ?>
                <?php else : ?>
                    <ul class="sa-footer__links">
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Столешницы</a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Подоконники</a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Ступени и лестницы</a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Камины и облицовка</a></li>
                    </ul>
                <?php endif; ?>
            </div>

            <div>
                <h4 class="sa-footer__heading">Услуги</h4>
                <?php if (has_nav_menu('footer_2')) : ?>
                    <?php wp_nav_menu([
                        'theme_location' => 'footer_2',
                        'container'      => false,
                        'menu_class'     => 'sa-footer__links',
                        'depth'          => 1,
                    ]); ?>
                <?php else : ?>
                    <ul class="sa-footer__links">
                        <li><a href="<?php echo esc_url(home_url('/about/')); ?>">Изготовление на заказ</a></li>
                        <li><a href="<?php echo esc_url(home_url('/calculator/')); ?>">Замер и 3D-проект</a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Монтаж изделий</a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Реставрация и полировка</a></li>
                    </ul>
                <?php endif; ?>
            </div>

            <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                <h4 class="sa-footer__heading">Контакты</h4>
                <div class="sa-footer__contact-item">
                    <a href="<?php echo esc_attr(sa_phone_href()); ?>" class="sa-footer__contact-phone" itemprop="telephone">
                        <i class="fa-solid fa-phone"></i> <?php echo esc_html(sa_phone()); ?>
                    </a>
                </div>
                <div class="sa-footer__contact-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span itemprop="email"><?php echo esc_html(sa_email()); ?></span>
                </div>
                <div class="sa-footer__contact-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <span itemprop="streetAddress"><?php echo esc_html(sa_address()); ?></span>
                </div>
                <meta itemprop="addressLocality" content="Москва">
                <meta itemprop="addressCountry" content="RU">
            </div>
        </div>

        <!-- SEO Footer Links -->
        <div class="sa-footer__seo">
            <?php echo wp_kses_post(sa_option('sa_footer_seo_text', 'Популярные запросы: <a href="' . esc_url(home_url('/materials/')) . '">кварцевый агломерат</a>, <a href="' . esc_url(home_url('/services/')) . '">столешница из искусственного камня цена</a>, <a href="' . esc_url(home_url('/materials/')) . '">avant quartz</a>, <a href="' . esc_url(home_url('/services/')) . '">подоконники из мрамора</a>, <a href="' . esc_url(home_url('/materials/')) . '">гранит absolute black</a>, <a href="' . esc_url(home_url('/materials/')) . '">акриловый камень grandex</a>.')); ?>
        </div>

        <div class="sa-footer__bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(sa_company_name()); ?>. Все права защищены.</p>
            <div>
                <?php
                $privacy = get_page_by_path('privacy');
                if ($privacy) : ?>
                    <a href="<?php echo esc_url(get_permalink($privacy)); ?>">Политика конфиденциальности</a>
                <?php endif; ?>
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
