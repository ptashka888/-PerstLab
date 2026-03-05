<?php
/**
 * Template Name: Country Landing
 * Template for: /avto-iz-korei/, /avto-iz-yaponii/, /avto-iz-kitaya/, /avto-iz-usa/, /avto-iz-oae/
 */

defined('ABSPATH') || exit;

get_header();

// Determine country from page slug
$slug = get_post_field('post_name', get_the_ID());
$country_map = [
    'avto-iz-korei'   => 'korea',
    'avto-iz-yaponii' => 'japan',
    'avto-iz-kitaya'  => 'china',
    'avto-iz-usa'     => 'usa',
    'avto-iz-oae'     => 'uae',
];
$country_code = $country_map[$slug] ?? '';
$country_data = $country_code ? cf_get_country_data($country_code) : [];

// 1. Hero
cf_block('hero', [
    'variant' => 'country',
    'country' => $country_code,
]);

// 2. Features / Counters
cf_block('features', ['variant' => 'counters']);

// 3. Country intro text
$intro = cf_get_field('cf_country_intro', get_the_ID());
if ($intro): ?>
    <section class="cf-section">
        <div class="cf-container">
            <div class="cf-content"><?php echo wp_kses_post($intro); ?></div>
        </div>
    </section>
<?php endif;

// 4. Advantages
$advantages = cf_get_field('cf_country_advantages', get_the_ID());
if ($advantages): ?>
    <section class="cf-section cf-section--alt">
        <div class="cf-container">
            <div class="cf-section__header">
                <h2 class="cf-section__title">Почему <?php echo esc_html($country_data['name'] ?? 'эта страна'); ?>?</h2>
            </div>
            <div class="cf-grid cf-grid--3">
                <?php foreach ($advantages as $adv): ?>
                    <div class="cf-card">
                        <div class="cf-card__body">
                            <span class="cf-card__icon"><?php echo esc_html($adv['cf_adv_icon'] ?? '✓'); ?></span>
                            <h3 class="cf-card__title"><?php echo esc_html($adv['cf_adv_title'] ?? ''); ?></h3>
                            <p class="cf-card__text"><?php echo esc_html($adv['cf_adv_text'] ?? ''); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif;

// 5. Popular models from this country
cf_block('country-models', ['country' => $country_code, 'limit' => 8]);

// 6. Steps
cf_block('steps', ['variant' => 'country']);

// 7. Calculator (preset country)
cf_block('calculator', ['variant' => 'turnkey', 'country' => $country_code]);

// 8. Cases from this country
cf_block('cases', ['variant' => 'grid', 'limit' => 4, 'country' => $country_code]);

// 9. Comparison table (countries)
cf_block('comparison-table', ['variant' => 'countries']);

// 10. Video Reviews
cf_block('reviews-video', ['variant' => 'country', 'country' => $country_code]);

// 11. FAQ
cf_block('faq', ['source' => 'country_' . $country_code]);

// 12. CTA
cf_block('cta-final', ['variant' => 'default']);

// SEO text
$seo_text = cf_get_field('cf_country_seo_text', get_the_ID());
if ($seo_text): ?>
    <section class="cf-section">
        <div class="cf-container">
            <div class="cf-content cf-content--seo"><?php echo wp_kses_post($seo_text); ?></div>
        </div>
    </section>
<?php endif;

// Interlinking
cf_block('interlinking', ['position' => 'footer']);

get_footer();
