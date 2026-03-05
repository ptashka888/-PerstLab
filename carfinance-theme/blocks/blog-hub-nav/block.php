<?php
defined('ABSPATH') || exit;

$current_topic = $args['current_topic'] ?? '';

$terms = get_terms([
    'taxonomy'   => 'blog_topic',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

if (is_wp_error($terms) || empty($terms)) {
    return;
}
?>
<aside class="cf-hub-nav">
    <h3 class="cf-hub-nav__title">Темы блога</h3>

    <ul class="cf-hub-nav__list">
        <?php foreach ($terms as $term) :
            $is_active = ($term->slug === $current_topic);
            $link      = get_term_link($term);
            if (is_wp_error($link)) {
                continue;
            }
        ?>
            <li class="cf-hub-nav__item<?php echo $is_active ? ' cf-hub-nav__item--active' : ''; ?>">
                <a href="<?php echo esc_url($link); ?>" class="cf-hub-nav__link">
                    <?php echo esc_html($term->name); ?>
                    <span class="cf-hub-nav__count"><?php echo (int) $term->count; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>
