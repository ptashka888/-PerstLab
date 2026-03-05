<?php
/**
 * Template: Single Car Model
 * URL: /catalog/{brand}/{model-slug}/
 */

defined('ABSPATH') || exit;

get_header();

$post_id   = get_the_ID();
$brands    = get_the_terms($post_id, 'car_brand');
$brand     = $brands ? $brands[0] : null;
$countries = get_the_terms($post_id, 'car_country');
$country   = $countries ? $countries[0] : null;

$price_from   = cf_get_field('cf_price_from', $post_id);
$price_to     = cf_get_field('cf_price_to', $post_id);
$year         = cf_get_field('cf_year', $post_id);
$year_to      = cf_get_field('cf_year_to', $post_id);
$engine_cc    = cf_get_field('cf_engine_cc', $post_id);
$power_hp     = cf_get_field('cf_power_hp', $post_id);
$fuel_type    = cf_get_field('cf_fuel_type', $post_id);
$transmission = cf_get_field('cf_transmission', $post_id);
$drive        = cf_get_field('cf_drive', $post_id);
$gallery      = cf_get_field('cf_gallery', $post_id);
$description  = cf_get_field('cf_full_description', $post_id);
$pros_cons    = cf_get_field('cf_pros_cons', $post_id);

$fuel_labels = ['petrol' => 'Бензин', 'diesel' => 'Дизель', 'hybrid' => 'Гибрид', 'electric' => 'Электро'];
$trans_labels = ['automatic' => 'Автомат', 'manual' => 'Механика', 'robot' => 'Робот', 'variator' => 'Вариатор'];
$drive_labels = ['fwd' => 'Передний', 'rwd' => 'Задний', 'awd' => 'Полный'];
?>

