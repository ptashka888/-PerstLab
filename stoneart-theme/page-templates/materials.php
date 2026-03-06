<?php
/**
 * Template Name: Материалы
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p style="color:var(--sa-gray-400);margin-top:0.5rem;">Кварцевый агломерат, натуральный камень, акрил и керамика</p>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<!-- Interactive Visualizer -->
<?php get_template_part('template-parts/visualizer'); ?>

<!-- Comparison Table -->
<?php get_template_part('template-parts/comparison'); ?>

<!-- Detailed Material Sections -->
<section class="sa-section sa-section--white">
    <div class="sa-container">
        <h2 class="sa-section__title">Подробнее о каждом материале</h2>
        <div style="margin-top:3rem;">
            <?php
            $material_details = [
                [
                    'title'    => 'Кварцевый агломерат',
                    'subtitle' => 'Avant Quartz, Caesarstone, Etna Quartz',
                    'desc'     => 'Кварцевый агломерат — это композитный материал, состоящий на 93% из природного кварца. Непористая структура делает его абсолютно устойчивым к пятнам, влаге и бактериям. Идеальный выбор для кухонных столешниц.',
                    'features' => ['Водопоглощение: 0.02%', 'Твердость по Моосу: 7', 'Термостойкость: до 150°C', 'Срок службы: 25+ лет'],
                    'image'    => 'https://images.unsplash.com/photo-1600607688969-a5bfcd646154?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'title'    => 'Натуральный гранит',
                    'subtitle' => 'Absolute Black, Star Galaxy, Baltic Brown',
                    'desc'     => 'Гранит — одна из самых твердых пород на планете. Выдерживает экстремальные температуры (до 800°C), не царапается стальными ножами. Подходит для наружных работ, ступеней и интенсивно используемых кухонь.',
                    'features' => ['Твердость по Моосу: 6-7', 'Термостойкость: до 800°C', 'Морозостойкость: F200+', 'Не выцветает на солнце'],
                    'image'    => 'https://images.unsplash.com/photo-1554162985-1d37e6bdf20a?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'title'    => 'Натуральный мрамор',
                    'subtitle' => 'Calacatta, Emperador, Statuario',
                    'desc'     => 'Мрамор — воплощение роскоши. Каждый слэб уникален благодаря неповторимому рисунку жил. Идеален для ванных комнат, каминных порталов, полов и декоративных панно. Требует бережного ухода.',
                    'features' => ['Уникальный природный рисунок', 'Полируется до зеркального блеска', 'Идеален для ванных и каминов', 'Требует защитной пропитки'],
                    'image'    => 'https://images.unsplash.com/photo-1598387181032-a3103a2db5b3?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'title'    => 'Акриловый камень',
                    'subtitle' => 'Grandex, Tristone, Staron',
                    'desc'     => 'Акриловый камень позволяет создавать изделия абсолютно любой формы без видимых швов. Теплый на ощупь, гигиеничный, легко реставрируется шлифовкой. Лучший выбор для бесшовных столешниц сложной конфигурации.',
                    'features' => ['Бесшовное соединение', 'Теплый на ощупь', 'Легко реставрируется', 'Любые формы и радиусы'],
                    'image'    => 'https://images.unsplash.com/photo-1584622781564-1d987f7333c1?auto=format&fit=crop&w=800&q=80',
                ],
            ];

            foreach ($material_details as $i => $mat) :
                $reverse = $i % 2 !== 0;
            ?>
                <div class="sa-catalog__featured<?php echo $reverse ? ' sa-catalog__featured--reverse' : ''; ?>" id="material-<?php echo esc_attr(sanitize_title($mat['title'])); ?>">
                    <img src="<?php echo esc_url($mat['image']); ?>" alt="<?php echo esc_attr($mat['title']); ?>" class="sa-catalog__featured-image" loading="lazy">
                    <div class="sa-catalog__featured-content">
                        <h3 class="sa-catalog__featured-title sa-font-serif"><?php echo esc_html($mat['title']); ?></h3>
                        <p style="font-size:0.875rem;color:var(--sa-primary-hover);font-weight:700;margin-bottom:1rem;"><?php echo esc_html($mat['subtitle']); ?></p>
                        <p class="sa-catalog__featured-text"><?php echo esc_html($mat['desc']); ?></p>
                        <ul class="sa-catalog__featured-list">
                            <?php foreach ($mat['features'] as $feat) : ?>
                                <li><i class="fa-solid fa-check"></i> <?php echo esc_html($feat); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php $calc = get_page_by_path('calculator'); ?>
                        <a href="<?php echo $calc ? esc_url(get_permalink($calc)) : '#quiz-section'; ?>" class="sa-btn sa-btn--primary">Рассчитать изделие</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quiz -->
<?php get_template_part('template-parts/quiz'); ?>

<?php get_footer(); ?>
