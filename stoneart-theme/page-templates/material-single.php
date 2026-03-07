<?php
/**
 * Template Name: Страница материала
 * Template Post Type: page
 *
 * Individual material page: /materialy/mramor/, /materialy/granit/ etc.
 *
 * @package StoneArt
 */
get_header();

// ACF fields
$mat_type    = function_exists('get_field') ? get_field('sa_mat_type')         : '';
$country     = function_exists('get_field') ? get_field('sa_mat_country')       : '';
$density     = function_exists('get_field') ? get_field('sa_mat_density')       : '';
$water_abs   = function_exists('get_field') ? get_field('sa_mat_water_abs')     : '';
$hardness    = function_exists('get_field') ? get_field('sa_mat_hardness')      : '';
$frost       = function_exists('get_field') ? get_field('sa_mat_frost')         : '';
$price_m2    = function_exists('get_field') ? get_field('sa_mat_price_m2')      : '';
$gallery     = function_exists('get_field') ? get_field('sa_mat_gallery')       : [];
$applications= function_exists('get_field') ? get_field('sa_mat_applications')  : [];
$care_text   = function_exists('get_field') ? get_field('sa_mat_care')          : '';

// Map material type to display name
$mat_names = [
    'mramor'     => 'Мрамор',
    'granit'     => 'Гранит',
    'oniks'      => 'Оникс',
    'travertin'  => 'Травертин',
    'kvartsit'   => 'Кварцит',
    'peshanik'   => 'Песчаник',
    'izvestnyak' => 'Известняк',
];
$mat_display = $mat_names[$mat_type] ?? get_the_title();

// Application links
$app_links = [
    'stoleshnitsy' => ['label' => 'Столешницы', 'slug' => 'stoleshnitsy'],
    'lestnitsy'    => ['label' => 'Лестницы',   'slug' => 'lestnitsy'],
    'kaminy'       => ['label' => 'Камины',      'slug' => 'kaminy'],
    'poly'         => ['label' => 'Полы',        'slug' => 'poly-i-oblitsovka'],
    'fasady'       => ['label' => 'Фасады',      'slug' => 'fasady'],
    'rakoviny'     => ['label' => 'Раковины',    'slug' => 'rakoviny'],
    'vanny'        => ['label' => 'Ванны',       'slug' => 'vanny'],
];
?>

