<?php
/**
 * Single Product Template
 *
 * @package StoneArt
 */
get_header();

$price_from  = function_exists('get_field') ? get_field('sa_product_price_from') : '';
$price_to    = function_exists('get_field') ? get_field('sa_product_price_to')   : '';
$thickness   = function_exists('get_field') ? get_field('sa_product_thickness')  : '';
$surface     = function_exists('get_field') ? get_field('sa_product_surface')    : [];
$edge        = function_exists('get_field') ? get_field('sa_product_edge')       : [];
$features    = function_exists('get_field') ? get_field('sa_product_features')   : '';
$gallery     = function_exists('get_field') ? get_field('sa_product_gallery')    : [];
$in_stock    = function_exists('get_field') ? get_field('sa_product_in_stock')   : true;

$materials   = get_the_terms(get_the_ID(), 'sa_material');
$types       = get_the_terms(get_the_ID(), 'sa_product_type');
$cats        = get_the_terms(get_the_ID(), 'sa_product_cat');

$surface_labels = [
    'polish'    => 'Полировка',
    'honed'     => 'Лощение',
    'thermal'   => 'Термообработка',
    'bush'      => 'Бучардирование',
    'antique'   => 'Антик',
    'sandblast' => 'Пескоструй',
];
$edge_labels = [
    'straight'  => 'Прямая',
    'bevel'     => 'Фаска',
    'round'     => 'Скругление',
    'profiled'  => 'Профильная',
    'carving'   => 'Резная',
];
?>

