<?php
/**
 * Template Part: PDF Catalog / Email Collection
 *
 * @package StoneArt
 */

$title = sa_option('sa_pdf_title', 'Получите полный каталог и прайс-лист');
$features = [
    sa_option('sa_pdf_feature1', 'Более 200 видов камня (Мрамор, Кварц, Акрил)'),
    sa_option('sa_pdf_feature2', 'Подробные цены на обработку кромок и вырезов'),
    sa_option('sa_pdf_feature3', 'Секретная скидка 5% за скачивание'),
];
?>

<section class="sa-section sa-section--accent sa-animate">
    <div class="sa-pdf__skew"></div>
    <div class="sa-container" style="position:relative;z-index:10;">
        <div class="sa-pdf__inner">
            <div class="sa-pdf__content">
                <h2 class="sa-pdf__title sa-font-serif"><?php echo esc_html($title); ?></h2>
                <?php foreach ($features as $feat) : ?>
                    <p class="sa-pdf__feature"><i class="fa-solid fa-check"></i> <?php echo esc_html($feat); ?></p>
                <?php endforeach; ?>
            </div>
            <div class="sa-pdf__form-wrap">
                <form id="pdf-form" class="sa-pdf__form">
                    <input type="email" name="email" placeholder="Введите ваш E-mail" required class="sa-pdf__input">
                    <button type="submit" class="sa-btn sa-btn--primary sa-btn--xl">
                        Скачать PDF-каталог
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