<div class="sa-page-header">
    <div class="sa-container">
        <?php sa_breadcrumbs(); ?>
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <?php if ($country) : ?>
            <p class="sa-page-header__subtitle">
                <i class="fa-solid fa-earth-europe sa-text-primary mr-2"></i>
                Происхождение: <?php echo esc_html($country); ?>
                <?php if ($price_m2) echo ' &nbsp;·&nbsp; от ' . esc_html($price_m2) . ' ₽/м²'; ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<main class="sa-main">

    <!-- Gallery + Description -->
    <section class="sa-section sa-section--white">
        <div class="sa-container">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:start;" class="sa-material-layout">

                <!-- Gallery -->
                <div>
                    <?php if ($gallery) : ?>
                        <div class="sa-material-gallery">
                            <?php $first = reset($gallery); ?>
                            <div class="sa-material-gallery__main" id="mat-gallery-main">
                                <img src="<?php echo esc_url($first['url']); ?>"
                                     alt="<?php echo esc_attr($first['alt'] ?: get_the_title()); ?>"
                                     width="<?php echo (int)$first['width']; ?>"
                                     height="<?php echo (int)$first['height']; ?>"
                                     loading="eager" style="width:100%;border-radius:var(--sa-radius);object-fit:cover;aspect-ratio:4/3;">
                            </div>
                            <?php if (count($gallery) > 1) : ?>
                                <div class="sa-material-gallery__thumbs" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:0.5rem;margin-top:0.75rem;">
                                    <?php foreach ($gallery as $img) : ?>
                                        <img src="<?php echo esc_url($img['sizes']['sa-card']); ?>"
                                             alt="<?php echo esc_attr($img['alt']); ?>"
                                             width="90" height="70"
                                             loading="lazy"
                                             class="sa-material-gallery__thumb"
                                             data-full="<?php echo esc_url($img['url']); ?>"
                                             style="cursor:pointer;border-radius:4px;object-fit:cover;border:2px solid transparent;">
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('sa-hero', ['style' => 'width:100%;border-radius:var(--sa-radius);', 'loading' => 'eager']); ?>
                    <?php endif; ?>
                </div>

                <!-- Description + Specs -->
                <div>
                    <div class="sa-prose" style="margin-bottom:1.5rem;">
                        <?php the_content(); ?>
                    </div>

                    <!-- Characteristics table -->
                    <?php if ($density || $water_abs || $hardness || $frost) : ?>
                        <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem;">Технические характеристики</h2>
                        <table class="sa-spec-table" style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <?php
                            $specs = [
                                'Плотность'          => $density     ? $density . ' кг/м³' : '',
                                'Водопоглощение'     => $water_abs   ? $water_abs . '%'    : '',
                                'Твёрдость (Мооса)'  => $hardness,
                                'Морозостойкость'    => $frost       ? 'F' . $frost         : '',
                                'Цена за м²'         => $price_m2    ? 'от ' . $price_m2 . ' ₽' : '',
                            ];
                            foreach ($specs as $label => $val) :
                                if (!$val) continue; ?>
                                <tr style="border-bottom:1px solid var(--sa-gray-200);">
                                    <td style="padding:0.6rem 1rem 0.6rem 0;font-weight:600;color:var(--sa-gray-600);width:50%;"><?php echo esc_html($label); ?></td>
                                    <td style="padding:0.6rem 0;"><?php echo esc_html($val); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>

                    <!-- Applications -->
                    <?php if ($applications) : ?>
                        <h2 style="font-size:1.125rem;font-weight:700;margin:1.5rem 0 0.75rem;">Рекомендуется для</h2>
                        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                            <?php foreach ($applications as $app_key) :
                                $app = $app_links[$app_key] ?? null;
                                if (!$app) continue;
                                $pg = get_page_by_path($app['slug']);
                                $url = $pg ? get_permalink($pg) : '#';
                                ?>
                                <a href="<?php echo esc_url($url); ?>" class="sa-badge sa-badge--link">
                                    ✓ <?php echo esc_html($app['label']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- CTA -->
                    <div style="margin-top:2rem;display:flex;gap:1rem;flex-wrap:wrap;">
                        <a href="<?php echo esc_url(get_page_by_path('calculator') ? get_permalink(get_page_by_path('calculator')) : '#quiz-section'); ?>"
                           class="sa-btn sa-btn--primary">
                            <i class="fa-solid fa-calculator"></i> Рассчитать стоимость
                        </a>
                        <a href="<?php echo esc_url(get_page_by_path('uslugi-zamer') ? get_permalink(get_page_by_path('uslugi-zamer')) : '#quiz-section'); ?>"
                           class="sa-btn sa-btn--outline">
                            <i class="fa-solid fa-ruler-combined"></i> Вызвать замерщика
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products from this material -->
    <?php
    $mat_name = $mat_display;
    $mat_products = new WP_Query([
        'post_type'      => 'sa_product',
        'posts_per_page' => 6,
        'tax_query'      => [[
            'taxonomy' => 'sa_material',
            'field'    => 'name',
            'terms'    => ['Натуральный ' . mb_strtolower($mat_name), $mat_name],
            'operator' => 'IN',
        ]],
    ]);
    if ($mat_products->have_posts()) : ?>
        <section class="sa-section sa-section--light">
            <div class="sa-container">
                <h2 class="sa-section__title">Изделия из <?php echo esc_html(mb_strtolower($mat_name) === 'мрамор' ? 'мрамора' : mb_strtolower($mat_name) . 'а'); ?></h2>
                <div class="sa-catalog__grid" style="margin-top:2rem;">
                    <?php while ($mat_products->have_posts()) : $mat_products->the_post();
                        $price_from = function_exists('get_field') ? get_field('sa_product_price_from') : '';
                        ?>
                        <article class="sa-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('sa-card', ['class' => 'sa-card__image', 'loading' => 'lazy']); ?>
                                </a>
                            <?php endif; ?>
                            <div class="sa-card__body">
                                <h3 class="sa-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="sa-card__text"><?php the_excerpt(); ?></p>
                                <?php if ($price_from) : ?>
                                    <div class="sa-card__price">от <?php echo number_format((int)$price_from, 0, '.', ' '); ?> ₽/м²</div>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="sa-card__link">Подробнее →</a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Care tips -->
    <?php if ($care_text) : ?>
        <section class="sa-section sa-section--white">
            <div class="sa-container">
                <h2 class="sa-section__title">Уход за <?php echo esc_html(mb_strtolower($mat_name)); ?>м</h2>
                <div class="sa-prose" style="max-width:780px;margin:1.5rem auto 0;">
                    <?php echo wp_kses_post(nl2br($care_text)); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- CTA -->
    <?php get_template_part('template-parts/cta-form'); ?>

</main>

<script>
// Thumbnail gallery switcher
document.querySelectorAll('.sa-material-gallery__thumb').forEach(function(thumb) {
    thumb.addEventListener('click', function() {
        var main = document.getElementById('mat-gallery-main').querySelector('img');
        if (main) main.src = this.dataset.full;
        document.querySelectorAll('.sa-material-gallery__thumb').forEach(function(t) {
            t.style.borderColor = 'transparent';
        });
        this.style.borderColor = 'var(--sa-primary)';
    });
});
</script>

<?php get_footer(); ?>
