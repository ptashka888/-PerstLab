<?php
/**
 * Block: Founder / Spokesperson
 * CEO quote + bio with E-E-A-T signals.
 */
defined('ABSPATH') || exit;

$photo      = '';
$name       = 'Артем Бараниченко';
$role       = 'Генеральный директор CarFinance MSK';
$bio        = '';
$experience = '';
$quote      = '';
$quote2     = '';
$achievements = [];
$socials    = [];

if (function_exists('cf_get_field')) {
    $photo      = cf_get_field('cf_founder_photo', 'option') ?: $photo;
    $name       = cf_get_field('cf_founder_name', 'option') ?: $name;
    $role       = cf_get_field('cf_founder_role', 'option') ?: $role;
    $bio        = cf_get_field('cf_founder_bio', 'option') ?: '';
    $experience = cf_get_field('cf_founder_experience', 'option') ?: '';
    $quote      = cf_get_field('cf_founder_quote', 'option') ?: '';
    $socials    = cf_get_field('cf_founder_socials', 'option') ?: [];
}

if (empty($bio)) {
    $bio = '8 лет назад я сам столкнулся с проблемой — хотел купить достойный автомобиль, но дилеры предлагали лишь переплату и кота в мешке. Так родилась идея CarFinance MSK: компания, которая работает исключительно в интересах клиента. Сегодня мы — команда из 28 специалистов с офисами во Владивостоке, Москве, Краснодаре и Сочи. Более 3100 семей уже получили свой автомобиль через нас.';
}

if (empty($quote)) {
    $quote = 'Мой принцип — ни одного клиента, которого я бы постыдился. Если автомобиль не подходит — честно скажу. Если есть риски — предупрежу. 95% наших клиентов рекомендуют нас друзьям, и это лучший показатель нашей работы.';
}

if (empty($experience)) {
    $experience = '8';
}

if (empty($achievements)) {
    $achievements = [
        ['icon' => '🏆', 'text' => 'ТОП-10 импортёров 2024'],
        ['icon' => '🤝', 'text' => '3100+ довольных клиентов'],
        ['icon' => '📍', 'text' => '4 офиса в России'],
        ['icon' => '⭐', 'text' => '95% рекомендуют нас'],
    ];
}
?>
<section class="cf-founder">
    <div class="cf-founder__container">
        <div class="cf-section-header cf-section-header--center">
            <p class="cf-section-header__overtitle">Слово основателя</p>
            <h2 class="cf-section-header__title">Мы строим компанию на доверии</h2>
        </div>

        <div class="cf-founder__grid">
            <div class="cf-founder__photo-col">
                <?php if ($photo) : ?>
                    <img class="cf-founder__photo" src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" width="400" height="500">
                <?php else : ?>
                    <div class="cf-founder__photo-placeholder">
                        <span class="cf-founder__initials">АБ</span>
                    </div>
                <?php endif; ?>

                <div class="cf-founder__experience">
                    <span class="cf-founder__experience-number"><?php echo esc_html($experience); ?>+</span>
                    <span class="cf-founder__experience-label">лет в&nbsp;авто&shy;импорте</span>
                </div>

                <div class="cf-founder__achievements">
                    <?php foreach ($achievements as $ach) : ?>
                        <div class="cf-founder__achievement">
                            <span class="cf-founder__achievement-icon"><?php echo esc_html($ach['icon']); ?></span>
                            <span class="cf-founder__achievement-text"><?php echo esc_html($ach['text']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="cf-founder__content-col">
                <div class="cf-founder__meta">
                    <h2 class="cf-founder__name"><?php echo esc_html($name); ?></h2>
                    <p class="cf-founder__role"><?php echo esc_html($role); ?></p>
                </div>

                <div class="cf-founder__bio">
                    <p><?php echo wp_kses_post($bio); ?></p>
                </div>

                <blockquote class="cf-founder__quote">
                    <svg class="cf-founder__quote-icon" width="40" height="30" viewBox="0 0 40 30" fill="none" aria-hidden="true">
                        <path d="M0 30V18C0 7.2 5.6 1.6 16.8 0l1.4 2.4C12.4 3.6 9 6.4 8 11.2H16V30H0zm24 0V18c0-10.8 5.6-16.4 16.8-17.6L42 2.8c-5.8 1.2-9.2 4-10.2 8.8H40V30H24z" fill="currentColor"/>
                    </svg>
                    <p><?php echo esc_html($quote); ?></p>
                    <footer class="cf-founder__quote-footer">
                        <cite><?php echo esc_html($name); ?>, <?php echo esc_html($role); ?></cite>
                    </footer>
                </blockquote>

                <div class="cf-founder__cta-row">
                    <?php if (!empty($socials['telegram'])) : ?>
                        <a class="cf-btn cf-btn--outline cf-founder__social-btn" href="<?php echo esc_url($socials['telegram']); ?>" target="_blank" rel="noopener">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161l-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
                            Telegram
                        </a>
                    <?php else : ?>
                        <a class="cf-btn cf-btn--outline cf-founder__social-btn" href="https://t.me/carfinancemsk" target="_blank" rel="noopener">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161l-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
                            Telegram
                        </a>
                    <?php endif; ?>

                    <a class="cf-btn cf-btn--primary cf-founder__cta" href="#cf-modal" data-modal="lead">
                        Задать вопрос директору
                    </a>
                </div>

                <a href="<?php echo esc_url(home_url('/o-nas/artem-baranichenko/')); ?>" class="cf-founder__author-link">
                    Подробнее об Артеме →
                </a>
            </div>
        </div>
    </div>
</section>
