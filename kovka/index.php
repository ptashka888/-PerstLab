<?php get_header(); ?>

<section class="kv-section">
    <div class="kv-container">
        <?php if (have_posts()) : ?>
        <div class="kv-grid-3">
            <?php while (have_posts()) : the_post(); ?>
            <div class="kv-blog-card">
                <?php if (has_post_thumbnail()) : ?>
                <img src="<?= esc_url(get_the_post_thumbnail_url(null, 'kv-wide')) ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                <?php endif; ?>
                <div class="kv-blog-card__body">
                    <div class="kv-blog-card__cat"><?php the_category(', '); ?></div>
                    <a href="<?php the_permalink(); ?>" class="kv-blog-card__title" style="display:block;text-decoration:none;color:var(--kv-text)"><?php the_title(); ?></a>
                    <div class="kv-blog-card__meta"><?php the_date(); ?></div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php echo paginate_links(); ?>
        <?php else : ?>
        <p>Записей не найдено.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
