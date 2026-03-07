<?php
/**
 * Template Name: О нас
 * Template Post Type: page
 */
get_header();
?>

<section class="kv-page-hero">
    <div class="kv-container" style="position:relative;z-index:2">
        <?php kv_breadcrumbs(); ?>
        <h1 style="margin-top:16px">О нашей кузнице</h1>
        <p class="kv-lead">19 лет создаём изделия из металла, которыми гордятся. Производство в Москве. Монтаж по России.</p>
    </div>
</section>

<!-- История -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-grid-2" style="gap:80px;align-items:center">
            <div>
                <span class="kv-section-label">Наша история</span>
                <h2>Основано в 2005 году мастером своего дела</h2>
                <p class="kv-lead kv-mt-16">Мы начинали как небольшая мастерская на 200 м² с тремя кузнецами и одним горном. Сегодня — завод площадью 1 800 м², 47 специалистов, современное ЧПУ-оборудование и живая традиция ручной художественной ковки.</p>
                <p style="color:var(--kv-text-muted)">За 19 лет мы реализовали более 1 200 проектов — от подсвечника до ворот для поместья. Наши изделия украшают частные дома, рестораны, отели, офисы и общественные пространства по всей России.</p>
                <div class="kv-mt-32">
                    <a href="#kv-modal" class="kv-btn kv-btn--primary kv-modal-open">Заказать изделие</a>
                </div>
            </div>
            <div style="display:grid;gap:20px">
                <?php
                $timeline = [
                    ['year' => '2005', 'event' => 'Открытие мастерской. 3 кузнеца, 1 горн, первые 20 заказов'],
                    ['year' => '2009', 'event' => 'Переезд в цех 600 м². Первый ЧПУ-станок для плазменной резки'],
                    ['year' => '2013', 'event' => 'Запуск покрасочной камеры. Горячее цинкование своими силами'],
                    ['year' => '2017', 'event' => '500-й выполненный проект. Команда выросла до 30 мастеров'],
                    ['year' => '2020', 'event' => 'Цех 1 800 м². Запуск 3D-проектирования и цифрового производства'],
                    ['year' => '2024', 'event' => 'Рейтинг 4.9★ на Яндекс и Google. 1 200+ реализованных проектов'],
                ];
                foreach ($timeline as $t) : ?>
                <div style="display:flex;gap:20px;align-items:flex-start">
                    <div style="background:var(--kv-accent);color:#fff;font-family:var(--kv-font-head);font-weight:800;font-size:.9rem;padding:8px 14px;border-radius:6px;flex-shrink:0;min-width:52px;text-align:center">
                        <?= $t['year'] ?>
                    </div>
                    <div style="padding-top:8px;font-size:.9rem;color:var(--kv-text-muted)"><?= esc_html($t['event']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Счётчики -->
<section class="kv-section kv-section--dark">
    <div class="kv-container">
        <div class="kv-counter-grid">
            <div class="kv-counter-item"><span class="kv-counter-val" style="color:#FFAB40" data-counter="19">0</span><span class="kv-counter-label">лет на рынке</span></div>
            <div class="kv-counter-item"><span class="kv-counter-val" style="color:#FFAB40" data-counter="1200">0</span><span class="kv-counter-label">реализованных проектов</span></div>
            <div class="kv-counter-item"><span class="kv-counter-val" style="color:#FFAB40" data-counter="47">0</span><span class="kv-counter-label">мастеров в команде</span></div>
            <div class="kv-counter-item"><span class="kv-counter-val" style="color:#FFAB40" data-counter="320">0</span><span class="kv-counter-label">отзывов с рейтингом 5★</span></div>
        </div>
    </div>
</section>

<!-- Команда -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Команда</span>
            <h2>Мастера, которым доверяют металл</h2>
            <p class="kv-lead">Средний стаж — 14 лет. Победители всероссийских конкурсов кузнецов.</p>
        </div>
        <div class="kv-grid-4">
            <?php
            $team = get_posts(['post_type' => 'kv_team', 'posts_per_page' => 8]);
            if (!$team) {
                $team = [
                    ['name' => 'Иван Кузнецов',   'role' => 'Основатель, главный кузнец', 'exp' => 22, 'init' => 'И', 'spec' => 'Художественная ковка, арт-объекты'],
                    ['name' => 'Пётр Железнов',    'role' => 'Мастер художественной ковки', 'exp' => 17, 'init' => 'П', 'spec' => 'Ворота, ограждения, перила'],
                    ['name' => 'Алексей Сварной',  'role' => 'Сварщик 6-го разряда',       'exp' => 14, 'init' => 'А', 'spec' => 'Конструктивные узлы'],
                    ['name' => 'Марина Дизайнова', 'role' => 'Главный дизайнер',            'exp' => 9,  'init' => 'М', 'spec' => '3D-проекты, визуализация'],
                    ['name' => 'Дмитрий Плавкин',  'role' => 'Мастер ЧПУ',                 'exp' => 11, 'init' => 'Д', 'spec' => 'Плазменная резка, ЧПУ-гибка'],
                    ['name' => 'Антон Горнов',     'role' => 'Мастер покраски',             'exp' => 8,  'init' => 'А', 'spec' => 'Порошковая окраска, цинкование'],
                    ['name' => 'Светлана Монтажная','role' => 'Менеджер проекта',           'exp' => 7,  'init' => 'С', 'spec' => 'Ведение клиентов, логистика'],
                    ['name' => 'Виктор Пружинин',  'role' => 'Мастер монтажа',             'exp' => 13, 'init' => 'В', 'spec' => 'Монтаж ворот и конструкций'],
                ];
            }
            foreach ($team as $m) :
                if (is_object($m)) {
                    $name  = $m->post_title;
                    $role  = kv_field('kv_role', $m->ID);
                    $exp   = kv_field('kv_experience', $m->ID);
                    $spec  = kv_field('kv_spec', $m->ID);
                    $photo = kv_field('kv_t_photo', $m->ID);
                    $init  = mb_substr($name, 0, 1);
                } else {
                    $name  = $m['name']; $role = $m['role']; $exp = $m['exp'];
                    $spec  = $m['spec']; $photo = ''; $init = $m['init'];
                }
            ?>
            <div class="kv-usp-card" style="text-align:center;padding:28px 20px">
                <div style="width:80px;height:80px;border-radius:50%;<?= $photo ? 'overflow:hidden;' : 'background:linear-gradient(135deg,var(--kv-accent),var(--kv-accent-dark));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;font-family:var(--kv-font-head);font-weight:700;' ?>margin:0 auto 16px">
                    <?php if ($photo) : ?><img src="<?= esc_url($photo) ?>" alt="<?= esc_attr($name) ?>" style="width:100%;height:100%;object-fit:cover"><?php else : echo $init; endif; ?>
                </div>
                <h3 style="font-size:.95rem;margin-bottom:4px"><?= esc_html($name) ?></h3>
                <p style="color:var(--kv-accent);font-size:.8rem;margin-bottom:4px"><?= esc_html($role) ?></p>
                <?php if ($exp) : ?><p style="font-size:.78rem;color:var(--kv-text-muted);margin-bottom:4px">Стаж: <?= esc_html($exp) ?> лет</p><?php endif; ?>
                <?php if ($spec) : ?><p style="font-size:.76rem;color:var(--kv-text-muted)"><?= esc_html($spec) ?></p><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Производство -->
<section class="kv-section kv-section--subtle">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Производство</span>
            <h2>Как устроена наша кузница</h2>
        </div>
        <div class="kv-grid-3">
            <?php
            $zones = [
                ['icon' => '🔥', 'title' => 'Кузнечный цех',         'text' => '8 горнов, ковочные прессы, механические молоты. Ручная художественная ковка и машинное производство.'],
                ['icon' => '⚙️', 'title' => 'ЧПУ и плазма',          'text' => 'Плазменная и лазерная резка с точностью ±0.5 мм. Ленточная пила, трубогиб ЧПУ, листогиб.'],
                ['icon' => '🔩', 'title' => 'Сварочный участок',      'text' => '15 сварочных постов. MIG/MAG, TIG, ручная дуговая. Контроль качества швов — 100%.'],
                ['icon' => '🎨', 'title' => 'Покрасочная камера',     'text' => 'Порошковая окраска с климат-контролем. Горячее цинкование собственным методом. 120 цветов RAL.'],
                ['icon' => '📐', 'title' => 'Дизайн-бюро',           'text' => '3D-проектирование в SolidWorks и AutoCAD. Визуализация изделия до старта производства.'],
                ['icon' => '🚚', 'title' => 'Логистика и монтаж',     'text' => 'Собственный транспортный парк. Монтажная бригада работает по всей России.'],
            ];
            foreach ($zones as $z) : ?>
            <div class="kv-material-card">
                <div class="kv-material-card__icon"><?= $z['icon'] ?></div>
                <div>
                    <h4><?= esc_html($z['title']) ?></h4>
                    <p><?= esc_html($z['text']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Отзывы -->
<section class="kv-section">
    <div class="kv-container">
        <div class="kv-section-head">
            <span class="kv-section-label">Отзывы</span>
            <h2>Нам доверяют</h2>
        </div>
        <div class="kv-grid-3">
            <?php
            $reviews = get_posts(['post_type' => 'kv_review', 'posts_per_page' => 3]);
            if ($reviews) :
                foreach ($reviews as $rv) :
                    $rating = kv_field('kv_rating', $rv->ID) ?: 5;
                    $city   = kv_field('kv_city', $rv->ID);
                    $photo  = kv_field('kv_r_photo', $rv->ID);
            ?>
            <div class="kv-review-card">
                <?php echo kv_stars($rating); ?>
                <p class="kv-review-text"><?= wp_kses_post($rv->post_content) ?></p>
                <div class="kv-review-author">
                    <?php if ($photo) : ?><img src="<?= esc_url($photo) ?>" alt="<?= esc_attr($rv->post_title) ?>"><?php endif; ?>
                    <div>
                        <div class="kv-review-author__name"><?= esc_html($rv->post_title) ?></div>
                        <div class="kv-review-author__meta"><?= esc_html($city) ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); endif; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="kv-section--sm">
    <div class="kv-container">
        <div class="kv-cta-banner">
            <div>
                <h2>Готовы к сотрудничеству?</h2>
                <p>Приезжайте в наш шоу-рум или закажите бесплатный звонок</p>
            </div>
            <div class="kv-cta-banner__actions">
                <a href="<?php echo home_url('/contacts/'); ?>" class="kv-btn kv-btn--white kv-btn--lg">📍 Как нас найти</a>
                <a href="#kv-modal" class="kv-btn kv-btn--ghost kv-modal-open">Заказать звонок</a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
