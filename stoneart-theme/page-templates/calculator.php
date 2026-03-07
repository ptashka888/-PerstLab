<?php
/**
 * Template Name: Калькулятор
 *
 * Stone price calculator page with quick estimate + quiz lead form.
 *
 * @package StoneArt
 */
get_header();
?>

<div class="sa-page-header">
    <div class="sa-container">
        <?php sa_breadcrumbs(); ?>
        <h1 class="sa-page-header__title"><?php the_title(); ?></h1>
        <p class="sa-page-header__subtitle">Рассчитайте ориентировочную стоимость изделия за 1 минуту</p>
    </div>
</div>

<!-- Quick Calculator -->
<section class="sa-section sa-section--white" id="calculator-section">
    <div class="sa-container">
        <div style="max-width:900px;margin:0 auto;">

            <!-- Tab switcher -->
            <div style="display:flex;border-bottom:2px solid var(--sa-gray-200);margin-bottom:2rem;gap:0.25rem;" role="tablist">
                <button class="sa-calc-tab active" data-calc-tab="quick" role="tab" aria-selected="true"
                        style="padding:0.75rem 1.5rem;font-weight:700;border:none;background:none;cursor:pointer;border-bottom:3px solid var(--sa-primary);margin-bottom:-2px;color:var(--sa-primary);">
                    <i class="fa-solid fa-bolt" style="margin-right:0.4rem;"></i> Быстрый расчёт
                </button>
                <button class="sa-calc-tab" data-calc-tab="quiz" role="tab" aria-selected="false"
                        style="padding:0.75rem 1.5rem;font-weight:700;border:none;background:none;cursor:pointer;color:var(--sa-gray-600);">
                    <i class="fa-solid fa-list-check" style="margin-right:0.4rem;"></i> Квиз-заявка
                </button>
            </div>

            <!-- Panel: Quick calculator -->
            <div data-calc-panel="quick">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;" class="sa-calc-grid">

                    <div>
                        <label class="sa-label">Тип изделия</label>
                        <select id="calc-type" class="sa-input" style="cursor:pointer;">
                            <option value="">Выберите тип</option>
                            <option value="stoleshnitsa">Столешница</option>
                            <option value="podokonnik">Подоконник</option>
                            <option value="lestnitsa">Лестница / ступени</option>
                            <option value="kamin">Камин / портал</option>
                            <option value="pol">Пол / облицовка</option>
                            <option value="rakoviny">Раковина / мойка</option>
                            <option value="vanna">Ванна из камня</option>
                            <option value="fasad">Фасад</option>
                        </select>
                    </div>

                    <div>
                        <label class="sa-label">Материал</label>
                        <select id="calc-material" class="sa-input" style="cursor:pointer;">
                            <option value="">Выберите материал</option>
                            <optgroup label="Натуральный камень">
                                <option value="mramor">Мрамор</option>
                                <option value="granit">Гранит</option>
                                <option value="oniks">Оникс</option>
                                <option value="travertin">Травертин</option>
                                <option value="kvartsit">Кварцит</option>
                            </optgroup>
                            <optgroup label="Искусственный камень">
                                <option value="kvarts">Кварцевый агломерат</option>
                                <option value="akril">Акриловый камень</option>
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="sa-label" id="calc-len-label">Длина (мм)</label>
                        <input type="number" id="calc-length" class="sa-input" placeholder="Например: 2400" min="100" max="10000">
                    </div>

                    <div>
                        <label class="sa-label" id="calc-wid-label">Ширина (мм)</label>
                        <input type="number" id="calc-width" class="sa-input" placeholder="Например: 600" min="100" max="5000">
                    </div>

                    <div>
                        <label class="sa-label">Единицы измерения</label>
                        <select id="calc-unit" class="sa-input" style="cursor:pointer;">
                            <option value="0">Миллиметры (мм)</option>
                            <option value="1">Сантиметры (см)</option>
                            <option value="2">Метры (м)</option>
                        </select>
                    </div>

                    <div>
                        <label class="sa-label">Толщина</label>
                        <select id="calc-thickness" class="sa-input" style="cursor:pointer;">
                            <option value="20">20 мм (стандарт)</option>
                            <option value="30">30 мм (усиленная)</option>
                            <option value="40">40 мм (премиум)</option>
                            <option value="60">60 мм (массив)</option>
                        </select>
                    </div>

                    <div>
                        <label class="sa-label">Вид кромки</label>
                        <select id="calc-edge" class="sa-input" style="cursor:pointer;">
                            <option value="straight">Прямая (торцевая)</option>
                            <option value="bevel">Фаска 45°</option>
                            <option value="round">Скругление R10</option>
                            <option value="profiled">Профильная (погонаж)</option>
                            <option value="carving">Резная / художественная</option>
                        </select>
                    </div>
                </div>

                <!-- Result -->
                <div id="calc-result-block" hidden style="background:var(--sa-gray-50);border:2px solid var(--sa-primary);border-radius:var(--sa-radius-lg);padding:1.75rem;text-align:center;margin-bottom:2rem;">
                    <div style="font-size:0.9rem;font-weight:600;color:var(--sa-gray-600);margin-bottom:0.5rem;">Ориентировочная стоимость</div>
                    <div id="calc-result"></div>
                    <p style="font-size:0.8rem;color:var(--sa-gray-500);margin-top:0.75rem;">
                        * Точная стоимость определяется после профессионального замера.
                    </p>
                    <button id="calc-submit-btn" class="sa-btn sa-btn--primary" style="margin-top:1rem;">
                        <i class="fa-solid fa-ruler-combined"></i> Вызвать замерщика для точного расчёта
                    </button>
                </div>
            </div>

            <!-- Panel: Quiz -->
            <div data-calc-panel="quiz" hidden>
                <?php get_template_part('template-parts/quiz'); ?>
            </div>

        </div>
    </div>
</section>

<!-- Comparison Table -->
<?php get_template_part('template-parts/comparison'); ?>

<!-- Process -->
<?php get_template_part('template-parts/process'); ?>

<!-- FAQ -->
<?php get_template_part('template-parts/faq-block', null, ['cat_name' => 'О ценах']); ?>

<!-- Reviews -->
<?php get_template_part('template-parts/reviews'); ?>

<!-- CTA -->
<?php get_template_part('template-parts/cta-form'); ?>

<?php get_footer(); ?>
