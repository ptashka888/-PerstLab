<?php
/**
 * Template Name: Услуги
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p style="color:var(--sa-gray-400);margin-top:0.5rem;">Полный цикл: от выбора материала до монтажа с гарантией 10 лет</p>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<!-- Services List from CPT -->
<section class="sa-section sa-section--white">
    <div class="sa-container">
        <?php
        $services = new WP_Query([
            'post_type'      => 'sa_service',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);

        if ($services->have_posts()) :
            $i = 0;
            while ($services->have_posts()) : $services->the_post();
                $price = function_exists('get_field') ? get_field('sa_service_price') : '';
                $features_text = function_exists('get_field') ? get_field('sa_service_features') : '';
                $features_list = $features_text ? explode("\n", $features_text) : [];
                $reverse = $i % 2 !== 0;
        ?>
            <div class="sa-catalog__featured<?php echo $reverse ? ' sa-catalog__featured--reverse' : ''; ?>" itemscope itemtype="https://schema.org/Service">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('sa-card', ['class' => 'sa-catalog__featured-image', 'loading' => 'lazy', 'itemprop' => 'image']); ?>
                <?php else : ?>
                    <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800" alt="<?php the_title_attribute(); ?>" class="sa-catalog__featured-image" loading="lazy">
                <?php endif; ?>
                <div class="sa-catalog__featured-content">
                    <h2 class="sa-catalog__featured-title sa-font-serif" itemprop="name"><?php the_title(); ?></h2>
                    <?php if ($price) : ?>
                        <div style="margin-bottom:1rem;font-size:1.5rem;font-weight:800;color:var(--sa-primary-hover);" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                            от <span itemprop="price"><?php echo esc_html($price); ?></span>
                            <meta itemprop="priceCurrency" content="RUB">
                            <span style="font-size:0.875rem;color:var(--sa-gray-500);">₽/п.м.</span>
                        </div>
                    <?php endif; ?>
                    <p class="sa-catalog__featured-text" itemprop="description"><?php echo get_the_excerpt(); ?></p>
                    <?php if ($features_list) : ?>
                        <ul class="sa-catalog__featured-list">
                            <?php foreach ($features_list as $feat) :
                                $feat = trim($feat);
                                if ($feat) : ?>
                                    <li><i class="fa-solid fa-check"></i> <?php echo esc_html($feat); ?></li>
                                <?php endif;
                            endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php $calc = get_page_by_path('calculator'); ?>
                    <a href="<?php echo $calc ? esc_url(get_permalink($calc)) : '#quiz-section'; ?>" class="sa-btn sa-btn--primary">Заказать расчет</a>
                </div>
            </div>
        <?php
                $i++;
            endwhile;
            wp_reset_postdata();
        else :
        ?>
            <!-- Fallback: show catalog template part -->
            <?php get_template_part('template-parts/catalog'); ?>
        <?php endif; ?>
    </div>
</section>

<!-- Process -->
<?php get_template_part('template-parts/process'); ?>

<!-- Care Tips -->
<?php get_template_part('template-parts/care-tips'); ?>

<!-- Quiz -->
<?php get_template_part('template-parts/quiz'); ?>

<?php get_footer(); ?>
