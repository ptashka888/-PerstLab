<?php
defined('ABSPATH') || exit;

$variant = $args['variant'] ?? 'default';
$topic   = $args['topic'] ?? '';
$limit   = (int) ($args['limit'] ?? 6);

$query_args = [
    'post_type'      => 'post',
    'posts_per_page' => $limit,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ($topic) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => 'blog_topic',
            'field'    => 'slug',
            'terms'    => $topic,
        ],
    ];
}

$posts_query = new WP_Query($query_args);

if (! $posts_query->have_posts()) {
    return;
}
?>
<section class="cf-blog-posts cf-blog-posts--<?php echo esc_attr($variant); ?>">
    <div class="cf-blog-posts__header">
        <h2 class="cf-blog-posts__title">Статьи и обзоры</h2>
    </div>

    <div class="cf-blog-posts__grid">
        <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
            <?php
            $post_id    = get_the_ID();
            $topics     = get_the_terms($post_id, 'blog_topic');
            $topic_term = ($topics && ! is_wp_error($topics)) ? $topics[0] : null;
            $excerpt    = function_exists('cf_excerpt') ? cf_excerpt($post_id) : get_the_excerpt();
            ?>
            <a href="<?php the_permalink(); ?>" class="cf-blog-posts__card">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="cf-blog-posts__image">
                        <?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
                    </div>
                <?php endif; ?>

                <div class="cf-blog-posts__body">
                    <div class="cf-blog-posts__meta">
                        <time class="cf-blog-posts__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date('d.m.Y')); ?>
                        </time>
                        <?php if ($topic_term) : ?>
                            <span class="cf-blog-posts__badge"><?php echo esc_html($topic_term->name); ?></span>
                        <?php endif; ?>
                    </div>

                    <h3 class="cf-blog-posts__card-title"><?php the_title(); ?></h3>

                    <?php if ($excerpt) : ?>
                        <p class="cf-blog-posts__excerpt"><?php echo esc_html($excerpt); ?></p>
                    <?php endif; ?>

                    <span class="cf-blog-posts__link">Читать &rarr;</span>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</section>
<?php wp_reset_postdata(); ?>
