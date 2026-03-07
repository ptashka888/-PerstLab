<?php
/**
 * Template Name: Страница категории
 * Template Post Type: page
 */

get_header();

$slug = get_post_field('post_name', get_the_ID());
$cats = kv_get_category_data();
$cat  = $cats[$slug] ?? ['name' => get_the_title(), 'icon' => '⚒️', 'desc' => ''];

// Получаем термин таксономии для этого slug
$term = get_term_by('slug', $slug, 'kv_category');
?>

<!-- PAGE HERO -->
<section class="kv-page-hero">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
        <div style="display:flex;align-items:center;gap:20px;margin-top:16px">
            <div style="width:72px;height:72px;background:rgba(230,81,0,.2);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:2rem;flex-shrink:0">
                <?= $cat['icon'] ?>
            </div>
            <div>
                <h1><?= esc_html($cat['name']) ?></h1>
                <p class="kv-lead" style="margin:0"><?= esc_html($cat['desc']) ?> — изготовление на заказ по вашим размерам</p>
            </div>
        </div>
        <div class="kv-btn-group kv-mt-24">
            <a href="#kv-catalog" class="kv-btn kv-btn--primary">Смотреть каталог</a>
            <a href="#kv-modal" class="kv-btn kv-btn--ghost kv-modal-open">Получить расчёт</a>
        </div>
    </div>
</section>

<!-- TRUST BAR -->
<div class="kv-trust">
    <div class="kv-container">
        <div class="kv-trust-list">
            <div class="kv-trust-item"><div class="kv-trust-item__icon">📐</div><div><div class="kv-trust-item__val">По вашим размерам</div><div class="kv-trust-item__label">Любой нестандарт</div></div></div>
            <div class="kv-trust-item"><div class="kv-trust-item__icon">🎨</div><div><div class="kv-trust-item__val">120 цветов RAL</div><div class="kv-trust-item__label">Порошок, патина, цинк</div></div></div>
            <div class="kv-trust-item"><div class="kv-trust-item__icon">⚡</div><div><div class="kv-trust-item__val">Срок от 7 дней</div><div class="kv-trust-item__label">Экспресс-изготовление</div></div></div>
            <div class="kv-trust-item"><div class="kv-trust-item__icon">🛡️</div><div><div class="kv-trust-item__val">Гарантия 25 лет</div><div class="kv-trust-item__label">Письменно в договоре</div></div></div>
            <div class="kv-trust-item"><div class="kv-trust-item__icon">🚚</div><div><div class="kv-trust-item__val">Монтаж по РФ</div><div class="kv-trust-item__label">Своя бригада</div></div></div>
        </div>
    </div>
</div>

