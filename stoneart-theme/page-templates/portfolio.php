<?php
/**
 * Template Name: Портфолио
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p style="color:var(--sa-gray-400);margin-top:0.5rem;">Реализованные проекты из натурального и искусственного камня</p>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<!-- Filters -->
<section class="sa-section sa-section--white" style="padding-bottom:0;">
    <div class="sa-container">
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:center;">
            <button class="sa-btn sa-btn--primary sa-portfolio-filter active" data-filter="all" style="font-size:0.8rem;">Все работы</button>
            <?php
            $cats = get_terms(['taxonomy' => 'sa_product_cat', 'hide_empty' => true]);
            foreach ($cats as $cat) : ?>
                <button class="sa-btn sa-btn--outline sa-portfolio-filter" data-filter="<?php echo esc_attr($cat->slug); ?>" style="font-size:0.8rem;">
                    <?php echo esc_html($cat->name); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="sa-section sa-section--white">
    <div class="sa-container">
        <?php
        $portfolio = new WP_Query([
            'post_type'      => 'sa_portfolio',
            'posts_per_page' => 12,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ($portfolio->have_posts()) : ?>
            <div class="sa-portfolio-grid">
                <?php while ($portfolio->have_posts()) : $portfolio->the_post();
                    $terms = get_the_terms(get_the_ID(), 'sa_product_cat');
                    $term_slugs = $terms ? implode(' ', wp_list_pluck($terms, 'slug')) : '';
                ?>
                    <a href="<?php the_permalink(); ?>" class="sa-portfolio-card sa-portfolio-item" data-categories="<?php echo esc_attr($term_slugs); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('sa-portfolio', ['class' => 'sa-portfolio-card__image', 'loading' => 'lazy']); ?>
                        <?php else : ?>
                            <img src="https://images.unsplash.com/photo-1600607688969-a5bfcd646154?auto=format&fit=crop&w=600&q=80" alt="<?php the_title_attribute(); ?>" class="sa-portfolio-card__image" loading="lazy">
                        <?php endif; ?>
                        <div class="sa-portfolio-card__overlay">
                            <h3 class="sa-portfolio-card__title"><?php the_title(); ?></h3>
                            <?php if ($terms) : ?>
                                <p class="sa-portfolio-card__meta"><?php echo esc_html($terms[0]->name); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div style="text-align:center;padding:4rem 0;">
                <i class="fa-solid fa-images" style="font-size:3rem;color:var(--sa-gray-300);margin-bottom:1rem;display:block;"></i>
                <p style="font-size:1.25rem;color:var(--sa-gray-500);">Портфолио скоро будет заполнено.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Quiz CTA -->
<?php get_template_part('template-parts/quiz'); ?>

<?php get_footer(); ?>