<div class="sa-page-header sa-page-header--compact">
    <div class="sa-container">
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<main class="sa-main">
    <section class="sa-section sa-section--white">
        <div class="sa-container">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:start;" class="sa-product-layout">

                <!-- Gallery column -->
                <div>
                    <?php if ($gallery) : ?>
                        <div class="sa-material-gallery">
                            <?php $first = reset($gallery); ?>
                            <div id="prod-gallery-main" style="position:relative;">
                                <?php if (!$in_stock) : ?>
                                    <span style="position:absolute;top:1rem;left:1rem;background:#ef4444;color:#fff;font-size:0.8rem;font-weight:700;padding:0.3rem 0.75rem;border-radius:999px;z-index:1;">Нет в наличии</span>
                                <?php else : ?>
                                    <span style="position:absolute;top:1rem;left:1rem;background:#22c55e;color:#fff;font-size:0.8rem;font-weight:700;padding:0.3rem 0.75rem;border-radius:999px;z-index:1;">В наличии</span>
                                <?php endif; ?>
                                <img src="<?php echo esc_url($first['url']); ?>"
                                     alt="<?php echo esc_attr($first['alt'] ?: get_the_title()); ?>"
                                     id="prod-main-img"
                                     style="width:100%;border-radius:var(--sa-radius);object-fit:cover;aspect-ratio:4/3;"
                                     loading="eager">
                            </div>
                            <?php if (count($gallery) > 1) : ?>
                                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:0.5rem;margin-top:0.75rem;">
                                    <?php foreach ($gallery as $img) : ?>
                                        <img src="<?php echo esc_url($img['sizes']['sa-card'] ?? $img['url']); ?>"
                                             alt="<?php echo esc_attr($img['alt']); ?>"
                                             width="80" height="60"
                                             loading="lazy"
                                             data-full="<?php echo esc_url($img['url']); ?>"
                                             class="sa-prod-thumb"
                                             style="cursor:pointer;border-radius:4px;object-fit:cover;border:2px solid transparent;transition:border-color 0.2s;">
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('sa-hero', ['style' => 'width:100%;border-radius:var(--sa-radius);', 'loading' => 'eager']); ?>
                    <?php endif; ?>
                </div>

                <!-- Info column -->
                <div>
                    <!-- Breadcrumb tags -->
                    <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;">
                        <?php if ($cats) : ?>
                            <?php foreach ($cats as $cat) : ?>
                                <?php
                                $cat_page = get_page_by_path($cat->slug);
                                $cat_url  = $cat_page ? get_permalink($cat_page) : '#';
                                ?>
                                <a href="<?php echo esc_url($cat_url); ?>" class="sa-badge"><?php echo esc_html($cat->name); ?></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($materials) : ?>
                            <?php foreach ($materials as $mat) : ?>
                                <span class="sa-badge sa-badge--secondary"><?php echo esc_html($mat->name); ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <h1 style="font-size:1.75rem;font-weight:800;line-height:1.2;margin-bottom:1rem;font-family:var(--sa-font-serif);">
                        <?php the_title(); ?>
                    </h1>

                    <!-- Price -->
                    <?php if ($price_from) : ?>
                        <div style="margin-bottom:1.5rem;">
                            <span style="font-size:2rem;font-weight:900;color:var(--sa-primary);">
                                от <?php echo number_format((int)$price_from, 0, '.', ' '); ?> ₽
                            </span>
                            <?php if ($price_to) : ?>
                                <span style="font-size:1.25rem;color:var(--sa-gray-500);margin-left:0.25rem;">
                                    — <?php echo number_format((int)$price_to, 0, '.', ' '); ?> ₽/м²
                                </span>
                            <?php else : ?>
                                <span style="font-size:1rem;color:var(--sa-gray-500);">/м²</span>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <div style="margin-bottom:1.5rem;font-size:1.25rem;font-weight:700;color:var(--sa-gray-600);">
                            Цена рассчитывается индивидуально
                        </div>
                    <?php endif; ?>

                    <!-- Excerpt -->
                    <?php if (has_excerpt()) : ?>
                        <p style="color:var(--sa-gray-700);line-height:1.7;margin-bottom:1.5rem;"><?php the_excerpt(); ?></p>
                    <?php endif; ?>

                    <!-- Specs -->
                    <table style="width:100%;border-collapse:collapse;font-size:0.9rem;margin-bottom:1.5rem;">
                        <?php if ($thickness) : ?>
                            <tr style="border-bottom:1px solid var(--sa-gray-200);">
                                <td style="padding:0.5rem 1rem 0.5rem 0;color:var(--sa-gray-600);width:40%;">Толщина</td>
                                <td style="padding:0.5rem 0;font-weight:600;"><?php echo esc_html($thickness); ?> мм</td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($surface && is_array($surface)) : ?>
                            <tr style="border-bottom:1px solid var(--sa-gray-200);">
                                <td style="padding:0.5rem 1rem 0.5rem 0;color:var(--sa-gray-600);">Обработка</td>
                                <td style="padding:0.5rem 0;font-weight:600;">
                                    <?php echo esc_html(implode(', ', array_map(fn($s) => $surface_labels[$s] ?? $s, (array)$surface))); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($edge && is_array($edge)) : ?>
                            <tr style="border-bottom:1px solid var(--sa-gray-200);">
                                <td style="padding:0.5rem 1rem 0.5rem 0;color:var(--sa-gray-600);">Кромка</td>
                                <td style="padding:0.5rem 0;font-weight:600;">
                                    <?php echo esc_html(implode(', ', array_map(fn($e) => $edge_labels[$e] ?? $e, (array)$edge))); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($materials) : ?>
                            <tr>
                                <td style="padding:0.5rem 1rem 0.5rem 0;color:var(--sa-gray-600);">Материал</td>
                                <td style="padding:0.5rem 0;font-weight:600;"><?php echo esc_html(implode(', ', wp_list_pluck($materials, 'name'))); ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>

                    <!-- Features -->
                    <?php if ($features) :
                        $feat_lines = array_filter(array_map('trim', explode("\n", $features)));
                        if ($feat_lines) : ?>
                            <ul style="list-style:none;padding:0;margin:0 0 1.5rem;display:flex;flex-direction:column;gap:0.5rem;">
                                <?php foreach ($feat_lines as $feat) : ?>
                                    <li style="display:flex;align-items:flex-start;gap:0.6rem;font-size:0.9rem;">
                                        <i class="fa-solid fa-check" style="color:var(--sa-primary);flex-shrink:0;margin-top:0.2rem;"></i>
                                        <?php echo esc_html($feat); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- CTA buttons -->
                    <div style="display:flex;flex-direction:column;gap:0.75rem;">
                        <a href="<?php echo esc_attr(sa_phone_href()); ?>" class="sa-btn sa-btn--primary" style="text-align:center;">
                            <i class="fa-solid fa-phone"></i> Заказать: <?php echo esc_html(sa_phone()); ?>
                        </a>
                        <a href="<?php echo esc_url(get_page_by_path('calculator') ? get_permalink(get_page_by_path('calculator')) : '#quiz-section'); ?>"
                           class="sa-btn sa-btn--outline" style="text-align:center;">
                            <i class="fa-solid fa-calculator"></i> Рассчитать стоимость
                        </a>
                        <?php if (sa_whatsapp() !== '#') : ?>
                            <a href="<?php echo esc_url(sa_whatsapp()); ?>" class="sa-btn" style="text-align:center;background:#22c55e;color:#fff;">
                                <i class="fa-brands fa-whatsapp"></i> Написать в WhatsApp
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Full description -->
    <?php if (get_the_content()) : ?>
        <section class="sa-section sa-section--light">
            <div class="sa-container">
                <h2 class="sa-section__title">Описание изделия</h2>
                <div class="sa-prose" style="max-width:900px;margin:1.5rem auto 0;">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Related products -->
    <?php
    $related_args = [
        'post_type'      => 'sa_product',
        'posts_per_page' => 4,
        'post__not_in'   => [get_the_ID()],
    ];
    if ($cats) {
        $related_args['tax_query'] = [[
            'taxonomy' => 'sa_product_cat',
            'field'    => 'term_id',
            'terms'    => wp_list_pluck($cats, 'term_id'),
        ]];
    }
    $related = new WP_Query($related_args);
    if ($related->have_posts()) : ?>
        <section class="sa-section sa-section--white">
            <div class="sa-container">
                <h2 class="sa-section__title">Похожие изделия</h2>
                <div class="sa-catalog__grid" style="margin-top:1.5rem;">
                    <?php while ($related->have_posts()) : $related->the_post();
                        $pf = function_exists('get_field') ? get_field('sa_product_price_from') : ''; ?>
                        <article class="sa-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('sa-card', ['class' => 'sa-card__image', 'loading' => 'lazy']); ?>
                                </a>
                            <?php endif; ?>
                            <div class="sa-card__body">
                                <h3 class="sa-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php if ($pf) : ?>
                                    <div class="sa-card__price">от <?php echo number_format((int)$pf, 0, '.', ' '); ?> ₽/м²</div>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="sa-card__link">Подробнее →</a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php get_template_part('template-parts/cta-form'); ?>

</main>

<script>
document.querySelectorAll('.sa-prod-thumb').forEach(function(t) {
    t.addEventListener('click', function() {
        document.getElementById('prod-main-img').src = this.dataset.full;
        document.querySelectorAll('.sa-prod-thumb').forEach(function(x) { x.style.borderColor = 'transparent'; });
        this.style.borderColor = 'var(--sa-primary)';
    });
});
</script>

<?php get_footer(); ?>
