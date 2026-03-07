<?php
/**
 * Template Name: Портфолио работ
 * Template Post Type: page
 */
get_header();
?>

<section class="kv-page-hero">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
        <h1 style="margin-top:16px">Портфолио — наши работы</h1>
        <p class="kv-lead">Более 1 200 реализованных проектов. Каждая работа — уникальное изделие ручной ковки</p>
    </div>
</section>

<!-- Фильтр -->
<div style="background:var(--kv-white);border-bottom:1px solid var(--kv-border);padding:20px 0;position:sticky;top:72px;z-index:100">
    <div class="kv-container">
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <span style="font-size:.82rem;font-weight:600;color:var(--kv-text-muted);margin-right:8px">Фильтр:</span>
            <button class="kv-btn kv-btn--primary kv-btn--sm kv-filter-btn active" data-filter="*">Все работы</button>
            <?php foreach (kv_get_category_data() as $slug => $cat) : ?>
            <button class="kv-btn kv-btn--secondary kv-btn--sm kv-filter-btn" data-filter="<?= esc_attr($slug) ?>">
                <?= $cat['icon'] ?> <?= esc_html($cat['name']) ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<section class="kv-section">
    <div class="kv-container">
        <div class="kv-grid-auto" id="kv-portfolio-grid">
            <?php
            $works = get_posts(['post_type' => 'kv_work', 'posts_per_page' => 24, 'post_status' => 'publish']);
            if ($works) :
                foreach ($works as $work) :
                    $img      = get_the_post_thumbnail_url($work->ID, 'kv-card') ?: get_template_directory_uri() . '/assets/img/placeholder.jpg';
                    $cat_terms= get_the_terms($work->ID, 'kv_category');
                    $cat_slug = $cat_terms ? $cat_terms[0]->slug : '';
                    $cat_name = $cat_terms ? $cat_terms[0]->name : '';
                    $city     = kv_field('kv_work_city', $work->ID);
                    $year     = kv_field('kv_work_year', $work->ID);
                    $budget   = kv_field('kv_work_budget', $work->ID);
            ?>
            <div class="kv-port-item" data-cat="<?= esc_attr($cat_slug) ?>" style="min-height:260px">
                <img src="<?= esc_url($img) ?>" alt="<?= esc_attr($work->post_title) ?>" loading="lazy">
                <div class="kv-port-item__overlay">
                    <div class="kv-port-item__cat"><?= esc_html($cat_name) ?> <?php if ($year) echo '· ' . esc_html($year); ?></div>
                    <div class="kv-port-item__title"><?= esc_html($work->post_title) ?></div>
                    <?php if ($city || $budget) : ?>
                    <div style="color:rgba(255,255,255,.65);font-size:.78rem;margin-top:4px">
                        <?php if ($city) echo esc_html($city); ?>
                        <?php if ($budget) echo ' · ' . number_format((int)$budget, 0, '.', '&nbsp;') . ' ₽'; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata();
            else :
            // Placeholder
            $placeholder_cats = ['vorota', 'zabory', 'lestnitsy', 'mebel', 'dekor', 'art', 'vorota', 'zabory', 'lestnitsy', 'mebel', 'dekor', 'art'];
            $cats = kv_get_category_data();
            for ($i = 0; $i < 12; $i++) :
                $c = $cats[$placeholder_cats[$i]];
            ?>
            <div class="kv-port-item" data-cat="<?= $placeholder_cats[$i] ?>" style="min-height:260px;background:linear-gradient(135deg,#2D1A00,#1C0F00)">
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:4rem;opacity:.2"><?= $c['icon'] ?></div>
                <div class="kv-port-item__overlay" style="opacity:1">
                    <div class="kv-port-item__cat"><?= esc_html($c['name']) ?></div>
                    <div class="kv-port-item__title">Пример работы <?= $i+1 ?></div>
                    <div style="color:rgba(255,255,255,.5);font-size:.75rem">Добавьте работы в WP Admin</div>
                </div>
            </div>
            <?php endfor; endif; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="kv-section--sm">
    <div class="kv-container">
        <div class="kv-cta-banner">
            <div>
                <h2>Хотите такой же проект?</h2>
                <p>Опишите задачу — разработаем дизайн и рассчитаем стоимость бесплатно</p>
            </div>
            <a href="#kv-modal" class="kv-btn kv-btn--white kv-btn--lg kv-modal-open">Заказать проект</a>
        </div>
    </div>
</section>

<style>
.kv-filter-btn.active { background: var(--kv-accent) !important; color: #fff !important; border-color: var(--kv-accent) !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.kv-filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.kv-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('#kv-portfolio-grid .kv-port-item').forEach(function(item) {
                if (filter === '*' || item.dataset.cat === filter) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php get_footer(); ?>
