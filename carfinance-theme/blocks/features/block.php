<?php
/**
 * Block: Features / Counters
 * Counters section (e.g., "2500+ авто доставлено", "8 лет на рынке").
 *
 * @param array $args {
 *     @type string $variant  'counters'|'icons'
 *     @type array  $items    [ ['value'=>..., 'label'=>..., 'suffix'=>...], ... ]
 * }
 */

defined('ABSPATH') || exit;

$variant = $args['variant'] ?? 'counters';
$items   = $args['items'] ?? [];

// Fallback: ACF repeater from current page
if (empty($items)) {
    $acf_items = cf_get_field('cf_counters', get_the_ID());
    if (is_array($acf_items)) {
        foreach ($acf_items as $row) {
            $items[] = [
                'value'  => $row['value'] ?? '',
                'label'  => $row['label'] ?? '',
                'suffix' => $row['suffix'] ?? '',
            ];
        }
    }
}

// Default items if still empty
if (empty($items)) {
    $items = [
        ['value' => '3100', 'suffix' => '+', 'label' => 'Авто доставлено', 'icon' => '🚗'],
        ['value' => '8',    'suffix' => '',  'label' => 'Лет на рынке',    'icon' => '📅'],
        ['value' => '95',   'suffix' => '%', 'label' => 'Рекомендуют нас', 'icon' => '⭐'],
        ['value' => '28',   'suffix' => '',  'label' => 'Сотрудников',     'icon' => '👥'],
        ['value' => '4',    'suffix' => '',  'label' => 'Офиса в России',  'icon' => '📍'],
        ['value' => '14',   'suffix' => '',  'label' => 'Дней до получения', 'icon' => '⚡'],
    ];
}

$section_class = 'cf-features';
if ($variant === 'icons') {
    $section_class .= ' cf-features--icons';
}
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="cf-container">
        <div class="cf-features__grid">
            <?php foreach ($items as $item):
                $value  = $item['value'] ?? '';
                $suffix = $item['suffix'] ?? '';
                $label  = $item['label'] ?? '';
                $icon   = $item['icon'] ?? '';
            ?>
                <div class="cf-features__item">
                    <?php if ($icon): ?>
                        <div class="cf-features__icon"><?php echo esc_html($icon); ?></div>
                    <?php endif; ?>
                    <div class="cf-features__value" data-target="<?php echo esc_attr($value); ?>">
                        0<?php echo esc_html($suffix); ?>
                    </div>
                    <div class="cf-features__label"><?php echo esc_html($label); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
