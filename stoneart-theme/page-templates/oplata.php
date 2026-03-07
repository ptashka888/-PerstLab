<?php
/**
 * Template Name: Способы оплаты
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
        <p class="sa-page-header__subtitle">Гибкие условия оплаты для физических и юридических лиц</p>
    </div>
</div>

<main class="sa-main">

    <section class="sa-section sa-section--white">
        <div class="sa-container">

            <!-- Payment methods -->
            <h2 class="sa-section__title" style="margin-bottom:2rem;">Способы оплаты</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;margin-bottom:3rem;">
                <?php
                $methods = [
                    [
                        'icon'  => 'fa-solid fa-money-bill-wave',
                        'title' => 'Наличные',
                        'text'  => 'Оплата наличными в шоуруме или при доставке. Выдаём кассовый чек.',
                        'badge' => 'Без комиссии',
                    ],
                    [
                        'icon'  => 'fa-solid fa-credit-card',
                        'title' => 'Банковская карта',
                        'text'  => 'Visa, Mastercard, Мир. В шоуруме и через онлайн-эквайринг.',
                        'badge' => 'Без комиссии',
                    ],
                    [
                        'icon'  => 'fa-solid fa-building-columns',
                        'title' => 'Безналичный расчёт',
                        'text'  => 'Для юридических лиц и ИП. Договор, счёт, закрывающие документы.',
                        'badge' => 'Юрлицам',
                    ],
                    [
                        'icon'  => 'fa-solid fa-percent',
                        'title' => 'Рассрочка 0%',
                        'text'  => 'Рассрочка на 6 или 12 месяцев через банки-партнёры без переплат.',
                        'badge' => 'Популярно',
                    ],
                ];
                foreach ($methods as $m) : ?>
                    <div class="sa-card" style="padding:1.75rem;position:relative;">
                        <?php if (!empty($m['badge'])) : ?>
                            <span style="position:absolute;top:1rem;right:1rem;background:var(--sa-primary);color:#fff;font-size:0.7rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:999px;">
                                <?php echo esc_html($m['badge']); ?>
                            </span>
                        <?php endif; ?>
                        <i class="<?php echo esc_attr($m['icon']); ?>" style="font-size:2rem;color:var(--sa-primary);margin-bottom:1rem;"></i>
                        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:0.5rem;"><?php echo esc_html($m['title']); ?></h3>
                        <p style="font-size:0.9rem;color:var(--sa-gray-600);"><?php echo esc_html($m['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Content / details -->
            <div class="sa-prose" style="max-width:780px;margin:0 auto;">
                <?php if (get_the_content()) : ?>
                    <?php the_content(); ?>
                <?php else : ?>
                    <h2>Условия оплаты</h2>
                    <p>Стандартная схема работы:</p>
                    <ol>
                        <li><strong>Предоплата 50%</strong> — после подписания договора и согласования проекта</li>
                        <li><strong>Остаток 50%</strong> — после приёмки готового изделия, до выезда монтажной бригады</li>
                    </ol>

                    <h2>Рассрочка и кредит</h2>
                    <p>Мы сотрудничаем с банками-партнёрами. Рассрочка 0% доступна при заказе от 50 000 ₽. Срок рассрочки — 6 или 12 месяцев. Для оформления потребуется паспорт.</p>

                    <h2>Для юридических лиц</h2>
                    <p>Работаем по договору с НДС. Полный пакет закрывающих документов: акт, счёт-фактура, товарная накладная. Оплата в течение 10 банковских дней с момента доставки.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php get_template_part('template-parts/cta-form'); ?>

</main>

<?php get_footer(); ?>
