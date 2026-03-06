<?php
/**
 * Main Template (Blog Archive Fallback)
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title"><?php
            if (is_home() && !is_front_page()) {
                echo 'Блог';
            } elseif (is_category()) {
                single_cat_title();
            } elseif (is_search()) {
                echo 'Результаты поиска: ' . esc_html(get_search_query());
            } else {
                echo 'Блог о камне';
            }
        ?></h1>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<section class="sa-section sa-section--gray">
    <div class="sa-container">
        <?php if (have_posts()) : ?>
            <div class="sa-blog-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="sa-blog-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('sa-card', ['class' => 'sa-blog-card__image']); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="https://images.unsplash.com/photo-1600607688969-a5bfcd646154?auto=format&fit=crop&w=400&q=80" alt="<?php the_title_attribute(); ?>" class="sa-blog-card__image" loading="lazy">
                            </a>
                        <?php endif; ?>
                        <div class="sa-blog-card__body">
                            <?php
                            $cats = get_the_category();
                            if ($cats) : ?>
                                <span class="sa-blog-card__category"><?php echo esc_html($cats[0]->name); ?></span>
                            <?php endif; ?>
                            <h2 class="sa-blog-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="sa-blog-card__excerpt"><?php echo get_the_excerpt(); ?></p>
                            <div class="sa-blog-card__meta">
                                <?php echo get_the_date(); ?> &middot; <?php echo esc_html(get_the_author()); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div style="text-align:center;margin-top:3rem;">
                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '&larr; Назад',
                    'next_text' => 'Далее &rarr;',
                ]);
                ?>
            </div>
        <?php else : ?>
            <div style="text-align:center;padding:4rem 0;">
                <p style="font-size:1.25rem;color:var(--sa-gray-500);">Записи не найдены.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
