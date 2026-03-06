<?php
/**
 * Template Part: Material Visualizer
 *
 * @package StoneArt
 */

$materials = sa_option('sa_materials_list');
if (!$materials) {
    $materials = [
        [
            'sa_mat_key'    => 'quartz',
            'sa_mat_title'  => 'Кварцевый агломерат',
            'sa_mat_desc'   => 'Идеальный выбор для кухонных столешниц. Абсолютно не впитывает влагу, не боится пятен вина и кофе.',
            'sa_mat_short'  => 'Практичность 10/10. Бренды: Caesarstone, Avant.',
            'sa_mat_badge1' => 'Хит для кухни',
            'sa_mat_badge2' => 'Avant / Caesarstone',
            'sa_mat_image'  => '',
        ],
        [
            'sa_mat_key'    => 'granite',
            'sa_mat_title'  => 'Натуральный гранит',
            'sa_mat_desc'   => 'Максимальная прочность и термостойкость до 800°C. Подходит для интенсивного использования и уличных зон.',
            'sa_mat_short'  => 'Максимальная прочность. Можно ставить горячую посуду.',
            'sa_mat_badge1' => 'Абсолютная прочность',
            'sa_mat_badge2' => '',
            'sa_mat_image'  => '',
        ],
        [
            'sa_mat_key'    => 'marble',
            'sa_mat_title'  => 'Натуральный мрамор',
            'sa_mat_desc'   => 'Премиальная эстетика с неповторимым природным рисунком. Идеален для ванных комнат, полов и облицовки каминов.',
            'sa_mat_short'  => 'Премиальная эстетика (Calacatta, Emperador). Для ванных.',
            'sa_mat_badge1' => 'Премиум статус',
            'sa_mat_badge2' => 'Calacatta',
            'sa_mat_image'  => '',
        ],
        [
            'sa_mat_key'    => 'acrylic',
            'sa_mat_title'  => 'Акриловый камень',
            'sa_mat_desc'   => 'Создание изделий любой сложной формы без видимых швов. Теплый на ощупь, легко реставрируется.',
            'sa_mat_short'  => 'Бесшовное соединение, любые формы. Бренд: Grandex.',
            'sa_mat_badge1' => 'Без швов',
            'sa_mat_badge2' => 'Grandex',
            'sa_mat_image'  => '',
        ],
    ];
}

$default_images = [
    'quartz'  => 'https://images.unsplash.com/photo-1600607688969-a5bfcd646154?auto=format&fit=crop&w=1000&q=80',
    'granite' => 'https://images.unsplash.com/photo-1554162985-1d37e6bdf20a?auto=format&fit=crop&w=1000&q=80',
    'marble'  => 'https://images.unsplash.com/photo-1598387181032-a3103a2db5b3?auto=format&fit=crop&w=1000&q=80',
    'acrylic' => 'https://images.unsplash.com/photo-1584622781564-1d987f7333c1?auto=format&fit=crop&w=1000&q=80',
];

$section_title = sa_option('sa_visualizer_title', 'Выберите свой идеальный материал');
?>

<section id="materials-interactive" class="sa-section sa-section--white sa-animate">
    <div class="sa-container">
        <h2 class="sa-section__title"><?php echo esc_html($section_title); ?></h2>

        <div class="sa-visualizer">
            <div class="sa-visualizer__image-wrap">
                <?php
                $first = $materials[0] ?? [];
                $first_key = $first['sa_mat_key'] ?? 'quartz';
                $first_img = !empty($first['sa_mat_image']['url']) ? $first['sa_mat_image']['url'] : ($default_images[$first_key] ?? '');
                ?>
                <img id="visualizer-img" src="<?php echo esc_url($first_img); ?>"
                     class="sa-visualizer__image"
                     alt="<?php echo esc_attr($first['sa_mat_title'] ?? 'Материал'); ?>"
                     loading="lazy">
                <div class="sa-visualizer__image-overlay"></div>
                <div class="sa-visualizer__image-info">
                    <div class="sa-visualizer__badges" id="visualizer-badges">
                        <?php if (!empty($first['sa_mat_badge1'])) : ?>
                            <span class="sa-visualizer__badge sa-visualizer__badge--primary"><?php echo esc_html($first['sa_mat_badge1']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($first['sa_mat_badge2'])) : ?>
                            <span class="sa-visualizer__badge sa-visualizer__badge--dark"><?php echo esc_html($first['sa_mat_badge2']); ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 id="visualizer-title" class="sa-visualizer__image-title"><?php echo esc_html($first['sa_mat_title'] ?? ''); ?></h3>
                    <p id="visualizer-desc" class="sa-visualizer__image-desc"><?php echo esc_html($first['sa_mat_desc'] ?? ''); ?></p>
                </div>
            </div>

            <div class="sa-visualizer__controls">
                <p class="sa-visualizer__controls-label">Категории камня</p>
                <div>
                    <?php foreach ($materials as $i => $mat) :
                        $key = $mat['sa_mat_key'] ?? 'mat_' . $i;
                        $img_url = !empty($mat['sa_mat_image']['url']) ? $mat['sa_mat_image']['url'] : ($default_images[$key] ?? '');
                    ?>
                        <button class="sa-visualizer__btn<?php echo $i === 0 ? ' active' : ''; ?>"
                                data-mat="<?php echo esc_attr($key); ?>"
                                data-img="<?php echo esc_url($img_url); ?>"
                                data-title="<?php echo esc_attr($mat['sa_mat_title'] ?? ''); ?>"
                                data-desc="<?php echo esc_attr($mat['sa_mat_desc'] ?? ''); ?>"
                                data-badge1="<?php echo esc_attr($mat['sa_mat_badge1'] ?? ''); ?>"
                                data-badge2="<?php echo esc_attr($mat['sa_mat_badge2'] ?? ''); ?>">
                            <div class="sa-visualizer__btn-title"><?php echo esc_html($mat['sa_mat_title'] ?? ''); ?></div>
                            <div class="sa-visualizer__btn-desc"><?php echo esc_html($mat['sa_mat_short'] ?? ''); ?></div>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
