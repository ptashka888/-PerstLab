<?php get_header(); the_post(); ?>

<?php
$price_from  = kv_field('kv_price_from');
$price_to    = kv_field('kv_price_to');
$metal       = kv_field('kv_metal');
$coating     = kv_field('kv_coating');
$lead_time   = kv_field('kv_lead_time') ?: '14–21 день';
$warranty    = kv_field('kv_warranty') ?: '25 лет';
$weight      = kv_field('kv_weight');
$section     = kv_field('kv_section');
$installable = kv_field('kv_installable');
$gallery     = kv_field('kv_gallery');
$product_faq = kv_field('kv_product_faq');
$popular     = kv_field('kv_popular');
$main_img    = get_the_post_thumbnail_url(null, 'kv-wide');
$terms_cat   = get_the_terms(null, 'kv_category');
$terms_mat   = get_the_terms(null, 'kv_material');
?>

<section class="kv-page-hero" style="padding:40px 0">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
    </div>
</section>

<section class="kv-section" style="padding-top:40px">
    <div class="kv-container">
        <div class="kv-grid-2" style="gap:60px;align-items:start">

            <!-- Галерея -->
            <div>
                <?php if ($gallery && is_array($gallery)) : ?>
                <div class="kv-product-gallery">
                    <div class="kv-product-thumbs" id="kv-thumbs">
                        <?php foreach ($gallery as $i => $img_item) :
                            $url = is_array($img_item) ? $img_item['url'] : $img_item; ?>
                        <div class="kv-product-thumb <?= $i === 0 ? 'active' : '' ?>" data-src="<?= esc_url($url) ?>">
                            <img src="<?= esc_url($url) ?>" alt="Фото <?= $i+1 ?>" loading="lazy">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="kv-product-main">
                        <img src="<?= esc_url(is_array($gallery[0]) ? $gallery[0]['url'] : $gallery[0]) ?>" alt="<?php the_title_attribute(); ?>" id="kv-main-img" loading="lazy">
                    </div>
                </div>
                <?php elseif ($main_img) : ?>
                <div class="kv-product-main" style="border-radius:12px;overflow:hidden">
                    <img src="<?= esc_url($main_img) ?>" alt="<?php the_title_attribute(); ?>">
                </div>
                <?php else : ?>
                <div style="aspect-ratio:4/3;background:linear-gradient(135deg,#2D1A00,#1C0F00);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:5rem">⚒️</div>
                <?php endif; ?>

                <!-- Теги -->
                <div class="kv-badge-row kv-mt-16">
                    <?php if ($popular) : ?><span class="kv-tag kv-tag--accent">⭐ Хит продаж</span><?php endif; ?>
                    <?php if ($terms_cat) : foreach ($terms_cat as $t) : ?><span class="kv-tag"><?= esc_html($t->name) ?></span><?php endforeach; endif; ?>
                    <?php if ($terms_mat) : foreach ($terms_mat as $t) : ?><span class="kv-tag"><?= esc_html($t->name) ?></span><?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Инфо -->
            <div>
                <?php if ($terms_cat) : ?>
                <div class="kv-card__cat"><?= esc_html($terms_cat[0]->name) ?></div>
                <?php endif; ?>
                <h1 style="margin-bottom:12px"><?php the_title(); ?></h1>

                <!-- Цена -->
                <div style="margin:20px 0;padding:20px;background:var(--kv-bg);border-radius:12px">
                    <div style="font-size:.8rem;color:var(--kv-text-muted);margin-bottom:4px">Стоимость</div>
                    <?php if ($price_from) : ?>
                    <div style="font-family:var(--kv-font-head);font-size:2rem;font-weight:800;color:var(--kv-accent)">
                        от <?= number_format((int)$price_from, 0, '.', '&nbsp;') ?>&nbsp;₽
                        <?php if ($price_to) echo '<span style="font-size:1.2rem;color:var(--kv-text-muted)"> — ' . number_format((int)$price_to, 0, '.', '&nbsp;') . '&nbsp;₽</span>'; ?>
                    </div>
                    <div style="font-size:.8rem;color:var(--kv-text-muted);margin-top:4px">Точная цена после бесплатного замера</div>
                    <?php else : ?>
                    <div style="font-size:1.4rem;font-weight:700;color:var(--kv-accent)">По запросу</div>
                    <?php endif; ?>
                </div>

                <!-- Описание -->
                <div style="color:var(--kv-text-muted);line-height:1.7;margin-bottom:24px">
                    <?php the_excerpt(); ?>
                </div>

                <!-- Характеристики -->
                <div class="kv-product-specs">
                    <?php if ($metal) : ?>
                    <div class="kv-spec-item"><div class="kv-spec-item__label">Материал</div><div class="kv-spec-item__val"><?= esc_html($metal) ?></div></div>
                    <?php endif; ?>
                    <?php if ($coating) : ?>
                    <div class="kv-spec-item"><div class="kv-spec-item__label">Покрытие</div><div class="kv-spec-item__val"><?= esc_html($coating) ?></div></div>
                    <?php endif; ?>
                    <div class="kv-spec-item"><div class="kv-spec-item__label">Срок изготовления</div><div class="kv-spec-item__val"><?= esc_html($lead_time) ?></div></div>
                    <div class="kv-spec-item"><div class="kv-spec-item__label">Гарантия</div><div class="kv-spec-item__val"><?= esc_html($warranty) ?></div></div>
                    <?php if ($weight) : ?>
                    <div class="kv-spec-item"><div class="kv-spec-item__label">Масса</div><div class="kv-spec-item__val"><?= esc_html($weight) ?> кг</div></div>
                    <?php endif; ?>
                    <?php if ($section) : ?>
                    <div class="kv-spec-item"><div class="kv-spec-item__label">Сечение прутка</div><div class="kv-spec-item__val"><?= esc_html($section) ?> мм</div></div>
                    <?php endif; ?>
                    <div class="kv-spec-item"><div class="kv-spec-item__label">Монтаж</div><div class="kv-spec-item__val"><?= $installable ? 'Включён в стоимость' : 'Рассчитывается отдельно' ?></div></div>
                    <div class="kv-spec-item"><div class="kv-spec-item__label">Размеры</div><div class="kv-spec-item__val">По вашему заказу</div></div>
                </div>

                <!-- CTA -->
                <div class="kv-btn-group">
                    <a href="#kv-modal" class="kv-btn kv-btn--primary kv-btn--lg kv-modal-open" data-product="<?php the_title_attribute(); ?>">
                        Заказать расчёт бесплатно
                    </a>
                    <?php $phone = get_theme_mod('kv_phone', '+7 (800) 555-00-00'); ?>
                    <a href="tel:<?= preg_replace('/\D/', '', $phone) ?>" class="kv-btn kv-btn--secondary kv-btn--lg">
                        📞 Позвонить
                    </a>
                </div>

                <!-- Мини-трасты -->
                <div style="margin-top:20px;padding:16px;background:rgba(230,81,0,.04);border:1px solid rgba(230,81,0,.15);border-radius:8px;font-size:.82rem;color:var(--kv-text-muted)">
                    ✓ Бесплатный выезд замерщика &nbsp;·&nbsp; ✓ Договор + гарантийный талон &nbsp;·&nbsp; ✓ Монтаж под ключ
                </div>
            </div>
        </div>

        <!-- Полное описание -->
        <?php if (get_the_content()) : ?>
        <div style="margin-top:64px;padding-top:64px;border-top:1px solid var(--kv-border)">
            <h2 style="margin-bottom:24px">Подробное описание</h2>
            <div class="kv-content" style="max-width:760px">
                <?php the_content(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- FAQ по изделию -->
        <?php if ($product_faq && is_array($product_faq)) : ?>
        <div style="margin-top:64px;padding-top:64px;border-top:1px solid var(--kv-border)">
            <h2 style="margin-bottom:32px">Вопросы и ответы</h2>
            <?php foreach ($product_faq as $faq) : ?>
            <div class="kv-faq-item">
                <div class="kv-faq-question" tabindex="0"><span><?= esc_html($faq['question']) ?></span><div class="kv-faq-icon">+</div></div>
                <div class="kv-faq-answer"><?= esc_html($faq['answer']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Похожие товары -->
        <div style="margin-top:64px;padding-top:64px;border-top:1px solid var(--kv-border)">
            <h2 style="margin-bottom:32px">Похожие изделия</h2>
            <div class="kv-grid-4">
                <?php
                $related_args = [
                    'post_type'      => 'kv_product',
                    'posts_per_page' => 4,
                    'post__not_in'   => [get_the_ID()],
                    'post_status'    => 'publish',
                ];
                if ($terms_cat) {
                    $related_args['tax_query'] = [['taxonomy' => 'kv_category', 'field' => 'term_id', 'terms' => wp_list_pluck($terms_cat, 'term_id')]];
                }
                $related = get_posts($related_args);
                foreach ($related as $rel) :
                    $rel_img   = get_the_post_thumbnail_url($rel->ID, 'kv-card');
                    $rel_price = kv_field('kv_price_from', $rel->ID);
                ?>
                <a href="<?= get_permalink($rel->ID) ?>" class="kv-card" style="text-decoration:none">
                    <?php if ($rel_img) : ?>
                    <img class="kv-card__img" src="<?= esc_url($rel_img) ?>" alt="<?= esc_attr($rel->post_title) ?>" loading="lazy">
                    <?php endif; ?>
                    <div class="kv-card__body">
                        <h3 class="kv-card__title" style="font-size:.9rem"><?= esc_html($rel->post_title) ?></h3>
                        <div class="kv-card__price"><?= $rel_price ? 'от ' . number_format((int)$rel_price, 0, '.', '&nbsp;') . '&nbsp;₽' : 'По запросу' ?></div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
// Переключение галереи
document.querySelectorAll('.kv-product-thumb').forEach(function(thumb) {
    thumb.addEventListener('click', function() {
        document.querySelectorAll('.kv-product-thumb').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('kv-main-img').src = this.dataset.src;
    });
});
</script>

<?php get_footer(); ?>
