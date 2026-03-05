<?php
/**
 * Template: Default Page
 * Used for SILO pillar pages and generic pages
 */

defined('ABSPATH') || exit;

get_header();
?>

<article class="cf-page">
    <div class="cf-container">
        <h1 class="cf-page__title"><?php the_title(); ?></h1>

        <div class="cf-page__content cf-content">
            <?php the_content(); ?>
        </div>
    </div>
</article>

<?php
cf_block('faq', ['source' => 'page']);
cf_block('cta-final', ['variant' => 'default']);
get_footer();
