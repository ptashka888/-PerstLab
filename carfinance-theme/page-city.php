<?php
/**
 * Template Name: City Landing
 * For city subdomains (e.g. krasnodar.carfinance-msk.ru)
 * LocalBusiness schema
 */

defined('ABSPATH') || exit;

get_header();

$city_data = function_exists('cf_get_city_data') ? cf_get_city_data() : [];
$city_name = $city_data['name'] ?? 'вашем городе';
$city_prep = $city_data['name_prepositional'] ?? $city_name;
?>

<?php cf_block('hero', [
    'variant'  => 'city',
    'title'    => 'Импорт автомобилей в ' . $city_prep,
    'subtitle' => 'Подберём, доставим и растаможим автомобиль из-за рубежа с доставкой в ' . $city_prep,
]); ?>

<?php cf_block('features', ['variant' => 'counters']); ?>

<?php if (!empty($city_data['address'])): ?>
    <section class="cf-section">
        <div class="cf-container">
            <div class="cf-section__header">
                <h2 class="cf-section__title">Наш офис в <?php echo esc_html($city_prep); ?></h2>
            </div>
            <?php cf_block('contacts-map'); ?>
        </div>
    </section>
<?php endif; ?>

<?php
// City stats
$stats = $city_data['stats'] ?? [];
if ($stats): ?>
    <section class="cf-section cf-section--alt">
        <div class="cf-container">
            <div class="cf-grid cf-grid--4">
                <?php foreach ($stats as $stat): ?>
                    <div class="cf-features__item">
                        <span class="cf-features__value"><?php echo esc_html($stat['cf_stat_value'] ?? ''); ?></span>
                        <span class="cf-features__label"><?php echo esc_html($stat['cf_stat_label'] ?? ''); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php
cf_block('country-cards', ['show_comparison' => false]);
cf_block('service-packages');
cf_block('calculator', ['variant' => 'turnkey']);
cf_block('cases', ['variant' => 'grid', 'limit' => 4]);
cf_block('reviews-video', ['limit' => 4]);
cf_block('steps', ['variant' => 'home']);
cf_block('faq', ['source' => 'city']);

$seo_text = $city_data['seo_text'] ?? '';
if (!$seo_text && function_exists('get_field')) {
    $seo_text = get_field('cf_city_seo_text');
}
if ($seo_text): ?>
    <section class="cf-section">
        <div class="cf-container">
            <div class="cf-content cf-content--seo"><?php echo wp_kses_post($seo_text); ?></div>
        </div>
    </section>
<?php endif;

cf_block('cta-final', ['variant' => 'city', 'city_name' => $city_name]);
get_footer();
