<?php get_header(); ?>

<section class="kv-page-hero">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
        <h1 style="margin-top:16px">Каталог кованых изделий</h1>
        <p class="kv-lead">Более 500 моделей ворот, заборов, лестниц, мебели и декора. Изготовление по вашим размерам.</p>
    </div>
</section>

<section class="kv-section">
    <div class="kv-container">
        <div class="kv-grid-2" style="gap:40px;align-items:start">

            <!-- Сайдбар-фильтр -->
            <aside style="grid-column:1/2;max-width:280px" id="kv-sidebar">
                <div style="background:var(--kv-white);border:1px solid var(--kv-border);border-radius:12px;padding:24px;position:sticky;top:92px">
                    <h4 style="margin-bottom:16px">Фильтры</h4>

                    <div class="kv-form-group">
                        <label style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--kv-text-muted)">Категория</label>
                        <?php foreach (kv_get_category_data() as $slug => $cat) :
                            $term = get_term_by('slug', $slug, 'kv_category');
                            $count = $term ? $term->count : 0;
                        ?>
                        <label style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--kv-border);cursor:pointer;font-size:.9rem">
                            <span style="display:flex;gap:8px">
                                <input type="checkbox" class="kv-filter-cat" value="<?= esc_attr($slug) ?>" style="accent-color:var(--kv-accent)">
                                <?= $cat['icon'] ?> <?= esc_html($cat['name']) ?>
                            </span>
                            <span style="background:var(--kv-bg);padding:2px 7px;border-radius:10px;font-size:.72rem"><?= $count ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="kv-form-group" style="margin-top:20px">
                        <label style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--kv-text-muted)">Покрытие</label>
                        <?php
                        $coatings = ['Порошковая окраска', 'Горячее цинкование', 'Патина', 'Нержавеющая сталь'];
                        foreach ($coatings as $c) : ?>
                        <label style="display:flex;gap:8px;align-items:center;padding:6px 0;cursor:pointer;font-size:.9rem">
                            <input type="checkbox" class="kv-filter-coating" value="<?= esc_attr($c) ?>" style="accent-color:var(--kv-accent)">
                            <?= esc_html($c) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="kv-form-group" style="margin-top:20px">
                        <label style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--kv-text-muted)">Цена</label>
                        <select class="kv-select" id="filter-price">
                            <option value="">Любая</option>
                            <option value="0-10000">До 10 000 ₽</option>
                            <option value="10000-30000">10 000 — 30 000 ₽</option>
                            <option value="30000-100000">30 000 — 100 000 ₽</option>
                            <option value="100000-999999">От 100 000 ₽</option>
                        </select>
                    </div>

                    <label style="display:flex;gap:8px;align-items:center;cursor:pointer;font-size:.9rem;padding:12px 0;border-top:1px solid var(--kv-border);margin-top:8px">
                        <input type="checkbox" id="filter-popular" style="accent-color:var(--kv-accent)">
                        ⭐ Только хиты продаж
                    </label>

                    <button class="kv-btn kv-btn--secondary" style="width:100%;margin-top:16px" id="kv-filter-reset">Сбросить фильтры</button>
                </div>
            </aside>

            <!-- Основная сетка -->
            <div style="grid-column:2/-1">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
                    <span style="font-size:.9rem;color:var(--kv-text-muted)">
                        Найдено: <strong><?php echo $wp_query->found_posts; ?></strong> изделий
                    </span>
                    <select class="kv-select" style="width:auto" id="catalog-sort">
                        <option>По новизне</option>
                        <option>Сначала хиты</option>
                        <option>Цена: по возрастанию</option>
                        <option>Цена: по убыванию</option>
                    </select>
                </div>

                <div class="kv-grid-auto" id="kv-catalog-grid">
                    <?php
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            $price_from = kv_field('kv_price_from');
                            $lead_time  = kv_field('kv_lead_time');
                            $popular    = kv_field('kv_popular');
                            $coating    = kv_field('kv_coating');
                            $img        = get_the_post_thumbnail_url(null, 'kv-card');
                            $cats       = get_the_terms(null, 'kv_category');
                    ?>
                    <article class="kv-card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="kv-card__img-wrap">
                            <?php if ($img) : ?>
                            <img class="kv-card__img" src="<?= esc_url($img) ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" width="600" height="450">
                            <?php else : ?>
                            <div class="kv-card__img" style="aspect-ratio:4/3;background:linear-gradient(135deg,#2D1A00,#1C0F00);display:flex;align-items:center;justify-content:center;font-size:3rem">⚒️</div>
                            <?php endif; ?>
                            <?php if ($popular) : ?><span class="kv-card__badge">Хит</span><?php endif; ?>
                        </div>
                        <div class="kv-card__body">
                            <?php if ($cats) : ?><div class="kv-card__cat"><?= esc_html($cats[0]->name) ?></div><?php endif; ?>
                            <h3 class="kv-card__title"><a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none"><?php the_title(); ?></a></h3>
                            <div class="kv-card__meta"><?php if ($lead_time) echo '⏱ ' . esc_html($lead_time); ?></div>
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
                    <?php endwhile;
                    else : ?>
                    <div style="grid-column:1/-1;text-align:center;padding:64px 0">
                        <div style="font-size:3rem;margin-bottom:16px">⚒️</div>
                        <h3>Изделий пока нет</h3>
                        <p style="color:var(--kv-text-muted)">Добавьте изделия в WordPress Admin → Изделия</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Пагинация -->
                <div style="margin-top:48px;text-align:center">
                    <?php echo paginate_links(['type' => 'list', 'prev_text' => '← Назад', 'next_text' => 'Вперёд →']); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