<!-- CATALOG -->
<section class="kv-section" id="kv-catalog">
    <div class="kv-container">

        <!-- Фильтр -->
        <div class="kv-filter-bar">
            <div class="kv-filter-group">
                <label>Покрытие</label>
                <select class="kv-select" id="filter-coating">
                    <option value="">Любое</option>
                    <option>Порошковая окраска</option>
                    <option>Горячее цинкование</option>
                    <option>Патина</option>
                </select>
            </div>
            <div class="kv-filter-group">
                <label>Сортировка</label>
                <select class="kv-select" id="filter-sort">
                    <option value="date">По новизне</option>
                    <option value="price_asc">Цена: по возрастанию</option>
                    <option value="price_desc">Цена: по убыванию</option>
                </select>
            </div>
            <div class="kv-filter-group">
                <label>Поиск</label>
                <input type="text" class="kv-input" id="filter-search" placeholder="Например, ворота 4 метра">
            </div>
        </div>

        <!-- Сетка -->
        <div class="kv-grid-auto" id="kv-products-grid">
            <?php
            $args = [
                'post_type'      => 'kv_product',
                'posts_per_page' => 12,
                'post_status'    => 'publish',
            ];
            if ($term) {
                $args['tax_query'] = [['taxonomy' => 'kv_category', 'field' => 'term_id', 'terms' => $term->term_id]];
            }
            $products = new WP_Query($args);

            if ($products->have_posts()) :
                while ($products->have_posts()) : $products->the_post();
                    $price_from  = kv_field('kv_price_from');
                    $lead_time   = kv_field('kv_lead_time');
                    $coating     = kv_field('kv_coating');
                    $popular     = kv_field('kv_popular');
                    $img         = get_the_post_thumbnail_url(null, 'kv-card') ?: get_template_directory_uri() . '/assets/img/placeholder.jpg';
            ?>
            <article class="kv-card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="kv-card__img-wrap">
                    <img class="kv-card__img" src="<?= esc_url($img) ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" width="600" height="450">
                    <?php if ($popular) : ?>
                    <span class="kv-card__badge">Хит</span>
                    <?php endif; ?>
                </div>
                <div class="kv-card__body">
                    <div class="kv-card__cat"><?= esc_html($cat['name']) ?></div>
                    <h3 class="kv-card__title"><a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none"><?php the_title(); ?></a></h3>
                    <div class="kv-card__meta">
                        <?php if ($lead_time) echo esc_html('⏱ ' . $lead_time); ?>
                        <?php if ($coating) : ?> · <span class="kv-tag" style="font-size:.7rem"><?= esc_html($coating) ?></span><?php endif; ?>
                    </div>
                    <div class="kv-card__price">
                        <?php if ($price_from) : ?>
                        <span class="kv-card__price-from">от </span><?= number_format((int)$price_from, 0, '.', '&nbsp;') ?>&nbsp;₽
                        <?php else : echo 'По запросу'; endif; ?>
                    </div>
                </div>
                <div class="kv-card__footer">
                    <a href="<?php the_permalink(); ?>" class="kv-btn kv-btn--secondary kv-btn--sm">Подробнее</a>
                    <a href="#kv-modal" class="kv-btn kv-btn--primary kv-btn--sm kv-modal-open" data-product="<?php the_title_attribute(); ?>">Заказать</a>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata();

            else :
            // Placeholder карточки
            for ($i = 1; $i <= 6; $i++) : ?>
            <div class="kv-card">
                <div style="aspect-ratio:4/3;background:linear-gradient(135deg,#2D1A00,#1C0F00);display:flex;align-items:center;justify-content:center;font-size:3rem;opacity:.4">⚒️</div>
                <div class="kv-card__body">
                    <div class="kv-card__cat"><?= esc_html($cat['name']) ?></div>
                    <h3 class="kv-card__title">Изделие <?= $i ?> — добавьте в WP Admin</h3>
                    <div class="kv-card__meta">⏱ 14–21 день</div>
                    <div class="kv-card__price"><span class="kv-card__price-from">от </span><?= number_format(mt_rand(8, 45) * 1000, 0, '.', '&nbsp;') ?>&nbsp;₽</div>
                </div>
                <div class="kv-card__footer">
                    <span class="kv-btn kv-btn--secondary kv-btn--sm">Подробнее</span>
                    <a href="#kv-modal" class="kv-btn kv-btn--primary kv-btn--sm kv-modal-open">Заказать</a>
                </div>
            </div>
            <?php endfor; endif; ?>
        </div>
    </div>
</section>

<!-- КОНТЕНТ СТРАНИЦЫ (из редактора WP) -->
<?php if (get_the_content()) : ?>
<section class="kv-section kv-section--subtle">
    <div class="kv-container">
        <div class="kv-grid-2" style="gap:64px;align-items:start">
            <div class="kv-content">
                <?php the_content(); ?>
            </div>
            <div>
                <div style="background:var(--kv-white);border:1px solid var(--kv-border);border-radius:16px;padding:32px">
                    <h3>Рассчитать стоимость</h3>
                    <p style="color:var(--kv-text-muted);font-size:.9rem;margin-bottom:20px">Укажите параметры — перезвоним с точной ценой</p>
                    <?php echo kv_lead_form('category-' . $slug); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="kv-section--sm">
    <div class="kv-container">
        <div class="kv-cta-banner">
            <div>
                <h2>Не нашли нужную модель?</h2>
                <p>Изготовим по вашему эскизу или разработаем дизайн с нуля — бесплатно</p>
            </div>
            <div class="kv-cta-banner__actions">
                <a href="#kv-modal" class="kv-btn kv-btn--white kv-btn--lg kv-modal-open">Заказать индивидуально</a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
