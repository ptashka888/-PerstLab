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
        ['value' => '2500', 'suffix' => '+', 'label' => 'Авто доставлено'],
        ['value' => '8',    'suffix' => '',  'label' => 'Лет на рынке'],
        ['value' => '98',   'suffix' => '%', 'label' => 'Довольных клиентов'],
        ['value' => '14',   'suffix' => '',  'label' => 'Дней средняя доставка'],
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
            ?>
                <div class="cf-features__item">
                    <div class="cf-features__value" data-target="<?php echo esc_attr($value); ?>">
                        0<?php echo esc_html($suffix); ?>
                    </div>
                    <div class="cf-features__label"><?php echo esc_html($label); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
