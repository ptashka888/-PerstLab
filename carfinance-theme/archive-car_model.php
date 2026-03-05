<?php
/**
 * Template: Car Model Archive (Catalog)
 * URL: /catalog/
 * Big filtrator + AJAX car grid
 */

defined('ABSPATH') || exit;

get_header();
?>

<section class="cf-section">
    <div class="cf-container">
        <div class="cf-section__header">
            <h1 class="cf-section__title">Каталог автомобилей</h1>
            <p class="cf-section__subtitle">Подберите автомобиль из-за рубежа по вашим параметрам</p>
        </div>
    </div>
</section>

<div class="cf-catalog">
    <div class="cf-container">
        <div class="cf-catalog__layout">
            <!-- Sidebar Filter -->
            <aside class="cf-catalog__sidebar">
                <?php cf_block('catalog-filter', ['mode' => 'sidebar']); ?>
            </aside>

            <!-- Results -->
            <main class="cf-catalog__main">
                <!-- Active filters -->
                <div class="cf-catalog__toolbar">
                    <div class="cf-catalog__count">
                        Найдено: <span id="cf-catalog-count"><?php echo esc_html($wp_query->found_posts); ?></span> авто
                    </div>
                    <div class="cf-catalog__sort">
                        <label for="cf-sort">Сортировка:</label>
                        <select id="cf-sort" class="cf-form__select">
                            <option value="popular">По популярности</option>
                            <option value="price_asc">Цена ↑</option>
                            <option value="price_desc">Цена ↓</option>
                            <option value="year_desc">Сначала новые</option>
                        </select>
                    </div>
                    <div class="cf-catalog__view-toggle">
                        <button class="cf-catalog__view cf-catalog__view--grid active" data-view="grid" aria-label="Сетка">▦</button>
                        <button class="cf-catalog__view cf-catalog__view--list" data-view="list" aria-label="Список">☰</button>
                    </div>
                </div>

                <!-- Car Grid -->
                <div id="cf-catalog-results" class="cf-catalog__grid cf-grid cf-grid--3">
                    <?php if (have_posts()): ?>
                        <?php while (have_posts()): the_post(); ?>
                            <?php cf_block('car-card', ['post_id' => get_the_ID()]); ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="cf-catalog__empty">
                            <p>Автомобили не найдены. Попробуйте изменить параметры фильтра.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination / Load More -->
                <div class="cf-catalog__pagination" id="cf-catalog-pagination">
                    <?php if ($wp_query->max_num_pages > 1): ?>
                        <button class="cf-btn cf-btn--secondary cf-btn--full" id="cf-load-more"
                                data-page="1"
                                data-max="<?php echo esc_attr($wp_query->max_num_pages); ?>">
                            Показать ещё
                        </button>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>

<?php
// Interlinking
cf_block('interlinking', ['position' => 'footer']);

get_footer();
