<?php
/**
 * Template Name: Calculator
 * Three modes: turnkey, customs, ownership
 */

defined('ABSPATH') || exit;

get_header();

$default_mode    = cf_get_field('cf_calc_default_mode', get_the_ID()) ?: 'turnkey';
$default_country = cf_get_field('cf_calc_default_country', get_the_ID()) ?: 'korea';
$intro           = cf_get_field('cf_calc_intro_text', get_the_ID());
$seo_text        = cf_get_field('cf_calc_seo_text', get_the_ID());
?>

<?php cf_block('hero', ['variant' => 'calculator', 'title' => 'Калькулятор стоимости автомобиля']); ?>

<?php if ($intro): ?>
    <section class="cf-section">
        <div class="cf-container">
            <div class="cf-content cf-content--intro"><?php echo wp_kses_post($intro); ?></div>
        </div>
    </section>
<?php endif; ?>

<?php cf_block('calculator', [
    'variant' => 'full',
    'country' => $default_country,
]); ?>

<section class="cf-section cf-section--alt">
    <div class="cf-container">
        <div class="cf-section__header">
            <h2 class="cf-section__title">Что входит в стоимость</h2>
        </div>
        <div class="cf-grid cf-grid--3">
            <div class="cf-card">
                <div class="cf-card__body">
                    <h3 class="cf-card__title">Таможенная пошлина</h3>
                    <p class="cf-card__text">Рассчитывается по объёму двигателя и возрасту автомобиля согласно ТК ЕАЭС</p>
                </div>
            </div>
            <div class="cf-card">
                <div class="cf-card__body">
                    <h3 class="cf-card__title">Утилизационный сбор</h3>
                    <p class="cf-card__text">Обязательный платёж, зависит от объёма двигателя и статуса покупателя</p>
                </div>
            </div>
            <div class="cf-card">
                <div class="cf-card__body">
                    <h3 class="cf-card__title">Доставка</h3>
                    <p class="cf-card__text">Морская доставка из страны отправления до порта назначения в России</p>
                </div>
            </div>
            <div class="cf-card">
                <div class="cf-card__body">
                    <h3 class="cf-card__title">СБКТС</h3>
                    <p class="cf-card__text">Свидетельство о безопасности конструкции транспортного средства</p>
                </div>
            </div>
            <div class="cf-card">
                <div class="cf-card__body">
                    <h3 class="cf-card__title">ЭПТС</h3>
                    <p class="cf-card__text">Электронный паспорт транспортного средства</p>
                </div>
            </div>
            <div class="cf-card">
                <div class="cf-card__body">
                    <h3 class="cf-card__title">Брокер</h3>
                    <p class="cf-card__text">Таможенное оформление и сопровождение документов</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
cf_block('faq', ['source' => 'calculator']);

if ($seo_text): ?>
    <section class="cf-section">
        <div class="cf-container">
            <div class="cf-content cf-content--seo"><?php echo wp_kses_post($seo_text); ?></div>
        </div>
    </section>
<?php endif;

cf_block('cta-final', ['variant' => 'default']);
get_footer();
