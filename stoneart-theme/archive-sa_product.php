<?php
/**
 * Product Archive / Catalog Template
 *
 * @package StoneArt
 */
get_header();

// Get filter params
$filter_cat  = isset($_GET['cat'])      ? sanitize_text_field($_GET['cat'])  : '';
$filter_mat  = isset($_GET['material']) ? sanitize_text_field($_GET['material']) : '';
$filter_type = isset($_GET['type'])     ? sanitize_text_field($_GET['type']) : '';

// Build tax query
$tax_query = ['relation' => 'AND'];
if ($filter_cat) {
    $tax_query[] = ['taxonomy' => 'sa_product_cat', 'field' => 'slug', 'terms' => $filter_cat];
}
if ($filter_mat) {
    $tax_query[] = ['taxonomy' => 'sa_material', 'field' => 'slug', 'terms' => $filter_mat];
}
if ($filter_type) {
    $tax_query[] = ['taxonomy' => 'sa_product_type', 'field' => 'slug', 'terms' => $filter_type];
}

$args = [
    'post_type'      => 'sa_product',
    'posts_per_page' => 12,
    'paged'          => max(1, get_query_var('paged')),
];
if (count($tax_query) > 1) {
    $args['tax_query'] = $tax_query;
}

$products = new WP_Query($args);

// Filters data
$all_cats  = get_terms(['taxonomy' => 'sa_product_cat',  'hide_empty' => true]);
$all_mats  = get_terms(['taxonomy' => 'sa_material',     'hide_empty' => true]);
$all_types = get_terms(['taxonomy' => 'sa_product_type', 'hide_empty' => true, 'parent' => 0]);
?>

<div class="sa-page-header">
    <div class="sa-container">
        <?php sa_breadcrumbs(); ?>
        <h1 class="sa-page-header__title">Каталог изделий из камня</h1>
        <p class="sa-page-header__subtitle">
            Столешницы, лестницы, камины, полы — производство под заказ
            <?php if ($products->found_posts) : ?>
                · <?php echo (int)$products->found_posts; ?> изделий
            <?php endif; ?>
        </p>
    </div>
</div>

