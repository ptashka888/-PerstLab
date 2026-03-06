<?php
/**
 * Default Page Template
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
    <div class="sa-content">
        <?php
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</section>

<?php get_footer(); ?>
