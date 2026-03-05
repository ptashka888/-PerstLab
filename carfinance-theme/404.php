<?php
/**
 * Template: 404 Not Found
 */

defined('ABSPATH') || exit;

get_header();
?>

<section class="cf-section cf-404">
    <div class="cf-container">
        <div class="cf-404__content">
            <span class="cf-404__code">404</span>
            <h1 class="cf-404__title">Страница не найдена</h1>
            <p class="cf-404__text">Возможно, страница была перемещена или удалена. Воспользуйтесь поиском или перейдите на главную.</p>
            <div class="cf-404__actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="cf-btn cf-btn--primary">На главную</a>
                <a href="<?php echo esc_url(get_post_type_archive_link('car_model')); ?>" class="cf-btn cf-btn--secondary">Каталог</a>
            </div>
            <form class="cf-404__search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="search" name="s" class="cf-form__input" placeholder="Поиск по сайту..." required>
                <button type="submit" class="cf-btn cf-btn--primary">Найти</button>
            </form>
        </div>
    </div>
</section>

<?php
cf_block('country-cards', ['show_comparison' => false]);
get_footer();
