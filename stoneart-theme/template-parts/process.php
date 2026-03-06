<?php
/**
 * Template Part: Work Process
 *
 * @package StoneArt
 */

$title = sa_option('sa_process_title', 'Простой процесс работы (до 14 дней)');

$steps = sa_option('sa_process_steps');
if (!$steps) {
    $steps = [
        ['sa_step_title' => 'Заявка', 'sa_step_text' => 'Вы проходите тест на сайте или звоните нам для предварительного расчета.'],
        ['sa_step_title' => 'Точный замер', 'sa_step_text' => 'Инженер приезжает с лазерным 3D-сканером и образцами камня. Договор.'],
        ['sa_step_title' => 'Производство', 'sa_step_text' => 'Резка и обработка на ЧПУ станках в нашем цеху в Москве (5-10 дней).'],
        ['sa_step_title' => 'Монтаж', 'sa_step_text' => 'Аккуратная доставка и установка мастерами (опыт от 7 лет) без строительной пыли.'],
    ];
}
?>

<section class="sa-section sa-section--white sa-animate">
    <div class="sa-container">
        <h2 class="sa-section__title"><?php echo esc_html($title); ?></h2>
        <div style="margin-top:4rem;">
            <div class="sa-process">
                <div class="sa-process__line"></div>
                <?php foreach ($steps as $i => $step) : ?>
                    <div class="sa-process__step">
                        <div class="sa-process__number"><?php echo $i + 1; ?></div>
                        <h4 class="sa-process__title"><?php echo esc_html($step['sa_step_title'] ?? ''); ?></h4>
                        <p class="sa-process__text"><?php echo esc_html($step['sa_step_text'] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
