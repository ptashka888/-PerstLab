<?php
/**
 * Portfolio Archive Template
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title">Портфолио</h1>
        <p style="color:var(--sa-gray-400);margin-top:0.5rem;">Наши реализованные проекты из натурального и искусственного камня</p>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<!-- Filters -->
<section class="sa-section sa-section--white" style="padding-bottom:0;">
    <div class="sa-container">
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:center;">
            <a href="<?php echo esc_url(get_post_type_archive_link('sa_portfolio')); ?>"
               class="sa-btn <?php echo !is_tax() ? 'sa-btn--primary' : 'sa-btn--outline'; ?>" style="font-size:0.8rem;">
                Все работы
            </a>
            <?php
            $cats = get_terms(['taxonomy' => 'sa_product_cat', 'hide_empty' => true]);
            foreach ($cats as $cat) : ?>
                <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                   class="sa-btn <?php echo is_tax('sa_product_cat', $cat->slug) ? 'sa-btn--primary' : 'sa-btn--outline'; ?>" style="font-size:0.8rem;">
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="sa-section sa-section--white">
    <div class="sa-container">
        <?php if (have_posts()) : ?>
            <div class="sa-portfolio-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="sa-portfolio-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('sa-portfolio', ['class' => 'sa-portfolio-card__image', 'loading' => 'lazy']); ?>
                        <?php else : ?>
                            <img src="https://images.unsplash.com/photo-1600607688969-a5bfcd646154?auto=format&fit=crop&w=600&q=80" alt="<?php the_title_attribute(); ?>" class="sa-portfolio-card__image" loading="lazy">
                        <?php endif; ?>
                        <div class="sa-portfolio-card__overlay">
                            <h3 class="sa-portfolio-card__title"><?php the_title(); ?></h3>
                            <?php
                            $terms = get_the_terms(get_the_ID(), 'sa_product_cat');
                            if ($terms) : ?>
                                <p class="sa-portfolio-card__meta"><?php echo esc_html($terms[0]->name); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>

            <div style="text-align:center;margin-top:3rem;">
                <?php the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '&larr; Назад',
                    'next_text' => 'Далее &rarr;',
                ]); ?>
            </div>
        <?php else : ?>
            <div style="text-align:center;padding:4rem 0;">
                <p style="font-size:1.25rem;color:var(--sa-gray-500);">Проекты скоро появятся.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