<main class="sa-main">
    <section class="sa-section sa-section--light">
        <div class="sa-container">
            <div style="display:grid;grid-template-columns:260px 1fr;gap:2rem;align-items:start;" class="sa-catalog-layout">

                <!-- Sidebar filters -->
                <aside class="sa-catalog__sidebar">
                    <form method="GET" action="<?php echo esc_url(get_post_type_archive_link('sa_product')); ?>">

                        <!-- Category filter -->
                        <?php if ($all_cats && !is_wp_error($all_cats)) : ?>
                            <div class="sa-filter-group">
                                <h3 class="sa-filter-group__title">Категория</h3>
                                <div style="display:flex;flex-direction:column;gap:0.35rem;">
                                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.9rem;">
                                        <input type="radio" name="cat" value="" <?php checked($filter_cat, ''); ?>> Все категории
                                    </label>
                                    <?php foreach ($all_cats as $cat) : ?>
                                        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.9rem;">
                                            <input type="radio" name="cat" value="<?php echo esc_attr($cat->slug); ?>" <?php checked($filter_cat, $cat->slug); ?>>
                                            <?php echo esc_html($cat->name); ?>
                                            <span style="color:var(--sa-gray-500);font-size:0.8rem;">(<?php echo (int)$cat->count; ?>)</span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Material filter -->
                        <?php if ($all_mats && !is_wp_error($all_mats)) : ?>
                            <div class="sa-filter-group" style="margin-top:1.5rem;">
                                <h3 class="sa-filter-group__title">Материал</h3>
                                <div style="display:flex;flex-direction:column;gap:0.35rem;">
                                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.9rem;">
                                        <input type="radio" name="material" value="" <?php checked($filter_mat, ''); ?>> Любой
                                    </label>
                                    <?php foreach ($all_mats as $mat) : ?>
                                        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.9rem;">
                                            <input type="radio" name="material" value="<?php echo esc_attr($mat->slug); ?>" <?php checked($filter_mat, $mat->slug); ?>>
                                            <?php echo esc_html($mat->name); ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="sa-btn sa-btn--primary" style="width:100%;margin-top:1.5rem;">
                            <i class="fa-solid fa-filter"></i> Применить
                        </button>
                        <?php if ($filter_cat || $filter_mat || $filter_type) : ?>
                            <a href="<?php echo esc_url(get_post_type_archive_link('sa_product')); ?>" class="sa-btn sa-btn--outline" style="width:100%;text-align:center;margin-top:0.5rem;">
                                Сбросить фильтры
                            </a>
                        <?php endif; ?>
                    </form>
                </aside>

                <!-- Product grid -->
                <div>
                    <!-- Category quick links -->
                    <?php if ($all_types && !is_wp_error($all_types)) : ?>
                        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1.5rem;">
                            <?php foreach ($all_types as $typ) : ?>
                                <a href="?type=<?php echo esc_attr($typ->slug); ?>"
                                   class="sa-btn sa-btn--sm <?php echo $filter_type === $typ->slug ? 'sa-btn--primary' : 'sa-btn--outline'; ?>">
                                    <?php echo esc_html($typ->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($products->have_posts()) : ?>
                        <div class="sa-catalog__grid">
                            <?php while ($products->have_posts()) : $products->the_post();
                                $price_from = function_exists('get_field') ? get_field('sa_product_price_from') : '';
                                $is_hit     = function_exists('get_field') ? get_field('sa_product_is_hit')     : false;
                                $mats       = get_the_terms(get_the_ID(), 'sa_material');
                                ?>
                                <article class="sa-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>" class="sa-card__img-wrap" style="position:relative;display:block;">
                                            <?php the_post_thumbnail('sa-card', ['class' => 'sa-card__image', 'loading' => 'lazy']); ?>
                                            <?php if ($is_hit) : ?>
                                                <span style="position:absolute;top:0.75rem;right:0.75rem;background:var(--sa-primary);color:#fff;font-size:0.7rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:999px;">Хит</span>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="sa-card__body">
                                        <?php if ($mats) : ?>
                                            <div class="sa-card__meta" style="font-size:0.8rem;color:var(--sa-gray-500);margin-bottom:0.25rem;"><?php echo esc_html($mats[0]->name); ?></div>
                                        <?php endif; ?>
                                        <h2 class="sa-card__title" style="font-size:1rem;">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <p class="sa-card__text"><?php the_excerpt(); ?></p>
                                        <?php if ($price_from) : ?>
                                            <div class="sa-card__price">от <?php echo number_format((int)$price_from, 0, '.', ' '); ?> ₽/м²</div>
                                        <?php endif; ?>
                                        <a href="<?php the_permalink(); ?>" class="sa-card__link">Подробнее →</a>
                                    </div>
                                </article>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($products->max_num_pages > 1) : ?>
                            <div class="sa-pagination" style="margin-top:2rem;text-align:center;">
                                <?php
                                echo paginate_links([
                                    'total'   => $products->max_num_pages,
                                    'current' => max(1, get_query_var('paged')),
                                    'type'    => 'list',
                                ]);
                                ?>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="sa-catalog__empty" style="text-align:center;padding:3rem;">
                            <i class="fa-solid fa-gem" style="font-size:3rem;color:var(--sa-gray-300);margin-bottom:1rem;"></i>
                            <h2 style="font-size:1.25rem;margin-bottom:0.5rem;">Изделия не найдены</h2>
                            <p style="color:var(--sa-gray-600);margin-bottom:1.5rem;">Попробуйте изменить фильтры или <a href="<?php echo esc_url(get_post_type_archive_link('sa_product')); ?>">сбросить их</a></p>
                            <a href="#quiz-section" class="sa-btn sa-btn--primary">Рассчитать стоимость</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php get_template_part('template-parts/cta-form'); ?>
</main>

<?php get_footer(); ?>
