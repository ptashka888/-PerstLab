<?php
/**
 * Template Name: Страница категории изделий
 * Template Post Type: page
 *
 * Category landing page: Столешницы, Лестницы, Камины, etc.
 *
 * @package StoneArt
 */
get_header();

$page_title  = get_the_title();
$page_id     = get_the_ID();
$intro_text  = function_exists('get_field') ? get_field('sa_cat_intro') : '';
$product_cat = function_exists('get_field') ? get_field('sa_cat_product_cat') : '';
$faq_cat_name= function_exists('get_field') ? get_field('sa_cat_faq_cat') : '';

// Determine category from page slug if ACF not set
if (!$product_cat) {
    $product_cat = basename(get_permalink());
}

// Map page slugs to taxonomy term names
$slug_to_term = [
    'stoleshnitsy'      => 'Столешницы',
    'lestnitsy'         => 'Лестницы и ступени',
    'kaminy'            => 'Камины',
    'poly-i-oblitsovka' => 'Полы и облицовка',
    'rakoviny'          => 'Раковины и мойки',
    'vanny'             => 'Ванны из камня',
    'fasady'            => 'Фасады',
    'malye-formy'       => 'Малые архитектурные формы',
    'pamyatniki'        => 'Памятники',
];
$cat_term_name = $slug_to_term[$product_cat] ?? $page_title;
?>

<div class="sa-page-header">
    <div class="sa-container">
        <?php sa_breadcrumbs(); ?>
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p class="sa-page-header__subtitle">Производство и установка · Москва и вся Россия · Гарантия 10 лет</p>
    </div>
</div>

<main class="sa-main">

    <!-- Intro text -->
    <?php if ($intro_text || get_the_content()) : ?>
    <section class="sa-section sa-section--white">
        <div class="sa-container">
            <div class="sa-prose">
                <?php
                if ($intro_text) {
                    echo wp_kses_post($intro_text);
                } else {
                    the_content();
                }
                ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Product grid with filters -->
    <section class="sa-section sa-section--light">
        <div class="sa-container">
            <h2 class="sa-section__title">Каталог: <?php echo esc_html($page_title); ?></h2>

            <!-- Filter by material -->
            <?php
            $materials = get_terms(['taxonomy' => 'sa_material', 'hide_empty' => true]);
            if ($materials && !is_wp_error($materials)) : ?>
                <div class="sa-cat-filters" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:2rem;">
                    <button class="sa-cat-filter-btn active" data-filter="all">Все материалы</button>
                    <?php foreach ($materials as $mat) : ?>
                        <button class="sa-cat-filter-btn" data-filter="<?php echo esc_attr($mat->slug); ?>">
                            <?php echo esc_html($mat->name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php
            $products_query = new WP_Query([
                'post_type'      => 'sa_product',
                'posts_per_page' => 12,
                'tax_query'      => [[
                    'taxonomy' => 'sa_product_cat',
                    'field'    => 'name',
                    'terms'    => $cat_term_name,
                ]],
            ]);

            if ($products_query->have_posts()) : ?>
                <div class="sa-catalog__grid sa-product-grid">
                    <?php while ($products_query->have_posts()) : $products_query->the_post();
                        $mats = get_the_terms(get_the_ID(), 'sa_material');
                        $mat_slugs = $mats ? implode(' ', wp_list_pluck($mats, 'slug')) : '';
                        $price_from = function_exists('get_field') ? get_field('sa_product_price_from') : '';
                        $is_hit = function_exists('get_field') ? get_field('sa_product_is_hit') : false;
                        ?>
                        <article class="sa-card sa-product-card" data-material="<?php echo esc_attr($mat_slugs); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="sa-card__img-wrap">
                                    <?php the_post_thumbnail('sa-card', ['class' => 'sa-card__image', 'loading' => 'lazy']); ?>
                                    <?php if ($is_hit) : ?>
                                        <span class="sa-card__badge">Хит</span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                            <div class="sa-card__body">
                                <?php if ($mats) : ?>
                                    <div class="sa-card__meta"><?php echo esc_html($mats[0]->name); ?></div>
                                <?php endif; ?>
                                <h3 class="sa-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <p class="sa-card__text"><?php the_excerpt(); ?></p>
                                <?php if ($price_from) : ?>
                                    <div class="sa-card__price">от <?php echo number_format((int)$price_from, 0, '.', ' '); ?> ₽/м²</div>
                                <?php endif; ?>
                                <div style="display:flex;gap:0.5rem;margin-top:1rem;">
                                    <a href="<?php the_permalink(); ?>" class="sa-btn sa-btn--outline sa-btn--sm">Подробнее</a>
                                    <a href="<?php echo esc_url(get_page_by_path('calculator') ? get_permalink(get_page_by_path('calculator')) : '#quiz-section'); ?>" class="sa-btn sa-btn--primary sa-btn--sm">Рассчитать</a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <?php
                // Pagination
                $total = $products_query->max_num_pages;
                if ($total > 1) {
                    echo '<div class="sa-pagination" style="margin-top:2rem;text-align:center;">';
                    echo paginate_links(['total' => $total, 'type' => 'list']);
                    echo '</div>';
                }
            else : ?>
                <div class="sa-catalog__empty">
                    <p>Изделия в этой категории скоро появятся. <a href="<?php echo esc_url(get_page_by_path('contacts') ? get_permalink(get_page_by_path('contacts')) : '#'); ?>">Свяжитесь с нами</a> для индивидуального расчёта.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Subcategories (child types) -->
    <?php
    $parent_type_term = get_term_by('name', $cat_term_name, 'sa_product_type');
    if ($parent_type_term) :
        $child_types = get_terms(['taxonomy' => 'sa_product_type', 'parent' => $parent_type_term->term_id, 'hide_empty' => false]);
        if ($child_types && !is_wp_error($child_types)) : ?>
            <section class="sa-section sa-section--white">
                <div class="sa-container">
                    <h2 class="sa-section__title">Подразделы</h2>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;margin-top:1.5rem;">
                        <?php foreach ($child_types as $child) : ?>
                            <div class="sa-card" style="padding:1.25rem;background:var(--sa-gray-50);border:1px solid var(--sa-gray-200);">
                                <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.5rem;">
                                    <?php echo esc_html($child->name); ?>
                                </h3>
                                <p style="font-size:0.875rem;color:var(--sa-gray-600);margin-bottom:1rem;">
                                    <?php echo (int)$child->count; ?> изделий
                                </p>
                                <a href="<?php echo esc_url(get_term_link($child)); ?>" class="sa-btn sa-btn--sm sa-btn--outline">Смотреть →</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif;
    endif; ?>

    <!-- Process steps -->
    <?php get_template_part('template-parts/process'); ?>

    <!-- FAQ for this category -->
    <?php if ($faq_cat_name) : get_template_part('template-parts/faq-block', null, ['cat_name' => $faq_cat_name]); endif; ?>

    <!-- CTA form -->
    <?php get_template_part('template-parts/cta-form'); ?>

    <!-- Reviews -->
    <?php get_template_part('template-parts/reviews'); ?>

</main>

<?php get_footer(); ?>
