<?php get_header(); the_post(); ?>

<section class="kv-page-hero">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
        <h1 style="margin-top:16px"><?php the_title(); ?></h1>
    </div>
</section>

<section class="kv-section">
    <div class="kv-container">
        <div class="kv-grid-2" style="gap:64px;align-items:start">
            <div class="kv-content" style="grid-column:1/span 2">
                <?php if (get_the_content()) : the_content();
                else : ?>
                <p style="color:var(--kv-text-muted)">Содержимое страницы добавьте в редакторе WordPress.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
