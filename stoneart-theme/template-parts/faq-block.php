<?php
/**
 * FAQ Block Template Part
 *
 * Usage: get_template_part('template-parts/faq-block', null, ['cat_name' => 'О материалах']);
 *
 * @package StoneArt
 */

$cat_name = $args['cat_name'] ?? '';
$limit    = $args['limit']    ?? 7;

$query_args = [
    'post_type'      => 'sa_faq',
    'posts_per_page' => $limit,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
];

if ($cat_name) {
    $query_args['tax_query'] = [[
        'taxonomy' => 'sa_faq_cat',
        'field'    => 'name',
        'terms'    => $cat_name,
    ]];
}

$faqs = new WP_Query($query_args);

if (!$faqs->have_posts()) {
    wp_reset_postdata();
    return;
}

// Schema data
$faq_schema_items = [];
?>

<section class="sa-section sa-section--white" id="faq">
    <div class="sa-container">
        <h2 class="sa-section__title">
            <?php echo $cat_name ? 'Вопросы: ' . esc_html($cat_name) : 'Часто задаваемые вопросы'; ?>
        </h2>

        <div class="sa-faq" style="max-width:780px;margin:2rem auto 0;">
            <?php $i = 0; while ($faqs->have_posts()) : $faqs->the_post(); $i++; ?>
                <?php
                $q = get_the_title();
                $a = get_the_content();
                $faq_schema_items[] = [
                    '@type' => 'Question',
                    'name'  => $q,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => wp_strip_all_tags($a)],
                ];
                ?>
                <div class="sa-faq__item">
                    <button class="sa-faq__question" aria-expanded="false" aria-controls="faq-block-<?php echo $i; ?>">
                        <span><?php echo esc_html($q); ?></span>
                        <i class="fa-solid fa-chevron-down sa-faq__chevron"></i>
                    </button>
                    <div class="sa-faq__answer" id="faq-block-<?php echo $i; ?>" hidden>
                        <div class="sa-prose" style="padding:1rem 1.25rem 1.25rem;">
                            <?php echo wp_kses_post($a); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php
        $faq_page = get_page_by_path('faq-page');
        if ($faq_page) : ?>
            <div style="text-align:center;margin-top:2rem;">
                <a href="<?php echo esc_url(get_permalink($faq_page)); ?>" class="sa-btn sa-btn--outline">
                    Все вопросы и ответы →
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($faq_schema_items) :
    $schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $faq_schema_items,
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
endif; ?>