<article class="cf-model">
    <div class="cf-container">
        <!-- Header -->
        <div class="cf-model__header">
            <div class="cf-model__meta">
                <?php if ($brand): ?>
                    <a href="<?php echo esc_url(get_term_link($brand)); ?>" class="cf-model__brand"><?php echo esc_html($brand->name); ?></a>
                <?php endif; ?>
                <?php if ($country): ?>
                    <?php $cd = cf_get_country_data($country->slug); ?>
                    <span class="cf-model__country"><?php echo esc_html($cd['flag'] ?? ''); ?> <?php echo esc_html($cd['name'] ?? $country->name); ?></span>
                <?php endif; ?>
            </div>
            <h1 class="cf-model__title"><?php the_title(); ?></h1>
            <?php if ($year): ?>
                <span class="cf-model__year"><?php echo esc_html($year); ?><?php echo $year_to ? '–' . esc_html($year_to) : ''; ?> г.</span>
            <?php endif; ?>
        </div>

        <div class="cf-model__layout">
            <!-- Gallery -->
            <div class="cf-model__gallery">
                <?php if ($gallery): ?>
                    <div class="cf-model__main-image">
                        <img src="<?php echo esc_url($gallery[0]['sizes']['large'] ?? $gallery[0]['url']); ?>"
                             alt="<?php echo esc_attr(get_the_title()); ?>"
                             width="800" height="600" loading="eager">
                    </div>
                    <?php if (count($gallery) > 1): ?>
                        <div class="cf-model__thumbs">
                            <?php foreach ($gallery as $i => $img): ?>
                                <button class="cf-model__thumb<?php echo $i === 0 ? ' cf-model__thumb--active' : ''; ?>"
                                        data-src="<?php echo esc_url($img['sizes']['large'] ?? $img['url']); ?>">
                                    <img src="<?php echo esc_url($img['sizes']['thumbnail'] ?? $img['url']); ?>"
                                         alt="" width="120" height="90" loading="lazy">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php elseif (has_post_thumbnail()): ?>
                    <div class="cf-model__main-image">
                        <?php the_post_thumbnail('large', ['loading' => 'eager']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Specs & Price -->
            <div class="cf-model__info">
                <!-- Price -->
                <div class="cf-model__price-block">
                    <span class="cf-model__price-label">Стоимость под ключ</span>
                    <span class="cf-model__price">
                        <?php if ($price_from): ?>
                            от <?php echo esc_html(cf_format_price($price_from)); ?>
                            <?php if ($price_to): ?>
                                до <?php echo esc_html(cf_format_price($price_to)); ?>
                            <?php endif; ?>
                        <?php else: ?>
                            По запросу
                        <?php endif; ?>
                    </span>
                    <a href="#cf-modal" class="cf-btn cf-btn--primary cf-btn--lg cf-btn--full" data-modal="lead">
                        Узнать точную цену
                    </a>
                    <a href="<?php echo esc_url(home_url('/calculator/')); ?>" class="cf-btn cf-btn--secondary cf-btn--full">
                        Рассчитать в калькуляторе
                    </a>
                </div>

                <!-- Specs Table -->
                <div class="cf-model__specs">
                    <h3>Характеристики</h3>
                    <table class="cf-table cf-table--specs">
                        <?php if ($engine_cc): ?>
                            <tr><td>Двигатель</td><td><?php echo esc_html(number_format($engine_cc / 1000, 1, '.', '')); ?> л (<?php echo esc_html($engine_cc); ?> куб.см)</td></tr>
                        <?php endif; ?>
                        <?php if ($power_hp): ?>
                            <tr><td>Мощность</td><td><?php echo esc_html($power_hp); ?> л.с.</td></tr>
                        <?php endif; ?>
                        <?php if ($fuel_type): ?>
                            <tr><td>Топливо</td><td><?php echo esc_html($fuel_labels[$fuel_type] ?? $fuel_type); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($transmission): ?>
                            <tr><td>КПП</td><td><?php echo esc_html($trans_labels[$transmission] ?? $transmission); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($drive): ?>
                            <tr><td>Привод</td><td><?php echo esc_html($drive_labels[$drive] ?? $drive); ?></td></tr>
                        <?php endif; ?>
                        <?php
                        $seats = cf_get_field('cf_body_seats', $post_id);
                        if ($seats): ?>
                            <tr><td>Мест</td><td><?php echo esc_html($seats); ?></td></tr>
                        <?php endif; ?>
                        <?php
                        $consumption = cf_get_field('cf_fuel_consumption', $post_id);
                        if ($consumption): ?>
                            <tr><td>Расход</td><td><?php echo esc_html($consumption); ?> л/100 км</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- Description -->
        <?php if ($description): ?>
            <div class="cf-model__description cf-content">
                <h2>Описание <?php the_title(); ?></h2>
                <?php echo wp_kses_post($description); ?>
            </div>
        <?php endif; ?>

        <!-- Pros & Cons -->
        <?php if ($pros_cons): ?>
            <div class="cf-model__pros-cons">
                <?php
                $pros = $pros_cons['cf_pros'] ?? '';
                $cons = $pros_cons['cf_cons'] ?? '';
                ?>
                <?php if ($pros): ?>
                    <div class="cf-model__pros">
                        <h3>Преимущества</h3>
                        <ul>
                            <?php foreach (explode("\n", $pros) as $line):
                                $line = trim($line);
                                if ($line): ?>
                                    <li><?php echo esc_html($line); ?></li>
                                <?php endif;
                            endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if ($cons): ?>
                    <div class="cf-model__cons">
                        <h3>Недостатки</h3>
                        <ul>
                            <?php foreach (explode("\n", $cons) as $line):
                                $line = trim($line);
                                if ($line): ?>
                                    <li><?php echo esc_html($line); ?></li>
                                <?php endif;
                            endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</article>

<?php
// Related auction lots
cf_block('cars-slider', ['variant' => 'related', 'post_id' => $post_id, 'limit' => 4]);

// Calculator preset
cf_block('calculator', [
    'variant' => 'turnkey',
    'country' => $country ? $country->slug : '',
]);

// Cases for this model
cf_block('cases', ['variant' => 'grid', 'limit' => 3]);

// FAQ
cf_block('faq', ['source' => 'car_model']);

// Related models (same brand — SILO rule)
cf_block('related-models', ['post_id' => $post_id, 'limit' => 4]);

// Interlinking
cf_block('interlinking', ['position' => 'footer']);

// CTA
cf_block('cta-final', ['variant' => 'default']);

get_footer();
