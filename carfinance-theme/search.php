<?php
/**
 * Template: Search Results
 */

defined('ABSPATH') || exit;

get_header();
?>

<section class="cf-section">
    <div class="cf-container">
        <h1 class="cf-section__title">
            Результаты поиска: «<?php echo esc_html(get_search_query()); ?>»
        </h1>
        <p class="cf-section__subtitle">
            <?php
            $found = $wp_query->found_posts;
            echo esc_html("Найдено результатов: {$found}");
            ?>
        </p>
    </div>
</section>

<div class="cf-search-results">
    <div class="cf-container">
        <?php if (have_posts()): ?>
            <div class="cf-search-results__list">
                <?php while (have_posts()): the_post(); ?>
                    <article class="cf-search-results__item">
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>" class="cf-search-results__image">
                                <?php the_post_thumbnail('medium', ['loading' => 'lazy']); ?>
                            </a>
                        <?php endif; ?>
                        <div class="cf-search-results__content">
                            <span class="cf-search-results__type">
                                <?php
                                $type_labels = [
                                    'car_model'    => 'Каталог',
                                    'auction_lot'  => 'Аукцион',
                                    'case_study'   => 'Кейс',
                                    'service_page' => 'Услуга',
                                    'post'         => 'Блог',
                                    'page'         => 'Страница',
                                ];
                                echo esc_html($type_labels[get_post_type()] ?? get_post_type());
                                ?>
                            </span>
                            <h2 class="cf-search-results__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="cf-search-results__excerpt"><?php echo esc_html(cf_excerpt(get_the_excerpt(), 30)); ?></p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if ($wp_query->max_num_pages > 1): ?>
                <div class="cf-catalog__pagination">
                    <?php echo paginate_links([
                        'total'     => $wp_query->max_num_pages,
                        'current'   => max(1, get_query_var('paged')),
                        'prev_text' => '← Назад',
                        'next_text' => 'Далее →',
                        'type'      => 'list',
                    ]); ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="cf-search-results__empty">
                <h2>Ничего не найдено</h2>
                <p>Попробуйте изменить поисковый запрос или перейдите в <a href="<?php echo esc_url(get_post_type_archive_link('car_model')); ?>">каталог</a>.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
cf_block('cta-final', ['variant' => 'default']);
get_footer();
