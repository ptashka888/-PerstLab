<?php
defined('ABSPATH') || exit;

$type = $args['type'] ?? '';
$data = $args['data'] ?? [];

if (! $type || empty($data)) {
    return;
}

if (! isset($data['@context'])) {
    $data['@context'] = 'https://schema.org';
}

if (! isset($data['@type'])) {
    $data['@type'] = $type;
}
?>
<script type="application/ld+json"><?php echo wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
