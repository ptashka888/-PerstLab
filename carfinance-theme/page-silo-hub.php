<?php
/**
 * Template Name: SILO Hub Page
 * Hub page for blog topic clusters
 */

defined('ABSPATH') || exit;

get_header();

$post_id = get_the_ID();
?>

<section class="cf-section">
    <div class="cf-container">
        <h1 class="cf-section__title"><?php the_title(); ?></h1>
        <?php if (has_excerpt()): ?>
            <p class="cf-section__subtitle"><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php endif; ?>
    </div>
</section>

<div class="cf-hub">
    <div class="cf-container">
        <div class="cf-hub__layout">
            <main class="cf-hub__main">
                <?php if (get_the_content()): ?>
                    <div class="cf-content">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <?php cf_block('blog-posts', ['variant' => 'hub', 'limit' => 12]); ?>
            </main>

            <aside class="cf-hub__sidebar">
                <?php cf_block('blog-hub-nav'); ?>
                <?php cf_block('silo-nav'); ?>
            </aside>
        </div>
    </div>
</div>

<?php
cf_block('interlinking', ['position' => 'footer']);
cf_block('cta-final', ['variant' => 'default']);
get_footer();
