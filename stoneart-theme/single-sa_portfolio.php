<?php
/**
 * Single Portfolio Template
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<section class="sa-section sa-section--white">
    <div class="sa-container" style="max-width:64rem;">
        <?php while (have_posts()) : the_post();
            $material  = function_exists('get_field') ? get_field('sa_portfolio_material') : '';
            $area      = function_exists('get_field') ? get_field('sa_portfolio_area') : '';
            $duration  = function_exists('get_field') ? get_field('sa_portfolio_duration') : '';
            $location  = function_exists('get_field') ? get_field('sa_portfolio_location') : '';
            $gallery   = function_exists('get_field') ? get_field('sa_portfolio_gallery') : [];
        ?>

            <?php if (has_post_thumbnail()) : ?>
                <div style="margin-bottom:2rem;">
                    <?php the_post_thumbnail('sa-hero', ['style' => 'border-radius:var(--sa-radius-xl);width:100%;max-height:500px;object-fit:cover;']); ?>
                </div>
            <?php endif; ?>

            <!-- Project Details -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:3rem;padding:2rem;background:var(--sa-gray-50);border-radius:var(--sa-radius-xl);">
                <?php if ($material) : ?>
                    <div>
                        <div style="font-size:0.75rem;color:var(--sa-gray-500);text-transform:uppercase;font-weight:700;">Материал</div>
                        <div style="font-weight:700;font-size:1.125rem;color:var(--sa-gray-900);"><?php echo esc_html($material); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ($area) : ?>
                    <div>
                        <div style="font-size:0.75rem;color:var(--sa-gray-500);text-transform:uppercase;font-weight:700;">Площадь</div>
                        <div style="font-weight:700;font-size:1.125rem;color:var(--sa-gray-900);"><?php echo esc_html($area); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ($duration) : ?>
                    <div>
                        <div style="font-size:0.75rem;color:var(--sa-gray-500);text-transform:uppercase;font-weight:700;">Срок работ</div>
                        <div style="font-weight:700;font-size:1.125rem;color:var(--sa-gray-900);"><?php echo esc_html($duration); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ($location) : ?>
                    <div>
                        <div style="font-size:0.75rem;color:var(--sa-gray-500);text-transform:uppercase;font-weight:700;">Объект</div>
                        <div style="font-weight:700;font-size:1.125rem;color:var(--sa-gray-900);"><?php echo esc_html($location); ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Content -->
            <div class="sa-content" style="max-width:none;padding:0;">
                <?php the_content(); ?>
            </div>

            <!-- Gallery -->
            <?php if ($gallery) : ?>
                <div style="margin-top:3rem;">
                    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;" class="sa-font-serif">Фотогалерея</h3>
                    <div class="sa-portfolio-grid">
                        <?php foreach ($gallery as $img) : ?>
                            <div class="sa-portfolio-card">
                                <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt'] ?? ''); ?>" class="sa-portfolio-card__image" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- CTA -->
            <div style="text-align:center;margin-top:3rem;padding:3rem;background:var(--sa-gray-900);border-radius:var(--sa-radius-xl);color:var(--sa-white);">
                <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;" class="sa-font-serif">Хотите такой же проект?</h3>
                <p style="color:var(--sa-gray-300);margin-bottom:1.5rem;">Рассчитаем стоимость вашего изделия за 10 минут</p>
                <?php $calc = get_page_by_path('calculator'); ?>
                <a href="<?php echo $calc ? esc_url(get_permalink($calc)) : '#quiz-section'; ?>" class="sa-btn sa-btn--primary sa-btn--lg">Рассчитать стоимость</a>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
