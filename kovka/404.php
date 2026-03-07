<?php get_header(); ?>

<section class="kv-section kv-section--lg" style="text-align:center;min-height:70vh;display:flex;align-items:center">
    <div class="kv-container">
        <div style="font-size:8rem;line-height:1;margin-bottom:16px">⚒️</div>
        <div style="font-family:var(--kv-font-head);font-size:6rem;font-weight:800;color:var(--kv-accent);line-height:1;margin-bottom:8px">404</div>
        <h1 style="margin-bottom:12px">Страница не найдена</h1>
        <p class="kv-lead" style="margin-bottom:36px">
            Похоже, эту страницу уже расковали на что-то более полезное.
            Попробуйте найти нужное в каталоге или вернитесь на главную.
        </p>
        <div class="kv-btn-group" style="justify-content:center">
            <a href="<?php echo home_url('/'); ?>" class="kv-btn kv-btn--primary kv-btn--lg">← На главную</a>
            <a href="<?php echo get_post_type_archive_link('kv_product'); ?>" class="kv-btn kv-btn--secondary kv-btn--lg">📦 Каталог</a>
            <a href="<?php echo home_url('/contacts/'); ?>" class="kv-btn kv-btn--secondary kv-btn--lg">📞 Контакты</a>
        </div>

        <!-- Быстрые ссылки -->
        <div style="margin-top:60px;display:flex;justify-content:center;gap:20px;flex-wrap:wrap">
            <?php foreach (kv_get_category_data() as $slug => $cat) : ?>
            <a href="<?= esc_url(home_url('/' . $slug . '/')) ?>" class="kv-tag kv-tag--accent" style="font-size:.9rem;padding:8px 16px">
                <?= $cat['icon'] ?> <?= esc_html($cat['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
