<?php
/**
 * Template Name: Калькулятор
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p style="color:var(--sa-gray-400);margin-top:0.5rem;">Рассчитайте стоимость вашего изделия из камня за 1 минуту</p>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<!-- Quiz -->
<?php get_template_part('template-parts/quiz'); ?>

<!-- Comparison Table -->
<?php get_template_part('template-parts/comparison'); ?>

<!-- Process -->
<?php get_template_part('template-parts/process'); ?>

<!-- Reviews -->
<?php get_template_part('template-parts/reviews'); ?>

<?php get_footer(); ?>
