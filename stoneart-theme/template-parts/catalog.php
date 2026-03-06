<?php
/**
 * Template Part: Product Catalog
 *
 * @package StoneArt
 */

$products = sa_option('sa_catalog_products');
if (!$products) {
    $products = [
        [
            'sa_prod_title'   => 'Каменные столешницы',
            'sa_prod_desc'    => 'Идеальное решение для вашей кухни. Столешницы из кварцевого агломерата или гранита не царапаются ножом, устойчивы к температурам и агрессивной химии.',
            'sa_prod_features' => "Прямые, угловые и с кухонным островом\nС интегрированной каменной мойкой\nИз кварца, акрила, гранита, керамики",
            'sa_prod_image'   => '',
            'sa_prod_layout'  => 'normal',
        ],
        [
            'sa_prod_title'   => 'Подоконники и откосы',
            'sa_prod_desc'    => 'Долговечные подоконники из акрила или мрамора. Не желтеют на солнце, не деформируются от батарей, не боятся влаги от цветов.',
            'sa_prod_features' => "Классические, эркерные, радиусные\nПодоконник, переходящий в столешницу\nКомплексная облицовка откосов",
            'sa_prod_image'   => '',
            'sa_prod_layout'  => 'reverse',
        ],
    ];
}

$default_featured_images = [
    'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800',
    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=800',
];

$extra_products = sa_option('sa_catalog_extra');
if (!$extra_products) {
    $extra_products = [
        [
            'sa_extra_title' => 'Лестницы и ступени',
            'sa_extra_desc'  => 'Облицовка бетонных и металлических лестниц гранитом и мрамором.',
            'sa_extra_image' => '',
        ],
        [
            'sa_extra_title' => 'Мебель для ванных',
            'sa_extra_desc'  => 'Столешницы под раковину, душевые поддоны, облицовка стен.',
            'sa_extra_image' => '',
        ],
        [
            'sa_extra_title' => 'Камины и порталы',
            'sa_extra_desc'  => 'Роскошная облицовка каминов слэбами мрамора (Bookmatch) и ониксом.',
            'sa_extra_image' => '',
        ],
    ];
}

$default_extra_images = [
    'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=400',
    'https://images.unsplash.com/photo-1620626011761-996317b8d101?w=400',
    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=400',
];

$calc = get_page_by_path('calculator');
$cta_url = $calc ? get_permalink($calc) : '#quiz-section';
?>

<section id="catalog" class="sa-section sa-section--gray sa-animate">
    <div class="sa-container">
        <h2 class="sa-section__title" style="font-size:2.5rem;margin-bottom:4rem;">Каталог изделий</h2>

        <?php foreach ($products as $i => $prod) :
            $img = !empty($prod['sa_prod_image']['url']) ? $prod['sa_prod_image']['url'] : ($default_featured_images[$i] ?? '');
            $reverse = ($prod['sa_prod_layout'] ?? '') === 'reverse';
            $features = explode("\n", $prod['sa_prod_features'] ?? '');
        ?>
            <div class="sa-catalog__featured<?php echo $reverse ? ' sa-catalog__featured--reverse' : ''; ?>">
                <img src="<?php echo esc_url($img); ?>"
                     alt="<?php echo esc_attr($prod['sa_prod_title'] ?? ''); ?>"
                     class="sa-catalog__featured-image"
                     loading="lazy">
                <div class="sa-catalog__featured-content">
                    <h3 class="sa-catalog__featured-title sa-font-serif"><?php echo esc_html($prod['sa_prod_title'] ?? ''); ?></h3>
                    <p class="sa-catalog__featured-text"><?php echo esc_html($prod['sa_prod_desc'] ?? ''); ?></p>
                    <ul class="sa-catalog__featured-list">
                        <?php foreach ($features as $feat) :
                            $feat = trim($feat);
                            if ($feat) : ?>
                                <li><i class="fa-solid fa-check"></i> <?php echo esc_html($feat); ?></li>
                            <?php endif;
                        endforeach; ?>
                    </ul>
                    <a href="<?php echo esc_url($cta_url); ?>" class="sa-btn sa-btn--primary">Заказать расчет</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="sa-catalog__grid">
            <?php foreach ($extra_products as $i => $extra) :
                $img = !empty($extra['sa_extra_image']['url']) ? $extra['sa_extra_image']['url'] : ($default_extra_images[$i] ?? '');
            ?>
                <div class="sa-card">
                    <img src="<?php echo esc_url($img); ?>"
                         alt="<?php echo esc_attr($extra['sa_extra_title'] ?? ''); ?>"
                         class="sa-card__image"
                         loading="lazy">
                    <h4 class="sa-card__title"><?php echo esc_html($extra['sa_extra_title'] ?? ''); ?></h4>
                    <p class="sa-card__text"><?php echo esc_html($extra['sa_extra_desc'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
