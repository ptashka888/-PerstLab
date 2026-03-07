<?php get_header(); the_post(); ?>

<section class="kv-page-hero" style="padding:40px 0">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
    </div>
</section>

<section class="kv-section" style="padding-top:40px">
    <div class="kv-container">
        <div class="kv-grid-2" style="gap:60px;align-items:start">
            <article style="grid-column:1/span 2;max-width:760px">
                <?php if (has_post_thumbnail()) : ?>
                <div style="border-radius:12px;overflow:hidden;margin-bottom:32px">
                    <?php the_post_thumbnail('kv-wide', ['loading' => 'lazy']); ?>
                </div>
                <?php endif; ?>

                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px">
                    <?php the_category('<span class="kv-tag kv-tag--accent">', '</span><span class="kv-tag kv-tag--accent">', '</span>'); ?>
                    <span class="kv-tag"><?php the_date(); ?></span>
                    <span class="kv-tag">⏱ <?php echo get_post_meta(get_the_ID(), 'kv_read_time', true) ?: '5'; ?> мин чтения</span>
                </div>

                <h1 style="margin-bottom:20px"><?php the_title(); ?></h1>

                <div class="kv-content" style="line-height:1.8">
                    <?php the_content(); ?>
                </div>

                <!-- Теги -->
                <div style="margin-top:40px;padding-top:24px;border-top:1px solid var(--kv-border)">
                    <?php the_tags('<div style="display:flex;gap:8px;flex-wrap:wrap">', '', '</div>'); ?>
                </div>
            </article>
        </div>

        <!-- CTA после статьи -->
        <div style="margin-top:64px">
            <div class="kv-cta-banner">
                <div>
                    <h2>Хотите заказать кованое изделие?</h2>
                    <p>Бесплатный расчёт стоимости за 15 минут</p>
                </div>
                <a href="#kv-modal" class="kv-btn kv-btn--white kv-btn--lg kv-modal-open">Получить расчёт</a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
