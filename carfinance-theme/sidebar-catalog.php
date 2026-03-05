<?php
/**
 * Template: Catalog Sidebar
 */

defined('ABSPATH') || exit;
?>

<aside class="cf-sidebar cf-sidebar--catalog">
    <?php cf_block('catalog-filter', ['mode' => 'sidebar']); ?>

    <?php if (is_active_sidebar('catalog-sidebar')): ?>
        <?php dynamic_sidebar('catalog-sidebar'); ?>
    <?php endif; ?>

    <?php cf_block('silo-nav'); ?>
</aside>
