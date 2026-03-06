<?php
/**
 * Template Part: Care Tips
 *
 * @package StoneArt
 */

$tips = sa_option('sa_care_tips');
if (!$tips) {
    $tips = [
        ['sa_tip_title' => 'Мягкая очистка', 'sa_tip_text' => 'Используйте только ph-нейтральные средства (мыльный раствор).', 'sa_tip_icon' => 'fa-solid fa-droplet', 'sa_tip_color' => 'blue'],
        ['sa_tip_title' => 'Пропитка (Мрамор)', 'sa_tip_text' => 'Натуральный мрамор требует защиты гидрофобизатором 1 раз в год.', 'sa_tip_icon' => 'fa-solid fa-shield', 'sa_tip_color' => 'green'],
        ['sa_tip_title' => 'Нет кислотам', 'sa_tip_text' => 'Берегите мраморные поверхности от лимона, вина и уксуса.', 'sa_tip_icon' => 'fa-solid fa-ban', 'sa_tip_color' => 'red'],
        ['sa_tip_title' => 'Подставки', 'sa_tip_text' => 'Используйте подставки под горячие сковороды на акриле и кварце.', 'sa_tip_icon' => 'fa-solid fa-temperature-arrow-up', 'sa_tip_color' => 'orange'],
    ];
}
?>

<section class="sa-section sa-section--white sa-animate" style="border-bottom:1px solid var(--sa-gray-100);">
    <div class="sa-container">
        <div class="sa-care">
            <div class="sa-care__info">
                <i class="fa-solid fa-hand-sparkles sa-care__icon"></i>
                <h3 class="sa-care__title sa-font-serif"><?php echo esc_html(sa_option('sa_care_title', 'Памятка по уходу')); ?></h3>
                <p class="sa-care__text"><?php echo esc_html(sa_option('sa_care_text', 'Соблюдайте эти простые правила, и ваши изделия прослужат столетия без реставрации.')); ?></p>
            </div>
            <div class="sa-care__grid">
                <?php foreach ($tips as $tip) :
                    $color = $tip['sa_tip_color'] ?? 'blue';
                    $icon  = $tip['sa_tip_icon'] ?? 'fa-solid fa-check';
                ?>
                    <div class="sa-care__item sa-care__item--<?php echo esc_attr($color); ?>">
                        <h4 class="sa-care__item-title"><i class="<?php echo esc_attr($icon); ?>" style="margin-right:0.5rem;"></i> <?php echo esc_html($tip['sa_tip_title'] ?? ''); ?></h4>
                        <p class="sa-care__item-text"><?php echo esc_html($tip['sa_tip_text'] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
