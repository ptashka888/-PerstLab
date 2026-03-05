<?php
defined('ABSPATH') || exit;

$variant = $args['variant'] ?? 'dealer-vs-us';

if ($variant === 'dealer-vs-us') {
    $columns = ['Критерий', 'Дилер', 'CarFinance'];
    $rows = [
        ['Цена',          'Наценка 20–40%',           'Прямые цены с аукционов',    'negative', 'positive'],
        ['Гарантия',      'Ограниченная гарантия',    'Полная проверка + гарантия',  'negative', 'positive'],
        ['Сроки',         'Только из наличия',        'От 2 недель под заказ',       'negative', 'positive'],
        ['Прозрачность',  'Скрытые платежи',          'Полная смета заранее',        'negative', 'positive'],
        ['Выбор',         'Ограничен складом',        '1000+ авто на аукционах',     'negative', 'positive'],
        ['Поддержка',     'До момента продажи',       'Сопровождение после покупки', 'negative', 'positive'],
    ];
} else {
    $columns = ['Параметр', 'Корея', 'Япония', 'Китай', 'США', 'ОАЭ'];
    $rows = [
        ['Средняя цена',          'от 1.2 млн ₽', 'от 800 тыс ₽', 'от 1.5 млн ₽', 'от 2 млн ₽', 'от 1.8 млн ₽'],
        ['Срок доставки',         '3–4 недели',    '2–3 недели',    '4–5 недель',    '6–8 недель',  '4–6 недель'],
        ['Стоимость растаможки',  'Средняя',       'Средняя',       'Средняя',       'Высокая',     'Средняя'],
        ['Качество авто',         'Высокое',       'Высокое',       'Среднее',       'Высокое',     'Высокое'],
        ['Выбор моделей',         'Широкий',       'Очень широкий', 'Широкий',       'Широкий',     'Средний'],
    ];
}
?>
<section class="cf-comparison-table cf-comparison-table--<?php echo esc_attr($variant); ?>">
    <div class="cf-comparison-table__container">
        <h2 class="cf-comparison-table__title">
            <?php echo $variant === 'dealer-vs-us'
                ? 'Почему мы, а не дилер?'
                : 'Сравнение стран для импорта'; ?>
        </h2>

        <div class="cf-comparison-table__wrapper">
            <table class="cf-table">
                <thead>
                    <tr>
                        <?php foreach ($columns as $col) : ?>
                            <th><?php echo esc_html($col); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($variant === 'dealer-vs-us') : ?>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <td class="cf-table__criterion"><?php echo esc_html($row[0]); ?></td>
                                <td class="cf-table__cell cf-table__cell--<?php echo esc_attr($row[3]); ?>">
                                    <?php echo esc_html($row[1]); ?>
                                </td>
                                <td class="cf-table__cell cf-table__cell--<?php echo esc_attr($row[4]); ?>">
                                    <?php echo esc_html($row[2]); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <?php foreach ($row as $cell) : ?>
                                    <td><?php echo esc_html($cell); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
