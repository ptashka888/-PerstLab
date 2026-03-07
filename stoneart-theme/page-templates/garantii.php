<?php
/**
 * Template Name: Гарантии
 * Template Post Type: page
 *
 * @package StoneArt
 */
get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <?php sa_breadcrumbs(); ?>
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p class="sa-page-header__subtitle">Официальная гарантия по договору на материал и монтаж</p>
    </div>
</div>

<main class="sa-main">

    <section class="sa-section sa-section--white">
        <div class="sa-container">

            <!-- Guarantee cards -->
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem;margin-bottom:3rem;">
                <?php
                $guarantees = [
                    ['icon' => 'fa-solid fa-gem',           'years' => '10 лет', 'title' => 'Гарантия на материал',  'text' => 'На все изделия из натурального и искусственного камня'],
                    ['icon' => 'fa-solid fa-screwdriver-wrench','years' => '3 года', 'title' => 'Гарантия на монтаж', 'text' => 'На работы по установке, герметизации и подгонке'],
                    ['icon' => 'fa-solid fa-file-contract',  'years' => '100%',   'title' => 'Договор',               'text' => 'Все условия прописываются в официальном договоре'],
                    ['icon' => 'fa-solid fa-shield-halved',  'years' => '24/7',   'title' => 'Поддержка',             'text' => 'Консультации по уходу на весь срок службы изделия'],
                ];
                foreach ($guarantees as $g) : ?>
                    <div class="sa-card" style="padding:2rem;text-align:center;">
                        <i class="<?php echo esc_attr($g['icon']); ?>" style="font-size:2.5rem;color:var(--sa-primary);margin-bottom:1rem;"></i>
                        <div style="font-size:2rem;font-weight:900;color:var(--sa-primary);margin-bottom:0.25rem;"><?php echo esc_html($g['years']); ?></div>
                        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:0.5rem;"><?php echo esc_html($g['title']); ?></h3>
                        <p style="font-size:0.9rem;color:var(--sa-gray-600);"><?php echo esc_html($g['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Content -->
            <div class="sa-prose" style="max-width:780px;margin:0 auto;">
                <?php if (get_the_content()) : ?>
                    <?php the_content(); ?>
                <?php else : ?>
                    <h2>Что покрывает гарантия</h2>
                    <p>Гарантия распространяется на:</p>
                    <ul>
                        <li>Дефекты материала, возникшие не по вине клиента</li>
                        <li>Нарушения геометрии и размеров изделия</li>
                        <li>Некачественный монтаж (расхождение швов, неровная установка)</li>
                        <li>Повреждение изделия при доставке</li>
                    </ul>

                    <h2>Что не покрывает гарантия</h2>
                    <ul>
                        <li>Механические повреждения, вызванные неправильной эксплуатацией</li>
                        <li>Воздействие химикатов, не предназначенных для камня</li>
                        <li>Естественное изменение цвета мрамора при длительном воздействии УФ</li>
                        <li>Сколы и трещины от точечного удара тяжёлыми предметами</li>
                    </ul>

                    <h2>Как воспользоваться гарантией</h2>
                    <ol>
                        <li>Сообщите нам о проблеме по телефону или e-mail</li>
                        <li>Мастер осмотрит изделие в течение 3 рабочих дней</li>
                        <li>При подтверждении гарантийного случая — ремонт или замена бесплатно</li>
                        <li>Срок устранения гарантийного дефекта — до 10 рабочих дней</li>
                    </ol>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <?php get_template_part('template-parts/cta-form'); ?>

</main>

<?php get_footer(); ?>
