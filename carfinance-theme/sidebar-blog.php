<?php
/**
 * Template: Blog Sidebar
 */

defined('ABSPATH') || exit;
?>

<aside class="cf-sidebar cf-sidebar--blog">
    <?php cf_block('blog-hub-nav'); ?>

    <?php if (is_active_sidebar('blog-sidebar')): ?>
        <?php dynamic_sidebar('blog-sidebar'); ?>
    <?php endif; ?>

    <?php cf_block('silo-nav'); ?>
</aside>
