<?php
/**
 * Template Part: Comparison Table
 *
 * @package StoneArt
 */

$title    = sa_option('sa_comparison_title', 'Какой камень выбрать?');
$subtitle = sa_option('sa_comparison_subtitle', 'Честное сравнение характеристик материалов для принятия верного решения при заказе столешницы или подоконника.');
?>

<section id="comparison" class="sa-section sa-section--gray sa-animate" style="border-top:1px solid var(--sa-gray-200);">
    <div class="sa-container">
        <h2 class="sa-section__title"><?php echo esc_html($title); ?></h2>
        <p class="sa-section__subtitle"><?php echo esc_html($subtitle); ?></p>

        <div class="sa-comparison">
            <table>
                <thead>
                    <tr>
                        <th>Характеристика</th>
                        <th>Кварцевый агломерат</th>
                        <th>Акриловый камень</th>
                        <th>Натуральный гранит</th>
                        <th>Мрамор / Оникс</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Впитываемость (Пятна)</td>
                        <td class="sa-good"><i class="fa-solid fa-shield-halved"></i> Нулевая</td>
                        <td class="sa-good"><i class="fa-solid fa-shield-halved"></i> Нулевая</td>
                        <td>Низкая (нужна защита)</td>
                        <td class="sa-bad">Высокая (впитывает вино)</td>
                    </tr>
                    <tr>
                        <td>Термостойкость</td>
                        <td>До 150°C</td>
                        <td class="sa-bad">Низкая (боится горячего)</td>
                        <td class="sa-good"><i class="fa-solid fa-fire"></i> До 800°C</td>
                        <td>До 150°C</td>
                    </tr>
                    <tr>
                        <td>Царапины</td>
                        <td class="sa-good">Высокая защита</td>
                        <td class="sa-bad">Легко царапается (реставрируется)</td>
                        <td class="sa-good">Очень сложно поцарапать</td>
                        <td>Средняя защита</td>
                    </tr>
                    <tr>
                        <td>Бесшовное соединение</td>
                        <td class="sa-bad">Нет (тонкий шов)</td>
                        <td class="sa-good"><i class="fa-solid fa-check"></i> Да, абсолютно</td>
                        <td class="sa-bad">Нет (тонкий шов)</td>
                        <td class="sa-bad">Нет (тонкий шов)</td>
                    </tr>
                    <tr>
                        <td>Идеально для:</td>
                        <td class="sa-best">Столешницы кухни</td>
                        <td class="sa-best">Сложные формы, ванны</td>
                        <td class="sa-best">Улица, ступени, кухни</td>
                        <td class="sa-best">Полы, камины, панно</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
