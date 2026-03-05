<?php
defined('ABSPATH') || exit;

$photo      = '';
$name       = 'Иван Лещенко';
$role       = 'Основатель CarFinance MSK';
$bio        = '';
$experience = '';
$quote      = '';
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
    $bio = 'Более 10 лет в автомобильном бизнесе. Помог сотням клиентов найти и привезти автомобиль мечты из-за рубежа по лучшей цене. Прозрачность, честность и индивидуальный подход — главные принципы работы.';
}

if (empty($quote)) {
    $quote = 'Мы не просто продаём автомобили — мы помогаем людям осуществить мечту. Каждый клиент для нас — это история, которой мы гордимся.';
}

if (empty($experience)) {
    $experience = '10+';
}
?>
<section class="cf-founder">
    <div class="cf-founder__container">
        <div class="cf-founder__grid">
            <div class="cf-founder__photo-col">
                <?php if ($photo) : ?>
                    <img class="cf-founder__photo" src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" width="400" height="500">
                <?php else : ?>
                    <div class="cf-founder__photo-placeholder"></div>
                <?php endif; ?>
                <div class="cf-founder__experience">
                    <span class="cf-founder__experience-number"><?php echo esc_html($experience); ?></span>
                    <span class="cf-founder__experience-label">лет опыта</span>
                </div>
            </div>

            <div class="cf-founder__content-col">
                <h2 class="cf-founder__name"><?php echo esc_html($name); ?></h2>
                <p class="cf-founder__role"><?php echo esc_html($role); ?></p>

                <div class="cf-founder__bio">
                    <p><?php echo esc_html($bio); ?></p>
                </div>

                <?php if ($quote) : ?>
                    <blockquote class="cf-founder__quote">
                        <p><?php echo esc_html($quote); ?></p>
                    </blockquote>
                <?php endif; ?>

                <?php if (!empty($socials) && is_array($socials)) : ?>
                    <div class="cf-founder__socials">
                        <?php if (!empty($socials['telegram'])) : ?>
                            <a class="cf-founder__social-link" href="<?php echo esc_url($socials['telegram']); ?>" target="_blank" rel="noopener" aria-label="Telegram">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161l-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($socials['whatsapp'])) : ?>
                            <a class="cf-founder__social-link" href="<?php echo esc_url($socials['whatsapp']); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.121.553 4.114 1.519 5.845L.053 23.681l5.972-1.435A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm6.29 16.908c-.262.738-1.539 1.37-2.122 1.418-.583.048-1.125.27-3.792-.789-3.222-1.278-5.265-4.574-5.424-4.787-.158-.213-1.293-1.722-1.293-3.284 0-1.562.818-2.33 1.108-2.65.29-.32.634-.4.846-.4.211 0 .422.002.607.011.195.009.456-.074.713.544.262.63.891 2.175.97 2.333.079.158.132.343.026.556-.105.213-.158.343-.316.528-.158.185-.334.412-.476.553-.158.158-.323.33-.139.646.185.316.821 1.354 1.763 2.193 1.21 1.078 2.23 1.413 2.546 1.571.316.158.502.132.686-.079.185-.211.79-.924.999-1.238.211-.316.422-.264.713-.158.29.105 1.845.871 2.161 1.029.316.158.527.237.606.369.079.132.079.764-.184 1.503z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($socials['instagram'])) : ?>
                            <a class="cf-founder__social-link" href="<?php echo esc_url($socials['instagram']); ?>" target="_blank" rel="noopener" aria-label="Instagram">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
