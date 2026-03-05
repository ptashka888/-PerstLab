<?php
defined('ABSPATH') || exit;

$position = $args['position'] ?? 'footer';

if (! function_exists('cf_render_interlinking')) {
    return;
}
?>
<div class="cf-interlinking cf-interlinking--<?php echo esc_attr($position); ?>">
    <?php cf_render_interlinking($position); ?>
</div>
