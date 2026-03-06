<?php
/**
 * Template Name: О компании
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

<!-- About Block -->
<?php get_template_part('template-parts/about'); ?>

<!-- Stone Stories -->
<?php get_template_part('template-parts/stone-stories'); ?>

<!-- Process -->
<?php get_template_part('template-parts/process'); ?>

<!-- Experts -->
<?php get_template_part('template-parts/experts'); ?>

<!-- Reviews -->
<?php get_template_part('template-parts/reviews'); ?>

<!-- Quiz -->
<?php get_template_part('template-parts/quiz'); ?>

<?php get_footer(); ?>
