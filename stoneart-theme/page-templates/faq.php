<?php
/**
 * Template Name: FAQ
 *
 * @package StoneArt
 */

get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p style="color:var(--sa-gray-400);margin-top:0.5rem;">Ответы на самые популярные вопросы о камне и наших услугах</p>
        <?php sa_breadcrumbs(); ?>
    </div>
</div>

<section class="sa-section sa-section--white">
    <div class="sa-container">
        <?php
        // Get FAQ categories
        $faq_cats = get_terms(['taxonomy' => 'sa_faq_cat', 'hide_empty' => true]);

        if ($faq_cats && !is_wp_error($faq_cats)) :
            foreach ($faq_cats as $cat) : ?>
                <div style="margin-bottom:3rem;">
                    <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;" class="sa-font-serif"><?php echo esc_html($cat->name); ?></h2>
                    <div class="sa-faq__list">
                        <?php
                        $faqs = new WP_Query([
                            'post_type'      => 'sa_faq',
                            'posts_per_page' => -1,
                            'tax_query'      => [
                                ['taxonomy' => 'sa_faq_cat', 'field' => 'term_id', 'terms' => $cat->term_id],
                            ],
                            'orderby' => 'menu_order',
                            'order'   => 'ASC',
                        ]);

                        while ($faqs->have_posts()) : $faqs->the_post(); ?>
                            <div class="sa-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                <button class="sa-faq__question" aria-expanded="false">
                                    <span itemprop="name"><?php the_title(); ?></span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <div class="sa-faq__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                    <div itemprop="text"><?php the_content(); ?></div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endforeach;
        else :
            // Fallback: show all FAQs without categories
            $faqs = new WP_Query([
                'post_type'      => 'sa_faq',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]);

            if ($faqs->have_posts()) : ?>
                <div class="sa-faq__list" itemscope itemtype="https://schema.org/FAQPage">
                    <?php while ($faqs->have_posts()) : $faqs->the_post(); ?>
                        <div class="sa-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <button class="sa-faq__question" aria-expanded="false">
                                <span itemprop="name"><?php the_title(); ?></span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <div class="sa-faq__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                <div itemprop="text"><?php the_content(); ?></div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            <?php else : ?>
                <!-- Default FAQ -->
                <div class="sa-faq__list" itemscope itemtype="https://schema.org/FAQPage">
                    <?php
                    $default_faqs = [
                        ['q' => 'Какой материал лучше для кухонной столешницы?', 'a' => 'Для кухни мы рекомендуем кварцевый агломерат (Avant, Caesarstone). Он не впитывает влагу, устойчив к царапинам и пятнам. Для бесшовных столешниц сложной формы подойдет акриловый камень (Grandex).'],
                        ['q' => 'Сколько стоит столешница из камня?', 'a' => 'Цена зависит от выбранного материала, размеров и сложности обработки. Кварцевый агломерат — от 8 000 ₽/п.м., акриловый камень — от 6 500 ₽/п.м., натуральный гранит — от 10 000 ₽/п.м. Точную стоимость рассчитаем после замера.'],
                        ['q' => 'Каковы сроки изготовления?', 'a' => 'Стандартный срок изготовления столешницы — 5-10 рабочих дней с момента замера. Сложные проекты (камины, лестницы) — до 14 дней. Замер выполняется в течение 1-2 дней после обращения.'],
                        ['q' => 'Предоставляете ли вы гарантию?', 'a' => 'Да, мы предоставляем официальную гарантию 10 лет по договору на материал и монтаж. Гарантия покрывает дефекты материала и качества установки.'],
                        ['q' => 'Как ухаживать за каменной столешницей?', 'a' => 'Кварц и акрил не требуют специального ухода — достаточно мыльного раствора. Натуральный мрамор нуждается в обработке гидрофобизатором 1 раз в год. Избегайте кислотных средств на мраморных поверхностях.'],
                        ['q' => 'Работаете ли вы за пределами Москвы?', 'a' => 'Да, мы выполняем проекты по всей Московской области и работаем с доставкой по РФ. Для удаленных проектов возможен удаленный замер по вашим чертежам.'],
                    ];
                    foreach ($default_faqs as $faq) : ?>
                        <div class="sa-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <button class="sa-faq__question" aria-expanded="false">
                                <span itemprop="name"><?php echo esc_html($faq['q']); ?></span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <div class="sa-faq__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                <div itemprop="text"><p><?php echo esc_html($faq['a']); ?></p></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif;
        endif; ?>
    </div>
</section>

<!-- Quiz CTA -->
<?php get_template_part('template-parts/quiz'); ?>

<?php get_footer(); ?>
