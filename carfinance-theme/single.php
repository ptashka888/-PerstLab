<?php
/**
 * Template: Single Blog Post
 * Article schema, E-E-A-T author card, hub-spoke nav
 */

defined('ABSPATH') || exit;

get_header();

$post_id     = get_the_ID();
$author_name = cf_get_field('cf_author_name', $post_id) ?: get_the_author();
$author_role = cf_get_field('cf_author_position', $post_id) ?: '';
$author_photo = cf_get_field('cf_author_photo', $post_id);
$show_toc    = cf_get_field('cf_show_toc', $post_id);
$topics      = get_the_terms($post_id, 'blog_topic');
$topic       = $topics ? $topics[0] : null;
?>

<article class="cf-article">
    <div class="cf-container">
        <div class="cf-article__layout">
            <!-- Main Content -->
            <main class="cf-article__main">
                <!-- Header -->
                <header class="cf-article__header">
                    <?php if ($topic): ?>
                        <a href="<?php echo esc_url(get_term_link($topic)); ?>" class="cf-article__topic">
                            <?php echo esc_html($topic->name); ?>
                        </a>
                    <?php endif; ?>
                    <h1 class="cf-article__title"><?php the_title(); ?></h1>
                    <div class="cf-article__meta">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d.m.Y')); ?></time>
                        <span>·</span>
                        <span><?php echo esc_html(ceil(str_word_count(get_the_content()) / 200)); ?> мин чтения</span>
                    </div>
                </header>

                <?php if (has_post_thumbnail()): ?>
                    <div class="cf-article__image">
                        <?php the_post_thumbnail('large', ['loading' => 'eager']); ?>
                    </div>
                <?php endif; ?>

                <!-- Content -->
                <div class="cf-article__content cf-content">
                    <?php the_content(); ?>
                </div>

                <!-- Author E-E-A-T Card -->
                <div class="cf-author-card">
                    <?php if ($author_photo): ?>
                        <img src="<?php echo esc_url(is_array($author_photo) ? $author_photo['sizes']['thumbnail'] : $author_photo); ?>"
                             alt="<?php echo esc_attr($author_name); ?>"
                             class="cf-author-card__photo"
                             width="80" height="80" loading="lazy">
                    <?php endif; ?>
                    <div class="cf-author-card__info">
                        <span class="cf-author-card__label">Автор статьи</span>
                        <strong class="cf-author-card__name"><?php echo esc_html($author_name); ?></strong>
                        <?php if ($author_role): ?>
                            <span class="cf-author-card__role"><?php echo esc_html($author_role); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tags -->
                <?php
                $tags = get_the_terms($post_id, 'catalog_tag');
                if ($tags && !is_wp_error($tags)): ?>
                    <div class="cf-article__tags">
                        <?php foreach ($tags as $tag): ?>
                            <a href="<?php echo esc_url(get_term_link($tag)); ?>" class="cf-tag"><?php echo esc_html($tag->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Related Models -->
                <?php
                $related_models = cf_get_field('cf_related_models', $post_id);
                if ($related_models): ?>
                    <div class="cf-article__related-models">
                        <h3>Модели из статьи</h3>
                        <div class="cf-grid cf-grid--2">
                            <?php foreach ($related_models as $model):
                                $model_id = is_object($model) ? $model->ID : $model;
                                cf_block('car-card', ['post_id' => $model_id]);
                            endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </main>

            <!-- Sidebar -->
            <aside class="cf-article__sidebar">
                <?php cf_block('blog-hub-nav', ['current_topic' => $topic ? $topic->slug : '']); ?>
                <?php cf_block('silo-nav'); ?>
                <?php if (is_active_sidebar('blog-sidebar')): ?>
                    <?php dynamic_sidebar('blog-sidebar'); ?>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</article>

<?php
cf_block('interlinking', ['position' => 'footer']);
cf_block('cta-final', ['variant' => 'default']);
get_footer();
