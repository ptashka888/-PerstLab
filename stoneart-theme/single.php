<?php
/**
 * Single Post Template
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
    <div class="sa-content" itemscope itemtype="https://schema.org/Article">
        <?php while (have_posts()) : the_post(); ?>
            <meta itemprop="headline" content="<?php the_title_attribute(); ?>">
            <meta itemprop="datePublished" content="<?php echo get_the_date('c'); ?>">
            <meta itemprop="dateModified" content="<?php echo get_the_modified_date('c'); ?>">
            <div itemprop="author" itemscope itemtype="https://schema.org/Person">
                <meta itemprop="name" content="<?php echo esc_attr(get_the_author()); ?>">
            </div>

            <?php if (has_post_thumbnail()) : ?>
                <div style="margin-bottom:2rem;">
                    <?php the_post_thumbnail('large', ['itemprop' => 'image', 'style' => 'border-radius:var(--sa-radius-xl);width:100%;']); ?>
                </div>
            <?php endif; ?>

            <div style="margin-bottom:1.5rem;font-size:0.875rem;color:var(--sa-gray-500);">
                <?php echo get_the_date(); ?> &middot; <?php echo esc_html(get_the_author()); ?>
                <?php
                $cats = get_the_category();
                if ($cats) :
                    echo ' &middot; ';
                    foreach ($cats as $i => $cat) {
                        if ($i > 0) echo ', ';
                        echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '" style="color:var(--sa-primary-hover);">' . esc_html($cat->name) . '</a>';
                    }
                endif;
                ?>
            </div>

            <div itemprop="articleBody">
                <?php the_content(); ?>
            </div>

            <!-- Related Posts (SILO) -->
            <?php sa_related_posts(); ?>

        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
