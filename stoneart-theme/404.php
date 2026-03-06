<?php
/**
 * 404 Template
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title">Страница не найдена</h1>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<section class="sa-section sa-section--white" style="text-align:center;">
    <div class="sa-container">
        <div style="max-width:32rem;margin:0 auto;">
            <i class="fa-solid fa-gem" style="font-size:5rem;color:var(--sa-primary);margin-bottom:2rem;display:block;"></i>
            <h2 style="font-size:6rem;font-weight:800;color:var(--sa-gray-200);line-height:1;">404</h2>
            <p style="font-size:1.25rem;color:var(--sa-gray-600);margin:1.5rem 0;">Запрашиваемая страница не существует или была перемещена.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="sa-btn sa-btn--primary sa-btn--lg">Вернуться на главную</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
