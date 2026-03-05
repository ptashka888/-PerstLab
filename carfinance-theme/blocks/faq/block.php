<?php
defined('ABSPATH') || exit;

$source = $args['source'] ?? '';
$items  = $args['items'] ?? [];

if (!empty($source) && function_exists('cf_get_faq_items')) {
    $items = cf_get_faq_items($source);
}

if (empty($items) || !is_array($items)) {
    $items = [];
}

// Output FAQPage schema
if (!empty($items) && function_exists('cf_schema_faqpage')) {
    cf_schema_faqpage($items);
}
?>
<?php if (!empty($items)) : ?>
<section class="cf-faq">
    <div class="cf-faq__container">
        <h2 class="cf-faq__title">Часто задаваемые вопросы</h2>

        <div class="cf-faq__list">
            <?php foreach ($items as $index => $item) : ?>
                <div class="cf-faq__item" id="cf-faq-item-<?php echo $index; ?>">
                    <button
                        class="cf-faq__question"
                        type="button"
                        aria-expanded="false"
                        aria-controls="cf-faq-answer-<?php echo $index; ?>"
                    >
                        <span class="cf-faq__question-text"><?php echo esc_html($item['question']); ?></span>
                        <span class="cf-faq__indicator" aria-hidden="true"></span>
                    </button>
                    <div
                        class="cf-faq__answer"
                        id="cf-faq-answer-<?php echo $index; ?>"
                        role="region"
                    >
                        <div class="cf-faq__answer-inner">
                            <?php echo wp_kses_post($item['answer']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
